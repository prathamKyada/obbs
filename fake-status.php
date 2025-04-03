<?php
session_start();

$status = isset($_GET['status']) ? $_GET['status'] : 'failure';

if ($status == "success") {
    $message = "Payment Successful! ✅";
    $color = "green";
    $buttonText = "Make a New Booking.....";
    $buttonLink = "services.php";
} else {
    $message = "Payment Failed! ❌";
    $color = "red";
    $buttonText = "Try Again";
    $buttonLink = "fake-payment.php";
}
?>

<!-- HTML starts here -->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment Status</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
</head>
<body>
    <div class="banner jarallax">
        <div class="agileinfo-dot">
            <?php include_once('includes/header.php'); ?>
            <div class="wthree-heading">
                <h2>Payment Status</h2>
            </div>
        </div>
    </div>

    <div class="contact">
        <div class="container text-center">
        <pre>
<?php
echo "DEBUG:\n";
echo "Status: $status\n";
echo "Session booking_id: " . ($_SESSION['booking_id'] ?? 'NOT SET');
?>
</pre>

            <h2 style="color: <?php echo $color; ?>"><?php echo $message; ?></h2>

            <?php if ($status == "success"): ?>
                <p>Would you like to receive your receipt on Gmail?</p>
                <form action="send-receipt.php" method="post">
                    <input type="hidden" name="booking_id" value="<?php echo $_SESSION['booking_id']; ?>">
                    <button type="submit" class="btn btn-success">Send via Gmail</button>
                </form>
            <?php endif; ?>

            <br>
            <a href="<?php echo $buttonLink; ?>" class="btn btn-primary mt-3"><?php echo $buttonText; ?></a>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body>
</html>
   
