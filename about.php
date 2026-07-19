<?php session_start(); include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | Baking Mellow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/about.css">
</head>
<body>
    <nav>
        <div class="container nav">
            <div class="logo"><a href="index.php"><h2>Baking <span>Mellow</span></h2></a></div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="about.php" class="active">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php" class="auth-btn">Login</a></li>
                <li><a href="cart.php" class="cart-icon"><i class="fas fa-shopping-bag"></i><span class="cart-count">0</span></a></li>
            </ul>
            <div class="hamburger" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
        </div>
    </nav>

    <section class="page-header" data-aos="fade-in">
        <div class="container">
            <h1>Our Story</h1>
            <p>Crafted with passion, baked with love — every bite tells a story</p>
            <div class="breadcrumb"><a href="index.php">Home</a> / <span>About</span></div>
        </div>
    </section>

    <section class="story-section" data-aos="fade-up">
        <div class="container">
            <div class="story-grid">
                <div class="story-image" data-aos="fade-right"><img src="images/logo.png" alt="Baking Mellow Kitchen"><div class="image-caption"><span>Since 2024</span></div></div>
                <div class="story-content" data-aos="fade-left">
                    <span class="subtitle">Our Journey</span>
                    <h2>The Sweet Beginning</h2>
                    <p class="story-text">Baking Mellow was born in Sargodha in 2024 — a small home bakery with a big dream. What started as a passion project in a family kitchen quickly grew into a beloved local brand known for its soft cakes, creative cupcakes, dreamy donuts, and elegant bouquets.</p>
                    <p class="story-text">Our mission is simple: make every celebration sweeter with fresh, affordable, beautifully crafted treats. Every product is made with love, care, and the commitment to deliver premium quality.</p>
                    <div class="story-stats">
                        <div class="stat"><span class="stat-number">2,000+</span><span class="stat-label">Happy Customers</span></div>
                        <div class="stat"><span class="stat-number">500+</span><span class="stat-label">Cakes Baked</span></div>
                        <div class="stat"><span class="stat-number">100%</span><span class="stat-label">Fresh Daily</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="values-section" data-aos="fade-up">
        <div class="container">
            <div class="section-header"><span class="subtitle">Our Values</span><h2 class="section-title">What Drives Us</h2><p class="section-sub">The principles that guide every creation in our kitchen</p></div>
            <div class="values-grid">
                <div class="value-card" data-aos="fade-up" data-aos-delay="100"><div class="value-icon"><i class="fas fa-seedling"></i></div><h3>Premium Ingredients</h3><p>We source the finest ingredients — French butter, Belgian chocolate, fresh cream, and locally grown seasonal fruits.</p></div>
                <div class="value-card" data-aos="fade-up" data-aos-delay="200"><div class="value-icon"><i class="fas fa-heart"></i></div><h3>Made With Love</h3><p>Every pastry is handcrafted with care and attention to detail. We pour our heart into every batch.</p></div>
                <div class="value-card" data-aos="fade-up" data-aos-delay="300"><div class="value-icon"><i class="fas fa-clock"></i></div><h3>Fresh Every Day</h3><p>We wake up early every morning to bake fresh. No shortcuts, no compromises — just pure quality.</p></div>
                <div class="value-card" data-aos="fade-up" data-aos-delay="400"><div class="value-icon"><i class="fas fa-hand-sparkles"></i></div><h3>Artisan Craftsmanship</h3><p>From folding croissant dough to piping frosting on cakes, we use traditional techniques for exceptional results.</p></div>
                <div class="value-card" data-aos="fade-up" data-aos-delay="500"><div class="value-icon"><i class="fas fa-users"></i></div><h3>Community First</h3><p>We're proud to be part of the Sargodha community. Supporting local farmers and businesses is our priority.</p></div>
                <div class="value-card" data-aos="fade-up" data-aos-delay="600"><div class="value-icon"><i class="fas fa-star"></i></div><h3>Uncompromising Quality</h3><p>We never settle for good enough. Every product must meet our high standards before it reaches your table.</p></div>
            </div>
        </div>
    </section>

    <section class="baker-section" data-aos="fade-up">
        <div class="container">
            <div class="baker-grid">
                <div class="baker-content" data-aos="fade-right">
                    <span class="subtitle">Meet the Baker</span>
                    <h2>Passion in Every Layer</h2>
                    <p class="baker-text">Hi, I'm <strong>Meerab</strong> — the heart and hands behind Baking Mellow. I started this journey with a simple love for baking and a dream to share joy through delicious, beautiful creations.</p>
                    <p class="baker-text">From my first imperfect cake to the elegant creations we craft today, every step has been a labor of love. I believe that baking is more than just mixing ingredients — it's about creating moments of happiness.</p>
                    <div class="baker-quote"><i class="fas fa-quote-left"></i><blockquote>"Every cake tells a story. Every croissant holds a memory. I bake because it's how I share my heart with the world."</blockquote><cite>— Meerab Imran</cite></div>
                </div>
                <div class="baker-image" data-aos="fade-left"><img src="images/baker.jpg" alt="Meet the Baker"><div class="baker-badge"><i class="fas fa-heart"></i><span>Since 2024</span></div></div>
            </div>
        </div>
    </section>

    <section class="cta-section" data-aos="fade-up">
        <div class="container">
            <div class="cta-box">
                <h2>Ready to Taste the Difference?</h2>
                <p>Experience the warmth of freshly baked goods made with love and premium ingredients.</p>
                <div class="cta-actions">
                    <a href="menu.php" class="btn"><i class="fas fa-store"></i> Explore Menu</a>
                    <a href="contact.php" class="btn-outline"><i class="fas fa-envelope"></i> Contact Us</a>
                </div>
            </div>
        </div>
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