<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

session_start();
include "dbcon.php";

if (
    isset($_POST['first_name']) &&
    isset($_POST['last_name']) &&
    isset($_POST['address']) &&
    isset($_POST['contact_number']) &&
    isset($_POST['username']) &&
    isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['confirm_password']) &&
    $_POST['password'] === $_POST['confirm_password']
) {
    $first_name = validate($_POST['first_name']);
    $last_name = validate($_POST['last_name']);
    $address = validate($_POST['address']);
    $contact_number = validate($_POST['contact_number']);
    $username = validate($_POST['username']);
    $email = validate($_POST['email']);
    $raw_password = validate($_POST['password']);


    // Check Email Availability
    $checkEmailQuery = "SELECT * FROM customers WHERE email=?";
    $checkEmailStmt = mysqli_prepare($db, $checkEmailQuery);
    mysqli_stmt_bind_param($checkEmailStmt, "s", $email);
    mysqli_stmt_execute($checkEmailStmt);
    $checkEmailResult = mysqli_stmt_get_result($checkEmailStmt);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        header("Location: cystemaregister.php?error=Email already registered.");
        exit();
    }
    
    $hashed_password = password_hash($raw_password, PASSWORD_BCRYPT);
    

    

    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['last_name'] = $last_name;
    $_SESSION['address'] = $address;
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    $_SESSION['contact_number'] = $contact_number;

    $status = "pending"; // Initial value


    $mail = new PHPMailer();

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "pblcystema@gmail.com";
    $mail->Password = "lebozeghtezfojuu";
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

  // Sender and recipient details
  $mail->setFrom("pblcystema@gmail.com", "CYSTEMA");
  $mail->addAddress($email);

  // Email content
  $mail->isHTML(true);
  $mail->Subject = "Welcome to Cystema";
  $mail->Body = '<div style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: white;">
  <h2 style="text-align: center;">Your One-Time Password (OTP) is <strong>' . $otp . '</strong> </h2>
  <p>Hi there,

  Thank you for signing up for a Cystema account!
  
  Please enter the OTP to verify your account so you can access the Dashboard and proceed with your shopping.
  
  This OTP is only valid for 10 minutes. If you did not request an OTP, contact  pblcystema@gmail.com
  
  If you have any questions, feel free to respond to this email or visit our Facebook link
  https://www.facebook.com/cystemaph?mibextid=ZbWKwL
  
  Best,
  Cystema team
  
   .</p>
  </div>';

        try {
            // Send the email
            $mail->send();

            // Prepare the SQL statement to insert OTP and status
            $sql = "INSERT INTO `customers` (`first_name`, `last_name`, `address`, `username`, `email`, `password`, `contact_number`, `otp`, `status`, `registration_time`, `admin_flag`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 0)";

            // Create a prepared statement
            $stmt = mysqli_prepare($db, $sql);

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "sssssssss", $first_name, $last_name, $address, $username, $email,$hashed_password, $contact_number, $otp, $status);



            // Execute the statement
            mysqli_stmt_execute($stmt);

            // Close the statement
            mysqli_stmt_close($stmt);

            // Redirect to OTP verification page
            header("Location: verify_otp.php");
            exit();
        } catch (Exception $e) {
            header("Location: registration.php?error=Failed to send OTP email");
            exit();
        }

} else {
    header("Location: registration.php");
    exit();
}

function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generateOTP($length = 6)
{
    $digits = '0123456789';
    $otp = '';

    for ($i = 0; $i < $length; $i++) {
        $otp .= $digits[rand(0, 9)];
    }

    return $otp;
}
?>
