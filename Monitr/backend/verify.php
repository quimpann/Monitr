<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';
session_start();

if (isset($_GET['token'])) {
    $token = substr($_GET['token'], 0, 11);

    $query = "SELECT * FROM users WHERE token = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $updateVerification = "UPDATE users SET verified = 1, token = NULL WHERE id = ?";
        $stmt = $conn->prepare($updateVerification);
        $stmt->bind_param("i", $row['id']);

        if ($stmt->execute()) {
            echo "Your account has been verified. Log in to continue!";
            header("Refresh: 3; url=../pages/loginForm.html");
            exit();
        } else {
            echo "Failed to update verification status<br>";
            echo "The token has expired or is invalid. Please try again.";
            header("Refresh: 3; url=../pages/landing.html");
            exit();
        }
    } else {
        echo "Token does not exist in the database<br>";
    }
} else {
    echo "Token is not set<br>";
}

?>
