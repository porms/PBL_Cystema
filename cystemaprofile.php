<?php
session_start();

include "dbcon.php";

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: cystemalogin.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch user information
$queryUser = "SELECT * FROM customers WHERE email = ?";
$stmtUser = mysqli_prepare($db, $queryUser);

if ($stmtUser) {
    mysqli_stmt_bind_param($stmtUser, "s", $email);

    $successUser = mysqli_stmt_execute($stmtUser);

    if ($successUser) {
        $resultUser = mysqli_stmt_get_result($stmtUser);

        if ($resultUser) {
            if (mysqli_num_rows($resultUser) > 0) {
                $user = mysqli_fetch_assoc($resultUser);

                if ($user) {
                    // Fetch transaction history
                    $queryTransaction = "SELECT * FROM transactions WHERE customer = ?";
                    $stmtTransaction = mysqli_prepare($db, $queryTransaction);

                    if ($stmtTransaction) {
                        mysqli_stmt_bind_param($stmtTransaction, "s", $user['email']);
                        mysqli_stmt_execute($stmtTransaction);
                        $transactions = mysqli_stmt_get_result($stmtTransaction);

                        // Fetch items in the cart
$queryCart = "SELECT * FROM cart WHERE user_email = ?";
$stmtCart = mysqli_prepare($db, $queryCart);

if ($stmtCart) {
    mysqli_stmt_bind_param($stmtCart, "s", $user['email']);
    mysqli_stmt_execute($stmtCart);
    $cart_items = mysqli_stmt_get_result($stmtCart);

  
} else {
    echo "Error: Unable to prepare the statement for cart items. " . mysqli_error($db);
}

                    } else {
                        echo "Error: Unable to prepare the statement for transaction history. " . mysqli_error($db);
                    }
                } else {
                    echo "Error: No data fetched for the user.";
                }
            } else {
                echo "Error: No rows found for the user email.";
                echo "User email from session: " . $_SESSION['email'];
            }
        } else {
            echo "Error: Unable to get result set for user information. " . mysqli_error($db);
        }
    } else {
        echo "Error: Unable to execute the statement for user information. " . mysqli_error($db);
    }

    // Close the user statement
    mysqli_stmt_close($stmtUser);
} else {
    echo "Error: Unable to prepare the statement for user information. " . mysqli_error($db);
}

$queryActivities = "SELECT * FROM user_activities WHERE user_email = ? ORDER BY timestamp DESC LIMIT 5";
$stmtActivities = mysqli_prepare($db, $queryActivities);

if ($stmtActivities) {
    mysqli_stmt_bind_param($stmtActivities, "s", $email);
    mysqli_stmt_execute($stmtActivities);
    $activities = mysqli_stmt_get_result($stmtActivities);
}

// Replace the existing queryTransaction block with the following code

$queryTransaction = "SELECT product_name, reference_number, payment_method, status FROM payment WHERE email = ?";
$stmtTransaction = mysqli_prepare($db, $queryTransaction);

if ($stmtTransaction) {
    mysqli_stmt_bind_param($stmtTransaction, "s", $user['email']);
    mysqli_stmt_execute($stmtTransaction);
    $transactions = mysqli_stmt_get_result($stmtTransaction);
} else {
    echo "Error: Unable to prepare the statement for transaction history. " . mysqli_error($db);
}



?>


