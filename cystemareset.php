<?php
use PHPMailer\PHPMailer\PHPMailer;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

include_once('dbcon.php');

if (isset($_REQUEST['pwdrst'])) {
    $email = $_REQUEST['email'];

    // Check for a valid email format on the server side
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format";
    } else {
        $check_email = mysqli_query($db, "SELECT email FROM customers WHERE email='$email'");
        $res = mysqli_num_rows($check_email);

        if ($res > 0) {
            $message = '<div>
                <p><b>Hello!</b></p>
                <p>You are receiving this email because we received a password reset request for your account.</p>
                <br>
                <p><button class="btn btn-primary"><a href="http://localhost/PBL_Cystema/passwordreset.php?secret=' . base64_encode($email) . '">Reset Password</a></button></p>
                <br>
                <p>If you did not request a password reset, no further action is required.</p>
            </div>';

            $mail = new PHPMailer(); // Create a new instance of PHPMailer
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "tls";
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->Username = "pblcystema@gmail.com";
            $mail->Password = "lebozeghtezfojuu";
            $mail->FromName = "CYSTEMA";
            $mail->AddAddress($email);
            $mail->Subject = "Reset Password";
            $mail->isHTML(true);
            $mail->Body = $message;

            if ($mail->send()) {
                $msg = "We have e-mailed your password reset link!";
            } else {
                $msg = "Error sending email: " . $mail->ErrorInfo;
            }
        } else {
            $msg = "We can't find a user with that email address";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="cystemareset.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <i class="fa-solid fa-cart-shopping" style="color: white;" disabled></i>
</div>
        </div>
    </nav>
      

        <div class="center">
        <h1>Reset your password</h1>
       
        <form onsubmit="return validateForm()">

            <div class="textfield">
            <input type="text" name="email" id="email" placeholder="" required>
            <span></span>
            <label>Email: </label>
        </div>
            
        <input type="submit" id="login" name="pwdrst" value="Submit" class="btn btn-success" />
            <div class="formgroup">
            <a href="cystemalogin.php" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>


       

       

      
    </header>
    <footer class= "footer">

  

</body>
</html>