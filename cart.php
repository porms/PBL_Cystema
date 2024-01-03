<?php
session_start();
//cart.php
require 'dbcon.php';

function updateSubtotalAndGrandTotal() {
    global $db;

    // Calculate subtotal
    $subtotalQuery = mysqli_query($db, "SELECT SUM(product_price * quantity) AS subtotal FROM cart WHERE product_price IS NOT NULL AND quantity IS NOT NULL");
    $subtotalResult = mysqli_fetch_assoc($subtotalQuery);

    if (!empty($subtotalResult)) {
        $subtotal = $subtotalResult['subtotal'];

        if (is_numeric($subtotal) && $subtotal !== '') {
            // Update grand total in the cart table
            mysqli_query($db, "UPDATE cart SET grand_total = " . floatval($subtotal));
        }
    }
}

if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];

    if (isset($_POST['update_update_btn'])) {
        $updateValue = $_POST['update_quantity'];
        $updateId = $_POST['update_quantity_id'];
        mysqli_query($db, "UPDATE cart SET quantity = '$updateValue' WHERE id = '$updateId' AND user_email = '$userEmail'");
        updateSubtotalAndGrandTotal();
        header('location:cart.php');
    }

    if (isset($_GET['remove'])) {
        $removeId = $_GET['remove'];
        mysqli_query($db, "DELETE FROM cart WHERE id = '$removeId' AND user_email = '$userEmail'");
        updateSubtotalAndGrandTotal();
        header('location:cart.php');
    }

    if (isset($_GET['delete_all'])) {
        mysqli_query($db, "DELETE FROM cart WHERE user_email = '$userEmail'");
        updateSubtotalAndGrandTotal();
        header('location:cart.php');
    }

    $selectCart = mysqli_query($db, "SELECT * FROM cart WHERE user_email = '$userEmail'");
    $grandTotal = 0;
} else {
    echo "Please log in to view your cart.";
    exit();
}

$queryActivities = "SELECT * FROM user_activities WHERE user_email = ? ORDER BY timestamp DESC LIMIT 5";
$stmtActivities = mysqli_prepare($db, $queryActivities);

if ($stmtActivities) {
    mysqli_stmt_bind_param($stmtActivities, "s", $userEmail);
    mysqli_stmt_execute($stmtActivities);
    $activities = mysqli_stmt_get_result($stmtActivities);
}
?>

<!-- Rest of your HTML code for displaying the cart -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" type="text/css" href="cart.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
</head>

<body>

<nav>
            <div class="wrapper">
              
                <input type="checkbox" id="toggle-menu" class="toggle-menu">
                <label for="toggle-menu" class="menu-icon"><i class="fas fa-bars"></i></label>
                <label for="toggle-menu" class="exit-icon"><i class="fas fa-times"></i></label>
               
                <ul class="nav-area">
                    <li><a href="cystemahome.php">Home</a></li>
                    <li><a href="cystemashirt.php" id="adopt-link">Shirt</a></li>
                    <li><a href="cystemabags.php">Bag</a></li>
                    <li><a href="cystemaaccessories.php">Accessories</a></li>
                </ul>
              
                <div class="logo">
                    <a href="cystemahome.php">
                        <img src="Logo Big.jpg" alt="logo" height="100px" width="100px">
                    </a>
                </div>

                <div class="leftpane">
    <i class="fa-solid fa-magnifying-glass" style="color: white;"></i>

    <?php
    if (!isset($_SESSION['email'])) {
        // User is not logged in, redirect to the login page
        echo '<a href="cystemalogin.php"><i class="fa-solid fa-user" style="color: white;"></i></a>';
    } else {
        // User is logged in, show profile and logout options
        echo '<div class="dropdown">';
        echo '<i class="fa-solid fa-user" style="color: white;"></i>';
        echo '<div class="dropdown-content">';
        echo '<a href="cystemaprofile.php">Profile</a>';
        echo '<a href="cystemalogout.php">Logout</a>';
        echo '</div>';
        echo '</div>';
    }
    ?>

    <!-- Link to the cart.php page -->
    <a href="cart.php"><i class="fa-solid fa-cart-shopping" style="color: white;"></i></a>
