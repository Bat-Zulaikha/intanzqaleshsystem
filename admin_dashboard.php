<?php
session_start(); // Start the session

// Check if admin is not logged in, redirect to admin login page
if (!isset($_SESSION['admin_email'])) {
    header("Location: adminlogin.php");
    exit();
}

// Include access.php for database connection
include 'access.php';

// Retrieve admin details from the session
$email = $_SESSION['admin_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin Login</title>
	<meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <nav>
        <?php include 'adminNav.php'; ?>
    </nav>
	<section>
    <div class="admin-dashboard-container">
        <h2>Welcome, Admin!</h2>
        <p>You are logged in as <?php echo $email; ?></p>
    </div>
    </section>
	<footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>