<?php

session_start();

header('Content-Type: application/json');

require_once '../users/db.php';

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    die(json_encode(['error' => 'User not authenticated']));
}

$username = $_SESSION['username'];

$sql = 'SELECT * FROM purchase_history WHERE username = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$purchases = $result->fetch_all(MYSQLI_ASSOC);

die(json_encode($purchases));
