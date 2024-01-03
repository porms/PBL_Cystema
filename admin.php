<?php
// admin.php

session_start();

// Check if the admin is not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: cystemalogin.php");
    exit();
}

require 'dbcon.php';

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch total number of products
$countSql = "SELECT COUNT(*) AS totalProducts FROM products";
$countResult = mysqli_query($db, $countSql);
$countRow = mysqli_fetch_assoc($countResult);
$totalProducts = $countRow['totalProducts'];

// Fetch quantity of every category
$categoryQuantitySql = "SELECT category, SUM(quantity) AS totalQuantity FROM products GROUP BY category";
$categoryQuantityResult = mysqli_query($db, $categoryQuantitySql);

// Fetch recent activities
$recentActivitiesSql = "SELECT * FROM activities ORDER BY timestamp DESC LIMIT 5";
$recentActivitiesResult = mysqli_query($db, $recentActivitiesSql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYSTEMA</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://kit.fontawesome.com/cafb8b4f1c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<nav>
    <div class="wrapper">
        <div class="logo">
            <img src="Logo Big.jpg" alt="logo" height="100px" width="100px">
        </div>
        <div class="nav-icons">
           
            <i class="fas fa-bell notification-icon"></i>
            <div class="profile-dropdown" id="profileDropdown">
                <i class="fa-solid fa-user" style="color: #eaecf1;"></i>
                <div class="dropdown-content" id="profileDropdownContent">
                    <a href="#">Settings</a>
                    <a href="logoutadmin.php">Logout</a>
                </div>
            </div>
            <i class="fas fa-bars togglesidebar"></i>
        </div>
    </div>
</nav>

<div class="sidebar">
    <ul class="sidemenu">
        <li>
            <a href="admin.php" class="active"><i class="fa-solid fa-table-columns icon" ></i>Dashboard</a>
        </li>
        <li>
            <a href="uploadproduct.php"><i class="fa-brands fa-product-hunt icon"></i>Manage Products</a>
        </li>
       
        <li>
<a href="inventory.php"><i class="fa-solid fa-table-list icon"></i>Inventory</a>
</li>
        <li>
            <a href="#">
                <i class="fa-solid fa-cart-shopping icon"></i>Manage Orders
                <i class="fa-solid fa-angle-right iconn"></i>
            </a>
            <ul class="orderdropdown">
                <li><a href="#">All Order</a></li>
                <li><a href="#">Pending Orders</a></li>
                <li><a href="#">Delivered Orders</a></li>
                <li><a href="#">Cancelled Orders</a></li>
            </ul>
        </li>
        <li>
            <a href="user_management.php"><i class="fa-solid fa-user icon"></i>Manage User</a>
        </li>
    </ul>
</div>

<div class="dashb">
    <div class="quick-stats">
        <h2>Quick Stats</h2>
        <p>Total Products: <?php echo $totalProducts; ?></p>
        <h2>Category Quantities</h2>
        <ul>
            <?php
            while ($categoryQuantityRow = mysqli_fetch_assoc($categoryQuantityResult)) {
                echo "<li>{$categoryQuantityRow['category']}: {$categoryQuantityRow['totalQuantity']}</li>";
            }
            ?>
        </ul>
    </div>

    <div class="recent-activities">
        <h2>Recent Activities</h2>
        <ul>
            <?php
            while ($activityRow = mysqli_fetch_assoc($recentActivitiesResult)) {
                echo "<li>{$activityRow['activity_description']} ({$activityRow['timestamp']})</li>";
            }
            ?>
        </ul>
    </div>

        </div>



<script>
    $(document).ready(function () {
        $(".togglesidebar").on("click", function () {
            $(".sidebar").toggleClass("show");
        });

        $(".sidebar .sidemenu > li > a").on('click', function (e) {
            const dropdown = $(this).siblings('.orderdropdown');
            dropdown.toggleClass('show');

            const angleIcon = $(this).find('.fa-angle-right');
            angleIcon.toggleClass('rotate-90');

            $(".sidebar .sidemenu > li > a").not(this).each(function () {
                $(this).siblings('.orderdropdown').removeClass('show');
                $(this).find('.fa-angle-right').removeClass('rotate-90');
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const allDropdown = document.querySelectorAll('.sidebar .orderdropdown');

            allDropdown.forEach(item => {
                const parentLi = item.parentElement;

                parentLi.addEventListener('click', function (e) {
                    e.preventDefault();
                    item.classList.toggle('show');

                    const angleIcon = parentLi.querySelector('.fa-angle-right');
                    if (angleIcon) {
                        angleIcon.classList.toggle('rotate-90');
                    }
                });
            });
        });

        function toggleDropdown(link) {
            link.classList.toggle('active');
        }

        $(".file-button").on("click", function (e) {
            e.preventDefault();
            $("#file").click();
        });

        $("#profileDropdown").on("click", function (e) {
            e.stopPropagation();
            $("#profileDropdownContent").toggle();
        });

        $(document).on("click", function (e) {
            if (!$("#profileDropdown").is(e.target) && !$("#profileDropdown").has(e.target).length) {
                $("#profileDropdownContent").hide();
            }
        });
    });
</script>

</body>
</html>
