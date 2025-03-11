<?php
session_start();
require('vendor/autoload.php');
include 'includes/db_connection.php';

if (!isset($_GET['payment_id'])) {
    echo "<script>alert('Invalid payment attempt!'); window.location.href='index.php';</script>";
    exit();
}

$payment_id = $_GET['payment_id'];
$user_id = $_SESSION['user_id'];

// Update the booking status
$update_query = "UPDATE bookings SET status = 'Paid', payment_id = ? WHERE user_id = ? AND status = 'Pending' LIMIT 1";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("si", $payment_id, $user_id);
$stmt->execute();

echo "<script>alert('Payment successful! Your booking is confirmed.'); window.location.href='booking-history.php';</script>";
exit();
?>
