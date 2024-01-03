<?php
session_start();
session_regenerate_id(true);
require 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $email = mysqli_real_escape_string($db, $_GET['email']);
    $entered_otp = mysqli_real_escape_string($db, $_GET['otp']); // OTP entered by the user

    // Check OTP
    $checkOtpQuery = "SELECT * FROM customers WHERE email=? AND otp=?";
    $checkOtpStmt = mysqli_prepare($db, $checkOtpQuery);
    mysqli_stmt_bind_param($checkOtpStmt, "ss", $email, $entered_otp);
    mysqli_stmt_execute($checkOtpStmt);
    $checkOtpResult = mysqli_stmt_get_result($checkOtpStmt);

    if (mysqli_num_rows($checkOtpResult) == 1) {
        // Update User Status
        $updateStatusQuery = "UPDATE customers SET status='verified' WHERE email=?";
        $updateStatusStmt = mysqli_prepare($db, $updateStatusQuery);
        mysqli_stmt_bind_param($updateStatusStmt, "s", $email);
        mysqli_stmt_execute($updateStatusStmt);

        header("Location: cystemalogin.php?verified=true"); // Redirect to login page with a success message
        exit();
    } else {
        header("Location: cystemalogin.php?error=Invalid OTP. Please try again."); // Redirect to login page with an error message
        exit();
    }
}

mysqli_close($db);
?>
