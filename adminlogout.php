<?php
session_start(); // Start the session

// Set logout message for admin
$_SESSION['logout_message'] = "You have been successfully logged out as admin.";

// Destroy the session for admin
unset($_SESSION['admin_email']);

// Redirect to the admin login page after logout
header("Location: adminlogin.php");
exit();
?>