<?php

session_start();

header('Content-Type: application/json');

require_once '../users/db.php';

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    die(json_encode(['error' => 'User not authenticated']));
}

$username = $_SESSION['username'];

$sql = 'SELECT id FROM purchase_history WHERE username = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$purchases = $result->fetch_all(MYSQLI_ASSOC);

$productTitles = [];
foreach ($purchases as $purchase) {
    $productId = $purchase['id'];
    $sql = 'SELECT title FROM products WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $productTitles[] = $product['title'];
}

die(json_encode($productTitles));
