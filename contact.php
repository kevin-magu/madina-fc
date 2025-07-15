<?php
$pageTitle = "Contact Us";
include 'includes/header.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // In a real application, you would process the form here
        // For this example, we'll just simulate success
        $success = true;
    }
}
?>

<section class="main-content">
    <div class="container">
        <h2 class="section-title">Contact Madina FC</h2>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <p>Thank you for your message! We'll get back to you soon.</p>
                <a href="contact.php" class="btn">Send another message</a>
            </div>
        <?php else: ?>
            <div class="contact-form">
                <?php if ($error): ?>
                    <div class="form-message error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form id="contactForm" method="POST">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>
            
            <div class="contact-info mt-5">
                <h3>Other Ways to Reach Us</h3>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> 123 Football Street, Madina</p>
                <p><i class="fas fa-phone"></i> <strong>Phone:</strong> +123 456 7890</p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> info@madinafc.com</p>
                <p><i class="fas fa-clock"></i> <strong>Office Hours:</strong> Monday-Friday, 9:00 AM - 5:00 PM</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>