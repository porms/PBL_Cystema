<?php
session_start();
require 'dbcon.php';

$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// Check database connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle add to cart logic
if (isset($_POST['add_to_cart'])) {
    // Validate input parameters
    if (isset($_POST['product_name'], $_POST['product_price'], $_POST['product_image'])) {
        $product_name = mysqli_real_escape_string($db, $_POST['product_name']);
        $product_price = mysqli_real_escape_string($db, $_POST['product_price']);
        $product_image = mysqli_real_escape_string($db, $_POST['product_image']);
        $product_quantity = 1;

        // Check if the product is already in the cart for the logged-in user
        $cartProducts = array_column($_SESSION['cart'], 'productName');

        if (in_array($product_name, $cartProducts)) {
            // Product is already in the cart, update quantity
            $key = array_search($product_name, $cartProducts);
            $_SESSION['cart'][$key]['quantity'] += 1;
            $message = 'Product Already Added To Cart';
        } else {
            // Product is not in the cart, add it to the cart session
            $_SESSION['cart'][] = [
                'productId' => null, // You may need to set this to the actual product ID
                'productName' => $product_name,
                'productPrice' => $product_price,
                'productImage' => $product_image,
                'quantity' => $product_quantity,
                'userEmail' => $userEmail,
            ];

            // Insert into the database (or update based on your logic)
            $query = "INSERT INTO cart (product_id, product_name, product_price, product_image, quantity, user_email) 
                      VALUES (null, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";

            $stmt = mysqli_prepare($db, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sdsis", $product_name, $product_price, $product_image, $product_quantity, $userEmail);
                $success = mysqli_stmt_execute($stmt);

                if (!$success) {
                    $message = "Error: " . mysqli_error($db);
                } else {
                    $message = "Product Added To Cart Successfully";
                }

                mysqli_stmt_close($stmt);
            } else {
                $message = "Error: Unable to prepare the statement. " . mysqli_error($db);
            }
        }
    } else {
        $message = "Error: Invalid input parameters";
    }
}

$queryActivities = "SELECT * FROM user_activities WHERE user_email = ? ORDER BY timestamp DESC LIMIT 5";
$stmtActivities = mysqli_prepare($db, $queryActivities);

if ($stmtActivities) {
    mysqli_stmt_bind_param($stmtActivities, "s", $userEmail);
    mysqli_stmt_execute($stmtActivities);
    $activities = mysqli_stmt_get_result($stmtActivities);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYSTEMA</title>
    <link rel="stylesheet" type="text/css" href="cystemahome.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

 
  
    
</head>
<body>



    <header>    
        <nav>
            <div class="wrapper">
              

            
           <i class="fas fa-bars menu-icon"></i>
               
               
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
                <i class="fas fa-search search-icon" style="color:white"></i>

                <div class="search-container">
    <input type="text" id="search-input" placeholder="Search..."   >
    <div id="search-results"></div>
    <span class="close-search-container">&times;</span>
</div>
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








<div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <img src="Blog.jpg" alt="Slide 1" style="width:100%;">
            </div>
            <div class="item">
                <img src="Blog 3.jpg" alt="Slide 2" style="width:100%;">
            </div>
            <div class="item">
                <img src="Blog 4.jpg" alt="Slide 3" style="width:100%;">
            </div>
        </div>
      <!-- Left and right controls -->
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    

<div class="shirts">
  <h1>Shirts</h1>
  <ul class="shirt-list">

  <?php


$category = "Shirts"; // Category of the items posted
$sql = "SELECT * FROM products WHERE category = '$category' LIMIT 5"; // Limit to the first 5 items
$result = mysqli_query($db, $sql);

echo "<ul class='shirt-list'>";

while ($row = mysqli_fetch_array($result)) {
    echo "<li>";
    echo "<div id='img_div'>";
    echo "<img src='image/" . $row['image'] . "'>";
    echo "<p>" . $row['product_name'] . "</p>";
    echo "<p>Price: ₱" . $row['price'] . "</p>"; 
    echo "<a href='product.php?product_id=" . $row['product_id'] ."&image=". "' class='view-details-link'>View Details</a>";
    echo "</div>";
    echo "</li>";
}

echo "</ul>";
?>


  </ul>
</div>

<!--bags-->

<div class="bags">
  <h1>Bags</h1>
  <ul class="bags-list">
    
  <?php


$category = "Bags"; // Category of the items posted
$sql = "SELECT * FROM products WHERE category = '$category' LIMIT 5"; // Limit to the first 5 items
$result = mysqli_query($db, $sql);

echo "<ul class='bags-list'>";

while ($row = mysqli_fetch_array($result)) {
    echo "<li>";
    echo "<div id='img_div'>";
    echo "<img src='image/" . $row['image'] . "'>";
    echo "<p>" . $row['product_name'] . "</p>";
    echo "<p>Price: ₱" . $row['price'] . "</p>"; 
    echo "<a href='product.php?product_id=" . $row['product_id'] ."&image=". "' class='view-details-link'>View Details</a>";
    echo "</div>";
    echo "</li>";
}

echo "</ul>";
?>


  </ul>
</div>



<div class="accessories">
<h1>Accessories</h1>
  <ul class="accessories-list">
    
  <?php

$category = "Accessories"; // Category of the items posted
$sql = "SELECT * FROM products WHERE category = '$category' ORDER BY upload_date DESC LIMIT 5";
 // Limit to the first 5 items
$result = mysqli_query($db, $sql);

echo "<ul class='accessories-list'>";

while ($row = mysqli_fetch_array($result)) {
    echo "<li>";
    echo "<div id='img_div'>";
    echo "<img src='image/" . $row['image'] . "'>";
    echo "<p>" . $row['product_name'] . "</p>";
    echo "<p>Price: ₱" . $row['price'] . "</p>"; 
    echo "<a href='product.php?product_id=" . $row['product_id'] ."&image=". "' class='view-details-link'>View Details</a>";
    echo "</div>";
    echo "</li>";
}

echo "</ul>";
?>


  </ul>
</div>

    
<footer>
  <div class="footer-container">
    <div class="social-links">
      <h2>Follow Us</h2>
      <ul class="links">
        <li><a href="https://www.tiktok.com/@cystemaph?_t=8gZhWSjUwTp&_r=1"><i class="fa-brands fa-tiktok"></i></a></li>
        <li><a href="https://instagram.com/cystemaph?igshid=NTc4MTIwNjQ2YQ=="><i class="fa-brands fa-instagram"></i></a></li>
        <li><a href="https://www.facebook.com/cystemaph?mibextid=ZbWKwL"><i class="fa-brands fa-facebook"></i></a></li>
      </ul>
    </div>

    <div class="quick-links">
      <h2>Quick Links</h2>
      <ul class="links">
        <li><a href="#">About Us</a></li>
        <li><a href="freedomwall.php">Freedom Wall</a></li>
      </ul>
    </div>
</div>

  <div class="footer-bottom">
    <p>&copy; 2023 CYSTEMA. All rights reserved.</p>
  </div>
</footer>

<script>
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

    $(document).ready(function () {
        // Initialize the carousel
        $('#myCarousel').carousel();
        let currentIndex = 0;
        const items = document.querySelectorAll('.carousel-item');
        const totalItems = items.length;

        function showNext() {
            if (currentIndex < totalItems - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateCarousel();
        }

        function showPrev() {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = totalItems - 1;
            }
            updateCarousel();
        }

        function updateCarousel() {
            const newTransformValue = -currentIndex * 100 + '%';
            document.querySelector('.carousel-inner').style.transform = 'translateX(' + newTransformValue + ')';
        }

        setInterval(showNext, 3000);  // Optional: automatic sliding every 3 seconds
    });

    var cartData = [];  // Initialize cartData as an empty array
    var userEmail = '<?php echo $userEmail; ?>';

    $(document).ready(function () {
        // Assuming you have a click event handler for adding items to the cart
        $('.add-to-cart-btn').click(function () {
            var button = $(this);
            var productId = button.data('product-id');
            var productName = button.data('product-name');
            var productPrice = button.data('product-price');
            var productImage = button.data('product-image');

            // Add the clicked item to the cartData array
            var cartItem = {
                productId: productId,
                productName: productName,
                productPrice: productPrice,
                productImage: productImage,
                quantity: 1,  // You may adjust the quantity as needed
                userEmail: userEmail
            };

            // Check if the item is already in the cart
            var itemIndex = cartData.findIndex(item => item.productId === productId);

            if (itemIndex !== -1) {
                // Item is already in the cart, update quantity
                cartData[itemIndex].quantity += 1;
                console.log("Product Already Added To Cart");
            } else {
                // Item is not in the cart, add it
                cartData.push(cartItem);
                console.log("Product Added To Cart Successfully");
            }

            // Make an AJAX request to update the cart
            $.ajax({
                type: 'POST',
                url: 'addToCart.php',
                contentType: 'application/json',  // Set content type to JSON
                data: JSON.stringify({
                    add_to_cart: true,
                    email: userEmail,
                    cart: cartData,
                }),
                success: function (response) {
                    console.log(response);
                    alert('Item added to cart successfully!');
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', xhr.responseText);
                    alert('AJAX request failed. Please check the console for more information.');
                }
            });

        });

        console.log("AJAX Request Data:", {
            add_to_cart: true,
            email: userEmail,
            cart: JSON.stringify(cartData),
        });
    });
</script>




</body>
</html>