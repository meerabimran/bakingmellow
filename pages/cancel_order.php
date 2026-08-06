<?php
session_start();
header('Content-Type: application/json');
include __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$order_id = intval($data['order_id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($order_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid order ID']);
    exit();
}

$stmt = $conn->prepare("SELECT id, status FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Order not found']);
    exit();
}

$order = $result->fetch_assoc();
if ($order['status'] !== 'pending') {
    echo json_encode(['success' => false, 'error' => 'Only pending orders can be cancelled']);
    exit();
}

$stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
$stmt->bind_param("i", $order_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Order cancelled']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to cancel order']);
}
?>
