<?php
session_start();

include 'access.php';

// Redirect to login if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php?redirect=cart.php");
    exit();
}

// Fetch cart items for the logged-in user
$email = $_SESSION['email'];
$query = "SELECT cart.cart_id, cart.quantity, product.*, cart.size FROM cart INNER JOIN product ON cart.product_id = product.product_id WHERE cart.email = '$email'";
$result = mysqli_query($connect, $query);

// Handle item removal from cart
if (isset($_POST['remove_item'])) {
    $cartIdToRemove = $_POST['remove_item'];
    $removeQuery = "DELETE FROM cart WHERE cart_id = '$cartIdToRemove'";
    mysqli_query($connect, $removeQuery);
    // Redirect back to cart page after removing the item
    header("Location: cart.php?item_removed=true");
    exit();
}

// Handle cart item quantity update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array to store selected product quantities
    $selectedProducts = [];
    foreach ($_POST['cart_id'] as $cartId) {
        // Check if the checkbox for this product is checked
        if (isset($_POST["quantity_$cartId"])) {
            $selectedProducts[$cartId] = $_POST["quantity_$cartId"];
        }
    }
    // Store selected products in session for use in payment.php
    $_SESSION['selectedProducts'] = $selectedProducts;
    // Redirect to payment page
    header("Location: payment.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Intanz Qalesh Shop - Cart</title>
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
        <h2>Shopping Cart</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Size</th> <!-- Added Size column -->
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Selected</th>
                        <th>Remove from cart</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="width: 100px;"></td>
                            <td><?php echo $row['name']; ?></td>
                            <td>$<?php echo $row['price']; ?></td>
                            <td><?php echo isset($row['size']) ? $row['size'] : 'N/A'; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>$<?php echo number_format($row['quantity'] * $row['price'], 2); ?></td>
                            <td>
                                <input type="hidden" name="cart_id[]" value="<?php echo $row['cart_id']; ?>">
                                <input type="checkbox" name="quantity_<?php echo $row['cart_id']; ?>" value="<?php echo $row['quantity']; ?>" checked>
                            </td>
                            <td>
                                <button type="submit" name="remove_item" value="<?php echo $row['cart_id']; ?>" onclick="return confirm('Are you sure you want to remove this item?')">REMOVE</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table><br>
            <div class="button-container">
                <button type="submit">Proceed to CHECKOUT</button>
            </div>
        </form>
    </section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>