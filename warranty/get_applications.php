<?php

session_start();

require_once '../users/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    die(json_encode(['error' => 'User not logged in']));
}

$username = $_SESSION['username'];

$sql = "SELECT * FROM applications WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$applications = $result->fetch_all(MYSQLI_ASSOC);

die(json_encode($applications));
