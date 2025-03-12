<?php
session_start();

if (!isset($_SESSION['booking_amount']) || !isset($_SESSION['booking_id'])) {
    echo "<script>alert('No pending booking found!'); window.location.href='book-services.php';</script>";
    exit();
}

$amount = $_SESSION['booking_amount'];
$booking_id = $_SESSION['booking_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Online Banquet Booking | Payment</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
</head>
<body>
    <div class="banner jarallax">
        <div class="agileinfo-dot">
            <?php include_once('includes/header.php'); ?>
            <div class="wthree-heading">
                <h2>Complete Your Payment</h2>
            </div>
        </div>
    </div>

    <div class="contact">
        <div class="container">
            <div class="agile-contact-form">
                <div class="col-md-6 contact-form-right">
                    <div class="contact-form-top">
                        <h3>Payment Details</h3>
                    </div>
                    <div class="agileinfo-contact-form-grid">
                        <form action="fake-process.php" method="POST">
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Booking ID:</label>
                                <div class="col-md-10">
                                    <p style="font-size: 20px"><strong><?php echo $booking_id; ?></strong></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Amount:</label>
                                <div class="col-md-10">
                                    <p style="font-size: 20px; color: green;"><strong>₹<?php echo $amount; ?></strong></p>
                                </div>
                            </div>

                            <!-- Payment Method Selection -->
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Payment Method:</label>
                                <div class="col-md-10">
                                    <select class="form-control" id="paymentMethod" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="upi">UPI</option>
                                        <option value="card">Debit / Credit Card</option>
                                        <option value="netbanking">Net Banking</option>
                                    </select>
                                </div>
                            </div>

                            <!-- UPI Payment -->
                            <div id="upiSection" class="payment-section" style="display: none;">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">UPI ID:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="upi_id" placeholder="example@upi">
                                    </div>
                                </div>
                            </div>

                            <!-- Card Payment -->
                            <div id="cardSection" class="payment-section" style="display: none;">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Card Number:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="card_number" placeholder="**** **** **** ****">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Expiry Date:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="expiry_date" placeholder="MM/YY">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">CVV:</label>
                                    <div class="col-md-10">
                                        <input type="password" class="form-control" name="cvv" placeholder="***">
                                    </div>
                                </div>
                            </div>

                            <!-- Net Banking -->
                            <div id="netbankingSection" class="payment-section" style="display: none;">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Select Bank:</label>
                                    <div class="col-md-10">
                                        <select class="form-control" name="bank">
                                            <option value="">Choose Bank</option>
                                            <option value="sbi">State Bank of India</option>
                                            <option value="hdfc">HDFC Bank</option>
                                            <option value="icici">ICICI Bank</option>
                                            <option value="axis">Axis Bank</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="tp">
                                <button type="submit" class="btn btn-primary">Proceed to Pay</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>

    <script>
        document.getElementById("paymentMethod").addEventListener("change", function() {
            document.querySelectorAll(".payment-section").forEach(section => section.style.display = "none");

            let selectedMethod = this.value;
            if (selectedMethod === "upi") {
                document.getElementById("upiSection").style.display = "block";
            } else if (selectedMethod === "card") {
                document.getElementById("cardSection").style.display = "block";
            } else if (selectedMethod === "netbanking") {
                document.getElementById("netbankingSection").style.display = "block";
            }
        });
    </script>
</body>
</html>
