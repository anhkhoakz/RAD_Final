<?php

header('Content-Type: application/json');

require_once '../users/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$data = json_decode(file_get_contents('php://input'), true);

$product_id = $data['product_id'];
$user_id = $data['user_id'];

if (empty($product_id) || empty($user_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request data']);
    exit;
}

$sql = "DELETE FROM cart WHERE product_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $product_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows) {
    echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
}
