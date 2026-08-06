<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }

$orders = $conn->query("
    SELECT o.*, COUNT(oi.id) as item_count 
    FROM orders o 
    LEFT JOIN order_items oi ON o.id = oi.order_id 
    WHERE o.user_id = {$_SESSION['user_id']} 
    GROUP BY o.id 
    ORDER BY o.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | Baking Mellow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
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
                <li><a href="logout.php" class="auth-btn">Logout</a></li>
                <li><a href="cart.php" class="cart-icon"><i class="fas fa-shopping-bag"></i><span class="cart-count">0</span></a></li>
            </ul>
            <div class="hamburger" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
        </div>
    </nav>

    <section class="page-header" data-aos="fade-in">
        <div class="container">
            <h1>My Orders</h1>
            <p>Track all your orders in one place</p>
            <div class="breadcrumb"><a href="index.php">Home</a> / <span>My Orders</span></div>
        </div>
    </section>

    <section class="orders-section" data-aos="fade-up">
        <div class="container">
            <?php if ($orders->num_rows > 0): ?>
                <div class="orders-list">
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <div class="order-card" data-aos="fade-up" data-aos-delay="100">
                            <div class="order-header">
                                <div class="order-info">
                                    <span class="order-number">#<?php echo $order['order_number']; ?></span>
                                    <span class="order-date"><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
                                </div>
                                <div class="order-status"><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></div>
                            </div>
                            <div class="order-body">
                                <div class="order-details">
                                    <div class="order-detail"><span>Items</span><strong><?php echo $order['item_count']; ?> items</strong></div>
                                    <div class="order-detail"><span>Total</span><strong>Rs. <?php echo number_format($order['total_amount'], 0); ?></strong></div>
                                    <div class="order-detail"><span>Payment</span><strong><?php echo ucfirst($order['payment_method']); ?></strong></div>
                                </div>
                                <div class="order-actions">
                                    <?php if ($order['status'] === 'pending'): ?>
                                        <button class="btn-sm" style="background:#e74c3c;" onclick="cancelOrder(<?php echo $order['id']; ?>)">Cancel</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-orders"><i class="fas fa-box-open"></i><h3>No orders yet</h3><p>You haven't placed any orders yet. Browse our menu and order something delicious!</p><a href="menu.php" class="btn">Browse Menu</a></div>
            <?php endif; ?>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
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
        function cancelOrder(id) {
            if (confirm('Are you sure you want to cancel this order?')) {
                fetch('cancel_order.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({order_id: id})
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) { alert('Order cancelled successfully'); location.reload(); }
                    else { alert('Error cancelling order: ' + data.error); }
                });
            }
        }
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
</body>
</html>