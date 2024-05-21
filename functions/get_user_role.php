<?php

session_start();

header('Content-Type: application/json');

require_once '../users/db.php';

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    die(json_encode(['error' => 'User not authenticated']));
}

$username = $_SESSION['username'];

$sql = "SELECT role FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $role = $row['role'];
    die(json_encode(['role' => $role]));
} else {
    http_response_code(404);
    die(json_encode(['error' => 'User not found']));
}
