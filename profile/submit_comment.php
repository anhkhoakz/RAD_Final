<?php

session_start();

require_once '../users/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    die(json_encode(array('status' => 'error', 'message' => 'You are not logged in')));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(array('status' => 'error', 'message' => 'Invalid request method')));
}

$username = $_SESSION['username'];

$data = json_decode(file_get_contents('php://input'), true);

$rating_star = $data['rating_star'];
$rating_comment = $data['comment'];
$product_id = $data['product_id'];
$invoice_number = $data['invoice_number'];

$query = $conn->prepare('SELECT * FROM purchase_history WHERE invoice_number = ? AND id = ? AND username = ?');
$query->bind_param('sis', $invoice_number, $product_id, $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    die(json_encode(array('status' => 'error', 'message' => 'Order not found')));
}

$sql = 'UPDATE purchase_history SET stars = ?, review = ? WHERE invoice_number = ? AND id = ? AND username = ?';
$query = $conn->prepare($sql);
$query->bind_param('issis', $rating_star, $rating_comment, $invoice_number, $product_id, $username);
$query->execute();


die(json_encode(array('status' => 'success', 'message' => 'Comment has been submitted')));
