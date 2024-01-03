<?php
$db = mysqli_connect('localhost', 'root', '', 'cystema');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch products from the database
$inventorySql = "SELECT * FROM products";
$inventoryResult = mysqli_query($db, $inventorySql);

// Filter products based on category or search term
if (isset($_GET['filter'])) {
    $filter = mysqli_real_escape_string($db, $_GET['filter']);
    if ($filter === 'instock') {
        $inventorySql .= " WHERE quantity > 0";
    } elseif ($filter === 'outofstock') {
        $inventorySql .= " WHERE quantity = 0";
    } elseif ($filter === 'lowquantity') {
        $lowQuantityThreshold = 10;
        $inventorySql .= " WHERE quantity <= $lowQuantityThreshold";
    }
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($db, $_GET['search']);
    $inventorySql .= " WHERE product_name LIKE '%$searchTerm%'";
    $inventoryResult = mysqli_query($db, $inventorySql);
}

// Arrange products in ascending or descending order
$order = isset($_GET['order']) && ($_GET['order'] === 'desc') ? 'DESC' : 'ASC';
$orderBy = isset($_GET['orderBy']) ? mysqli_real_escape_string($db, $_GET['orderBy']) : 'product_name';
$inventorySql .= " ORDER BY $orderBy $order";

$inventoryResult = mysqli_query($db, $inventorySql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYSTEMA - Inventory</title>
    <link rel="stylesheet" type="text/css" href="inventory.css">
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


    <div id="content">
        <h2>Inventory</h2>
        <form method="get" action="inventory.php">
            <label for="filter">Filter:</label>
            <select name="filter" id="filter">
                <option value="all">All Products</option>
                <option value="instock">In Stock</option>
                <option value="outofstock">Out of Stock</option>
                <option value="lowquantity">Low Quantity</option>
            </select>

            <label for="orderBy">Order by:</label>
            <select name="orderBy" id="orderBy">
                <option value="product_name">Name</option>
                <option value="price">Price</option>
                <option value="quantity">Quantity</option>
                <option value="category">Category</option>
            </select>

            <label for="order">Order:</label>
            <select name="order" id="order">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>

            <input type="submit" value="Apply Filter">
        </form>

        <form method="get" action="inventory.php">
            <input type="text" name="search" placeholder="Search by product name">
            <input type="submit" value="Search">
        </form>
<div class="itable">
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($inventoryResult)) : ?>
                    <tr>
                        <td><?php echo $row['product_id']; ?></td>
                        <td><img src="image/<?php echo $row['image']; ?>" alt="Product Image" width="100" height="100"></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td>
                            <?php
                            if ($row['quantity'] <= 0) {
                                echo 'Out of Stock';
                            } elseif ($row['quantity'] <= 10) {
                                echo 'Low on Stock';
                            } else {
                                echo 'In Stock';
                            }
                            ?>
                        </td>
                        <td><?php echo $row['description']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
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
        </script>
</body>
</html>
