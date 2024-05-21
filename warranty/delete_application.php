<?php

session_start();

require_once '../users/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    die(json_encode(['error' => 'User not logged in']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die(json_encode(['error' => 'Invalid request method']));
}

$username = $_SESSION['username'];
$id = $_GET['id'];

$sql = "SELECT * FROM applications WHERE id = ? and username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $id, $username);
$stmt->execute();
$result = $stmt->get_result();
$application = $result->fetch_assoc();

if (!$application) {
    die(json_encode(['error' => 'Application not found']));
}

$sql = "DELETE FROM applications WHERE id = ? and username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $id, $username);
$stmt->execute();

die(json_encode(['message' => 'Application deleted successfully']));
