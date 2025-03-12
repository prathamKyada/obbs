<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

use Razorpay\Api\Api;

// Razorpay API Credentials
$api_key = "YOUR_RAZORPAY_KEY_ID";
$api_secret = "YOUR_RAZORPAY_SECRET";
$api = new Api($api_key, $api_secret);

include 'includes/dbconnection.php';

// Retrieve amount from SESSION
$amount = $_SESSION['booking_amount'];
$booking_id = $_SESSION['booking_id'];

if (!$amount) {
    echo "<script>alert('No pending booking found!'); window.location.href='index.php';</script>";
    exit();
}

// Create a Razorpay order
$orderData = [
    'receipt'         => "BOOKING_" . $booking_id,
    'amount'          => $amount * 100,
    'currency'        => 'INR',
    'payment_capture' => 1
];

$order = $api->order->create($orderData);
$order_id = $order['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Online Banquet Booking System | Payment</title>

    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

    <!-- bootstrap-css -->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <!--// bootstrap-css -->
    <!-- css -->
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <!--// css -->
    <!-- font-awesome icons -->
    <link href="css/font-awesome.css" rel="stylesheet"> 
    <!-- //font-awesome icons -->
    <!-- font -->
    <link href="//fonts.googleapis.com/css?family=Josefin+Sans:100,100i,300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700italic,700,400italic,300italic,300' rel='stylesheet' type='text/css'>
    <!-- //font -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
</head>
<body>
    <!-- banner -->
    <div class="banner jarallax">
        <div class="agileinfo-dot">
            <?php include_once('includes/header.php');?>
            <div class="wthree-heading">
                <h2>Complete Your Payment</h2>
            </div>
        </div>
    </div>
    <!-- //banner -->
    
    <!-- payment section -->
    <div class="contact">
        <div class="container">
            <div class="agile-contact-form">
                <div class="col-md-6 contact-form-right">
                    <div class="contact-form-top">
                        <h3>Payment Details</h3>
                    </div>
                    <div class="agileinfo-contact-form-grid">
                        <form>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Booking ID:</label>
                                <div class="col-md-10">
                                    <p style="font-size: 20px"><strong><?php echo htmlspecialchars($booking_id); ?></strong></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Amount:</label>
                                <div class="col-md-10">
                                    <p style="font-size: 20px; color: green;"><strong>₹<?php echo htmlspecialchars($amount); ?></strong></p>
                                </div>
                            </div>

                            <br>
                            <div class="tp">
                                <button id="rzp-button1" class="btn btn-primary">Pay Now</button>
                            </div>
                        </form>

                        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                        <script>
                            var options = {
                                "key": "<?php echo $api_key; ?>",
                                "amount": "<?php echo $amount * 100; ?>",
                                "currency": "INR",
                                "order_id": "<?php echo $order_id; ?>",
                                "handler": function (response){
                                    window.location.href = "payment-success.php?payment_id=" + response.razorpay_payment_id;
                                }
                            };
                            var rzp1 = new Razorpay(options);
                            document.getElementById('rzp-button1').onclick = function(e){
                                rzp1.open();
                                e.preventDefault();
                            }
                        </script>

                    </div>
                </div>

                <div class="clearfix"> </div>
            </div>
        </div>
    </div>
    <!-- //payment section -->

    <?php include_once('includes/footer.php'); ?>

    <!-- jarallax -->
    <script src="js/jarallax.js"></script>
    <script src="js/SmoothScroll.min.js"></script>
</body>
</html>
