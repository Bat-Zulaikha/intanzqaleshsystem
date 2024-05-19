<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    session_start(); // Start session to manage user login status

    // Check for logout message and display it if exists
    $logout_message = isset($_SESSION['logout_message']) ? $_SESSION['logout_message'] : "";
    unset($_SESSION['logout_message']); // Clear the logout message after displaying

    if(isset($_SESSION['admin_email'])) {
        // If admin is already logged in, redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    }

    include 'access.php'; // Include database connection file

    if(isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Fetch admin details from the database
        $query = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
        $result = mysqli_query($connect, $query);

        if(mysqli_num_rows($result) == 1) {
            // If admin exists, set session variables and redirect to admin dashboard
            $_SESSION['admin_email'] = $email;
            header("Location: admin_dashboard.php");
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
        <?php include 'adminNav.php'; ?>
    </nav>
	<section>
    <div class="login-container">
        <?php if (!empty($logout_message)) : ?>
            <div class="logout-message"><?php echo $logout_message; ?></div>
        <?php endif; ?>
        <form action="adminlogin.php" method="POST">
			<h2>Admin Login</h2>
			<label>Enter Admin Email:</label>
            <input type="text" name="email" placeholder="Email" required>
            <label>Enter Admin Password:</label>
			<input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit" name="login">Login</button><br>
        </form>
    </div>
	</section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>