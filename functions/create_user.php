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

$username = $_SESSION['username'];

$sql = 'SELECT id FROM users WHERE username = ? AND role = "admin"';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die(json_encode(['error' => 'User is not an admin']));
}

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'];
$username = $data['username'];
$email = $data['email'];
$role = $data['role'];


if (empty($name) || empty($username) || empty($email) || empty($role)) {
    die(json_encode(['error' => 'All fields are required']));
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die(json_encode(['error' => 'Invalid email address']));
}

function generatePassword($length = 8)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $password;
}

$create_datetime = date("Y-m-d H:i:s");
$password = generatePassword();
$activated = 1;
$activation_token = generatePassword(15);

$sql = 'SELECT * FROM users WHERE username = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    die(json_encode(['error' => 'Username is already taken']));
}

$sql = 'SELECT * FROM users WHERE email = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    die(json_encode(['error' => 'Email is already taken']));
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = 'INSERT INTO users (name, username, email, password, create_datetime, activated, activation_token, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssssiss', $name, $username, $email, $hashed_password, $create_datetime, $activated, $activation_token, $role);
$stmt->execute();

die(json_encode(['success' => 'User created successfully']));
