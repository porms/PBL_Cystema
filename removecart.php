<?php

require 'dbcon.php';

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Delete the item from the cart
    $query = "DELETE FROM cart WHERE product_id = ?";
    $stmt = mysqli_prepare($db, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $productId);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            echo "Item removed from the cart successfully!";
        } else {
            echo "Error: " . mysqli_error($db);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: Unable to prepare the statement. " . mysqli_error($db);
    }
} else {
    echo "Error: Incomplete data received.";
}
?>
