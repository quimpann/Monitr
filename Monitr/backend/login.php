<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //dis checks ip user exigs
    $usernameChecker = "SELECT verified from users where username = ?";
    $stmt = $conn->prepare($usernameChecker);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $usernameResult = $stmt->get_result();

    //ip user is less than 1, aka 0
    if ($usernameResult->num_rows < 1) {
        echo '<script>alert("This account does not exist! Please create an account first in the Sign Up page.")</script>';
        header("Refresh: 1; url=../pages/landing.html");
        exit();

        //ip username is greater than 0 edi ccheck niya kung verified na ba
    } elseif ($usernameResult->num_rows > 0) {
        $verifiedChecker = "SELECT verified from users where username = ?";
        $stmt = $conn->prepare($verifiedChecker);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $verifiedResult = $stmt->get_result();

        //ip verified is less than 1, aka 0 = di pa verified
        if ($verifiedResult->num_rows == 0) {
            echo '<script>alert("Your account is not yet verified. Please check your inbox or spam folder.")</script>';
            header("Refresh: 1; url=../pages/loginForm.html");
            exit();
        } else {
            $row = $verifiedResult->fetch_assoc();
            if ($row['verified'] == 0) {
                echo '<script>alert("Your account is not yet verified. Please check your inbox or spam folder.")</script>';
                header("Refresh: 1; url=../pages/loginForm.html");
                exit();
            } else {
                //ip verified is equal to 1 = verified, then ccheck kung nagmmatch ba username
                $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($id, $hashed_password);

                //ccheck kung nagmmatch ba password
                if ($stmt->fetch() && password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $id;

                    echo "Login successful!";
                    header("Location: ../pages/dashboard.html");
                    exit;
                } else {
                    echo '<script>("Invalid username or password!")</script>';
                }
            }

            // di k alam kung anong klaseng error ung kailangan magawa ni user para mapunta rito
        }
    } else {
        echo '<script>alert("An error has occurred. Please try again. If the issue persists, please contact support.")</>';
        header("Refresh: 1; url=../pages/landing.html");
        exit();
    }
    $stmt->close();
    $conn->close();
}



?>