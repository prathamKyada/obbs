<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['obbsuid']) || strlen($_SESSION['obbsuid']) == 0) {
    header("Location: login.php");
    exit();
}

$servicePrice = 0;
$bid = isset($_GET['bookid']) ? $_GET['bookid'] : null;

// Fetch service price if bookid exists
if ($bid) {
    $sql = "SELECT ServicePrice FROM tblservice WHERE ID = :bid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bid', $bid, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $servicePrice = $result ? $result->ServicePrice : 0;
}

if (isset($_POST['submit'])) {
    $uid = $_SESSION['obbsuid'];
    $bookingfrom = $_POST['bookingfrom'];
    $bookingto = $_POST['bookingto'];
    $eventtype = $_POST['eventtype'];
    $nop = $_POST['nop'];
    $message = $_POST['message'];
    $bookingid = mt_rand(100000000, 999999999);

    // Insert booking WITHOUT price
    $sql = "INSERT INTO tblbooking (BookingID, ServiceID, UserID, BookingFrom, BookingTo, EventType, Numberofguest, Message) 
            VALUES (:bookingid, :bid, :uid, :bookingfrom, :bookingto, :eventtype, :nop, :message)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookingid', $bookingid, PDO::PARAM_STR);
    $query->bindParam(':bid', $bid, PDO::PARAM_STR);
    $query->bindParam(':uid', $uid, PDO::PARAM_STR);
    $query->bindParam(':bookingfrom', $bookingfrom, PDO::PARAM_STR);
    $query->bindParam(':bookingto', $bookingto, PDO::PARAM_STR);
    $query->bindParam(':eventtype', $eventtype, PDO::PARAM_STR);
    $query->bindParam(':nop', $nop, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);

    if ($query->execute()) {
        $_SESSION['booking_amount'] = $servicePrice;
        $_SESSION['booking_id'] = $bookingid;

        // Redirect to FAKE PAYMENT PAGE
        header("Location: fake-payment.php?amount=" . urlencode($servicePrice));
        exit();
    } else {
        echo "Database Error: ";
        print_r($query->errorInfo());
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Online Banquet Booking System | Book Services</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
</head>
<body>
    <div class="banner jarallax">
        <div class="agileinfo-dot">
            <?php include_once('includes/header.php'); ?>
            <div class="wthree-heading">
                <h2>Book Services</h2>
            </div>
        </div>
    </div>

    <div class="contact">
        <div class="container">
            <div class="agile-contact-form">
                <div class="col-md-6 contact-form-right">
                    <div class="contact-form-top">
                        <h3>Book Services</h3>
                    </div>
                    <div class="agileinfo-contact-form-grid">
                        <form method="post">
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Service Price:</label>
                                <div class="col-md-10">
                                    <p style="font-size: 20px; color: green;"><strong>₹<?php echo htmlentities($servicePrice); ?></strong></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Booking From:</label>
                                <div class="col-md-10">
                                    <input type="date" class="form-control" required name="bookingfrom">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Booking To:</label>
                                <div class="col-md-10">
                                    <input type="date" class="form-control" required name="bookingto">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Type of Event:</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="eventtype" required>
                                        <option value="">Choose Event Type</option>
                                        <?php 
                                        $sql2 = "SELECT * FROM tbleventtype";
                                        $query2 = $dbh->prepare($sql2);
                                        $query2->execute();
                                        $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($result2 as $row) { ?>
                                            <option value="<?php echo htmlentities($row->EventType); ?>"><?php echo htmlentities($row->EventType); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Number of Guests:</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" required name="nop">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Message (if any)</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" required name="message"></textarea> 
                                </div>
                            </div>

                            <br>
                            <div class="tp">
                                <button type="submit" class="btn btn-primary" name="submit">Book</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="clearfix"> </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body>    
</html>
