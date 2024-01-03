<?php
session_start();
include "dbcon.php";

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: cystemalogin.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Customers_id'])) {
    // Retrieve and sanitize user input
    $Customers_id = $_POST['Customers_id'];
    $firstName = mysqli_real_escape_string($db, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($db, $_POST['last_name']);
    $contactNumber = mysqli_real_escape_string($db, $_POST['contact_number']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);

    // Update user data in the database
    $query = "UPDATE customers SET first_name=?, last_name=?, contact_number=?, username=?, email=? WHERE Customers_id=?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "sssssi", $firstName, $lastName, $contactNumber, $username, $email, $Customers_id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Redirect to user management page after successful update
        header("Location: user_management.php");
        exit();
    } else {
        // Handle error
        echo "Error updating user.";
    }
} else {
    // Redirect to user management page if the form is not submitted
    header("Location: user_management.php");
    exit();
}
?>

