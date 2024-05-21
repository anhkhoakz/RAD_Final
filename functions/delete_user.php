<?php

session_start();

if (!isset($_SESSION['username'])) {
    die(json_encode(['error' => 'You are not logged in']));
}

require_once '../users/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die(json_encode(['error' => 'Invalid request method']));
}

$username = $_SESSION['username'];

$sql = 'SELECT * FROM users WHERE username = ? AND role = "admin"';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die(json_encode(['error' => 'You are not authorized to delete users']));
}

if (!isset($_GET['username'])) {
    die(json_encode(['error' => 'Username is required']));
}

$deleting_username = $_GET['username'];

$sql = 'SELECT * FROM users WHERE username = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $deleting_username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$role = $user['role'];

if ($role === 'admin') {
    die(json_encode(['error' => 'You cannot delete an admin user']));
}

$sql = 'DELETE FROM users WHERE username = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $deleting_username);
$stmt->execute();

echo json_encode(['success' => 'User deleted successfully']);
