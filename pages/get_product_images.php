<?php
header('Content-Type: application/json');
include __DIR__ . '/../includes/db.php';

if (isset($_GET['ids'])) {
    $ids = explode(',', $_GET['ids']);
    $ids = array_map('intval', $ids);
    $idPlaceholders = implode(',', array_fill(0, count($ids), '?'));
    
    $stmt = $conn->prepare("SELECT id, image_url FROM products WHERE id IN ($idPlaceholders)");
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = [
            'id' => $row['id'],
            'image_url' => $row['image_url']
        ];
    }
    
    echo json_encode(['success' => true, 'images' => $images]);
} else {
    echo json_encode(['success' => false, 'error' => 'No IDs provided']);
}
?>
