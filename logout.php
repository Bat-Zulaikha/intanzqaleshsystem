<?php
session_start(); // Start the session

// Set logout message
$_SESSION['logout_message'] = "You have been successfully logged out.";

// Destroy the session
session_destroy();

// Redirect to the login page after logout
header("Location: login.php");
exit();
?>