<?php
session_start();
include('includes/dbconnection.php');

if (!isset($_POST['booking_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='services.php';</script>";
    exit();
}

$bookingId = $_POST['booking_id'];

// Fetch booking and user info
$sql = "SELECT u.FullName, u.Email, b.BookingID, s.ServiceName, s.ServicePrice, b.BookingFrom, b.BookingTo 
        FROM tblbooking b 
        JOIN tbluser u ON b.UserID = u.ID 
        JOIN tblservice s ON b.ServiceID = s.ID 
        WHERE b.BookingID = :bookingId";
$query = $dbh->prepare($sql);
$query->bindParam(':bookingId', $bookingId, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

$success = false;
$email = '';
if ($result) {
    $name = $result['FullName'];
    $email = $result['Email'];
    $service = $result['ServiceName'];
    $price = $result['ServicePrice'];
    $from = $result['BookingFrom'];
    $to = $result['BookingTo'];

    $subject = "Your Banquet Booking Receipt";
    $headers = "From: no-reply@banquet.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $message = "
    <html>
    <head><title>Booking Receipt</title></head>
    <body>
        <h2>Thank you for your booking, $name!</h2>
        <p><strong>Booking ID:</strong> $bookingId</p>
        <p><strong>Service:</strong> $service</p>
        <p><strong>Price:</strong> ₹$price</p>
        <p><strong>Booking From:</strong> $from</p>
        <p><strong>Booking To:</strong> $to</p>
        <br>
        <p>We look forward to serving you!</p>
        <p><em>- Online Banquet Booking Team</em></p>
    </body>
    </html>
    ";

    if (mail($email, $subject, $message, $headers)) {
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Online Banquet Booking | Receipt Status</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
</head>
<body>
    <div class="banner jarallax">
        <div class="agileinfo-dot">
            <?php include_once('includes/header.php'); ?>
            <div class="wthree-heading">
                <h2>Receipt Status</h2>
            </div>
        </div>
    </div>

    <div class="contact">
        <div class="container text-center">
            <?php if ($success): ?>
                <h2 style="color: green;">📩 Receipt has been sent to your email: <strong><?php echo htmlentities($email); ?></strong></h2>
            <?php else: ?>
                <h2 style="color: red;">❌ Failed to send receipt. Please try again later.</h2>
            <?php endif; ?>

            <br>
            <a href="services.php" class="btn btn-primary">Back to Services</a>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body>
</html>
