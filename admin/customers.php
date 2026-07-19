<?php
include 'includes/auth.php';
include '../includes/db.php';

// Fetch all customers
$customers = $conn->query("
    SELECT u.*, COUNT(o.id) as order_count, SUM(o.total_amount) as total_spent 
    FROM users u 
    LEFT JOIN orders o ON u.id = o.user_id 
    GROUP BY u.id 
    ORDER BY u.created_at DESC
");
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main class="admin-content">
    <div class="admin-header">
        <h1>Customers</h1>
    </div>
    
    <div class="admin-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($customers->num_rows > 0): ?>
                        <?php while ($customer = $customers->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $customer['id']; ?></td>
                                <td>
                                    <strong><?php echo $customer['firstname'] . ' ' . $customer['lastname']; ?></strong>
                                </td>
                                <td><?php echo $customer['email']; ?></td>
                                <td><?php echo $customer['phone'] ?: '-'; ?></td>
                                <td><?php echo $customer['order_count']; ?></td>
                                <td>Rs. <?php echo number_format($customer['total_spent'] ?? 0, 0); ?></td>
                                <td><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No customers found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>