<?php
            $product_name = isset($_GET['product_name']) ? $_GET['product_name'] : '';
            $product_price = isset($_GET['product_price']) ? $_GET['product_price'] : '';
            $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : '';
            $total_price = isset($_GET['total_price']) ? $_GET['total_price'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="cystemaprofile.css">
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
            <i class="fas fa-search search-icon" style="color:white"></i>
                 
               
        <div class="search-container">
             <input type="text" id="search-input" placeholder="Search..."   >
             <div id="search-results"></div>
            
        </div>
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
      <a href="cart.php"><i class="fa-solid fa-cart-shopping" style="color: white;"></i></a>

      <div class="notification-icon">
        <i class="fa-solid fa-bell" style="color: white;"></i>
        <div class="notification-list">
          
        <?php while ($activity = mysqli_fetch_assoc($activities)) : ?>
    <?php if (isset($activity['activity_type']) && isset($activity['timestamp'])) : ?>
        <a href="#"><?php echo $activity['activity_type']; ?> - <?php echo $activity['timestamp']; ?></a>
    <?php else : ?>
        <p>Invalid activity data</p>
    <?php endif; ?>
<?php endwhile; ?>

          
            <a href="user_activities.php" class="view-all-link">View All</a>
        </div>
    </div>
</div>
        </div>
    </nav>

   
   
           
    </div>
</header>
<body>

    <div class="profile">
    <h1>Welcome, <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>!</h1>
</div>

<div class="userinfo">
    <h2>User Information</h2>
    <ul>
        <li><strong>Username:</strong> <?php echo $user['username']; ?></li>
        <li><strong>Email:</strong> <?php echo $user['email']; ?></li>
        <!-- Add other user information fields as needed -->
    </ul>
</div>

<div class="action">


<div class="transac">
    <h2>Transaction History</h2>
    <ul>
        <?php while ($transaction = mysqli_fetch_assoc($transactions)) : ?>
            <li>
                Product Name: <?php echo $transaction['product_name']; ?><br>
                Reference Number: <?php echo $transaction['reference_number']; ?><br>
                Payment Method: <?php echo $transaction['payment_method']; ?><br>
                Status: <?php echo $transaction['status']; ?>
            </li>
        <?php endwhile; ?>
    </ul>
</div>



        <div class="shopcart">
    <h2>Shopping Cart</h2>
    <?php if (mysqli_num_rows($cart_items) > 0) : ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = mysqli_fetch_assoc($cart_items)) : ?>
                    <tr>
                        <td><img src="image/<?php echo $item['product_image']; ?>" alt="<?php echo $item['product_name']; ?>" width="100px" height="100px"></td>
                        <td><?php echo $item['product_name']; ?></td>
                        <td>₱<?php echo number_format($item['product_price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₱<?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></td>
                        <td><button class="remove-from-cart-btn" data-product-id="<?php echo $item['product_id']; ?>">Remove</button></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Add the Checkout button -->
        <button class="checkout-btn">Checkout</button>
    <?php else : ?>
        <p>Your shopping cart is empty. Add items before proceeding to checkout.</p>
    <?php endif; ?>
</div>


<script>
     $(document).ready(function () {
        $('.menu-icon').click(function () {
            $('.nav-area').toggleClass('show');
        });
    });
    
  $(document).ready(function () {
    $('.search-icon').click(function () {
      $('.search-container').slideToggle();
    });
  });
</script>
            <script>
$(document).ready(function () {
    var $searchContainer = $('.search-container');
    var $searchInput = $('#search-input');

    
    $searchInput.on('input', function () {
        var query = $(this).val().trim();

     
        if (query !== '') {
            
            $.ajax({
                url: 'search.php',
                method: 'GET',
                data: { query: query },
                success: function (response) {
                    $('#search-results').html(response);
                },
                error: function (error) {
                    console.error(error);
                }
            });
        } else {
           
            $('#search-results').html('');
        }
    });

    $('.menu-icon').click(function () {
            $('.nav-area').toggleClass('show');
        });
        $('.search-icon').click(function () {
        $('.search-container').toggleClass('show');
    })

   


   
});





    </script>

<script>
$(document).ready(function () {

    // Function to update the cart list in the sidebar
    function updateCartList() {
        $.ajax({
            url: 'getCartItems.php', // Create a new PHP file to retrieve cart items from the server
            method: 'GET',
            success: function (response) {
                $("#cart-list").html(response);
            },
            error: function (error) {
                console.error(error);
            }
        });
    }

  
    $(".add-to-cart-btn").on("click", function () {
        var productId = $(this).data("product-id");
        var productName = $(this).data("product-name");
        var productPrice = $(this).data("product-price");

        // Store a reference to the outer 'this'
        var addButton = $(this);

        // Send AJAX request to add the item to the cart
        $.ajax({
            url: 'addToCart.php',
            method: 'POST',
            data: {
                productId: productId,
                productName: productName,
                productPrice: productPrice
            },
            success: function (response) {
                console.log(response);
                updateCartList(); // Update cart list after adding an item
            },
            error: function (error) {
                console.error(error);
            }
        });
    });

    $(document).on("click", ".remove-from-cart-btn", function () {
    var productId = $(this).data("product-id");

    // Store a reference to the outer 'this'
    var removeButton = $(this);

    // Send AJAX request to remove the item from the cart
    $.ajax({
        url: 'removecart.php',
        method: 'POST',
        data: {
            productId: productId
        },
        success: function (response) {
            console.log(response);
            updateCartList(); // Update cart list after removing an item
        },
        error: function (error) {
            console.error(error);
        }
    });
});

});
</script>






  </script>

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
    $(document).ready(function () {

       
        $('.menu-icon').click(function () {
            $('.nav-area').toggleClass('show');
        });

    });
</script>

<script>
    $(document).ready(function () {
        $(".checkout-btn").on("click", function () {
            // Use AJAX to fetch current cart items
            $.ajax({
                url: 'getCartItems.php',
                method: 'GET',
                success: function (response) {
                    var cartItems = JSON.parse(response);

                    if (cartItems.length > 0) {
                        // Take the details of the first item in the cart
                        var item = cartItems[0];

                        // Redirect to checkout.php with product details as query parameters
                        window.location.href = "checkout.php" +
                            "?product_name=" + encodeURIComponent(item['product_name']) +
                            "&product_price=" + encodeURIComponent(item['product_price']) +
                            "&quantity=" + encodeURIComponent(item['quantity']) +
                            "&total_price=" + encodeURIComponent(item['product_price'] * item['quantity']);
                    } else {
                        // Handle the case where the cart is empty
                        alert("Your shopping cart is empty. Add items before proceeding to checkout.");
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    });
</script>






</body>
</html>