<?php

session_start();

header('Content-Type: application/json');

require_once '../users/db.php';

$user_name = $_SESSION('user_name');

if (empty($product_id) || empty($quantity) || empty($user_name)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request data']);
    exit;
}

$sql = "SELECT * FROM cart WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_name);
$stmt->execute();

$result = $stmt->get_result();
$cart = $result->fetch_all(MYSQLI_ASSOC);

$total = 0;

foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

echo json_encode(['status' => 'success', 'total' => $total]);
