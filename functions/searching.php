<?php

header('Content-Type: application/json');

require_once '../users/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die('Method not allowed');
}

if (!isset($_GET['search'])) {
    die('Search parameter is required');
}

$search = $_GET['search'];
$query = "SELECT * FROM products WHERE title LIKE '%$search%' or img LIKE '%$search%' or price LIKE '%$search%' or category LIKE '%$search%'";
$result = mysqli_query($conn, $query);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
die(json_encode($users));
