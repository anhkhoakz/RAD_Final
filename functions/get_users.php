<?php

session_start();

header('Content-Type: application/json');

require_once '../users/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die(json_encode(['error' => 'Invalid request method']));
}
$username = $_SESSION['username'];
$findRole = $_GET['role'];

// echo $username;


if (empty($findRole)) {
    die(json_encode(['error' => 'Role is required']));
}

$sql = 'SELECT * FROM users WHERE role = "admin" AND username = ?';

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die(json_encode(['error' => 'You are not authorized to perform this action']));
}

$sql = 'SELECT * FROM users WHERE role = ? AND role != "admin"';

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $findRole);
$stmt->execute();

$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

die(json_encode($users));
