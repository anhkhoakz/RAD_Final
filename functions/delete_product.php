<?php

session_start();

if (!isset($_SESSION['username'])) {
    die(json_encode(['error' => 'You are not logged in']));
}

require_once '../users/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die(json_encode(['error' => 'Invalid request method']));
}

$username = $_SESSION['username'];

$sql = 'SELECT * FROM users WHERE username = ? AND role = "admin"';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die(json_encode(['error' => 'You are not authorized to delete proucts']));
}

if (!isset($_GET['product_id'])) {
    die(json_encode(['error' => 'Product ID is required']));
}

$product_id = $_GET['product_id'];

$sql = 'SELECT * FROM products WHERE id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die(json_encode(['error' => 'Product not found']));
}

$sql = 'DELETE FROM products WHERE id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $product_id);
$stmt->execute();

echo json_encode(['success' => 'Product deleted successfully']);
