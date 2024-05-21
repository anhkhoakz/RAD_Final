<?php

session_start();

require_once '../users/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    // die(json_encode(array('status' => 'error', 'message' => 'You are not logged in')));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(array('status' => 'error', 'message' => 'Invalid request method')));
}

// $username = $_SESSION['username'];
$username = "anhkhoakz";

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['order_id'])) {
    die(json_encode(array('status' => 'error', 'message' => 'Order ID is required')));
}

if (!isset($data['product_id'])) {
    die(json_encode(array('status' => 'error', 'message' => 'Product ID is required')));
}

$order_id = $data['order_id'];
$product_id = $data['product_id'];

$query = $conn->prepare('SELECT * FROM purchase_history WHERE invoice_number = ? AND id = ? AND username = ?');
$query->bind_param('sis', $order_id, $product_id, $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    die(json_encode(array('status' => 'error', 'message' => 'Order not found')));
}

$sql = 'DELETE FROM purchase_history WHERE invoice_number = ? AND id = ? AND username = ?';
$query = $conn->prepare($sql);
$query->bind_param('sis', $order_id, $product_id, $username);
$query->execute();

die(json_encode(array('status' => 'success', 'message' => 'Order has been cancelled')));
