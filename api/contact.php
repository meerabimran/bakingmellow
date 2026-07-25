<?php
session_start();
include 'includes/db.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    $errors = [];
    if (empty($name)) { $errors[] = 'Name is required.'; }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Valid email is required.'; }
    if (empty($message) || strlen($message) < 10) { $errors[] = 'Message must be at least 10 characters.'; }
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
        if ($stmt->execute()) {
            $success_message = 'Your message has been sent successfully! We\'ll get back to you within 24 hours. 🎉';
            $name = $email = $phone = $subject = $message = '';
        } else {
            $error_message = 'There was an error sending your message. Please try again later.';
        }
    } else {
        $error_message = implode('<br>', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us | Baking Mellow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">
</head>
<body>
    <nav>
        <div class="container nav">
            <div class="logo"><a href="index.php"><h2>Baking <span>Mellow</span></h2></a></div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
                <li><a href="login.php" class="auth-btn">Login</a></li>
                <li><a href="cart.php" class="cart-icon"><i class="fas fa-shopping-bag"></i><span class="cart-count">0</span></a></li>
            </ul>
            <div class="hamburger" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
        </div>
    </nav>

    <section class="page-header" data-aos="fade-in">
        <div class="container">
            <h1>Get In Touch</h1>
            <p>We'd love to hear from you — whether it's a question, a custom order, or just to say hello</p>
            <div class="breadcrumb"><a href="index.php">Home</a> / <span>Contact</span></div>
        </div>
    </section>

    <section class="contact-section" data-aos="fade-up">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info" data-aos="fade-right">
                    <h3>Let's Connect</h3>
                    <p class="info-desc">Reach out to us for custom orders, special events, or any questions. We're here to make your celebrations sweeter.</p>
                    <div class="info-items">
                        <div class="info-item"><div class="info-icon"><i class="fas fa-location-dot"></i></div><div><h4>Visit Us</h4><p>Baking Mellow Bakery<br>Sargodha, Pakistan</p></div></div>
                        <div class="info-item"><div class="info-icon"><i class="fas fa-phone"></i></div><div><h4>Call Us</h4><p>+92 342 6275592<br>Mon - Sat: 9AM - 8PM</p></div></div>
                        <div class="info-item"><div class="info-icon"><i class="fas fa-envelope"></i></div><div><h4>Email Us</h4><p>bakingmellow@gmail.com<br>We reply within 24 hours</p></div></div>
                    </div>
                    <div class="social-connect">
                        <h4>Follow Us</h4>
                        <div class="social-links">
                            <a href="#" class="social-link instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link whatsapp"><i class="fab fa-whatsapp"></i></a>
                            <a href="#" class="social-link pinterest"><i class="fab fa-pinterest-p"></i></a>
                        </div>
                    </div>
                </div>

                <div class="contact-form-wrapper" data-aos="fade-left">
                    <div class="form-header"><h3>Send Us a Message</h3><p>Fill out the form and we'll get back to you as soon as possible</p></div>
                    <?php if ($success_message): ?><div class="alert success"><i class="fas fa-check-circle"></i><?php echo $success_message; ?></div><?php endif; ?>
                    <?php if ($error_message): ?><div class="alert error"><i class="fas fa-exclamation-circle"></i><?php echo $error_message; ?></div><?php endif; ?>
                    <form method="POST" action="contact.php" class="contact-form" id="contactForm">
                        <div class="form-row">
                            <div class="form-group"><label>Your Name</label><div class="input-group"><i class="fas fa-user"></i><input type="text" name="name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required></div></div>
                            <div class="form-group"><label>Email Address</label><div class="input-group"><i class="fas fa-envelope"></i><input type="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required></div></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label>Phone Number</label><div class="input-group"><i class="fas fa-phone"></i><input type="tel" name="phone" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($phone ?? ''); ?>"></div></div>
                            <div class="form-group"><label>Subject</label><div class="input-group"><i class="fas fa-tag"></i><select name="subject"><option value="">Select a subject</option><option value="General Inquiry">General Inquiry</option><option value="Custom Order">Custom Order</option><option value="Feedback">Feedback</option><option value="Event Booking">Event Booking</option><option value="Partnership">Partnership</option><option value="Other">Other</option></select></div></div>
                        </div>
                        <div class="form-group"><label>Your Message</label><div class="input-group"><i class="fas fa-comment"></i><textarea name="message" rows="5" placeholder="Tell us how we can help..." required><?php echo htmlspecialchars($message ?? ''); ?></textarea></div></div>
                        <div class="form-group"><label class="checkbox-label"><input type="checkbox" required><span>I agree to the <a href="privacy.php">Privacy Policy</a> and consent to being contacted</span></label></div>
                        <button type="submit" class="btn btn-full"><i class="fas fa-paper-plane"></i> Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="map-section" data-aos="fade-up">
        <div class="container"><div class="map-container"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3482.093291712978!2d72.67454247557423!3d32.20490467378562!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3898d5ab654ec9e1%3A0x5b6f6e7e3d4e2b4c!2sSargodha%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div></div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });
        function toggleMenu() { document.querySelector('.nav-links').classList.toggle('active'); }
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const count = cart.reduce((total, item) => total + item.quantity, 0);
            const badge = document.querySelector('.cart-count');
            if (badge) { badge.textContent = count; badge.style.display = count > 0 ? 'block' : 'none'; }
        }
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
</body>
</html>