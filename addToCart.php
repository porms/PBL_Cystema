<?php
session_start();
require 'dbcon.php';

if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = $_POST['productId'];
        $productName = $_POST['productName'];
        $productPrice = $_POST['productPrice'];
        $productImage = $_POST['productImage'];
        $quantity = $_POST['quantity'];

        // Check if the product is already in the cart
        $checkQuery = mysqli_query($db, "SELECT * FROM cart WHERE product_id = '$productId' AND user_email = '$userEmail'");
        $rowCount = mysqli_num_rows($checkQuery);

        

        if ($rowCount > 0) {
            // Product is already in the cart, update the quantity
            mysqli_query($db, "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = '$productId' AND user_email = '$userEmail'");
        } else {
            // Product is not in the cart, add it
                mysqli_query($db, "INSERT INTO cart (user_email, product_id, product_name, product_price, product_image, quantity) 
                VALUES ('$userEmail', '$productId', '$productName', '$productPrice', '$productImage', '$quantity')");
                $userEmail =  $_SESSION['email'];
                $activityType = 'Add To Cart';
                $activityDescription = "Item Added to Cart: $productName";
                logUserActivity($db, $userEmail, $activityType, $activityDescription);
        }

        // Update subtotal and grand total
        include 'updateCartTotals.php';

        echo 'Item added to the cart successfully.';
    } else {
        echo 'Invalid request.';
    }
} else {
    echo 'Please log in to add items to the cart.';
}



function logUserActivity($db, $userEmail, $activityType, $activityDescription) {
    $activitySql = "INSERT INTO user_activities (user_email, activity_type, activity_description, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($db, $activitySql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $userEmail, $activityType, $activityDescription);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        // Handle the error if needed
        echo "Error: Unable to prepare the statement for user activity. " . mysqli_error($db);
    }
}
?>
