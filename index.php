<!DOCTYPE html>
<html lang="en">
<head>
	<title>Intanz Qalesh Shop</title>
	<meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" type="text/css" href="style1.css">
	<style>
		.container {
		  margin: 20px 0 0 115px;
		  width: 70%;
		  max-width: 1200px;
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

        .promotion {
            padding: 40px;
            background-color: #f9ca24;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: center;
        }
		
		div {
			text-align: center;
			display: block;
			margin-left: auto;
			margin-right: auto;
			width: 40%;
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
		<?php include 'nav.php'; ?>
	</nav>
	<section>
		<article>
			<h2 align="center">Welcome to Intanz Qalesh Shop</h2>
            <div class="container">
			<br>
				<img src="/intanzqaleshsystem/intanzPics/shopPic.jpg" width="100" height="470">
				<img src="/intanzqaleshsystem/intanzPics/shopPic2.jpg" width="100" height="470">
				<img src="/intanzqaleshsystem/uploads/Round neck laki.jpg" width="100" height="470">
				<img src="/intanzqaleshsystem/intanzPics/shopPic3.jpg" width="100" height="470">
			</div>
            <div class="promotion">
                <h3>Special Discount!</h3>
                <p>Get 20% off on all men's shirts this weekend! Hurry, limited stock available.</p>
            </div><br>
            <div class="container">
				<img src="/intanzqaleshsystem/intanzPics/Vehicle toy.jpg" width="100" height="470">
				<img src="/intanzqaleshsystem/intanzPics/toysPic.jpg" width="100" height="470">
				<img src="/intanzqaleshsystem/intanzPics/Medical toy.jpg" width="100" height="470">
			</div>
            <div class="promotion">
                <h3>Exclusive Offer!</h3>
                <p>Buy any two items and get the third one for free. Offer valid only on kids' toys.</p>
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
</body>
</html>