<?php
include 'includes/auth.php';
include '../includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
    $is_new = isset($_POST['is_new']) ? 1 : 0;
    
    if (empty($name) || empty($category) || $price <= 0) {
        $error = 'Please fill in all required fields.';
    } else {
        // Handle image upload
        $image_url = 'placeholder.jpg';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $new_name = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                $upload_path = '../images/' . $new_name;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $image_url = $new_name;
                }
            }
        }
        
        $stmt = $conn->prepare("
            INSERT INTO products (name, category, description, price, image_url, is_available, is_bestseller, is_new) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssdsiii", $name, $category, $description, $price, $image_url, $is_available, $is_bestseller, $is_new);
        
        if ($stmt->execute()) {
            $success = 'Product added successfully!';
        } else {
            $error = 'Error adding product: ' . $conn->error;
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main class="admin-content">
    <div class="admin-header">
        <h1>Add New Product</h1>
        <a href="products.php" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
    
    <?php if ($error): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="admin-card">
        <form method="POST" action="" enctype="multipart/form-data" class="admin-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Category *</label>
                    <select id="category" name="category" required>
                        <option value="">Select category</option>
                        <option value="Cakes">Cakes</option>
                        <option value="Cupcakes">Cupcakes</option>
                        <option value="Pastries">Pastries</option>
                        <option value="Cookies">Cookies</option>
                        <option value="Bread">Artisan Bread</option>
                        <option value="Tarts">Tarts</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="price">Price (Rs.) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
            </div>
            
            <div class="form-row checkboxes">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_available" checked>
                    <span>Available</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="is_bestseller">
                    <span>Best Seller</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="is_new">
                    <span>New Arrival</span>
                </label>
            </div>
            
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Save Product
            </button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>