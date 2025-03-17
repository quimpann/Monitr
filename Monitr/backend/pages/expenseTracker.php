<?php

session_start();
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expenseItemName = $_POST['expenseItemName'];
    $expenseCategory = $_POST['expenseCategory'];
    $expenseAmount = $_POST['expenseAmount'];
    $expenseStartDate = $_POST['expenseStartDate'];
    $expenseDeadline = $_POST['expenseDeadline'];

    //gets the id of the user
    $user_id = $_SESSION['user_id'];

    $insertExpense = "INSERT INTO expenses(user_id, expenseName, expenseCategory, expenseAmount, expenseStartDate, expenseDeadline, dateCreated) VALUES (?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($insertExpense);
    $stmt->bind_param("issdss",  $user_id, $expenseItemName, $expenseCategory, $expenseAmount, $expenseStartDate, $expenseDeadline);

    if ($stmt = $conn->prepare($insertExpense)) {
        $stmt->bind_param("issdss", $user_id, $expenseItemName, $expenseCategory, $expenseAmount, $expenseStartDate, $expenseDeadline);

        if ($stmt->execute()) {
            echo "Expense added successfully!";
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

?>