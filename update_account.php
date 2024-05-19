<?php
    error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	include 'access.php'; // Include database connection file

    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
    }

    if(isset($_POST['update'])) {
        $email = $_SESSION['email'];
        $full_name = $_POST['full_name'];
        $phone_number = $_POST['phone_number'];
        $address = $_POST['address'];

        // Update customer information in the database
        $query = "UPDATE customer SET full_name='$full_name', phone_number='$phone_number', address='$address' WHERE email='$email'";
        
        if(mysqli_query($connect, $query)) {
            echo "<script>alert('Account information updated successfully.');</script>";
            // Redirect to the account page after successful update
            header("Location: account.php");
            exit();
        } else {
            echo "<script>alert('Failed to update account information. Please try again later.');</script>";
            // Redirect to the account page if update fails
            header("Location: account.php");
            exit();
        }
    } else {
        // Redirect to the account page if update button not clicked
        header("Location: account.php");
        exit();
    }
?>