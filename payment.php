<?php
//payment.php
session_start();



// Check if the order details are stored in the session
if (!isset($_SESSION['order_details'])) {
    // Redirect to the checkout page if order details are not available
    header('Location: checkout.php');
    exit();
}

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

// Retrieve order details from the session
$order_details = $_SESSION['order_details'];

// Generate a random reference number
$reference_number = generateReferenceNumber();

// Update the order details with the reference number
$order_details['reference_number'] = $reference_number;

// Save order details to the database
saveToDatabase($order_details);



// Send delivery receipt via email
sendDeliveryReceipt($order_details);



// Function to generate a random reference number
function generateReferenceNumber($length = 12) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $random_string = '';

    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $random_string;
}

// Function to send a delivery receipt via email
function sendDeliveryReceipt($order_details) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Set up SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pblcystema@gmail.com'; // Replace with your email address
        $mail->Password   = 'lebozeghtezfojuu'; // Replace with your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Set up sender and recipient
        $mail->setFrom('pblcystema@gmail.com', 'CYSTEMA'); // Replace with your email and name
        $mail->addAddress($order_details['email'], $order_details['first_name'] . ' ' . $order_details['last_name']);

        // Set email content
        $mail->isHTML(true);
        $mail->Subject = 'Order Confirmation and Delivery Receipt';
        $mail->Body    = generateReceiptHTML($order_details);

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        // Handle any exceptions
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

// Function to generate HTML content for the delivery receipt
function generateReceiptHTML($order_details) {
    $html = '<h1>Order Confirmation and Delivery Receipt</h1>';
    $html .= '<p><strong>Reference Number:</strong> ' . $order_details['reference_number'] . '</p>';
    $html .= '<p><strong>Product(s):</strong> ' . $order_details['product_names'] . '</p>';
    $html .= '<p><strong>Total Price:</strong> ₱' . $order_details['total_price'] . '</p>';
    $html .= '<h2>Delivery Information:</h2>';
    $html .= '<p><strong>Name:</strong> ' . $order_details['first_name'] . ' ' . $order_details['last_name'] . '</p>';
    $html .= '<p><strong>Contact Number:</strong> ' . $order_details['contact_number'] . '</p>';
    $html .= '<p><strong>Email:</strong> ' . $order_details['email'] . '</p>';
    $html .= '<p><strong>Address:</strong> ' . $order_details['lot_number'] . ', ' . $order_details['street'] . ', ' . $order_details['barangay'] . ',' . $order_details['city'] . ', ' . $order_details['post_code'] . '</p>';
    $html .= '<p><strong>Payment Method:</strong> ' . $order_details['payment_method'] . '</p>';
    $html .= '<p><strong>Status:</strong> ' . $order_details['status'] . '</p>';
    $html .= '<p>Your order will be delivered soon. Thank you for shopping with us!</p>';
    
    return $html;
}

// Function to save order details to the database
function saveToDatabase($order_details) {
    // Establish database connection (update with your database credentials)
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'cystema';

    $db = mysqli_connect($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }








    // Initialize status if not set
    if (!isset($order_details['status'])) {
        $order_details['status'] = 'pending';
    }

    // Escape and sanitize data before insertion (prevent SQL injection)
    $reference_number = mysqli_real_escape_string($db, $order_details['reference_number']);
    $product_name = mysqli_real_escape_string($db, $order_details['product_names']);
    $total_price = mysqli_real_escape_string($db, $order_details['total_price']);
    $first_name = mysqli_real_escape_string($db, $order_details['first_name']);
    $last_name = mysqli_real_escape_string($db, $order_details['last_name']);
    $contact_number = mysqli_real_escape_string($db, $order_details['contact_number']);
    $email = mysqli_real_escape_string($db, $order_details['email']);
    $lot_number = mysqli_real_escape_string($db, $order_details['lot_number']);
    $street = mysqli_real_escape_string($db, $order_details['street']);
    $barangay = mysqli_real_escape_string($db, $order_details['barangay']);
    $city = mysqli_real_escape_string($db, $order_details['city']);
    $post_code = mysqli_real_escape_string($db, $order_details['post_code']);
    $payment_method = mysqli_real_escape_string($db, $order_details['payment_method']);
    $status = mysqli_real_escape_string($db, $order_details['status']);

    // SQL insertion query
    $sql = "INSERT INTO payment (reference_number, product_name, total_price, first_name, last_name, contact_number, email, lot_number, street, barangay, city, post_code, payment_time, status, payment_method) 
            VALUES ('$reference_number', '$product_name', '$total_price', '$first_name', '$last_name', '$contact_number', '$email', '$lot_number', '$street', '$barangay', '$city', '$post_code', NOW(), '$status', '$payment_method')";

    // Execute the query
    if (mysqli_query($db, $sql)) {

         "Order details saved to the database successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($db);
    }

    // Close the database connection
    mysqli_close($db);
}










?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link rel="stylesheet" type="text/css" href="payment1.css">
</head>
<body>
    <div class="container">
        <h1>Order successfully Confirmed.</h1>
        <p>Your Order will be Delivered Soon. Thank you for Shopping with Us!</p>
        <p>Payment Status: <strong><?php echo $order_details['status']; ?></strong></p>
        
        <div class="button-container">
            <a href="cart.php"><button class="button">Go To Shopping Cart</button></a>
        </div>

        <div class="button-container">
            <a href="cystemahome.php"><button class="button">Go To Home</button></a>
        </div>
    </div>
</body>
</html>
