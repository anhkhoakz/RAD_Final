<?php
session_start();

require_once '../users/db.php';

header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(array("code" => 2, "message" => $_SERVER["REQUEST_METHOD"] . " method is not supported.")));
}

$data = json_decode(file_get_contents('php://input'), true);

$product_id = $data['product_id'];
$quantity = $data['quantity'];
$user_name = $_SESSION['username'];

$sql = "SELECT * FROM cart WHERE product_id = $product_id AND username = '$user_name'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $sql = "UPDATE cart SET quantity = $quantity WHERE product_id = $product_id AND username = '$user_name'";
}

if ($conn->query($sql) === TRUE) {
    die(json_encode(array("code" => 0, "message" => "Product updated successfully.")));
} else {
    http_response_code(500);
    die(json_encode(array("code" => 1, "message" => "Error: " . $sql . "<br>" . $conn->error)));
}
