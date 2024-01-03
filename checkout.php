<?php
session_start();
require 'dbcon.php';

$email = $_SESSION['email'];

if (isset($_POST['order_btn'])) {
    $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($db, $_POST['last_name']);
    $contact_number = mysqli_real_escape_string($db, $_POST['number']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $payment_method = mysqli_real_escape_string($db, $_POST['payment_method']);
    $street = mysqli_real_escape_string($db, $_POST['street']);
    $lot_number = mysqli_real_escape_string($db, $_POST['lot_number']);
    $barangay = mysqli_real_escape_string($db, $_POST['barangay']);
    $city = mysqli_real_escape_string($db, $_POST['city']);
    $post_code = mysqli_real_escape_string($db, $_POST['post_code']);

    $cart_query = mysqli_query($db, "SELECT * FROM `cart` WHERE user_email = '$email'") or die(mysqli_error($db));

    $price_total = 0;
    $product_names = ''; // Initialize as an empty string

    if (mysqli_num_rows($cart_query) > 0) {
        while ($product_item = mysqli_fetch_assoc($cart_query)) {
            $product_names .= $product_item['product_name'] . ' (' . $product_item['quantity'] . '), ';
            $product_price = number_format($product_item['product_price'] * $product_item['quantity']);
            $price_total += $product_price;

            // Update product quantity in the database using prepared statement
            $product_name = $product_item['product_name'];
            $quantity_ordered = $product_item['quantity'];
            
            $update_query = mysqli_prepare($db, "UPDATE `products` SET quantity = quantity - ? WHERE product_name = ?");
            mysqli_stmt_bind_param($update_query, "is", $quantity_ordered, $product_name);
            mysqli_stmt_execute($update_query) or die(mysqli_error($db));
            mysqli_stmt_close($update_query);
        }

        // Clear the user's cart after placing the order
        mysqli_query($db, "DELETE FROM `cart` WHERE user_email = '$email'") or die(mysqli_error($db));

        // Remove the trailing comma and space
        $total_product = rtrim($product_names, ', ');

        $detail_query = mysqli_prepare($db, "INSERT INTO `shopping_order` (first_name, last_name, contact_number, email, payment_method, street, lot_number, barangay, city, post_code, total_products, price_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        mysqli_stmt_bind_param($detail_query, "sssssssssssd", $first_name, $last_name, $contact_number, $email, $payment_method, $street, $lot_number, $barangay, $city, $post_code, $total_product, $price_total);

        mysqli_stmt_execute($detail_query);

        if (mysqli_stmt_affected_rows($detail_query) > 0) {
            // Store relevant information in the session
            $_SESSION['order_details'] = [
                'product_names' => $total_product,
                'total_price' => $price_total,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'contact_number' => $contact_number,
                'email' => $email,
                'payment_method' => $payment_method,
                'street' => $street,
                'lot_number' => $lot_number,
                'barangay' => $barangay,
                'city' => $city,
                'post_code' => $post_code,
                'status' => 'pending',
            ];
        
            // Add activity record after placing an order
            $activity_type = 'Order Placed';
            $activity_description = 'User placed an order.';
            $activity_timestamp = date('Y-m-d H:i:s');
        
            $activity_insert_query = mysqli_prepare($db, "INSERT INTO `user_activities` (user_email, activity_type, activity_description, timestamp) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($activity_insert_query, "ssss", $email, $activity_type, $activity_description, $activity_timestamp);
            mysqli_stmt_execute($activity_insert_query);
            mysqli_stmt_close($activity_insert_query);
        
            // Redirect to the payment page
            header('Location: payment.php');
            exit();
        } else {
            die("Error: " . mysqli_stmt_error($detail_query));
        }

        mysqli_stmt_close($detail_query);
    } else {
        echo "<div class='display-order'><span>Your Cart is Empty!</span></div>";
    }
}

$queryActivities = "SELECT * FROM user_activities WHERE user_email = ? ORDER BY timestamp DESC LIMIT 5";
$stmtActivities = mysqli_prepare($db, $queryActivities);

if ($stmtActivities) {
    mysqli_stmt_bind_param($stmtActivities, "s", $email);
    mysqli_stmt_execute($stmtActivities);
    $activities = mysqli_stmt_get_result($stmtActivities);
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" type="text/css" href="checkout_styles.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    
</head>
<body>
   
    <header>    
        <nav>
            <div class="wrapper">
              
               
                <i class="fas fa-bars menu-icon"></i>
                
               
                <ul class="nav-area">
                    <li><a href="cystemahome.php">Home</a></li>
                    <li><a href="cystemashirt.php">Shirt</a></li>
                    <li><a href="cystemabags.php">Bag</a></li>
                    <li><a href="cystemaaccessories.php">Accessories</a></li>
                </ul>
              
                <div class="logo">
                    <a href="cystemahome.php">
                        <img src="Logo Big.jpg" alt="logo" height="100px" width="100px">
                    </a>
                </div>

                <div class="leftpane">
                  <!--  <i class="fa-solid fa-magnifying-glass" style="color: white;"> -->
               

<!--</i>-->
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

    <div class="notification-icon">
        <i class="fa-solid fa-bell" style="color: white;"></i>
        <div class="notification-list">
          
        <?php if (!isset($_SESSION['email'])) : ?>
    <a href="cystemalogin.php">Login</a>
<?php else : ?>
    <?php while ($activity = mysqli_fetch_assoc($activities)) : ?>
        <?php if (isset($activity['activity_type']) && isset($activity['timestamp'])) : ?>
            <a href="#"><?php echo $activity['activity_type']; ?> - <?php echo $activity['timestamp']; ?></a>
        <?php else : ?>
            <p>Invalid activity data</p>
        <?php endif; ?>
    <?php endwhile; ?>
                <a href="user_activities.php" class="view-all-link">View All</a>

<?php endif; ?>
        </div>
    </div>


</div>
            </div>
           
        </nav>

       
        <div class="container">

<section class="checkout-form">

   <h1 class="heading">Complete Your Order</h1>

   <form action="" method="post">

   <div class="display-order">
      <?php
    $select_cart = mysqli_query($db, "SELECT * FROM `cart` WHERE user_email = '$email'");
    $total_price = 0;
    
    if (mysqli_num_rows($select_cart) > 0) {
        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $product_price = $fetch_cart['product_price'] * $fetch_cart['quantity'];
            $total_price += $product_price;
            ?>
            <span><?= $fetch_cart['product_name']; ?>(<?= $fetch_cart['quantity']; ?>)</span>
            <?php
        }
        $grand_total = sprintf("%.2f", $total_price);
    } else {
        echo "<div class='display-order'><span>Your Cart is Empty!</span></div>";
    }
    ?>
    <span class="grand-total"> Grand Total : ₱<?= $grand_total; ?>/- </span>
    
       
        
   </div>

      <div class="flex">

      <div class="inputBox">
            <span>First Name</span>
            <input type="text" placeholder="First Name" name="first_name" pattern="[A-Za-z ]+" required>
         </div>

         <div class="inputBox">
            <span>Last Name</span>
            <input type="text" placeholder="Last Name" name="last_name" pattern="[A-Za-z ]+" required>
         </div>

         <div class="inputBox">
            <span>Contact Number</span>
            <input type="tel" placeholder="Contact Number" name="number" pattern="[0-9]{11}" required>
         </div>

         <div class="inputBox">
            <span>Email</span>
            <input type="email" placeholder="Email" name="email" required>
         </div>

         <div class="inputBox">
    <span>Payment Method</span>
    <select name="payment_method" id="payment_method">
        <option value="COD">Cash on Delivery</option>
        <!-- Add more payment options as needed -->
    </select>
</div>

      <div class="inputBox">
            <span>Lot Number</span>
            <input type="text" placeholder="Lot Number" name="lot_number" required>
         </div>


         <div class="inputBox">
            <span>Street</span>
            <input type="text" placeholder="Street" name="street" required>
         </div>

         <div class="inputBox">
            <span>Baranggay</span>
            <input type="text" placeholder="Barangay" name="barangay" required>
         </div>

         <div class="inputBox">
            <span>City</span>
            <select name="city">
                                    <option value="Abulug">Abulug</option>
                                    <option value="Adlay/Bislig">Adlay/Bislig</option>
                                    <option value="Agoncillo">Agoncillo</option>
                                    <option value="Agoo">Agoo</option>
                                    <option value="Aheron/Ozamis">Aheron/Ozamis</option>
                                    <option value="Ajuy">Ajuy</option>
                                    <option value="Alabel">Alabel</option>
                                    <option value="Alah">Alah</option>
                                    <option value="Alaminos">Alaminos</option>
                                    <option value="Alasang/Siain">Alasang/Siain</option>
                                    <option value="Albuera">Albuera</option>
                                    <option value="Iligan">Iligan</option>
                                    <option value="Iloilo">Iloilo</option>
                                    <option value="Lapu-Lapu">Lapu-Lapu</option>
                                    <option value="Las Pinas">Las Pinas</option>
                                    <option value="Lucena">Lucena</option>
                                    <option value="Makati">Makati</option>
                                    <option value="Malabon">Malabon</option>
                                    <option value="Mandaluyong">Mandaluyong</option>
                                    <option value="Mandaue">Mandaue</option>
                                    <option value="Manila">Manila</option>
                                    <option value="Marikina">Marikina</option>
                                    <option value="Muntinlupa">Muntinlupa</option>
                                    <option value="Navotas">Navotas</option>
                                    <option value="Olongapo">Olongapo</option>
                                    <option value="Pangasinan">Pangasinan</option>
                                    <option value="Paranaque">Paranaque</option>
                                    <option value="Pasay">Pasay</option>
                                    <option value="Pasig">Pasig</option>
                                    <option value="Palawan">Palawan</option>
                                    <option value="Quezon City">Quezon City</option>
                                    <option value="Quezon Province">Quezon Province</option>
                                    <option value="San Juan">San Juan</option>
                                    <option value="Taguig">Taguig</option>
                                    <option value="Valenzuela">Valenzuela</option>
                                    <option value="Zamboanga">Zamboanga</option>
                                </select>
                             </div>

                             <div class="inputBox">
    <span>Postal Code</span>
    <input type="text" placeholder="Postal Code" name="post_code" pattern="[0-9]{4,8}" required>
</div>

      <input type="submit" value="Order Now" name="order_btn" class="btn">
   </form>

</section>

</div>


<script>
    $(document).ready(function () {
        // Redirect to login page when the user icon is clicked and not logged in
        $('.fa-user').click(function () {
            redirectToLogin();
        });

        // Toggle the dropdown menu for logged-in users
        $('.dropdown').click(function () {
            toggleDropdown();
        });

        // Function to redirect to login page
        function redirectToLogin() {
            <?php if (!isset($_SESSION['email'])) : ?>
                window.location.href = "cystemalogin.php";
            <?php endif; ?>
        }

        // Function to toggle the dropdown menu
        function toggleDropdown() {
            <?php if (isset($_SESSION['email'])) : ?>
                $(".dropdown-content").toggle();
            <?php endif; ?>
        }
    });
</script>
<script>
    $(document).ready(function () {

        $('.menu-icon').click(function () {
        $('.nav-area').toggleClass('show');
    });

});

</script>

<script>
        $(document).ready(function () {
            // Show notification list on icon hover
            $('.notification-icon').mouseenter(function () {
                $('.notification-list').slideDown(200);
            });

            // Show notification list on link hover and prevent it from closing
            $('.notification-list').mouseenter(function (e) {
                e.stopPropagation(); // Stop the event from reaching the parent (notification-list)
                $('.notification-icon')
            });

            // Hide notification list on mouseleave from the icon
            $('.notification-icon').mouseleave(function () {
                e.stopPropagation(); 
                $('.notification-list').slideUp(500);
            });

            // Hide notification list on mouseleave from the list
            $('.notification-list').mouseleave(function () {
                $('.notification-list').slideUp(500);
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('form').addEventListener('submit', function (event) {
            const firstName = document.querySelector('[name="first_name"]').value;
            const lastName = document.querySelector('[name="last_name"]').value;
            const contactNumber = document.querySelector('[name="number"]').value;
            const postalCode = document.querySelector('[name="post_code"]').value;

            if (!/^[A-Za-z ]+$/.test(firstName)) {
                alert('Invalid First Name. Please use letters and spaces only.');
                event.preventDefault();
            }

            if (!/^[A-Za-z ]+$/.test(lastName)) {
                alert('Invalid Last Name. Please use letters and spaces only.');
                event.preventDefault();
            }

            if (!/^[0-9]{11}$/.test(contactNumber)) {
                alert('Invalid Contact Number. Please enter 11 numeric characters.');
                event.preventDefault();
            }

            if (!/^[0-9]{4,8}$/.test(postalCode)) {
                alert('Invalid Postal Code. Please enter 4 to 8 numeric characters.');
                event.preventDefault();
            }
        });
    });
</script>
   
</body>
</html>