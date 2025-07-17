<?php
require_once realpath(dirname(__DIR__) . '/../includes/db_connect.php');

// Enhanced token retrieval with proper header parsing
function getAuthToken() {
    // 1. Check cookie first (primary method)
    if (isset($_COOKIE['admin_token'])) {
        return $_COOKIE['admin_token'];
    }
    
    // 2. Check Authorization header (for API requests)
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        if (preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            return $matches[1];
        }
    }
    
    // 3. Fallback to GET parameter (only for specific cases)
    return $_GET['token'] ?? null;
}

// Main authentication flow
$token = getAuthToken();

if (!$token) {
    if (isApiRequest()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Authentication required']);
    } else {
        header("Location: admin/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    }
    exit;
}

// Token validation (without revoked check)
$stmt = $conn->prepare("
    SELECT at.admin_id, a.full_name, at.expires_at
    FROM admin_tokens at
    JOIN admins a ON a.id = at.admin_id
    WHERE at.token = ? 
    AND at.expires_at > NOW()
    LIMIT 1
");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Clear invalid cookie
    setcookie('admin_token', '', time() - 3600, '/', '', true, true);
    
    if (isApiRequest()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid or expired session']);
    } else {
        header("Location: login.php?session=expired");
    }
    exit;
}

// Extract admin data
$admin = $result->fetch_assoc();
$adminId = $admin['admin_id'];
$adminName = $admin['full_name'];

// Optional: Refresh token if nearing expiration (last 6 hours)
$expiresIn = strtotime($admin['expires_at']) - time();
if ($expiresIn < 6 * 60 * 60) {
    refreshAuthToken($adminId, $token);
}

// Helper functions
function isApiRequest() {
    return strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false || 
           strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false;
}

function refreshAuthToken($adminId, $oldToken) {
    global $conn;
    
    $newToken = bin2hex(random_bytes(32));
    $newExpiry = time() + (2 * 24 * 60 * 60);
    
    $conn->begin_transaction();
    try {
        // Delete old token (instead of revoking)
        $conn->query("DELETE FROM admin_tokens WHERE token = '$oldToken'");
        
        // Issue new token
        $stmt = $conn->prepare("
            INSERT INTO admin_tokens (admin_id, token, expires_at)
            VALUES (?, ?, FROM_UNIXTIME(?))
        ");
        $stmt->bind_param("isi", $adminId, $newToken, $newExpiry);
        $stmt->execute();
        
        // Set new cookie
        setcookie('admin_token', $newToken, [
            'expires' => $newExpiry,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Token refresh failed: " . $e->getMessage());
    }
}