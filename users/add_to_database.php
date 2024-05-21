<?php
include 'db.php';

$orderDetails = json_decode(file_get_contents('php://input'), true);

session_start();

$user = $_SESSION['username'];
$sql1 = "SELECT * FROM users WHERE username = ?";
$stmt = $con->prepare($sql1);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
}

// Insert order details into database
$date = date('Y-m-d');
foreach ($orderDetails as $order) {
    $title = $order['title'];
    $price = $order['price'];
    $quantity = $order['quantity'];
    $subtotalAmount = $order['subtotal_amount'];
    $invoiceNumber = $order['invoice_number'];
    $sql = "INSERT INTO orders (price, title, quantity, subtotal_amount, date, invoice_number, user_id) VALUES (? , ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssss", $price, $title, $quantity, $subtotalAmount, $date, $invoiceNumber, $id);
    $stmt->execute();
}


$con->close();
