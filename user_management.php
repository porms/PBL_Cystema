<?php
session_start();
include "dbcon.php";

// Check if the user is logged in and has admin role; otherwise, redirect to the login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: cystemalogin.php");
    exit();
}


// Fetch users from the database
$users = [];
$query = "SELECT * FROM customers";
$result = mysqli_query($db, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

if (isset($_GET['delete'])) {
    $Customers_id = mysqli_real_escape_string($db, $_GET['delete']); // Use mysqli_real_escape_string to prevent SQL injection

    $delete_sql = "DELETE FROM customers WHERE Customers_id='$Customers_id'";
    if (mysqli_query($db, $delete_sql)) {
        $msg = "User deleted successfully.";
    } else {
        $msg = "Error deleting User: " . mysqli_error($db);
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYSTEMA</title>
    <link rel="stylesheet" type="text/css" href="usermanagement.css">
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
       
            
            <div class="profile-dropdown" id="profileDropdown">
    <i class="fa-solid fa-user" style="color: #eaecf1;"></i>
    <div class="dropdown-content" id="profileDropdownContent">
       <!-- <a href="#">Profile</a>-->
        <a href="#">Settings</a>
        <a href="logoutadmin.php">Logout</a>
    </div>
</div>
        <i class="fas fa-bars togglesidebar"></i>
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

<div class="divide">
<h2>User Management</h2>
</div>

<div class="userdisplay">
    <table class="usertable">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Contact Number</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <!-- Loop through the users and display them in a table -->
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?php echo $user['Customers_id']; ?></td>
                <td><?php echo $user['first_name']; ?></td>
                <td><?php echo $user['last_name']; ?></td>
                <td><?php echo $user['contact_number']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['status']; ?></td>
                <td>
                <a href="edit_user.php?id=<?php echo $user['Customers_id']; ?>" class="action-button edit-button"><i class="fas fa-pen-to-square"></i> Edit</a>

                    
                    <a href="user_management.php?delete=<?php echo $user['Customers_id']; ?>" class="action-button delete-button"><i class="fas fa-trash"></i>Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
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

        // Close other dropdowns
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
});

function toggleDropdown(link) {
    link.classList.toggle('active');
}

$(".file-button").on("click", function (e) {
    e.preventDefault();
    $("#file").click();
});

$("#profileDropdown").on("click", function (e) {
        e.stopPropagation(); // Prevent the document click event from immediately closing the dropdown

        $("#profileDropdownContent").toggle();
    });

    // Close the dropdown content when clicking outside the profile dropdown
    $(document).on("click", function (e) {
        if (!$("#profileDropdown").is(e.target) && !$("#profileDropdown").has(e.target).length) {
            $("#profileDropdownContent").hide();
        }
    });



    </script>

</body>
</html>

