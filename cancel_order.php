<?php
session_start(); // Start session to manage user login status

// Check if customer is not logged in, redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: login.php?redirect=orders.php");
    exit();
}

// Include database connection
include 'access.php';

// Check if payment_id is provided in the POST request
if (isset($_POST['payment_id'])) {
    $paymentId = $_POST['payment_id'];

    // Update payment status to "failed" for the specified payment ID
    $updateQuery = "UPDATE payment SET payment_status = 'failed' WHERE payment_id = $paymentId";
    mysqli_query($connect, $updateQuery);

    // Redirect back to the orders page
    header("Location: orders.php");
    exit();
} else {
    // If payment_id is not provided, redirect back to the orders page
    header("Location: orders.php");
    exit();
}
?>