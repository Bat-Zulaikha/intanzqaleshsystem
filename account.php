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
		<div class="account-container">
			<?php
				error_reporting(E_ALL);
				ini_set('display_errors', 1);
				
				include 'access.php'; // Include database connection file

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
			<form action="update_account.php" method="POST">
				<h2>Account Information</h2>
				<label for="full_name">Full Name:</label>
				<input type="text" id="full_name" name="full_name" value="<?php echo $customer['full_name']; ?>" required><br>

				<label for="email">Email:</label>
				<input type="email" id="email" name="email" value="<?php echo $customer['email']; ?>" readonly><br>

				<label for="phone_number">Phone Number:</label>
				<input type="text" id="phone_number" name="phone_number" value="<?php echo $customer['phone_number']; ?>" required><br>

				<label for="address">Address:</label>
				<textarea id="address" name="address" required><?php echo $customer['address']; ?></textarea><br>

				<button type="submit" name="update">Update</button>
				<div class="logout-button">
					<a href="logout.php"><button type="button">Logout</button></a>
				</div>
			</form>
		</div>
	</section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>