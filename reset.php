<html>  
<head>  
    <title>Forgot Password</title>  
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="reset.css">
</head>

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
                <p><button class="btn btn-primary"><a href="http://localhost/Cystema/passwordreset.php?secret=' . base64_encode($email) . '">Reset Password</a></button></p>
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
<body>
    <div class="center">
        <h3>Forgot Password</h3>
        <form id="validate_form" method="post">
            <div class="textfield">
                <input type="text" name="email" id="email" required data-parsley-type="email" data-parsley-trigger="keyup" />
                <label for="email">Email Address</label>
            </div>
            <input type="submit" id="login" name="pwdrst" value="Send Password Reset Link" />
            <a href="cystemalogin.php">Cancel</a>
            <p class="error"><?php if(!empty($msg)){ echo $msg; } ?></p>
        </form>
    </div>
</body>
</html>