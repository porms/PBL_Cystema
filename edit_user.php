<?php
$msg = "";

$db = mysqli_connect('localhost', 'root', '', 'cystema');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['update'])) {
    $Customers_id = $_POST['Customers_id'];
    $firstName = mysqli_real_escape_string($db, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($db, $_POST['last_name']);
    $contactNumber = mysqli_real_escape_string($db, $_POST['contact_number']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);

    // Update user data in the database
    $update_query = "UPDATE customers SET first_name=?, last_name=?, contact_number=?, username=?, email=? WHERE Customers_id=?";
    $stmt = mysqli_prepare($db, $update_query);
    mysqli_stmt_bind_param($stmt, "sssssi", $firstName, $lastName, $contactNumber, $username, $email, $Customers_id);

    if (mysqli_stmt_execute($stmt)) {
        $msg = "Update successful";
    } else {
        $msg = "Update failed: " . mysqli_error($db);
    }
}

if (isset($_GET['id'])) {
    $userID = $_GET['id'];

    // Fetch user data based on the ID
    $fetch_query = "SELECT * FROM customers WHERE Customers_id = ?";
    $stmt = mysqli_prepare($db, $fetch_query);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $user = mysqli_fetch_assoc($result);
    } else {
        header("Location: edit_user.php");
        exit();
    }
} else {
    header("Location: user_management.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="edituser.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://kit.fontawesome.com/cafb8b4f1c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Edit User</title>
</head>
<body>


<nav>
    <div class="wrapper">
        <div class="logo">
            <img src="Logo Big.jpg" alt="logo" height="100px" width="100px">
        </div>
        <div class="nav-icons">
        <input type="text" class="search-box" placeholder="Search...">
            <i class="fas fa-bell notification-icon"></i>
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


<div class="sidebarupdate">
    <ul class="sidemenu">
        <li>
            <a href="admin.php" class="active"><i class="fa-solid fa-table-columns icon" ></i>Dashboard</a>
        </li>
        <li>
            <a href="uploadproduct.php"><i class="fa-brands fa-product-hunt icon"></i>Manage Products</a>
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
    </ul>
</div>



<div class="userupdate">
    <h2>Edit User</h2>
    <form method="post" action="edit_user.php" enctype="multipart/form-data">

        <input type="hidden" name="Customers_id" value="<?php echo $user['Customers_id']; ?>">

        <div>
            <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" placeholder="First Name" class="box">
        </div>

        <div>
            <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" placeholder="Last Name" class="box">
        </div>

        <div>
            <input type="text" id="contact_number" name="contact_number" value="<?php echo $user['contact_number']; ?>" placeholder="Contact Number" class="box">
        </div>

        <div>
            <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" placeholder="Username" class="box">
        </div>

        <div>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Email" class="box">
        </div>

        <button type="submit" name="update" class="btn">Update</button>
    </form>
</div>

<a href="user_management.php"><i class="fa-solid fa-arrow-left"></i>Back to User Management</a>

<script>
$(document).ready(function () {
    $(".togglesidebar").on("click", function () {
        $(".sidebarupdate").toggleClass("show");
    });

    $(".sidebarupdate .sidemenu > li > a").on('click', function (e) {
        const dropdown = $(this).siblings('.orderdropdown');
        dropdown.toggleClass('show');
        
        const angleIcon = $(this).find('.fa-angle-right');
        angleIcon.toggleClass('rotate-90');

        // Close other dropdowns
        $(".sidebarupdate .sidemenu > li > a").not(this).each(function () {
            $(this).siblings('.orderdropdown').removeClass('show');
            $(this).find('.fa-angle-right').removeClass('rotate-90');
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const allDropdown = document.querySelectorAll('.sidebarupdate .orderdropdown');

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
</script>

</body>
</html>

