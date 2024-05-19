<?php
    error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	include 'access.php'; // Include database connection file

    // Check if the user is logged in
    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
    }

    $email = $_SESSION['email'];

    // Fetch customer details from the database
    $query = "SELECT * FROM customer WHERE email='$email'";
    $result = mysqli_query($connect, $query);
    $customer = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Intanz Qalesh: Account Dashboard</title>
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
		<?php include 'nav.php'; ?>
	</nav>
	<section>
	<article>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo $customer['full_name']; ?></h2>
        <p><strong>Email:</strong> <?php echo $customer['email']; ?></p>
        <p><strong>Phone Number:</strong> <?php echo $customer['phone_number']; ?></p>
        <p><strong>Address:</strong> <?php echo $customer['address']; ?></p><br>
		<a href="account.php"><button>Update Information</button></a>
        <a href="logout.php"><button>Logout</button></a>
    </div>
	</article>
	</section>
	<footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>