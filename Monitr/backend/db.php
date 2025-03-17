<?php
$host = "127.0.0.1"; // Use IP instead of "localhost" to prevent issues
$user = "root";
$password = "";
$database = "monitr"; // Make sure this is correct

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
