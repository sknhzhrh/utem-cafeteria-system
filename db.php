<?php
$host = "127.0.0.1";
$user = "root";
$password = "";
$dbname = "cafeteria_db";
$port = 3306;

$conn = new mysqli($host, $user, $password, $dbname,$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>