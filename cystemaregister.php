<!--cystemaregister.php-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="registercystema.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

  
    
<div class="content">
    <div class="center">
        <h3>Create Account</h3>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <form action="regvalid.php" method="post" id="registrationForm" onsubmit="return validateForm()">



            <div class="textfield">
                <input type="text" name="first_name" id="first_name" required oninput="validateName(this)">
                <span></span>
                <label>First Name: </label>
            </div>

            <div class="textfield">
                <input type="text" name="last_name" id="last_name" required oninput="validateName(this)">
                <span></span>
                <label>Last Name: </label>
            </div>

            <div class="textfield">
                <input type="text" name="address" id="address" required oninput>
                <span></span>
                <label>Address: </label>
            </div>

            <div class="textfield">
                <input type="text" name="contact_number" id="contact_number" required oninput="validateNumber(this)">
                <span></span>
                <label>Contact Number: </label>
            </div>

            <div class="textfield">
                <input type="email" name="email" id="email" required oninput="validateEmail(this)" onBlur="checkEmailAvailability()">
                <span></span>
                <label>Email: </label>
                <span id="emailAvailability"></span>
            </div>

            <div class="textfield">
                <input type="text" name="username" id="username" required>
                <span></span>
                <label>Username: </label>
            </div>

            <div class="textfield">
                <input type="password" name="password" id="password" required>
                <span class="toggle-password">
                    <i class="fa fa-eye" id="togglePassword" onclick="togglePasswordField('password')"></i>
                </span>
                <label>Password: </label>
            </div>

            <div class="textfield">
                <input type="password" name="confirm_password" id="confirm_password" required>
                <span class="toggle-password">
                    <i class="fa fa-eye" id="toggleConfirmPassword" name="toggleConfirmPassword"
                        onclick="togglePasswordField('confirm_password')"></i>
                </span>
                <label>Confirm Password: </label>
            </div>

            <div class="privacy">
                <input type="checkbox" id="privacy-checkbox" name="privacy-checkbox" required>
                <label for="privacy-checkbox">
                    I agree to the <a href="privacy.php" target="_blank">Privacy Agreement</a>
                </label>
            </div>
            <div class="g-recaptcha" id="recaptcha" data-sitekey="6LcFu64oAAAAAJTiCd7O36TWAE4jRtQhwbSnByz4"></div>
            <input type="submit" name="create" value="Create" id="create-button">
        </form>
    </div>
</div>
<!-- ... (previous code) -->

<!-- ... (previous code) -->






<footer>
  <p>Follow Us</p>
  <ul class="links">
    <li><a href="https://www.tiktok.com/@cystemaph?_t=8gZhWSjUwTp&_r=1"><i class="fa-brands fa-tiktok"></i></a></li>
    <li><a href="https://instagram.com/cystemaph?igshid=NTc4MTIwNjQ2YQ=="><i class="fa-brands fa-instagram"></i></a></li>
    <li><a href="https://www.facebook.com/cystemaph?mibextid=ZbWKwL"><i class="fa-brands fa-facebook"></i></a></li>
  </ul>
</footer>


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

<script>
$(document).ready(function () {

    $('.menu-icon').click(function () {
        $('.nav-area').toggleClass('show');
    });

})
</script>

<script>
    function validateForm() {
        // Validate First Name and Last Name
        var nameRegex = /^[a-zA-Z ]*$/;
        var firstName = document.getElementById('first_name').value.trim();
        var lastName = document.getElementById('last_name').value.trim();

        if (!nameRegex.test(firstName) || !nameRegex.test(lastName)) {
            alert('First Name and Last Name should contain letters and spaces only.');
            return false;
        }

        // Validate Contact Number
        var contactNumber = document.getElementById('contact_number').value.trim();

        if (contactNumber.length !== 11 || isNaN(contactNumber)) {
            alert('Contact Number should be 11 digits and contain only numbers.');
            return false;
        }

        // Validate Username
        var usernameRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/;
        var username = document.getElementById('username').value.trim();

        if (!usernameRegex.test(username)) {
            alert('Username should be 12 characters, with 1 uppercase, 1 lowercase, 1 number, and 1 special character.');
            return false;
        }

        // Validate Password and Confirm Password
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,16}$/;
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm_password').value;

        if (!passwordRegex.test(password) || password !== confirmPassword) {
            alert('Password should be 12-16 characters with 1 uppercase, 1 lowercase, 1 number, and 1 special character. Passwords must match.');
            return false;
        }

        // Validate Recaptcha
        if (!grecaptcha.getResponse()) {
            alert('Please complete the Recaptcha challenge.');
            return false;
        }

        // Validate Data Privacy Checkbox
        if (!document.getElementById('privacy-checkbox').checked) {
            alert('Please agree to the Data Privacy Agreement.');
            return false;
        }

        return true;
    }
</script>

<script>
    // Password Copy-Paste Prevention
    var passwordField = document.getElementById('password');
    var confirmPasswordField = document.getElementById('confirm_password');

    [passwordField, confirmPasswordField].forEach(function(field) {
        field.addEventListener('input', function() {
            var isPasting = false;

            field.addEventListener('paste', function(e) {
                isPasting = true;
                setTimeout(function() {
                    isPasting = false;
                }, 1);
            });

            field.addEventListener('input', function(e) {
                if (isPasting) {
                    field.value = '';
                    alert('Copying and pasting passwords is not allowed. Please type your password.');
                }
            });
        });
    });
</script>

</body>
</html>