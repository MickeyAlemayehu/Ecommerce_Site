<?php
$host = 'localhost:3307';
$db = 'clothing_store';
$user = 'root';
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
