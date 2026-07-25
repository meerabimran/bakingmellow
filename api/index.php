<?php header('Content-Type: text/html; charset=utf-8'); ?>
<?php
session_start();
include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baking Mellow | Artisan Bakery</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    
    <style>
        .hero {
            background: linear-gradient(rgba(10, 10, 10, 0.7), rgba(10, 10, 10, 0.7)), 
                        url('images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .fresh-banner {
            background: linear-gradient(135deg, #d4a373 0%, #b8895e 100%);
            padding: 80px 0;
            text-align: center;
        }
        .fresh-banner h2 {
            color: #0a0a0a;
            font-size: 48px;
            margin-bottom: 20px;
        }
        .fresh-banner p {
            color: #1a1a1a;
            font-size: 20px;
            max-width: 600px;
            margin: 0 auto 30px;
        }
        .fresh-banner .btn {
            background: #0a0a0a;
            color: #d4a373;
            border: 2px solid #0a0a0a;
        }
        .fresh-banner .btn:hover {
            background: transparent;
            color: #0a0a0a;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav>
        <div class="container nav">
            <div class="logo">
                <a href="index.php">
                    <h2>Baking <span>Mellow</span></h2>
                </a>
            </div>
            
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php" class="auth-btn">Login</a></li>
                <li><a href="cart.php" class="cart-icon">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count">0</span>
                </a></li>
            </ul>
            
            <div class="hamburger" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero" data-aos="fade-in">
        <div class="container hero-content">
            <div class="hero-text" data-aos="fade-right" data-aos-delay="200">
                <span class="tagline">
                    <i class="fas fa-seedling"></i> Artisan Bakery • Fresh Daily
                </span>
                <h1>Crafted With Passion,<br>Baked To Perfection</h1>
                <p>From buttery croissants to handcrafted celebration cakes, every creation begins with carefully selected ingredients and timeless baking techniques.</p>
                <div class="hero-buttons">
                    <a href="menu.php" class="btn"><i class="fas fa-store"></i> Explore Menu</a>
                    <a href="about.php" class="btn-outline"><i class="fas fa-heart"></i> Our Story</a>
                </div>
            </div>
            <div class="hero-image" data-aos="fade-left" data-aos-delay="400">
                <div class="hero-image-wrapper">
                    <img src="images/bm.png" alt="Premium Cake">
                    <div class="floating-badge">
                        <span>Premium</span>
                        <small>Ingredients</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-indicator">
            <span>Scroll</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- CATEGORIES -->
    <section class="categories-section" data-aos="fade-up">
        <div class="container">
            <div class="section-header">
                <span class="subtitle">Explore Our</span>
                <h2 class="section-title">Signature Collection</h2>
                <p class="section-sub">Handcrafted daily with premium ingredients and timeless techniques</p>
            </div>
            <div class="categories-grid">
                <div class="category-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="category-image">
                        <img src="images/category-cakes.jpg" alt="Cakes">
                        <div class="category-overlay"><h3>Cakes</h3><span>From Rs. 2,500</span></div>
                    </div>
                </div>
                <div class="category-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="category-image">
                        <img src="images/category-cupcakes.jpg" alt="Cupcakes">
                        <div class="category-overlay"><h3>Cupcakes</h3><span>From Rs. 1,800</span></div>
                    </div>
                </div>
                <div class="category-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="category-image">
                        <img src="images/category-pastries.jpg" alt="Pastries">
                        <div class="category-overlay"><h3>Pastries</h3><span>From Rs. 750</span></div>
                    </div>
                </div>
                <div class="category-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="category-image">
                        <img src="images/category-bread.jpg" alt="Bread">
                        <div class="category-overlay"><h3>Artisan Bread</h3><span>From Rs. 600</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BEST SELLERS -->
    <section class="products-section" data-aos="fade-up">
        <div class="container">
            <div class="section-header">
                <span class="subtitle">Customer Favorites</span>
                <h2 class="section-title">Best Sellers</h2>
                <p class="section-sub">The most loved treats from our kitchen, handpicked by you</p>
            </div>
            <div class="products-grid">
                <div class="product-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-image">
                        <img src="images/product-chocolate-cake.jpg" alt="Belgian Chocolate Cake">
                        <div class="product-badge">Best Seller</div>
                    </div>
                    <div class="product-info">
                        <h3>Belgian Chocolate Cake</h3>
                        <p class="product-desc">Rich cocoa sponge layered with silky Belgian chocolate ganache.</p>
                        <div class="product-footer">
                            <span class="price">Rs. 3,250</span>
                            <button class="btn-sm" onclick="addToCart(1, 'Belgian Chocolate Cake', 3250)"><i class="fas fa-plus"></i> Add</button>
                        </div>
                    </div>
                </div>
                <div class="product-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="product-image">
                        <img src="images/product-croissant.jpg" alt="Butter Croissant">
                        <div class="product-badge">Fresh</div>
                    </div>
                    <div class="product-info">
                        <h3>Butter Croissant</h3>
                        <p class="product-desc">Hand-laminated with cultured French butter, revealing crisp golden layers.</p>
                        <div class="product-footer">
                            <span class="price">Rs. 750</span>
                            <button class="btn-sm" onclick="addToCart(2, 'Butter Croissant', 750)"><i class="fas fa-plus"></i> Add</button>
                        </div>
                    </div>
                </div>
                <div class="product-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="product-image">
                        <img src="images/product-strawberry-tart.jpg" alt="Strawberry Tart">
                        <div class="product-badge">Seasonal</div>
                    </div>
                    <div class="product-info">
                        <h3>Strawberry Tart</h3>
                        <p class="product-desc">Buttery pâte sucrée filled with vanilla bean crème pâtissière.</p>
                        <div class="product-footer">
                            <span class="price">Rs. 1,850</span>
                            <button class="btn-sm" onclick="addToCart(3, 'Strawberry Tart', 1850)"><i class="fas fa-plus"></i> Add</button>
                        </div>
                    </div>
                </div>
                <div class="product-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="product-image">
                        <img src="images/product-cinnamon-roll.jpg" alt="Cinnamon Roll">
                        <div class="product-badge">Popular</div>
                    </div>
                    <div class="product-info">
                        <h3>Cinnamon Roll</h3>
                        <p class="product-desc">Soft spirals infused with Saigon cinnamon, brown sugar, and finished with a smooth vanilla glaze.</p>
                        <div class="product-footer">
                            <span class="price">Rs. 650</span>
                            <button class="btn-sm" onclick="addToCart(4, 'Cinnamon Roll', 650)"><i class="fas fa-plus"></i> Add</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-footer" data-aos="fade-up">
                <a href="menu.php" class="btn">View Full Menu <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US -->
    <section class="why-section" data-aos="fade-up">
        <div class="container">
            <div class="section-header">
                <span class="subtitle">Why Choose Us</span>
                <h2 class="section-title">The Baking Mellow Difference</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon"><i class="fas fa-seedling"></i></div>
                    <h3>Premium Ingredients</h3>
                    <p>French butter, Belgian chocolate, fresh cream and locally sourced seasonal fruits.</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon"><i class="fas fa-clock"></i></div>
                    <h3>Fresh Every Morning</h3>
                    <p>Every order is prepared fresh to ensure exceptional flavor and texture. No shortcuts.</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon"><i class="fas fa-hand-sparkles"></i></div>
                    <h3>Handcrafted Artistry</h3>
                    <p>Every pastry is shaped, baked and decorated with artisan care and attention to detail.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FRESH BANNER -->
    <section class="fresh-banner" data-aos="fade-up">
        <div class="container">
            <h2>Freshly Baked Every Morning</h2>
            <p>Wake up to the aroma of freshly baked pastries. Order now and enjoy the taste of quality.</p>
            <a href="menu.php" class="btn">Order Now <i class="fas fa-arrow-right"></i></a>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="testimonials-section" data-aos="fade-up">
        <div class="container">
            <div class="section-header">
                <span class="subtitle">What People Say</span>
                <h2 class="section-title">Loved By Our Customers</h2>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testimonial-text">"The croissants were unbelievably flaky and buttery. Every bite felt like it came straight from a Parisian bakery."</p>
                    <div class="testimonial-author"><img src="images/avatar1.jpg" alt="Sarah"><div><h4>Sarah A.</h4><span>Regular Customer</span></div></div>
                </div>
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testimonial-text">"The birthday cake exceeded every expectation. Beautifully decorated and incredibly rich in flavor. My family loved it!"</p>
                    <div class="testimonial-author"><img src="images/avatar2.jpg" alt="Ahmed"><div><h4>Ahmed K.</h4><span>Happy Customer</span></div></div>
                </div>
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testimonial-text">"Our family now orders every celebration cake from Baking Mellow. The quality is consistently amazing."</p>
                    <div class="testimonial-author"><img src="images/avatar3.jpg" alt="Hira"><div><h4>Hira M.</h4><span>Loyal Customer</span></div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- INSTAGRAM GALLERY -->
    <section class="gallery-section" data-aos="fade-up">
        <div class="container">
            <div class="section-header">
                <span class="subtitle">Follow Us</span>
                <h2 class="section-title">Fresh From Our Kitchen</h2>
                <p class="section-sub">Behind the scenes, fresh creations, and daily inspiration</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100"><img src="images/gallery1.jpg" alt="Bakery"><div class="gallery-overlay"><i class="fab fa-instagram"></i></div></div>
                <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200"><img src="images/gallery2.jpg" alt="Bakery"><div class="gallery-overlay"><i class="fab fa-instagram"></i></div></div>
                <div class="gallery-item" data-aos="zoom-in" data-aos-delay="300"><img src="images/gallery3.jpg" alt="Bakery"><div class="gallery-overlay"><i class="fab fa-instagram"></i></div></div>
                <div class="gallery-item" data-aos="zoom-in" data-aos-delay="400"><img src="images/gallery4.jpg" alt="Bakery"><div class="gallery-overlay"><i class="fab fa-instagram"></i></div></div>
                <div class="gallery-item" data-aos="zoom-in" data-aos-delay="500"><img src="images/gallery5.jpg" alt="Bakery"><div class="gallery-overlay"><i class="fab fa-instagram"></i></div></div>
                <div class="gallery-item" data-aos="zoom-in" data-aos-delay="600"><img src="images/gallery6.jpg" alt="Bakery"><div class="gallery-overlay"><i class="fab fa-instagram"></i></div></div>
            </div>
            <div class="section-footer" data-aos="fade-up">
                <a href="https://instagram.com/bakingmellow" target="_blank" class="btn-outline"><i class="fab fa-instagram"></i> Follow @bakingmellow</a>
            </div>
        </div>
    </section>

    <!-- NEWSLETTER -->
    <section class="newsletter-section" data-aos="fade-up">
        <div class="container">
            <div class="newsletter-box">
                <div class="newsletter-content">
                    <h2>Stay Inspired</h2>
                    <p>Be the first to discover seasonal creations, exclusive offers and freshly baked favorites delivered to your inbox.</p>
                    <form id="newsletterForm" onsubmit="subscribeNewsletter(event)">
                        <div class="newsletter-input">
                            <input type="email" id="newsletterEmail" placeholder="Enter your email address" required>
                            <button type="submit" class="btn"><i class="fas fa-paper-plane"></i> Subscribe</button>
                        </div>
                        <small>No spam, ever. Unsubscribe anytime.</small>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h2 class="footer-logo">Baking <span>Mellow</span></h2>
                    <p class="footer-desc">Crafted daily with patience, precision, and premium ingredients.</p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="cart.php">Cart</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Contact Info</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-location-dot"></i> Sargodha, Pakistan</li>
                        <li><i class="fas fa-phone"></i> +92 342 6275592</li>
                        <li><i class="fas fa-envelope"></i> bakingmellow@gmail.com</li>
                        <li><i class="fas fa-clock"></i> Mon-Sat: 9AM - 8PM</li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Opening Hours</h3>
                    <ul class="hours">
                        <li>Monday - Saturday</li>
                        <li><strong>9:00 AM - 8:00 PM</strong></li>
                        <li>Sunday</li>
                        <li><strong>10:00 AM - 6:00 PM</strong></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Baking Mellow. All Rights Reserved. | Crafted with <i class="fas fa-heart" style="color: #d4a373;"></i> by MEERAB IMRAN</p>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });
        function toggleMenu() { document.querySelector('.nav-links').classList.toggle('active'); }

        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        function addToCart(id, name, price) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) { existingItem.quantity += 1; } 
            else { cart.push({ id, name, price, quantity: 1 }); }
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
            showNotification('Added to cart! 🛒');
        }
        function updateCartCount() {
            const count = cart.reduce((total, item) => total + item.quantity, 0);
            const badge = document.querySelector('.cart-count');
            if (badge) { badge.textContent = count; badge.style.display = count > 0 ? 'block' : 'none'; }
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
        function subscribeNewsletter(e) {
            e.preventDefault();
            alert('✅ Thank you for subscribing to Baking Mellow newsletter!');
            document.getElementById('newsletterForm').reset();
        }
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
    <style>
        .notification { position: fixed; top: 100px; right: 20px; z-index: 9999; opacity: 0; transform: translateY(-20px); transition: all 0.3s ease; }
        .notification.show { opacity: 1; transform: translateY(0); }
        .notification-content { background: #171717; border: 1px solid #d4a373; border-radius: 12px; padding: 15px 25px; display: flex; align-items: center; gap: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .notification-icon { background: #d4a373; color: #0a0a0a; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
    </style>
</body>
</html>