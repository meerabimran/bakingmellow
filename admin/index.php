<?php
include 'includes/auth.php';
include '../includes/db.php';

// Get stats
$stats = [];

// Total products
$result = $conn->query("SELECT COUNT(*) as count FROM products");
$stats['products'] = $result->fetch_assoc()['count'];

// Total orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders");
$stats['orders'] = $result->fetch_assoc()['count'];

// Total customers
$result = $conn->query("SELECT COUNT(*) as count FROM users");
$stats['customers'] = $result->fetch_assoc()['count'];

// Total messages
$result = $conn->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0");
$stats['unread_messages'] = $result->fetch_assoc()['count'];

// Revenue
$result = $conn->query("SELECT SUM(total_amount) as revenue FROM orders WHERE status != 'cancelled'");
$stats['revenue'] = $result->fetch_assoc()['revenue'] ?? 0;

// Recent orders
$recent_orders = $conn->query("
    SELECT o.*, u.firstname, u.lastname 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC LIMIT 5
");

// Recent messages
$recent_messages = $conn->query("
    SELECT * FROM contact_messages 
    WHERE is_read = 0 
    ORDER BY created_at DESC LIMIT 5
");
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main class="admin-content">
    <div class="admin-header">
        <h1>Dashboard</h1>
        <div class="admin-header-right">
            <span>Welcome, <?php echo $_SESSION['admin_username']; ?>!</span>
            <button class="sidebar-toggle"><i class="fas fa-bars"></i></button>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-cake"></i></div>
            <div class="stat-info">
                <span class="stat-label">Total Products</span>
                <span class="stat-number"><?php echo $stats['products']; ?></span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-truck"></i></div>
            <div class="stat-info">
                <span class="stat-label">Total Orders</span>
                <span class="stat-number"><?php echo $stats['orders']; ?></span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <span class="stat-label">Total Customers</span>
                <span class="stat-number"><?php echo $stats['customers']; ?></span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-envelope"></i></div>
            <div class="stat-info">
                <span class="stat-label">Unread Messages</span>
                <span class="stat-number"><?php echo $stats['unread_messages']; ?></span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-info">
                <span class="stat-label">Total Revenue</span>
                <span class="stat-number">Rs. <?php echo number_format($stats['revenue'], 0); ?></span>
            </div>
        </div>
    </div>
    
    <div class="admin-grid">
        <div class="admin-card">
            <div class="card-header">
                <h3>Recent Orders</h3>
                <a href="orders.php" class="btn-sm">View All</a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_orders->num_rows > 0): ?>
                            <?php while ($order = $recent_orders->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $order['order_number']; ?></td>
                                    <td><?php echo $order['firstname'] . ' ' . $order['lastname']; ?></td>
                                    <td>Rs. <?php echo number_format($order['total_amount'], 0); ?></td>
                                    <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No orders yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="admin-card">
            <div class="card-header">
                <h3>Unread Messages</h3>
                <a href="messages.php" class="btn-sm">View All</a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_messages->num_rows > 0): ?>
                            <?php while ($msg = $recent_messages->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $msg['name']; ?></td>
                                    <td><?php echo $msg['subject'] ?: 'General'; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($msg['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">No unread messages</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>