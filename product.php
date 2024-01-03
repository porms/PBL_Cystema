<?php


session_start();
require 'dbcon.php';

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

$queryActivities = "SELECT * FROM user_activities WHERE user_email = ? ORDER BY timestamp DESC LIMIT 5";
$stmtActivities = mysqli_prepare($db, $queryActivities);

if ($stmtActivities) {
    mysqli_stmt_bind_param($stmtActivities, "s", $email);
    mysqli_stmt_execute($stmtActivities);
    $activities = mysqli_stmt_get_result($stmtActivities);
}

if (isset($_GET['product_id']) && isset($_GET['image'])) {
    $productId = $_GET['product_id'];
    $imageFilename = $_GET['image'];

   

    $sql = "SELECT * FROM products WHERE product_id = '$productId'";
    $result = mysqli_query($db, $sql);

    if ($row = mysqli_fetch_array($result)) {
       
       
        $productName = $row['product_name'];
        $productPrice = $row['price'];
        $imageFilename = $row['image'];
        $description = $row['description'];
      
        ?>



        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="product.css">
            <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

            <title><?php echo $productName; ?> - Product Details</title>
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
                  <!--  <i class="fa-solid fa-magnifying-glass" style="color: white;"> -->
               
<div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <div id="search-results"></div>
</div>
<!--</i>-->
        <?php
        if (!isset($_SESSION['email'])) {
       
            echo '<a href="cystemalogin.php"><i class="fa-solid fa-user" style="color: white;"></i></a>';
        } else {
           
            echo '<div class="dropdown">';
            echo '<i class="fa-solid fa-user" style="color: white;"></i>';
            echo '<div class="dropdown-content">';
            echo '<a href="cystemaprofile.php">Profile</a>';
            echo '<a href="cystemalogout.php">Logout</a>';

            echo '</div>';
            echo '</div>';
        }
        ?>
     <a href="cart.php">   <i class="fa-solid fa-cart-shopping" style="color: white;"></i></a>

     <div class="notification-icon">
        <i class="fa-solid fa-bell" style="color: white;"></i>
        <div class="notification-list">
            <?php if (!isset($_SESSION['email'])) : ?>
                <a href="cystemalogin.php">Login</a>
            <?php else : ?>
                <?php
                // Assuming $activities is defined and contains notification activities
                while ($activity = mysqli_fetch_assoc($activities)) :
                ?>
                    <?php if (isset($activity['activity_type']) && isset($activity['timestamp'])) : ?>
                        <a href="#"><?php echo $activity['activity_type']; ?> - <?php echo $activity['timestamp']; ?></a>
                    <?php else : ?>
                        <p>Invalid activity data</p>
                    <?php endif; ?>
                <?php endwhile; ?>
                <a href="#" class="view-all-link">View All</a>
            <?php endif; ?>
        </div>
    </div>
</div>
   
     
    


                </div>
            
            </nav>

      
   
        <div class="product-container">
                <div class="product-image">
                    <img src="image/<?php echo $imageFilename; ?>" alt="<?php echo $productName; ?> Image">
                </div>
                <div class="product-details">
                    <h1><?php echo $productName; ?></h1>
                    <p>Price: ₱<?php echo $productPrice; ?></p>
                    <div class="product-description">
                        <p><?php echo $description; ?></p>
                    </div>
                    <div class="sizes">
        <label for="size">Sizes:</label>
        <select id="size" name="size">
            <option value="small">Small</option>
            <option value="medium">Medium</option>
            <option value="large">Large</option>
            <option value="large">XL</option>
            <option value="large">XXL</option>
          
        </select>
    </div>

   
    <div class="quantity">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1">
    </div>

    <div class="buttons">
        <button class="add-to-cart-btn"
                data-product-id="<?php echo $productId; ?>"
                data-product-name="<?php echo $productName; ?>"
                data-product-price="<?php echo $productPrice; ?>"
                data-product-image="<?php echo $imageFilename; ?>"
        >
            Add to Cart
        </button>
        <a href="checkout.php"><button id="buy-button">Buy Now</button></a>
    </div>
</div>

          
          
          
          
          
          <script>
    $(document).ready(function () {
        $('.menu-icon').click(function () {
        $('.nav-area').toggleClass('show');
        })
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
    // Other scripts...

    $(".add-to-cart-btn").on("click", function () {
        var productId = $(this).data("product-id");
        var productName = $(this).data("product-name");
        var productPrice = $(this).data("product-price");
        var productImage = $(this).data("product-image");
        var quantity = $("#quantity").val(); // Get the selected quantity

        // Send AJAX request to add the item to the cart
        $.ajax({
            url: 'addToCart.php',
            method: 'POST',
            data: {
                productId: productId,
                productName: productName,
                productPrice: productPrice,
                productImage: productImage,
                quantity: quantity
            },
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.error(error);
            }
        });
    });

    // Other scripts...
});
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
                $('.notification-icon').show()
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
        </body>
        </html>
        
        
        
        <?php
    } else {
        // Product not found, handle accordingly (e.g., redirect to an error page)
        header("Location: error.php");
        exit();
    }
} else {
    // Product ID or image not provided in the URL, handle accordingly (e.g., redirect to an error page)
    header("Location: error.php");
    exit();
}
?>
