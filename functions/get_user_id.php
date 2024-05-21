<?php
session_start();

if (isset($_SESSION["username"])) {
    require_once '../users/db.php';

    $username = $_SESSION["username"];
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row["id"];
        die(json_encode(array("code" => 0, "user_id" => $user_id)));
    } else {
        die(json_encode(array("code" => 1, "message" => "User not found.")));
    }
} else {
    die(json_encode(array("code" => 2, "message" => "User not logged in.")));
}
