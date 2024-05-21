<?php

session_start();

require_once '../users/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    die(json_encode(array('status' => 'error', 'message' => 'You are not logged in')));
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die(json_encode(array('status' => 'error', 'message' => 'Invalid request method')));
}

$username = $_SESSION['username'];

$product_id = $_GET['product_id'];
$invoice_number = $_GET['invoice_number'];

$query = $conn->prepare('SELECT stars, review FROM purchase_history WHERE invoice_number = ? AND id = ? AND username = ?');
$query->bind_param('sis', $invoice_number, $product_id, $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    die(json_encode(array('status' => 'error', 'message' => 'Order not found')));
}

die(json_encode(array('status' => 'success', 'data' => $result->fetch_assoc())));
