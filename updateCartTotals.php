<?php
global $db;

// Calculate subtotal
$subtotalQuery = mysqli_query($db, "SELECT SUM(product_price * quantity) AS subtotal FROM cart WHERE user_email = '$userEmail'");
$subtotalResult = mysqli_fetch_assoc($subtotalQuery);

if (!empty($subtotalResult)) {
    $subtotal = $subtotalResult['subtotal'];

    if (is_numeric($subtotal) && $subtotal !== '') {
        // Update grand total in the cart table
        mysqli_query($db, "UPDATE cart SET grand_total = " . floatval($subtotal) . " WHERE user_email = '$userEmail'");
    }
}
?>
