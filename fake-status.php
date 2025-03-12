<?php
session_start();

$status = isset($_GET['status']) ? $_GET['status'] : 'failure';

if ($status == "success") {
    $message = "Payment Successful! ✅";
    $color = "green";
    $buttonText = "Make a New Booking";
    $buttonLink = "services.php";  // Redirects to services page
} else {
    $message = "Payment Failed! ❌";
    $color = "red";
    $buttonText = "Try Again";
    $buttonLink = "fake-process.php";  // Redirects back to payment page
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center mt-5">
        <h2 style="color: <?php echo $color; ?>"><?php echo $message; ?></h2>
        <a href="<?php echo $buttonLink; ?>" class="btn btn-primary mt-3"><?php echo $buttonText; ?></a>
    </div>
</body>
</html>
