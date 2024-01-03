<?php
$msg = "";

$db = mysqli_connect('localhost', 'root', '', 'cystema');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}




// admin.php

session_start();

// Check if the admin is not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: cystemalogin.php");
    exit();
}




if (isset($_POST['upload'])) {
    // Connect to the database
    $db = mysqli_connect('localhost', 'root', '', 'cystema');

    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get the data from the form
    $image = $_FILES['file']['name'];
    $product_name = $_POST['product_name'];
    $category = $_POST['category']; // Added category field
    $price = $_POST['price'];       
    $quantity=$_POST['quantity'];
    $description = $_POST['description'];

    // Construct the SQL query
    $sql = "INSERT INTO products (`product_id`, `category`, `product_name`, `image`, `price`, `description`, `quantity`)
        VALUES ('', '$category', '$product_name', '$image', '$price', '$description', '$quantity')";

    if (mysqli_query($db, $sql)) {
        // Log the activity
    $activityType = 'Product Upload';
    $activityDescription = "New product uploaded: $product_name";
    logActivity($db, $activityType, $activityDescription);

        // Move the uploaded image into the folder
        $target = "image/" . basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            $msg = "Image and product data uploaded successfully.";
        } else {
            $msg = "Error moving uploaded image.";
        }
    } else {
        $msg = "Error uploading product data: " . mysqli_error($db);
    }
}

if (isset($_GET['delete'])) {
    $product_id = mysqli_real_escape_string($db, $_GET['delete']); // Use mysqli_real_escape_string to prevent SQL injection

        //$activityType = 'Product Updated';
        //$activityDescription = "Updated Product: $product_name";
        //logActivity($db, $activityType, $activityDescription);

    $delete_sql = "DELETE FROM products WHERE product_id='$product_id'";
    if (mysqli_query($db, $delete_sql)) {

        

        $msg = "Product deleted successfully.";
    } else {
        $msg = "Error deleting product: " . mysqli_error($db);
    }
}

// Fetch products from the database, limiting to three items per category
$sql = "SELECT product_id, image, product_name, price, quantity, category FROM products GROUP BY category";

$result = mysqli_query($db, $sql);

function logActivity($db, $activityType, $activityDescription) {
    $activitySql = "INSERT INTO activities (activity_type, activity_description) VALUES ('$activityType', '$activityDescription')";
    mysqli_query($db, $activitySql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYSTEMA</title>
    <link rel="stylesheet" type="text/css" href="upload.css">
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



<div id="content">

        
<form method="post" action="uploadproduct.php" enctype="multipart/form-data">
    <input type="hidden" name="size" value="1000000">
    <h3>ADD NEW PRODUCT</h3>
    <div>
    
<label for="file" class="file-button">Choose File</label>
<input type="file" name="file" id="file" class="file-input" accept="image/*" style="display: none;">


      
    </div>
    
    <div>
        <select name="category" class="box">
            <option value="Shirts" class="box">Shirts</option>
            <option value="Bags" class="box">Bags</option>
            <option value="Accessories" class="box">Accessories</option>
            <!-- Add more categories as needed -->
        </select>
    </div>
    
    <div>
        <input type="text" name="product_name" placeholder="Name of the Product" class="box">
    </div>

    <div>
        <input type="text" name="price" placeholder="Price" class="box">
    </div>

    <div>
    <input type="number" name="quantity" placeholder="Enter quantity" class="box" min="1" value="" required>
</div>

    
    <div>
        <textarea name="description" cols="40" rows="4" placeholder="Description" class="box"></textarea>
    </div>
    
    <div>
        <input type="submit" name="upload" value="Upload Product" class="btn">
    </div>
</form>
</div>


<div id="upproduct">
<div class ="divide">
    <h2>Products</h2>
</div>

<div class="productdisplay">
    <table class="productttable">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Category</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>

        <?php
        // Loop through the result set
        while ($row = mysqli_fetch_assoc($result)) {
            $category = $row['category'];

            // Fetch only three items per category
            $categorySql = "SELECT product_id, image, product_name, price, quantity FROM products WHERE category='$category'";
            $categoryResult = mysqli_query($db, $categorySql);

            // Display each item in the category
            while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
        ?>
                <tr>
                    <td><img src="image/<?php echo $categoryRow['image']; ?>" alt="Product Image" width="100" height="100"></td>
                    <td><?php echo $categoryRow['product_name'] ?> </td>
                    <td><?php echo $categoryRow['price'] ?></td>
                    <td><?php echo $categoryRow['quantity'] ?></td>
                    <td><?php echo $category ?></td>
                    <td>
                        <a href="admin_update.php?edit=<?php echo $categoryRow['product_id'] ?>" class="action-button edit-button">
                            <i class="fas fa-pen-to-square"></i> Edit
                        </a>
                        <a href="uploadproduct.php?delete=<?php echo $categoryRow['product_id'] ?>" class="action-button delete-button">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
        <?php
            }
        }
        ?>
    </table>
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

