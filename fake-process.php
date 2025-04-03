<?php
session_start();
sleep(1); // Fake delay for payment processing

// Randomly decide success or failure
$payment_status = (rand(0, 1) == 1) ? "success" : "failure";

header("Location: fake-status.php?status=$payment_status");
exit();
?>
