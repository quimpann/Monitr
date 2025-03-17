<?php
$host = 'localhost';
$db = 'monitr';
$user = 'root';
$pass = ''; // Leave empty if no password, otherwise enter your MySQL password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully!";
?>
