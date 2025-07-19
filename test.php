<?php 
require_once 'includes/db_connect.php';
function generateUniqueClubId($conn) {
    do {
        // Generate random 4-digit code
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Prepare and execute query to check existence
        $stmt = $conn->prepare("SELECT 1 FROM players WHERE club_id = ? LIMIT 1");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();

        $exists = $result->num_rows > 0;


    } while ($exists);

    return $code;
}

// Usage
$uniqueClubId = generateUniqueClubId($conn);
echo $uniqueClubId;
?>