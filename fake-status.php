<?php
session_start();

$status = isset($_GET['status']) ? $_GET['status'] : 'failure';

if ($status == "success") {
    $message = "Payment Successful! ✅";
    $color = "green";
    $buttonText = "Make a New Booking";
    $buttonLink = "services.php";
} else {
    $message = "Payment Failed! ❌";
    $color = "red";
    $buttonText = "Try Again";
    $buttonLink = "fake-payment.php";
}
?>

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
            <h2 style="color: <?php echo $color; ?>"><?php echo $message; ?></h2>
            <a href="<?php echo $buttonLink; ?>" class="btn btn-primary mt-3"><?php echo $buttonText; ?></a>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body>
</html>
