<?php
session_start();
include __DIR__ . '/../includes/db.php';
if (isset($_SESSION['user_id'])) { header('Location: index.php'); exit(); }

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    if (strlen($firstname) < 2) $errors[] = 'First name must be at least 2 characters.';
    if (strlen($lastname) < 2) $errors[] = 'Last name must be at least 2 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm_password) $errors[] = 'Passwords do not match.';
    
    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) $errors[] = 'This email is already registered.';
    }
    
    if (empty($errors)) {
        $hashed = hash('sha256', $password);
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $lastname, $email, $phone, $hashed);
        if ($stmt->execute()) {
            $success = 'Account created successfully! You can now login.';
            $firstname = $lastname = $email = $phone = '';
        } else {
            $errors[] = 'An error occurred. Please try again.';
        }
    }
    if (!empty($errors)) $error = implode('<br>', $errors);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account | Baking Mellow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <nav>
        <div class="container nav">
            <div class="logo"><a href="index.php"><h2>Baking <span>Mellow</span></h2></a></div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php" class="auth-btn">Login</a></li>
                <li><a href="cart.php" class="cart-icon"><i class="fas fa-shopping-bag"></i><span class="cart-count">0</span></a></li>
            </ul>
            <div class="hamburger" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
        </div>
    </nav>

    <section class="auth-section" data-aos="fade-up">
        <div class="container">
            <div class="auth-container">
                <div class="auth-box">
                    <div class="auth-header"><h2>Create Account</h2><p>Join Baking Mellow and enjoy exclusive benefits</p></div>
                    <?php if ($error): ?><div class="alert error"><i class="fas fa-exclamation-circle"></i><?php echo $error; ?></div><?php endif; ?>
                    <?php if ($success): ?><div class="alert success"><i class="fas fa-check-circle"></i><?php echo $success; ?></div><?php endif; ?>
                    <form method="POST" action="register.php" class="auth-form">
                        <div class="form-row">
                            <div class="form-group"><label>First Name</label><div class="input-group"><i class="fas fa-user"></i><input type="text" name="firstname" placeholder="Enter first name" value="<?php echo htmlspecialchars($firstname ?? ''); ?>" required></div></div>
                            <div class="form-group"><label>Last Name</label><div class="input-group"><i class="fas fa-user"></i><input type="text" name="lastname" placeholder="Enter last name" value="<?php echo htmlspecialchars($lastname ?? ''); ?>" required></div></div>
                        </div>
                        <div class="form-group"><label>Email Address</label><div class="input-group"><i class="fas fa-envelope"></i><input type="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required></div></div>
                        <div class="form-group"><label>Phone Number</label><div class="input-group"><i class="fas fa-phone"></i><input type="tel" name="phone" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($phone ?? ''); ?>"></div></div>
                        <div class="form-group"><label>Password</label><div class="input-group"><i class="fas fa-lock"></i><input type="password" name="password" placeholder="Create a password (min 6 characters)" required></div></div>
                        <div class="form-group"><label>Confirm Password</label><div class="input-group"><i class="fas fa-lock"></i><input type="password" name="confirm_password" placeholder="Confirm your password" required></div></div>
                        <button type="submit" class="btn btn-full"><i class="fas fa-user-plus"></i> Create Account</button>
                    </form>
                    <div class="auth-footer"><p>Already have an account? <a href="login.php">Sign In</a></p></div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
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
