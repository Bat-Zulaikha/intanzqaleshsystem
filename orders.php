<?php
session_start(); // Start session to manage user login status

// Check if customer is not logged in, redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: login.php?redirect=orders.php");
    exit();
}

// Include database connection
include 'access.php';

// Define default sorting order
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'payment_date DESC';

// Fetch orders for the logged-in customer
$email = $_SESSION['email'];
$query = "SELECT p.product_id, p.name, p.image, py.payment_id, py.amount, py.payment_date, py.payment_status, py.quantity, (py.quantity * p.price) AS total
          FROM payment py
          INNER JOIN product p ON py.product_id = p.product_id
          WHERE py.email='$email'
          ORDER BY $order_by";
$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Intanz Qalesh Shop - My Orders</title>
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
        <h2>My Orders</h2>
        <div>
            <form method="GET">
                <label>Sort By:</label>
                <select name="order_by">
                    <option value="payment_date DESC" <?php echo ($order_by == 'payment_date DESC') ? 'selected' : ''; ?>>Latest to Older</option>
                    <option value="payment_date ASC" <?php echo ($order_by == 'payment_date ASC') ? 'selected' : ''; ?>>Older to Latest</option>
                    <option value="payment_status ASC" <?php echo ($order_by == 'payment_status ASC') ? 'selected' : ''; ?>>Status (A-Z)</option>
                    <option value="payment_status DESC" <?php echo ($order_by == 'payment_status DESC') ? 'selected' : ''; ?>>Status (Z-A)</option>
                </select>
                <button type="submit">Sort</button>
            </form>
        </div><br>
        <table>
            <tr>
                <th>Payment ID</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['payment_id']; ?></td>
                    <td><img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="width: 100px;"></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>$<?php echo $row['total']; ?></td>
                    <td><?php echo $row['payment_date']; ?></td>
                    <td><?php echo $row['payment_status']; ?></td>
                    <td><button onclick="cancelOrder(<?php echo $row['payment_id']; ?>)">Cancel Order</button></td>
                </tr>
            <?php endwhile; ?>
        </table><br>
        <button onclick="window.print()">Print Orders</button>
    </section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
    
    <script>
        function cancelOrder(paymentId) {
            var confirmCancel = confirm("Are you sure you want to cancel this order?");
            if (confirmCancel) {
                // AJAX request to update payment status
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Reload the page to reflect the updated status
                        window.location.reload();
                    }
                };
                xhttp.open("POST", "cancel_order.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("payment_id=" + paymentId);
            }
        }
    </script>
</body>
</html>