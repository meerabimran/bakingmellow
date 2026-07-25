<?php
session_start();
include '../includes/db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%"; $params[] = "%$search%";
}
if (!empty($category) && $category !== 'all') {
    $sql .= " AND category = ?";
    $params[] = $category;
}
$sql .= " ORDER BY is_bestseller DESC, id ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$categories = $conn->query("SELECT DISTINCT category FROM products")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu | Baking Mellow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/menu.css">
</head>
<body>

    <nav>
        <div class="container nav">
            <div class="logo"><a href="index.php"><h2>Baking <span>Mellow</span></h2></a></div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php" class="active">Menu</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php" class="auth-btn">Login</a></li>
                <li><a href="cart.php" class="cart-icon"><i class="fas fa-shopping-bag"></i><span class="cart-count">0</span></a></li>
            </ul>
            <div class="hamburger" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
        </div>
    </nav>

    <section class="page-header" data-aos="fade-in">
        <div class="container">
            <h1>Our Menu</h1>
            <p>Handcrafted daily with premium ingredients — every bite tells a story</p>
            <div class="breadcrumb"><a href="index.php">Home</a> / <span>Menu</span></div>
        </div>
    </section>

    <section class="menu-section" data-aos="fade-up">
        <div class="container">
            <div class="menu-toolbar">
                <div class="search-box">
                    <form method="GET" action="menu.php">
                        <input type="text" name="search" placeholder="Search our menu..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="filter-buttons">
                    <a href="menu.php" class="filter-btn <?php echo empty($category) ? 'active' : ''; ?>">All</a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="?category=<?php echo urlencode($cat['category']); ?>" class="filter-btn <?php echo $category === $cat['category'] ? 'active' : ''; ?>"><?php echo htmlspecialchars($cat['category']); ?></a>
                    <?php endforeach; ?>
                </div>
                <div class="view-toggle">
                    <button onclick="setView('grid')" class="active" id="gridView"><i class="fas fa-th"></i></button>
                    <button onclick="setView('list')" id="listView"><i class="fas fa-list"></i></button>
                </div>
            </div>

            <div class="products-grid" id="productContainer" data-aos="fade-up">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card" data-aos="fade-up" data-aos-delay="100">
                            <div class="product-image" onclick="openProductModal(<?php echo $product['id']; ?>)">
                                <img src="../images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php if ($product['is_bestseller']): ?><div class="product-badge">Best Seller</div><?php endif; ?>
                                <?php if ($product['is_new']): ?><div class="product-badge new">New</div><?php endif; ?>
                                <div class="quick-view"><i class="fas fa-eye"></i> Quick View</div>
                            </div>
                            <div class="product-info">
                                <div class="product-category"><?php echo htmlspecialchars($product['category']); ?></div>
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-desc"><?php echo htmlspecialchars(substr($product['description'], 0, 80)) . '...'; ?></p>
                                <div class="product-footer">
                                    <span class="price">Rs. <?php echo number_format($product['price'], 0); ?></span>
                                    <button class="btn-sm" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>', <?php echo $product['price']; ?>)"><i class="fas fa-plus"></i> Add</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-products">
                        <i class="fas fa-search"></i>
                        <h3>No products found</h3>
                        <p>Try adjusting your search or filter.</p>
                        <a href="menu.php" class="btn">View All Products</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- PRODUCT MODAL -->
    <div class="product-modal" id="productModal">
        <div class="modal-overlay" onclick="closeProductModal()"></div>
        <div class="modal-content">
            <button class="modal-close" onclick="closeProductModal()"><i class="fas fa-times"></i></button>
            <div class="modal-grid">
                <div class="modal-image"><img id="modalImage" src="" alt="Product"></div>
                <div class="modal-info">
                    <div class="modal-category" id="modalCategory"></div>
                    <h2 id="modalName"></h2>
                    <div class="modal-rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><span>(4.5) 128 reviews</span></div>
                    <p class="modal-price" id="modalPrice"></p>
                    <p class="modal-desc" id="modalDesc"></p>
                    <div class="modal-options">
                        <div class="modal-quantity">
                            <label>Quantity</label>
                            <div class="qty-controls">
                                <button onclick="changeQty(-1)">−</button>
                                <span id="modalQty">1</span>
                                <button onclick="changeQty(1)">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button class="btn" onclick="addToCartFromModal()"><i class="fas fa-shopping-bag"></i> Add to Cart</button>
                        <button class="btn-outline"><i class="fas fa-heart"></i> Wishlist</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });
        function toggleMenu() { document.querySelector('.nav-links').classList.toggle('active'); }
        function setView(view) {
            const container = document.getElementById('productContainer');
            document.getElementById('gridView').classList.toggle('active', view === 'grid');
            document.getElementById('listView').classList.toggle('active', view === 'list');
            container.classList.toggle('list-view', view === 'list');
            localStorage.setItem('menuView', view);
        }
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('menuView') === 'list') setView('list');
            updateCartCount();
        });

        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        function addToCart(id, name, price) {
            const existing = cart.find(item => item.id === id);
            if (existing) existing.quantity++; else cart.push({ id, name, price, quantity: 1 });
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

        let modalProductId = null, modalQty = 1;
        function openProductModal(id) {
            fetch(`get_product.php?id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const p = data.product;
                        modalProductId = p.id;
                        document.getElementById('modalImage').src = `../images/${p.image_url}`;
                        document.getElementById('modalCategory').textContent = p.category;
                        document.getElementById('modalName').textContent = p.name;
                        document.getElementById('modalPrice').textContent = `Rs. ${Number(p.price).toLocaleString()}`;
                        document.getElementById('modalDesc').textContent = p.description;
                        document.getElementById('productModal').classList.add('active');
                        document.body.style.overflow = 'hidden';
                        modalQty = 1;
                        document.getElementById('modalQty').textContent = '1';
                    }
                });
        }
        function closeProductModal() {
            document.getElementById('productModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        function changeQty(change) {
            modalQty += change;
            if (modalQty < 1) modalQty = 1;
            document.getElementById('modalQty').textContent = modalQty;
        }
        function addToCartFromModal() {
            if (modalProductId) {
                fetch(`get_product.php?id=${modalProductId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            for (let i = 0; i < modalQty; i++) addToCart(data.product.id, data.product.name, data.product.price);
                            closeProductModal();
                        }
                    });
            }
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeProductModal(); });
    </script>
</body>
</html>