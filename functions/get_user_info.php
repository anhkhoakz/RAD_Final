<?php

session_start();

header('Content-Type: application/json');

require_once '../users/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die(json_encode(['error' => 'Invalid request method']));
}

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    die(json_encode(['error' => 'User not authenticated']));
}

$AdminUsername = $_SESSION['username'];

$sql = 'SELECT * FROM users WHERE username = ? AND role = "admin"';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $AdminUsername);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die(json_encode(['error' => 'You are not authorized to delete proucts']));
}

if (!isset($_GET['username'])) {
    die(json_encode(['error' => 'Username is required']));
}

$username = $_GET['username'];

$sql = "SELECT id, name, username, email, create_datetime, role FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

die(json_encode($user));
