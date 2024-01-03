<?php
session_start();
require 'dbcon.php';

if (!$db) {
  die("Connection failed: " . mysqli_connect_error());
}

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if(isset($_POST['add_to_cart'])){
    // Validate input parameters
    if(isset($_POST['product_name'], $_POST['product_price'], $_POST['product_image'])){
        $product_name = mysqli_real_escape_string($db, $_POST['product_name']);
        $product_price = mysqli_real_escape_string($db, $_POST['product_price']);
        $product_image = mysqli_real_escape_string($db, $_POST['product_image']);
        $product_quantity = 1;

        $select_cart = mysqli_query($db, "SELECT * FROM `cart` WHERE product_name = '$product_name'");
        
        if(mysqli_num_rows($select_cart) > 0){
            $message[] = 'Product Already Added To Cart';
        } else {
            $insert_product = mysqli_query($db, "INSERT INTO `cart` (product_name, product_price, product_image, quantity) VALUES ('$product_name', '$product_price', '$product_image', '$product_quantity')");
            $message[] = 'Product Added To Cart Successfully';
        }
    } else {
        $message[] = 'Invalid input parameters';
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
    <title>Bags</title>
    <link rel="stylesheet" type="text/css" href="cystemabags.css">
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

                <i class="fas fa-search search-icon" style="color:white"></i>

<div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
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

       

        <div class="bags">
  <h1>Bags</h1>
  <ul class="bags-list">
  <?php
$db = mysqli_connect('localhost', 'root', '', 'cystema');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$category = "Bags"; // Specify the category you want to display
$sql = "SELECT * FROM products WHERE category = '$category'";
$result = mysqli_query($db, $sql);
$counter = 0;

while ($row = mysqli_fetch_array($result)) {
    echo "<li>";
    echo "<img src='image/" . $row['image'] . "'>";
    echo "<p>" . $row['product_name'] . "</p>";
    echo "<p>Price: ₱" . $row['price'] . "</p>"; 
    echo "<a href='product.php?product_id=" . $row['product_id'] ."&image=". "' class='view-details-link'>View Details</a>";
   
    echo "</li>";

    $counter++;

    if ($counter % 5 == 0) {
        // Start a new row after every 5 images
        echo "</ul><ul class='bags-list'>";
    }
}
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
   
</body>
</html>