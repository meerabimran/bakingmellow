<?php
include 'includes/auth.php';
include '../includes/db.php';

$id = intval($_GET['id'] ?? 0);
$product = null;
$error = '';
$success = '';

// Fetch product
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}

if (!$product) {
    header('Location: products.php');
    exit();
}

// Handle form submission
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
        $image_url = $product['image_url'];
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $new_name = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                $upload_path = '../images/' . $new_name;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // Delete old image if not placeholder
                    if ($image_url !== 'placeholder.jpg' && file_exists('../images/' . $image_url)) {
                        unlink('../images/' . $image_url);
                    }
                    $image_url = $new_name;
                }
            }
        }
        
        $stmt = $conn->prepare("
            UPDATE products 
            SET name = ?, category = ?, description = ?, price = ?, image_url = ?, 
                is_available = ?, is_bestseller = ?, is_new = ?
            WHERE id = ?
        ");
        $stmt->bind_param("sssdsiiii", $name, $category, $description, $price, $image_url, $is_available, $is_bestseller, $is_new, $id);
        
        if ($stmt->execute()) {
            $success = 'Product updated successfully!';
            // Refresh product data
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
        } else {
            $error = 'Error updating product: ' . $conn->error;
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main class="admin-content">
    <div class="admin-header">
        <h1>Edit Product</h1>
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
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Category *</label>
                    <select id="category" name="category" required>
                        <option value="Cakes" <?php echo $product['category'] === 'Cakes' ? 'selected' : ''; ?>>Cakes</option>
                        <option value="Cupcakes" <?php echo $product['category'] === 'Cupcakes' ? 'selected' : ''; ?>>Cupcakes</option>
                        <option value="Pastries" <?php echo $product['category'] === 'Pastries' ? 'selected' : ''; ?>>Pastries</option>
                        <option value="Cookies" <?php echo $product['category'] === 'Cookies' ? 'selected' : ''; ?>>Cookies</option>
                        <option value="Bread" <?php echo $product['category'] === 'Bread' ? 'selected' : ''; ?>>Artisan Bread</option>
                        <option value="Tarts" <?php echo $product['category'] === 'Tarts' ? 'selected' : ''; ?>>Tarts</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="price">Price (Rs.) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo $product['price']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <?php if ($product['image_url'] !== 'placeholder.jpg'): ?>
                        <small style="color:#888;margin-top:5px;display:block;">
                            Current: <img src="../images/<?php echo $product['image_url']; ?>" style="width:40px;height:40px;object-fit:cover;border-radius:4px;vertical-align:middle;margin-left:5px;">
                        </small>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-row checkboxes">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_available" <?php echo $product['is_available'] ? 'checked' : ''; ?>>
                    <span>Available</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="is_bestseller" <?php echo $product['is_bestseller'] ? 'checked' : ''; ?>>
                    <span>Best Seller</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="is_new" <?php echo $product['is_new'] ? 'checked' : ''; ?>>
                    <span>New Arrival</span>
                </label>
            </div>
            
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Update Product
            </button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>