<?php
session_start();

require_once '../users/db.php';


$username = $_SESSION['username'];

header("Content-Type: application/json; charset=utf-8");

if (empty($username)) {
    http_response_code(400);
    die(json_encode(array("code" => 3, 'message' => 'Please provide a product id')));
}

$sql = 'SELECT * FROM cart WHERE username = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $username);
$stmt->execute();

$result = $stmt->get_result();

$cart = array();
while ($row = $result->fetch_assoc()) {
    $cart[] = $row;
}

die(json_encode($cart));
