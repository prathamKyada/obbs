<?php
session_start();
require('vendor/autoload.php'); // Ensure Razorpay SDK is installed via Composer

use Razorpay\Api\Api;

// Razorpay API Credentials
$api_key = "YOUR_RAZORPAY_KEY_ID";
$api_secret = "YOUR_RAZORPAY_SECRET";

$api = new Api($api_key, $api_secret);

// Fetch user details
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_connection.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch pending booking details
$booking_query = "SELECT * FROM bookings WHERE user_id = ? AND status = 'Pending' LIMIT 1";
$stmt = $conn->prepare($booking_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$booking_result = $stmt->get_result();
$booking = $booking_result->fetch_assoc();

if (!$booking) {
    echo "<script>alert('No pending booking found!'); window.location.href='index.php';</script>";
    exit();
}

// Create a Razorpay order
$orderData = [
    'receipt'         => "BOOKING_" . $booking['id'],
    'amount'          => $booking['amount'] * 100, // Convert to paise
    'currency'        => 'INR',
    'payment_capture' => 1 // Auto-capture payment
];

$order = $api->order->create($orderData);
$order_id = $order['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Complete Your Payment</h2>
        <p>Booking ID: <?php echo htmlspecialchars($booking['id']); ?></p>
        <p>Amount: ₹<?php echo htmlspecialchars($booking['amount']); ?></p>

        <button id="rzp-button1" class="btn btn-success">Pay Now</button>
    </div>

    <script>
        var options = {
            "key": "<?php echo $api_key; ?>",
            "amount": "<?php echo $booking['amount'] * 100; ?>",
            "currency": "INR",
            "name": "OBBS",
            "description": "Banquet Booking Payment",
            "image": "https://yourwebsite.com/logo.png",
            "order_id": "<?php echo $order_id; ?>",
            "handler": function (response){
                window.location.href = "payment-success.php?payment_id=" + response.razorpay_payment_id;
            },
            "prefill": {
                "name": "<?php echo $user['name']; ?>",
                "email": "<?php echo $user['email']; ?>",
                "contact": "<?php echo $user['phone']; ?>"
            },
            "theme": {
                "color": "#3399cc"
            }
        };
        var rzp1 = new Razorpay(options);
        document.getElementById('rzp-button1').onclick = function(e){
            rzp1.open();
            e.preventDefault();
        }
    </script>
</body>
</html>
