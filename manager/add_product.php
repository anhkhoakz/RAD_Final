<?php

session_start();
require_once('../users/db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

if (
    !isset($_POST['product_name']) || !isset($_POST['price']) ||
    !isset($_POST['quantity']) || !isset($_POST['category']) ||
    !isset($_FILES['img'])
) {
    http_response_code(400);
    die(json_encode(['error' => 'Missing required fields']));
}

$product_name = $_POST['product_name'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$category = $_POST['category'];

$img = $_FILES['img'];
$ImgName = $img['name'];
$ImgTmpName = $img['tmp_name'];
$BaseLink = "http://localhost/coffee-shop-website/assets/images/";
$uploadDir = '../assets/images/';
$uploadFile = $uploadDir . basename($ImgName);
$destination = $BaseLink . $ImgName;


if (move_uploaded_file($ImgTmpName, $uploadFile)) {
    $sql = "INSERT INTO products (title, price, quantity, category, img) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdiss', $product_name, $price, $quantity, $category, $destination);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Product added successfully']);
    } else {
        http_response_code(500);
        die(json_encode(['error' => 'Failed to add product']));
    }
    $stmt->close();
} else {
    http_response_code(500);
    die(json_encode(['error' => 'Failed to upload image']));
}
