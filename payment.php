<?php
session_start();

include 'access.php';

$email = $_SESSION['email'];

// Retrieve selected products from the session
if (isset($_SESSION['selectedProducts'])) {
    $selectedProducts = $_SESSION['selectedProducts'];
}

// Process payment
if (isset($_POST['process_payment'])) {
    // Retrieve payment method
    $paymentMethod = $_POST['payment_method'];

    // Set payment status based on payment method
    $paymentStatus = ($paymentMethod == 'walk_in') ? 'pending' : 'completed';

    // Insert payment details into the payment table
    foreach ($selectedProducts as $cartId => $quantity) {
        $cartId = mysqli_real_escape_string($connect, $cartId);
        $quantity = mysqli_real_escape_string($connect, $quantity);

        $query = "SELECT product.product_id, product.price FROM product INNER JOIN cart ON product.product_id = cart.product_id WHERE cart.cart_id = '$cartId'";
        $result = mysqli_query($connect, $query);
        $row = mysqli_fetch_assoc($result);
        $productId = $row['product_id'];
        $price = $row['price'];
        $amount = $price * $quantity;

        // Insert payment details into the payment table, including quantity
        $insertQuery = "INSERT INTO payment (email, product_id, quantity, amount, payment_date, payment_status, payment_method) VALUES ('$email', '$productId', '$quantity', '$amount', NOW(), '$paymentStatus', '$paymentMethod')";
        mysqli_query($connect, $insertQuery);
        
        $updateStockQuery = "UPDATE product SET stock = stock - $quantity WHERE product_id = '$productId'";
        mysqli_query($connect, $updateStockQuery);

        // Remove the paid products from the cart
        $removeFromCartQuery = "DELETE FROM cart WHERE cart_id = '$cartId'";
        mysqli_query($connect, $removeFromCartQuery);
    }

    // Clear selected products from the session
    unset($_SESSION['selectedProducts']);

    // Redirect to orders.php
    header("Location: orders.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Intanz Qalesh Shop - Payment</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="style1.css">
    <style>
        .item-container {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
            display: flex;
            align-items: center;
        }

        .item-image {
            width: 100px;
            height: 100px;
            margin-right: 20px;
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
        <br><br><h2>Selected Products</h2>
        <?php
        if (isset($selectedProducts)) { // Check if $selectedProducts is set
            $totalPayment = 0;
            foreach ($selectedProducts as $cartId => $quantity) {
                $cartId = mysqli_real_escape_string($connect, $cartId);
                $quantity = mysqli_real_escape_string($connect, $quantity);

                $query = "SELECT product.product_id, product.name, product.price, product.image, cart.size FROM product INNER JOIN cart ON product.product_id = cart.product_id WHERE cart.cart_id = '$cartId'";
                $result = mysqli_query($connect, $query);
                $row = mysqli_fetch_assoc($result);

                $productName = $row['name'];
                $price = $row['price'];
                $selectedSize = $row['size']; // Retrieve selected size from the cart
                $subtotal = $price * $quantity;
                $totalPayment += $subtotal;
                ?>
                <div class="item-container">
                    <img src="<?php echo $row['image']; ?>" class="item-image" alt="<?php echo $productName; ?>">
                    <div>
                        <small><p><strong>Product Name:</strong> <?php echo $productName; ?></p>
                        <p><strong>Price:</strong> $<?php echo $price; ?></p>
                        <p><strong>Size:</strong> <?php echo $selectedSize; ?></p> <!-- Display selected size -->
                        <p><strong>Quantity:</strong> <?php echo $quantity; ?></p>
                        <p><strong>Subtotal:</strong> $<?php echo $subtotal; ?></p></small>
                    </div>
                </div>
                <?php
            }
            // Display total payment amount
            echo "<p>Total Payment: RM$totalPayment</p>";
        }
        ?>
        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to proceed with the payment?')">
            <h2>Checkout</h2>
            <p>Select Payment Method:</p>
            <input type="radio" id="walk_in" name="payment_method" value="walk_in" required>
            <label for="walk_in">Walk In and Pay</label><br>
            <input type="radio" id="online_banking" name="payment_method" value="online_banking" required>
            <label for="online_banking">Online Banking</label><br>
            <input type="radio" id="card" name="payment_method" value="card" required>
            <label for="card">Card / Debit</label><br>
            <button type="submit" name="process_payment">Process Payment</button>
        </form><br>
    </section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>