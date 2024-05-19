<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    session_start(); // Start session to manage user login status

    $logout_message = isset($_SESSION['logout_message']) ? $_SESSION['logout_message'] : "";
    unset($_SESSION['logout_message']); // Clear the logout message after displaying

    if(isset($_SESSION['email'])) {
        // If user is already logged in, redirect to dashboard
        header("Location: customer_dashboard.php");
        exit();
    }

    include 'access.php'; // Include database connection file

    if(isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Fetch user details from the database
        $query = "SELECT * FROM customer WHERE email='$email' AND password='$password'";
        $result = mysqli_query($connect, $query);

        if(mysqli_num_rows($result) == 1) {
            // If user exists, set session variables and redirect to dashboard
            $_SESSION['email'] = $email;

            // Insert login date into customer_log table
            $login_date = date("Y-m-d H:i:s");
            $insert_query = "INSERT INTO customer_log (email, login_date) VALUES ('$email', '$login_date')";
            mysqli_query($connect, $insert_query);

            header("Location: customer_dashboard.php");
            exit();
        } else {
            // If login fails, show error message
            echo "<script>alert('Invalid email or password. Please try again.');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Intanz Qalesh: Login</title>
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
		<div class="login-container">
			<?php if (!empty($logout_message)) : ?>
				<div class="logout-message"><?php echo $logout_message; ?></div>
			<?php endif; ?>
			<form action="login.php" method="POST">
				<h2>Login</h2>
				<label>Enter email address:</label>
				<input type="text" name="email" placeholder="email@mail.com" required><br><br>
				<label>Enter password:</label>
				<input type="password" name="password" placeholder="Password" required><br><br>
				<button type="submit" name="login">Login</button><br>
			</form>
			<p>Haven't registered yet? <a href="register.php">Register here</a>.</p>
		</div>
	</section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>