<?php
include 'includes/auth.php';
include '../includes/db.php';

// Mark message as read
if (isset($_GET['read'])) {
    $id = intval($_GET['read']);
    $stmt = $conn->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: messages.php');
    exit();
}

// Delete message
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: messages.php');
    exit();
}

// Fetch all messages
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main class="admin-content">
    <div class="admin-header">
        <h1>Messages</h1>
    </div>
    
    <div class="admin-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($messages->num_rows > 0): ?>
                        <?php while ($msg = $messages->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $msg['name']; ?></strong><br>
                                    <small style="color:#888;"><?php echo $msg['phone'] ?: '-'; ?></small>
                                </td>
                                <td><?php echo $msg['email']; ?></td>
                                <td><?php echo $msg['subject'] ?: 'General'; ?></td>
                                <td style="max-width:300px;">
                                    <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        <?php echo htmlspecialchars(substr($msg['message'], 0, 80)); ?>
                                        <?php if (strlen($msg['message']) > 80): ?>...<?php endif; ?>
                                    </div>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($msg['created_at'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $msg['is_read'] ? 'status-delivered' : 'status-pending'; ?>">
                                        <?php echo $msg['is_read'] ? 'Read' : 'Unread'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?read=<?php echo $msg['id']; ?>" class="btn-sm btn-edit" title="Mark as read">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="?delete=<?php echo $msg['id']; ?>" 
                                       class="btn-sm btn-delete"
                                       onclick="return confirm('Delete this message?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No messages found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>