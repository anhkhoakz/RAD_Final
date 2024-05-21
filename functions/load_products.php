<?php
require_once("../users/db.php");

header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    die(json_encode(array("code" => 2, "message" => $_SERVER["REQUEST_METHOD"] . " method is not supported.")));
}

$sql = 'SELECT * FROM  products';
$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    die(json_encode(array('error' => 'Select product fail')));
}

$data = $result->fetch_all(MYSQLI_ASSOC);
die(json_encode($data));
