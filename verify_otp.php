<?php
// verify_otp.php
session_start();

if (!isset($_SESSION['otp']) || !isset($_SESSION['email'])) {
    header("Location: home.php");
    exit();
}

require "dbcon.php"; // Include your database connection file

if (isset($_POST['otp'])) {
    $enteredOTP = validate($_POST['otp']);
    $storedOTP = $_SESSION['otp'];

    if ($enteredOTP == $storedOTP) {
        // OTP verification successful

        // Retrieve the registered values
        $first_name = validate($_SESSION['first_name']);
        $last_name = validate($_SESSION['last_name']);
        $address = validate($_SESSION['address']);
        $username = validate($_SESSION['username']);
        $email = validate($_SESSION['email']);
        $password = validate($_SESSION['password']);
        $contact_number = validate($_SESSION['contact_number']); // Get contact number from the session

                // Check if the email already exists
                $checkEmailQuery = "SELECT COUNT(*) as count FROM `customers` WHERE `email` = ?";
                $stmtCheckEmail = mysqli_prepare($db, $checkEmailQuery);
                mysqli_stmt_bind_param($stmtCheckEmail, "s", $email);
                mysqli_stmt_execute($stmtCheckEmail);
                mysqli_stmt_bind_result($stmtCheckEmail, $count);
                mysqli_stmt_fetch($stmtCheckEmail);
                mysqli_stmt_close($stmtCheckEmail);
            
              
         // Prepare the SQL statement to update status
                    $updateStatusSql = "UPDATE `customers` SET `status` = 'verified' WHERE `email` = ?";
                    $stmtUpdateStatus = mysqli_prepare($db, $updateStatusSql);
                    mysqli_stmt_bind_param($stmtUpdateStatus, "s", $email);
                    mysqli_stmt_execute($stmtUpdateStatus);
                    mysqli_stmt_close($stmtUpdateStatus);

                // Clear the session variables
                unset($_SESSION['otp']);
                unset($_SESSION['email']);
                unset($_SESSION['first_name']);
                unset($_SESSION['last_name']);
                unset($_SESSION['address']);
                unset($_SESSION['username']);
                unset($_SESSION['password']);
                unset($_SESSION['contact_number']);

               // Redirect to success page or any other desired page
               header("Location: cystemalogin.php?error=Account Verified Successfully.");

    exit();
        } else {
            // Incorrect OTP entered
                    // Update the status to 'notverified'
                    $updateStatusSql = "UPDATE `customers` SET `status` = 'notverified' WHERE `email` = ?";
                    $stmtUpdateStatus = mysqli_prepare($db, $updateStatusSql);
                    mysqli_stmt_bind_param($stmtUpdateStatus, "s", $email);
                    mysqli_stmt_execute($stmtUpdateStatus);
                    mysqli_stmt_close($stmtUpdateStatus);


            // Redirect back to OTP verification page with error message
            header("Location: verify_otp.php?error=The OTP you entered is incorrect. Please try again.");
            exit();
        }
}

                    $status = "pending"; // You may need to retrieve the status from the database based on the email in the session
                    if ($status !== 'pending') {
                        // Redirect or handle accordingly (e.g., show an error)
                        header("Location: cystemaregister.php?error=Invalid registration status.");
                        exit();
                    }

function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <link rel="stylesheet" type="text/css" href="stylev.css">
    <link rel="shortcut icon" type="image/png" href="logochoice1.jpg">
</head>
<body>
   
    <div class="votp">
    <div class="container">
        <h2>OTP Verification</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <p>Please enter the OTP sent to your email <strong><?php echo $_SESSION['email']; ?></strong></p>
    </div>

    <form action="verify_otp.php" method="post">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit">Verify OTP</button>
    </form>
        </div>
</body>
</html>