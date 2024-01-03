<?php
$msg = "";


$db = mysqli_connect('localhost', 'root', '', 'cystema');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}


if (isset($_POST['update'])) {
    $product_id = $_POST['product_id'];
    $product_name = mysqli_real_escape_string($db, $_POST['product_name']);
    $category = mysqli_real_escape_string($db, $_POST['category']);
    $price = mysqli_real_escape_string($db, $_POST['price']);
    $quantity = mysqli_real_escape_string($db, $_POST['quantity']);
    $description = mysqli_real_escape_string($db, $_POST['description']);

   
    if ($_FILES['file']['name'] != '') {
        $image = $_FILES['file']['name'];

        
        $target = "image/" . basename($image);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
           
            $update_sql = "UPDATE products SET product_name='$product_name', category='$category', price='$price', quantity='$quantity', description='$description', image='$image' WHERE product_id='$product_id'";

            if (mysqli_query($db, $update_sql)) {

                $activityType = 'Product Updated';
                $activityDescription = "Updated Product: $product_name";
                logActivity($db, $activityType, $activityDescription);
            


                $msg = "Product information updated successfully.";
            } else {
                $msg = "Error updating product information: " . mysqli_error($db);
            }
        } else {
            $msg = "Error moving uploaded image.";
        }
    } else {
       
        $update_sql = "UPDATE products SET product_name='$product_name', category='$category', price='$price', quantity='$quantity', description='$description' WHERE product_id='$product_id'";

        if (mysqli_query($db, $update_sql)) {

            $activityType = 'Product Updated';
            $activityDescription = "Updated Product: $product_name";
            logActivity($db, $activityType, $activityDescription);

            $msg = "Product information updated successfully.";
        } else {
            $msg = "Error updating product information: " . mysqli_error($db);
        }
    }
}


if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];

    
    $fetch_sql = "SELECT * FROM products WHERE product_id='$edit_id'";
    $result = mysqli_query($db, $fetch_sql);
    $row = mysqli_fetch_assoc($result);
} else {
    
    header("Location: uploadproduct.php");
    exit();
}


// admin.php

session_start();

// Check if the admin is not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: cystemalogin.php");
    exit();
}



function logActivity($db, $activityType, $activityDescription) {
    $activitySql = "INSERT INTO activities (activity_type, activity_description) VALUES ('$activityType', '$activityDescription')";
    mysqli_query($db, $activitySql);
}




?>





<!DOCTYPE html>
<html lang="en">
<head>
    <title>CYSTEMA - Update Product</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="upload.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://kit.fontawesome.com/cafb8b4f1c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

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


<body>
    <div id="contentupdate">
        <form method="post" action="admin_update.php" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
            <h3>UPDATE PRODUCT</h3>
            <div>
                <label for="file" class="file-button">Choose File</label>
                <input type="file" name="file" id="file" class="file-input" accept="image/*" style="display: none;">
                
            </div>
            
            <div>
                <select name="category" class="box">
                    <option value="Shirts" <?php echo ($row['category'] == 'Shirts') ? 'selected' : ''; ?>>Shirts</option>
                    <option value="Bags" <?php echo ($row['category'] == 'Bags') ? 'selected' : ''; ?>>Bags</option>
                    <option value="Accessories" <?php echo ($row['category'] == 'Accessories') ? 'selected' : ''; ?>>Accessories</option>
                   
                </select>
            </div>
            
            <div>
                <input type="text" name="product_name" value="<?php echo $row['product_name']; ?>" placeholder="Name of the Product" class="box">
            </div>

            <div>
                <input type="text" name="price" value="<?php echo $row['price']; ?>" placeholder="Price" class="box">
            </div>

            <div>
    <input type="number" name="quantity" placeholder="Enter quantity" class="box" min="1" value="<?php echo $row['quantity']; ?>" required>
</div>
            
            <div>
                <textarea name="description" cols="40" rows="4" placeholder="Description" class="box"><?php echo $row['description']; ?></textarea>
            </div>
            
            <div>
                <input type="submit" name="update" value="Update Product" class="btn">
            </div>
        </form>
    </div>

    <div class="message">
        <?php echo $msg; ?>
    </div>



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




