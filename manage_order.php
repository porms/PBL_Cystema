<?php
// manage_orders.php

session_start();


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: cystemalogin.php");
    exit();
}

require 'dbcon.php';

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}


$orderStatus = isset($_GET['status']) ? $_GET['status'] : 'all';


if ($orderStatus === 'all') {
    $ordersSql = "SELECT * FROM orders";
} elseif ($orderStatus === 'pending') {
    $ordersSql = "SELECT * FROM orders WHERE status = 'Pending'";
} elseif ($orderStatus === 'delivered') {
    $ordersSql = "SELECT * FROM orders WHERE status = 'Delivered'";
} elseif ($orderStatus === 'cancelled') {
    $ordersSql = "SELECT * FROM orders WHERE status = 'Cancelled'";
} else {
    // Invalid status, handle accordingly (redirect or show an error)
    header("Location: manage_orders.php?status=all");
    exit();
}

$ordersResult = mysqli_query($db, $ordersSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYSTEMA - Manage Orders</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
 
</head>
<body>

    <div class="order-list">
        <h2>Orders (Status: <?php echo ucfirst($orderStatus); ?>)</h2>
        <ul>
            <?php
            while ($orderRow = mysqli_fetch_assoc($ordersResult)) {
                echo "<li>{$orderRow['order_id']} - {$orderRow['customer_name']} - {$orderRow['status']}</li>";
                // Display other order details as needed
            }
            ?>
        </ul>
    </div>

 

</body>
</html>
