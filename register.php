<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $host = 'localhost';
    $user = 'root';
    $pswd = ''; 
    $dbase = 'intanzqaleshsystem';
    $connect = mysqli_connect($host, $user, $pswd, $dbase);
    
    if (!$connect) {
        echo "Sorry, page not found.";
    } else {
        echo "";
    }

    if(isset($_POST['register'])) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone_number = $_POST['phone_number'];
        $address = $_POST['address'];

        // Check if email already exists
        $query = "SELECT * FROM customer WHERE email='$email'";
        $result = mysqli_query($connect, $query);

        if(mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email already exists. Please use a different email.');";
            echo "window.location.href = 'register.php';</script>";
        } else {
            // Insert new user data into database
            $insert_query = "INSERT INTO customer (full_name, email, password, phone_number, address) 
                             VALUES ('$full_name', '$email', '$password', '$phone_number', '$address')";
            
            if(mysqli_query($connect, $insert_query)) {
                echo "<script>alert('Registration successful. Please login to continue.');";
                echo "window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('Registration failed. Please try again later.');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Intanz Qalesh Shop</title>
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
		<div class="register-container">
			<h2>Register</h2>
			<form action="register.php" method="POST">
				<label>Enter full name:</label>
				<input type="text" name="full_name" placeholder="Full Name" required><br><br>
				<label>‎ Enter email:</label>
				<input type="text" name="email" placeholder="email@mail.com" required><br><br>
				<label>Enter password:</label>
				<input type="password" name="password" placeholder="Password" required><br><br>
				<label>‎ Enter phone number:</label>
				<input type="text" name="phone_number" placeholder="012-3456789" required><br><br>
				<label>Enter address:</label>
				<textarea name="address" placeholder="Address" required></textarea><br><br>
				<button type="submit" name="register">Register</button>
			</form>
			<p>Already have an account? <a href="login.php">Login here</a>.</p>
		</div>
	</section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>