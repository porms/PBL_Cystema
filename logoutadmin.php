<?php
// admin_logout.php

session_start();

// Mark the admin as logged out
$_SESSION['admin_logged_in'] = false;

// Destroy the session
session_destroy();

header("Location: cystemalogin.php");
exit();
?>
