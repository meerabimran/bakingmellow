<?php session_start(); include __DIR__ . '/../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | Baking Mellow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cart.css">
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
                <li><a href="cart.php" class="cart-icon active"><i class="fas fa-shopping-bag"></i><span class="cart-count">0</span></a></li>
            </ul>
            <div class="hamburger" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
        </div>
    </nav>

    <section class="page-header" data-aos="fade-in">
        <div class="container">
            <h1>Your Cart</h1>
            <p>Review your selections before checkout</p>
            <div class="breadcrumb"><a href="index.php">Home</a> / <a href="menu.php">Menu</a> / <span>Cart</span></div>
        </div>
    </section>

    <section class="cart-section" data-aos="fade-up">
        <div class="container">
            <div class="cart-layout">
                <div class="cart-items">
                    <div class="cart-header"><h3>Your Items</h3><span id="itemCount">0 items</span></div>
                    <div id="cartContainer">
                        <div class="cart-empty">
                            <i class="fas fa-shopping-bag"></i>
                            <h3>Your cart is empty</h3>
                            <p>Looks like you haven't added anything to your cart yet.</p>
                            <a href="menu.php" class="btn">Browse Menu</a>
                        </div>
                    </div>
                </div>
                <div class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row"><span>Subtotal</span><span id="subtotal">Rs. 0</span></div>
                    <div class="summary-row"><span>Delivery Fee</span><span id="deliveryFee">Rs. 200</span></div>
                    <div class="summary-row total"><span>Total</span><span id="totalAmount">Rs. 0</span></div>
                    <div class="promo-code">
                        <input type="text" id="promoInput" placeholder="Promo code">
                        <button class="btn-sm" onclick="applyPromo()">Apply</button>
                    </div>
                    <div class="checkout-actions">
                        <button class="btn" id="checkoutBtn" onclick="proceedToCheckout()"><i class="fas fa-lock"></i> Proceed to Checkout</button>
                        <a href="menu.php" class="btn-outline"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });
        function toggleMenu() { document.querySelector('.nav-links').classList.toggle('active'); }

        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const DELIVERY_FEE = 200;

        function renderCart() {
            const container = document.getElementById('cartContainer');
            const itemCount = document.getElementById('itemCount');
            if (cart.length === 0) {
                container.innerHTML = `<div class="cart-empty"><i class="fas fa-shopping-bag"></i><h3>Your cart is empty</h3><p>Looks like you haven't added anything yet.</p><a href="menu.php" class="btn">Browse Menu</a></div>`;
                itemCount.textContent = '0 items';
                updateTotals();
                updateCartCount();
                return;
            }
            let html = '', subtotal = 0;
            cart.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                html += `<div class="cart-item" data-index="${index}">
                    <div class="cart-item-image"><img src="../images/${item.image || 'placeholder.jpg'}" alt="${item.name}"></div>
                    <div class="cart-item-details">
                        <h4>${item.name}</h4>
                        <span class="cart-item-price">Rs. ${item.price.toLocaleString()}</span>
                        <div class="cart-item-actions">
                            <div class="cart-qty-controls">
                                <button onclick="updateQuantity(${index}, -1)">−</button>
                                <span>${item.quantity}</span>
                                <button onclick="updateQuantity(${index}, 1)">+</button>
                            </div>
                            <button class="cart-remove" onclick="removeItem(${index})"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                    <div class="cart-item-total">Rs. ${itemTotal.toLocaleString()}</div>
                </div>`;
            });
            container.innerHTML = html;
            itemCount.textContent = `${cart.reduce((total, item) => total + item.quantity, 0)} items`;
            updateTotals(subtotal);
            updateCartCount();
        }

        function updateQuantity(index, change) {
            if (cart[index]) {
                cart[index].quantity += change;
                if (cart[index].quantity < 1) cart[index].quantity = 1;
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCart();
            }
        }
        function removeItem(index) {
            if (confirm('Remove this item from your cart?')) {
                cart.splice(index, 1);
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCart();
                showNotification('Item removed from cart');
            }
        }
        function updateTotals(subtotal) {
            if (!subtotal) subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
            const total = subtotal + DELIVERY_FEE;
            document.getElementById('subtotal').textContent = `Rs. ${subtotal.toLocaleString()}`;
            document.getElementById('totalAmount').textContent = `Rs. ${total.toLocaleString()}`;
            document.getElementById('checkoutBtn').disabled = cart.length === 0;
        }
        function updateCartCount() {
            const count = cart.reduce((total, item) => total + item.quantity, 0);
            const badge = document.querySelector('.cart-count');
            if (badge) { badge.textContent = count; badge.style.display = count > 0 ? 'block' : 'none'; }
        }
        function proceedToCheckout() {
            if (cart.length === 0) { showNotification('Your cart is empty!'); return; }
            fetch('check_login.php')
                .then(res => res.json())
                .then(data => {
                    if (data.logged_in) window.location.href = 'checkout.php';
                    else if (confirm('Please login to proceed with checkout. Go to login page?')) window.location.href = 'login.php?redirect=cart';
                })
                .catch(() => {
                    if (confirm('Please login to proceed with checkout. Go to login page?')) window.location.href = 'login.php?redirect=cart';
                });
        }
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.innerHTML = `<div class="notification-content"><span class="notification-icon">✓</span><span>${message}</span></div>`;
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.classList.add('show');
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }, 100);
        }

        function applyPromo() {
            const promoInput = document.getElementById('promoInput');
            const code = promoInput.value.trim();
            const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
            const promoCodes = {
                'MELLOW10': 0.10,
                'SWEET20': 0.20,
                'FREE': 1.00
            };
            if (code in promoCodes) {
                const discountPercent = promoCodes[code];
                const discountAmount = subtotal * discountPercent;
                const newSubtotal = subtotal - discountAmount;
                const newTotal = newSubtotal + DELIVERY_FEE;
                document.getElementById('subtotal').textContent = `Rs. ${newSubtotal.toLocaleString()}`;
                document.getElementById('totalAmount').textContent = `Rs. ${newTotal.toLocaleString()}`;
                showNotification(`✅ Promo applied! You saved Rs. ${discountAmount.toLocaleString()}`);
                promoInput.value = '';
            } else {
                showNotification('❌ Invalid promo code. Try MELLOW10, SWEET20, or FREE.');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
            if (cartItems.length > 0) {
                const ids = cartItems.map(item => item.id).join(',');
                fetch(`get_product_images.php?ids=${ids}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            data.images.forEach(imgData => {
                                const item = cart.find(i => i.id === imgData.id);
                                if (item) item.image = imgData.image_url;
                            });
                            localStorage.setItem('cart', JSON.stringify(cart));
                            renderCart();
                        }
                    })
                    .catch(() => {
                        cart.forEach(item => item.image = 'placeholder.jpg');
                        localStorage.setItem('cart', JSON.stringify(cart));
                        renderCart();
                    });
            }
            renderCart();
        });
    </script>
    <style>
        .notification { position: fixed; top: 100px; right: 20px; z-index: 9999; opacity: 0; transform: translateY(-20px); transition: all 0.3s ease; }
        .notification.show { opacity: 1; transform: translateY(0); }
        .notification-content { background: var(--secondary); border: 1px solid var(--primary); border-radius: 12px; padding: 15px 25px; display: flex; align-items: center; gap: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .notification-icon { background: var(--primary); color: var(--background); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
    </style>
</body>
</html>
