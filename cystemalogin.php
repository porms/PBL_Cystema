<?php
//cystemalogin_
session_start();
session_regenerate_id(true);
require 'dbcon.php';
    
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);


        // Check if the login attempt is from an admin
        $adminQuery = "SELECT * FROM admin WHERE email=? AND password=?";
        $adminStmt = mysqli_prepare($db, $adminQuery);
        mysqli_stmt_bind_param($adminStmt, "ss", $email, $password);
        mysqli_stmt_execute($adminStmt);
        $adminResult = mysqli_stmt_get_result($adminStmt);
    
        if (mysqli_num_rows($adminResult) == 1) {
            $_SESSION['admin'] = $email;
            // Mark the admin as logged in
            $_SESSION['admin_logged_in'] = true;
            header('location: admin.php');
            
            exit();
        }
    

          // Check if the user exists
    $loginQuery = "SELECT * FROM customers WHERE email=?";
    $loginStmt = mysqli_prepare($db, $loginQuery);
    mysqli_stmt_bind_param($loginStmt, "s", $email);
    mysqli_stmt_execute($loginStmt);
    $loginResult = mysqli_stmt_get_result($loginStmt);

    // Check if the user is verified
    $checkStatusQuery = "SELECT status FROM customers WHERE email=?";
    $checkStatusStmt = mysqli_prepare($db, $checkStatusQuery);
    mysqli_stmt_bind_param($checkStatusStmt, "s", $email);
    mysqli_stmt_execute($checkStatusStmt);
    $checkStatusResult = mysqli_stmt_get_result($checkStatusStmt);

    $userStatus = mysqli_fetch_assoc($checkStatusResult)['status'];

    if ($userStatus !== 'verified') {
        header("Location: cystemalogin.php?error=Email not verified. Please check your email for verification instructions.");
        exit();
    }

  

    if ($row = mysqli_fetch_assoc($loginResult)) {
        

        // Verify the entered password against the hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $email;
            header("Location: cystemahome.php"); // Redirect to the home page or any other authenticated page
            exit();
        } else {
            header("Location: cystemalogin.php?error=Invalid email or password. Please try again.");
            exit();
        }
    } else {
        header("Location: cystemalogin.php?error=Invalid email or password. Please try again." . $row['password'] );
        exit();
    }
}

mysqli_close($db);
?>













<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="cystemalogin.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
                <i class="fa-solid fa-magnifying-glass search-icon" style="color: white;"></i>
 


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

           

            </div>
           
        </nav>



      
</header>
        <div class="center">
         <div class="container">
            <h1>LOGIN</h1>
            <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        </div>   
        
       
        <form onsubmit="return validateForm()" method="post" action="cystemalogin.php">



        <div class="textfield">
                    <input type="email" name="email" id="email" required>
                    <span></span>
                    <label>Email: </label>
                </div>

            <div class="textfield">
            <input type="password" name="password" id="password" required oninput="validatePassword(this)">
                    <span class ="toggle-password">
                        <i class="fa fa-eye" id="togglePassword" onclick="togglePasswordField('password')"></i>
                    </span>
                    <label>Password: </label>
                </div>

            <a href="cystemareset.php">Forgot Password?</a>
            <div class="g-recaptcha" data-sitekey="6LcFu64oAAAAAJTiCd7O36TWAE4jRtQhwbSnByz4"></div>
            <input type="submit" name="login" value="Login" id="login-button">


            <div class="register">
            <a href="cystemaregister.php">Create Account</a>
            </div>
        </form>
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

    $('.close-search-container').click(function () {
        console.log('Close icon clicked'); // Check if this log is displayed
        $('.search-container').removeClass('show');
    });

   
});





    </script>


<script>
    function validateForm() {
        // Validate Email
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var email = document.getElementById('email').value.trim();

        if (!emailRegex.test(email)) {
            alert('Invalid email address.');
            return false;
        }

        // Validate Password
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,16}$/;
        var password = document.getElementById('password').value;

        if (!passwordRegex.test(password)) {
            alert('Password must be 12-16 characters long with 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.');
            return false;
        }

        // Validate Recaptcha
        if (!grecaptcha.getResponse()) {
            alert('Please complete the Recaptcha challenge.');
            return false;
        }


        // Password Copy-Paste Prevention
        if (document.activeElement.tagName === 'INPUT' && document.activeElement.type === 'password') {
            document.activeElement.addEventListener('paste', function (e) {
                e.preventDefault();
                alert('Copying and pasting passwords is not allowed. Please type your password.');
            });
        }

        return true;
    }
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
  // Function to toggle password visibility
function togglePasswordField(fieldId) {
    var field = document.getElementById(fieldId);
    var toggle = document.getElementById('toggle' + fieldId);

    if (field.type === 'password') {
        field.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}





</script>

       

       

      

        

  

  

</body>
</html>
