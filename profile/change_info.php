use function http_response_code;
<?php

session_start();

header('Content-Type: application/json');

require_once '../users/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    die(json_encode(['error' => 'Invalid request method']));
}

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    die(json_encode(['error' => 'User not authenticated']));
}

$username = $_SESSION['username'];

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email is required']);
    exit;
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email']);
    exit;
}

if (!isset($data['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Name is required']);
    exit;
}

if (!isset($data['phone'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Phone is required']);
    exit;
}

if (!preg_match('/^\d{10}$/', $data['phone'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid phone number']);
    exit;
}

if (!isset($data['address'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Address is required']);
    exit;
}

$email = $data['email'];
$name = $data['name'];
$phone = $data['phone'];
$address = $data['address'];

$sql = "UPDATE users SET email = ?, name = ?, phonenumber = ?, address = ? WHERE username = ?";
$params = [$email, $name, $phone, $address, $username];

if (isset($data['password']) && !empty($data['password'])) {
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $sql = "UPDATE users SET email = ?, name = ?, phonenumber = ?, address = ?, password = ? WHERE username = ?";
    $params = [$email, $name, $phone, $address, $password, $username];
}

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();

die(json_encode(['message' => 'User info updated']));
