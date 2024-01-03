<?php
// search.php

session_start();
require 'dbcon.php';

if (isset($_GET['query'])) {
    $searchQuery = mysqli_real_escape_string($db, $_GET['query']);

    $sql = "SELECT * FROM products WHERE product_name LIKE '%$searchQuery%'";
    $result = mysqli_query($db, $sql);

    // Display search results
    while ($row = mysqli_fetch_array($result)) {
        // Display product information with a link to the product.php page
        echo "<a href='product.php?product_id={$row['product_id']}&image={$row['image']}'>{$row['product_name']}</a><br>";
    }
} else {
    // No search query provided
    echo "Please enter a search query.";
}
?>
