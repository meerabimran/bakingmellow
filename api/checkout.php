<?php
session_start();
include 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php?redirect=checkout'); exit(); }

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT firstname, lastname, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$order_placed = false;
$order_number = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $postal_code = $_POST['postal_code'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $payment_method = $_POST['payment_method'] ?? 'cash';
    $cart_data = json_decode($_POST['cart_data'] ?? '[]', true);
    
    if (empty($cart_data)) {
        $error_message = 'Your cart is empty. Please add items before checkout.';
    } else {
        $subtotal = 0;
        foreach ($cart_data as $item) $subtotal += $item['price'] * $item['quantity'];
        $delivery_fee = 200;
        $total_amount = $subtotal + $delivery_fee;
        $order_number = 'BM' . date('Ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        $delivery_address = "$address, $city, $postal_code";
        
        $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, total_amount, status, payment_method, delivery_address, delivery_notes) VALUES (?, ?, ?, 'pending', ?, ?, ?)");
        $stmt->bind_param("isdsss", $user_id, $order_number, $total_amount, $payment_method, $delivery_address, $notes);
        
        if ($stmt->execute()) {
            $order_id = $conn->insert_id;
            $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
            foreach ($cart_data as $item) {
                $stmt_items->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
                $stmt_items->execute();
            }
            $order_placed = true;
        } else {
            $error_message = 'There was an error processing your order. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Baking Mellow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/checkout.css">
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

    <?php if ($order_placed): ?>
    <section class="order-success" data-aos="fade-in">
        <div class="container">
            <div class="success-box">
                <div class="success-icon"><i class="fas fa-check-circle"></i></div>
                <h1>Order Placed Successfully! 🎉</h1>
                <p class="order-number">Order #<?php echo $order_number; ?></p>
                <p>Thank you for your order! We'll start preparing your treats right away.</p>
                <div class="success-actions">
                    <a href="my_orders.php" class="btn">View My Orders</a>
                    <a href="menu.php" class="btn-outline">Continue Shopping</a>
                </div>
            </div>
        </div>
    </section>
    <?php else: ?>
    <section class="page-header" data-aos="fade-in">
        <div class="container">
            <h1>Checkout</h1>
            <p>Complete your order by filling in your delivery details</p>
            <div class="breadcrumb"><a href="index.php">Home</a> / <a href="cart.php">Cart</a> / <span>Checkout</span></div>
        </div>
    </section>

    <section class="checkout-section" data-aos="fade-up">
        <div class="container">
            <?php if ($error_message): ?><div class="alert error"><i class="fas fa-exclamation-circle"></i><?php echo $error_message; ?></div><?php endif; ?>
            <div class="checkout-layout">
                <div class="checkout-form">
                    <h3>Delivery Information</h3>
                    <form id="checkoutForm" method="POST" action="checkout.php">
                        <input type="hidden" name="cart_data" id="cartData">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input type="text" name="postal_code" placeholder="e.g. 40100" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Delivery Address</label>
                            <textarea name="address" rows="3" placeholder="Street address, building, apartment number" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" placeholder="e.g. Sargodha" required>
                        </div>
                        <div class="form-group">
                            <label>Order Notes (Optional)</label>
                            <textarea name="notes" rows="2" placeholder="Special instructions, delivery time preferences, etc."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Payment Method</label>
                            <div class="payment-options">
                                <label class="payment-option"><input type="radio" name="payment_method" value="cash" checked><span><i class="fas fa-money-bill-wave"></i> Cash on Delivery</span></label>
                                <label class="payment-option"><input type="radio" name="payment_method" value="card"><span><i class="fas fa-credit-card"></i> Credit/Debit Card</span></label>
                                <label class="payment-option"><input type="radio" name="payment_method" value="bank"><span><i class="fas fa-university"></i> Bank Transfer</span></label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-large" id="placeOrderBtn"><i class="fas fa-lock"></i> Place Order</button>
                    </form>
                </div>
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div class="order-items" id="orderItems"></div>
                    <div class="summary-totals">
                        <div class="summary-row"><span>Subtotal</span><span id="checkoutSubtotal">Rs. 0</span></div>
                        <div class="summary-row"><span>Delivery Fee</span><span>Rs. 200</span></div>
                        <div class="summary-row total"><span>Total</span><span id="checkoutTotal">Rs. 0</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php include 'includes/footer.php'; ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });
        function toggleMenu() { document.querySelector('.nav-links').classList.toggle('active'); }
        const DELIVERY_FEE = 200;
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        function renderOrderSummary() {
            const container = document.getElementById('orderItems');
            const subtotalEl = document.getElementById('checkoutSubtotal');
            const totalEl = document.getElementById('checkoutTotal');
            if (cart.length === 0) {
                container.innerHTML = `<div class="cart-empty-summary"><p>Your cart is empty</p><a href="menu.php" class="btn-sm">Go to Menu</a></div>`;
                subtotalEl.textContent = 'Rs. 0'; totalEl.textContent = 'Rs. 0';
                return;
            }
            let html = '', subtotal = 0;
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                html += `<div class="order-item"><div class="order-item-info"><span class="order-item-name">${item.name}</span><span class="order-item-qty">× ${item.quantity}</span></div><span class="order-item-price">Rs. ${itemTotal.toLocaleString()}</span></div>`;
            });
            container.innerHTML = html;
            const total = subtotal + DELIVERY_FEE;
            subtotalEl.textContent = `Rs. ${subtotal.toLocaleString()}`;
            totalEl.textContent = `Rs. ${total.toLocaleString()}`;
        }

        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const cartData = localStorage.getItem('cart');
            if (!cartData || JSON.parse(cartData).length === 0) { e.preventDefault(); alert('Your cart is empty!'); return; }
            document.getElementById('cartData').value = cartData;
            const btn = document.getElementById('placeOrderBtn');
            btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        });

        function updateCartCount() {
            const count = cart.reduce((total, item) => total + item.quantity, 0);
            const badge = document.querySelector('.cart-count');
            if (badge) { badge.textContent = count; badge.style.display = count > 0 ? 'block' : 'none'; }
        }
        document.addEventListener('DOMContentLoaded', function() { renderOrderSummary(); updateCartCount(); });
    </script>
</body>
</html>