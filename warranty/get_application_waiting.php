<?php

session_start();

require_once '../users/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    die(json_encode(['error' => 'User not logged in']));
}

$username = $_SESSION['username'];

$sql = "SELECT role FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['role'] !== 'admin' && $user['role'] !== 'employee') {
    die(json_encode(['error' => 'You are not authorized to view applications']));
}

$sql = "SELECT * FROM applications WHERE status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$applications = $result->fetch_all(MYSQLI_ASSOC);

die(json_encode($applications));
