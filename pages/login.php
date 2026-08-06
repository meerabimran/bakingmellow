<?php
session_start();
include __DIR__ . '/../includes/db.php';
if (isset($_SESSION['user_id'])) { header('Location: index.php'); exit(); }

$error = '';
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        $stmt = $conn->prepare("SELECT id, firstname, lastname, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user && hash('sha256', $password) === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
            $_SESSION['user_email'] = $user['email'];
            if ($redirect === 'cart') header('Location: cart.php');
            elseif ($redirect === 'checkout') header('Location: checkout.php');
            else header('Location: index.php');
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Baking Mellow</title>
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
                <li><a href="login.php" class="auth-btn active">Login</a></li>
                <li><a href="cart.php" class="cart-icon"><i class="fas fa-shopping-bag"></i><span class="cart-count">0</span></a></li>
            </ul>
            <div class="hamburger" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
        </div>
    </nav>

    <section class="auth-section" data-aos="fade-up">
        <div class="container">
            <div class="auth-container">
                <div class="auth-box">
                    <div class="auth-header"><h2>Welcome Back</h2><p>Sign in to access your orders and save your preferences</p></div>
                    <?php if ($error): ?><div class="alert error"><i class="fas fa-exclamation-circle"></i><?php echo $error; ?></div><?php endif; ?>
                    <form method="POST" action="login.php" class="auth-form">
                        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
                        <div class="form-group">
                            <label>Email Address</label>
                            <div class="input-group"><i class="fas fa-envelope"></i><input type="email" name="email" placeholder="Enter your email" required></div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group"><i class="fas fa-lock"></i><input type="password" name="password" placeholder="Enter your password" required></div>
                        </div>
                        <button type="submit" class="btn btn-full"><i class="fas fa-sign-in-alt"></i> Sign In</button>
                    </form>
                    <div class="auth-footer"><p>Don't have an account? <a href="register.php">Create Account</a></p></div>
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
