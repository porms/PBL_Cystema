<?php
session_start();

include "dbcon.php";

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: cystemalogin.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch items in the cart
$queryCart = "SELECT * FROM cart WHERE user_email = ?";
$stmtCart = mysqli_prepare($db, $queryCart);

if ($stmtCart) {
    mysqli_stmt_bind_param($stmtCart, "s", $email);
    mysqli_stmt_execute($stmtCart);
    $cartItems = mysqli_stmt_get_result($stmtCart);

    // Convert the result set to an associative array
    $cartItemsArray = array();
    while ($item = mysqli_fetch_assoc($cartItems)) {
        $cartItemsArray[] = $item;
    }

    // Output the cart items as JSON
    echo json_encode($cartItemsArray);
} else {
    echo "Error: Unable to prepare the statement for cart items. " . mysqli_error($db);
}

// Close the statement
mysqli_stmt_close($stmtCart);
?>
