<?php
// storeReminder.php

// Add your database connection code here
require 'dbcon.php';

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve data from the AJAX request
$day = $_POST['day'];
$month = $_POST['month'];
$year = $_POST['year'];
$reminderText = $_POST['reminderText'];

// Perform database insertion (replace with your actual database table and structure)
$insertSql = "INSERT INTO reminders (day, month, year, reminder_text) VALUES ($day, $month, $year, '$reminderText')";
if (mysqli_query($db, $insertSql)) {
    echo "Reminder stored successfully!";
} else {
    echo "Error storing reminder: " . mysqli_error($db);
}

mysqli_close($db);
?>