</div>


            </div>
           
        </nav>

<div class="container">

<section class="shopping-cart">

   <h1 class="heading">Shopping Cart</h1>

   <table>

      <thead>
         <th>Image</th>
         <th>Name</th>
         <th>Price</th>
         <th>Quantity</th>
         <th>Total Price</th>
         <th>Action</th>
      </thead>

      <tbody>
      <?php
   $user_email = $_SESSION['email'];
   $select_cart = mysqli_query($db, "SELECT * FROM `cart` WHERE user_email = '$user_email'");
   $grand_total = 0;
   $error_flag = false; // Initialize error flag

   while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
   ?>
        <tr>
        <td><img src="image/<?php echo $fetch_cart['product_image']; ?>" alt="Product Image" width="300" height="300"></td>
         <td><?php echo $fetch_cart['product_name']; ?></td>
        <td>₱<?php echo number_format($fetch_cart['product_price']); ?></td>

        <td>
            <form action="" method="post">
               <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['id']; ?>">
               <input type="number" name="update_quantity" min="1" value="<?php echo $fetch_cart['quantity']; ?>">
               <input type="submit" value="Update" name="update_update_btn">
            </form>
         </td>

            <td>₱<?php echo $sub_total = number_format($fetch_cart['product_price'] * $fetch_cart['quantity']); ?>/-</td>
            <td><a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" onclick="return confirm('Remove Item From Cart?')" class="delete-btn"> <i class="fas fa-trash"></i> Remove</a></td>
        </tr>
<?php

$sub_total = $fetch_cart['product_price'] * $fetch_cart['quantity'];
$grand_total += $sub_total;
        // Check if $grand_total is numeric, if not, set it to zero
        if (!is_numeric($grand_total)) {
            $grand_total = 0;
        }
        
        // Check if $sub_total is numeric, if not, set it to zero
        if (!is_numeric($sub_total)) {
            $sub_total = 0;
        }
        

    }

?>

         <tr class="table-bottom">
            <td><a href="cystemahome.php" class="option-btn" style="margin-top: 0;">Continue Shopping</a></td>
   
            <td colspan="3">Grand Total</td>
<td>₱<?php echo number_format($grand_total, 2, '.', ','); ?></td>

            <td><a href="cart.php?delete_all" onclick="return confirm('Are you sure you want to Delete All?');" class="delete-btn"> <i class="fas fa-trash"></i> Delete All </a></td>
         </tr>

      </tbody>

   </table>

   <div class="checkout-btn">
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Procced To Checkout</a>
   </div>

</section>

</div>

<footer>
  <p>Follow Us</p>
  <ul class="links">
    <li><a href="https://www.tiktok.com/@cystemaph?_t=8gZhWSjUwTp&_r=1"><i class="fa-brands fa-tiktok"></i></a></li>
    <li><a href="https://instagram.com/cystemaph?igshid=NTc4MTIwNjQ2YQ=="><i class="fa-brands fa-instagram"></i></a></li>
    <li><a href="https://www.facebook.com/cystemaph?mibextid=ZbWKwL"><i class="fa-brands fa-facebook"></i></a></li>
  </ul>
</footer>
   
<!-- custom js file link  -->
<script>
let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .navbar');

menu.onclick = () =>{
   menu.classList.toggle('fa-times');
   navbar.classList.toggle('active');
};

window.onscroll = () =>{
   menu.classList.remove('fa-times');
   navbar.classList.remove('active');
};


document.querySelector('#close-edit').onclick = () =>{
   document.querySelector('.edit-form-container').style.display = 'none';
   window.location.href = 'admin.php';
};




</script>

</body>
</html>