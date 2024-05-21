<?php

session_start();

header('Content-Type: application/json');

require_once '../users/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['error' => 'Invalid request method']));
}

if (!isset($_SESSION['username'])) {
    die(json_encode(['error' => 'User not logged in']));
}

function generateID($length = 10)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $password;
}

$username = $_SESSION['username'];
$data = json_decode(file_get_contents('php://input'), true);
$application_type = $data['application_type'];
$product = $data['product'];
$message = $data['message'];
$phonenumber = $data['phonenumber'];

if (empty($application_type) || empty($product) || empty($message) || empty($phonenumber)) {
    die(json_encode(['error' => 'All fields are required']));
}

$id = generateID();
$created_date = date("Y-m-d H:i:s");
$status = 'pending';

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$name = $user['name'];
$email = $user['email'];

$sql = "INSERT INTO applications (id, username, name, email, application_type, product, message, phonenumber, created_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssssssss', $id, $username, $name, $email, $application_type, $product, $message, $phonenumber, $created_date, $status);
$stmt->execute();

die(json_encode(['message' => 'Application created successfully']));
