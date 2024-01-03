<?php
session_start();
require 'dbcon.php';

if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];

    // Fetch user activities from the database
    $activityQuery = "SELECT * FROM user_activities WHERE user_email = '$userEmail' ORDER BY timestamp DESC";
    $result = mysqli_query($db, $activityQuery);

    if (!$result) {
        // Handle the error if needed
        echo "Error: Unable to fetch user activities. " . mysqli_error($db);
    }
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: cystemalogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYSTEMA</title>
    <link rel="stylesheet" type="text/css" href="useract.css">
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
                <i class="fa-solid fa-magnifying-glass" style="color: white;"></i>

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

</div>


            </div>
           
        </nav>
    </header>

    <!-- Display user activities -->
    <div class="user-activities-container">
        <?php if (isset($_SESSION['email'])) : ?>
            <h2>Your Activities</h2>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <li>
                        <p>Type: <?php echo $row['activity_type']; ?></p>
                        <p>Description: <?php echo $row['activity_description']; ?></p>
                        <p>Timestamp: <?php echo $row['timestamp']; ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p>Please log in to view your activities.</p>
        <?php endif; ?>
    </div>
    <footer>
        <!-- Include your footer content here -->
    </footer>
</body>
</html>
