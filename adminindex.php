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

// Array of default notices
$defaultNotices = [
    "Important Notice: Please update product prices before the end of the day.",
    "Reminder: Staff meeting scheduled for tomorrow at 10:00 AM.",
    "Urgent: Server maintenance planned for this weekend. Inform all staff members.",
    "New product arrivals! Check inventory and update stock levels accordingly.",
    "Holiday Notice: The shop will remain closed on the upcoming public holiday.",
    "Feedback needed: Please share your suggestions for improving our online ordering system.",
];

// Initialize the session notices array if not already set
if (!isset($_SESSION['notices'])) {
    $_SESSION['notices'] = [];
}

// Handle new notice submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_notice'])) {
    $newNotice = trim($_POST['new_notice']);
    if (!empty($newNotice)) {
        $_SESSION['notices'][] = $newNotice;
    }
}

// Combine default and session notices
$notices = array_merge($defaultNotices, $_SESSION['notices']);

// Randomly select a notice to display
$randomIndex = array_rand($notices);
$randomNotice = $notices[$randomIndex];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Intanz Qalesh Shop - Admin Homepage</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="style1.css">
    <style>
        div {
            text-align: center;
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 90%;
        }
        
        .container {
          margin: 20px 0 0 115px;
          width: 70%;
          height: 450px;
          display: flex;
          justify-content: center;
          gap: 10px;
        }

        .container img {
          width: 140px;
          height: 400px;
          object-fit:cover;
          border-radius: 10px;
          border: 2px solid rgba(255, 255, 255, 0.684);
          transition: all ease-in-out 0.5s;
        }

        .container img:hover {
          width: 85%;
        }

        .carousel {
            overflow: hidden;
            white-space: nowrap;
            text-align: center;
            position: relative; /* Position relative for absolute positioning */
            height: 80px; /* Height of the carousel */
        }

        .carousel-item {
            display: inline-block;
            padding: 1px;
            position: absolute; /* Position absolute to stack items */
            top: 0;
            left: 0;
            width: 100%; /* Set width to match parent */
            opacity: 0; /* Initially hide items */
            transition: opacity 0.5s ease; /* Apply transition for fading effect */
        }

        /* Styling for admin notices */
        .notice {
            background-color: #f9ca24;
            font-size: 16px;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: inline-block;
            width: auto; /* Set width to auto to fit content */
        }

        .notice p {
            margin: 0;
        }

        .carousel-item.active {
            opacity: 1;
        }
        
        .map-container {
            width: 100%;
            text-align: center; /* Center the iframe horizontally */
            margin-bottom: 20px;
        }

        .map-container iframe {
            border-radius: 5px;
            border: 1px solid #ccc;
            max-width: 100%;
            height: 200px;
            width: 100%;
        }
        
        .social-icons {
            width: 15%;
        }
    </style>
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <nav>
        <?php include 'adminNav.php'; ?>
    </nav>
    <section>
        <aside>
            <h2>Welcome, Admin!</h2>
            <p>Logged in as: <?php echo $email; ?></p>
            <p><a href="logout.php">Logout</a></p>
        </aside>
        <article>
            <h2>Admin Notices</h2>
            <div class="carousel">
                <?php foreach ($notices as $key => $notice) : ?>
                    <div class="carousel-item <?php echo $key === 0 ? 'active' : ''; ?>">
                        <div class="notice">
                            <p><?php echo $notice; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <form method="post" action="">
                <label for="new_notice">Add New Notice:</label><br>
                <textarea id="new_notice" name="new_notice" rows="4" cols="50"></textarea><br>
                <button type="submit">Submit</button>
            </form>
        </article>
        <article>
            <div class="container">
            <br>
                <img src="/intanzqaleshsystem/intanzPics/shopPic.jpg" width="100" height="470">
                <img src="/intanzqaleshsystem/intanzPics/shopPic2.jpg" width="100" height="470">
                <img src="/intanzqaleshsystem/intanzPics/shopPic3.jpg" width="100" height="470">
            </div>
        </article>
        <aside>
            <h2>Contact Information</h2>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.0751759175167!2d102.25852421475742!3d2.1869335543873754!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d1fb2a3e4c7f4d%3A0xdcd56ad0e08ed531!2sUptown%20Banda%20Hilir%2C%20Jalan%20PM%202%2C%20Plaza%20Mahkota%2C%2075000%20Melaka!5e0!3m2!1sen!2smy!4v1621534024694!5m2!1sen!2smy" allowfullscreen="" loading="lazy"></iframe>
            </div>
            <p>Shop Address: <a href="https://maps.app.goo.gl/VjD95pVsgh5a5jtw8">Uptown Banda Hilir, <br>Jalan PM 2, Plaza Mahkota, 75000 Melaka</a></p>
            <p>Email: <a href="https://mail.google.com">intanzqalesh@gmail.com</a></p>
            <p>Phone: <a href="https://contacts.google.com">+1234567890</a></p>
            <p>Follow us on social media:</p>
            <div class="social-icons">
                <p>Facebook<a href="https://www.facebook.com/"><img src="facebook.ico" alt="Facebook"></a></p>
                <p>Instagram<a href="https://www.instagram.com/"><img src="instagram.ico" alt="Instagram"></a></p>
                <p>Twitter/X<a href="https://twitter.com/"><img src="twitter.ico" alt="Twitter"></a></p>
            </div>
        </aside>
    </section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Set the speed for carousel animation
            var speed = 5000; // Change this to adjust sliding speed

            // Function to move the carousel
            function moveCarousel() {
                var $carouselItems = $(".carousel-item");
                var $activeItem = $(".carousel-item.active");
                var nextIndex = ($activeItem.index() + 1) % $carouselItems.length;
                $activeItem.removeClass("active");
                $carouselItems.eq(nextIndex).addClass("active");
            }

            // Interval to move the carousel automatically
            setInterval(moveCarousel, speed);
        });
    </script>
</body>
</html>