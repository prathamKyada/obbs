<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Twilio\Rest\Client;

session_start();
include('includes/dbconnection.php');

if (!isset($_POST['booking_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='services.php';</script>";
    exit();
}

$bookingId = $_POST['booking_id'];

// Fetch booking and user info including mobile number
$sql = "SELECT u.FullName, u.Email, u.MobileNumber, b.BookingID, s.ServiceName, s.ServicePrice, b.BookingFrom, b.BookingTo 
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
    $phone = ltrim($result['MobileNumber'], '0'); // Clean phone number (remove leading zero)
    $service = $result['ServiceName'];
    $price = $result['ServicePrice'];
    $from = $result['BookingFrom'];
    $to = $result['BookingTo'];

    // Email subject and HTML body
    $subject = "Your Banquet Booking Receipt";
    $message = '
    <!DOCTYPE html>
    <html>
    <head><style>
        body { font-family: "Helvetica Neue", Helvetica, Arial; background: #f8f9fa; color: #333; padding: 20px; }
        .receipt-container { background: #fff; border-radius: 10px; max-width: 600px; margin: auto; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .receipt-header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        .receipt-header h2 { margin: 0; color: #007bff; }
        .receipt-details p { font-size: 16px; margin: 8px 0; }
        .price { font-size: 18px; color: green; font-weight: bold; }
        .footer { margin-top: 30px; font-style: italic; text-align: center; color: #6c757d; }
    </style></head>
    <body>
    <div class="receipt-container">
        <div class="receipt-header"><h2>OBBS | Banquet Booking Receipt</h2></div>
        <div class="receipt-details">
            <p><strong>Hi ' . htmlentities($name) . ',</strong></p>
            <p>Thank you for your booking!</p>
            <p><strong>Booking ID:</strong> ' . htmlentities($bookingId) . '</p>
            <p><strong>Service:</strong> ' . htmlentities($service) . '</p>
            <p class="price"><strong>Price:</strong> ₹' . htmlentities($price) . '</p>
            <p><strong>Booking From:</strong> ' . htmlentities($from) . '</p>
            <p><strong>Booking To:</strong> ' . htmlentities($to) . '</p>
        </div>
        <div class="footer">We look forward to hosting your event!<br>— Online Banquet Booking System</div>
    </div>
    </body></html>';

    // WhatsApp text version
    $receiptText = "🧾 OBBS Booking Receipt\n----------------------\n";
    $receiptText .= "Hi $name,\nBooking ID: $bookingId\nService: $service\nPrice: ₹$price\nFrom: $from\nTo: $to\n\nThanks for booking with OBBS!";

    // PHPMailer setup
    require 'includes/phpmailer/PHPMailer.php';
    require 'includes/phpmailer/SMTP.php';
    require 'includes/phpmailer/Exception.php';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gopalpatel8094@gmail.com';         // your Gmail
        $mail->Password = 'xcvv tmrp rfch yzvj';              // your App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('gopalpatel8094@gmail.com', 'OBBS System');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        $success = true;
        $receiptText = "🧾 OBBS Booking Receipt\n----------------------\n";
        $receiptText .= "Hi $name,\n";
        $receiptText .= "Booking ID: $bookingId\n";
        $receiptText .= "Service: $service\n";
        $receiptText .= "Price: ₹$price\n";
        $receiptText .= "From: $from\nTo: $to\n\n";
        $receiptText .= "Thanks for booking with OBBS!";

        // Twilio
        require_once 'includes/twilio/autoload.php';
        $twilioSID = 'AC9809d3fa72d679204b3528171827ca43';
        $twilioToken = 'a12f2081a05d6f16b713c7ab7b80ef2f';
        $twilio = new Client($twilioSID, $twilioToken);

        $twilio->messages->create(
            "whatsapp:+91$phone", // ✅ user's mobile with Indian country code
            [
                "from" => "whatsapp:+14155238886",
                "body" => $receiptText
            ]
        );

    } catch (Exception $e) {
        $success = false;
        error_log("Email/WhatsApp Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Online Banquet Booking | Receipt Status</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
    <div class="banner jarallax">
        <div class="agileinfo-dot">
            <?php include_once('includes/header.php'); ?>
            <div class="wthree-heading"><h2>Receipt Status</h2></div>
        </div>
    </div>
    <div class="contact">
        <div class="container text-center">
            <?php if ($success): ?>
                <h2 style="color: green;">📩 Receipt sent to <strong><?php echo htmlentities($email); ?></strong> and on WhatsApp 📲</h2>
            <?php else: ?>
                <h2 style="color: red;">❌ Failed to send receipt. Please try again.</h2>
            <?php endif; ?>
            <br>
            <a href="services.php" class="btn btn-primary">Back to Services</a>
        </div>
    </div>
    <?php include_once('includes/footer.php'); ?>
</body>
</html>
