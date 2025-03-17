<?php

require 'db.php';
require 'C:/xampp/htdocs/Webprog/Monitr_variant/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Debugging: Log all environment variables
error_log(print_r($_ENV, true));


$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->safeLoad();

//Debugging mesgs
error_log("SMTP_HOST:" . $_ENV['SMTP_HOST'] ?? 'NOT LOADED');
error_log("SMTP_USER:" . $_ENV['SMTP_USER'] ?? 'NOT LOADED');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 10]);
    if(empty($email) || empty($username) || empty($password)) {
        header("Location: ../landing.html?error=emptyfields");
        exit();
    }
    //genreatess random token
    $token = bin2hex(random_bytes(18));
    // * (1) checks if email alrady exisst
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // ? why not lagyan to ng else tas iinsert non sa db ung stmt? it cuz email lang laman nito i think
    if ($result->num_rows > 0) {
        
        header("Location: ../pages/landing.html?error=emailtaken");
        $message = "This email alredi taken brah";
        echo "<script>alert('$message');</script>";
        exit();
    }
    $stmt->close();
    $sql = "INSERT INTO users (email, username, password, token) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $email, $username, $password, $token);

    if ($stmt->execute()) {
           $stmt->close();
           $conn->close();
            sendVerificationEmail($email, $token);
            header("Location:../pages/loginForm.html?success=emailsent");
            exit();
        } else {
            header("Location: ../pages/landing.html?error=sqlerror");
            exit();
        }
    }

    function sendVerificationEmail($email, $token) {
        $mail = new PHPMailer(true);
        try {
            global $_ENV;

            $mail->isSMTP();
            $mail->Host= $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
            $mail->Port = $_ENV['SMTP_PORT'];
            $mail->setFrom('siomairice23@gmail.com','Monitr');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Verify your email";
            $mail->Body = "
    <h2> Annyeong!! </h2>
    <p> Welcome! </p>
    <a href='http://localhost/webprog/Monitr_variant/Monitr/backend/verify.php?email=$email&token=$token'>
        Verify your email address
    </a>";
    $mail->AltBody = "unyaa u sak!!!!!!!";

            $mail->send();
        } catch (Exception $e) {
            error_log("Mailer error: " . $mail->ErrorInfo);
        }
    }
?>