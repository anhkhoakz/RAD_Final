<?php

session_start();

require_once '../users/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

function generateInvoiceNumber()
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $invoiceNumber = '';
    for ($i = 0; $i < 25; $i++) {
        $invoiceNumber .= $characters[rand(0, $charactersLength - 1)];
    }
    return $invoiceNumber;
}

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not logged in']);
}

$username = $_SESSION['username'];
// $username = "anhkhoakz";

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'];
$phonenumber = $data['phonenumber'];
$address = $data['address'];

if (empty($name) || empty($phonenumber) || empty($address)) {
    echo json_encode(['error' => 'Please fill in all fields']);
    exit();
}

$sql = "SELECT * FROM cart WHERE username = '$username'";
$result = $conn->query($sql);

$data = $result->fetch_all(MYSQLI_ASSOC);
$created_date = date('Y-m-d H:i:s');
$invoice_number = generateInvoiceNumber();

for ($i = 0; $i < count($data); $i++) {
    $product = $data[$i];
    $product_id = $product['product_id'];

    $quantity = $product['quantity'];

    $sql = "SELECT price FROM products WHERE id = $product_id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();

    $price = $product['price'];

    $subtotal_amount = $price * $quantity;

    $sql = "INSERT INTO purchase_history (id, price, quantity, subtotal_amount, created_date, invoice_number, username, review, name, phonenumber, address) VALUES ($product_id, $price, $quantity, $subtotal_amount, '$created_date', '$invoice_number', '$username', NULL, '$name', '$phonenumber', '$address')";

    $conn->query($sql);
}

$sql = "DELETE FROM cart WHERE username = '$username'";
$conn->query($sql);

echo json_encode(['success' => 'Checkout successful']);
