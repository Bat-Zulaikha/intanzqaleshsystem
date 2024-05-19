<?php
session_start();

include 'access.php';

// Check if admin is not logged in, redirect to admin login page
if (!isset($_SESSION['admin_email'])) {
    header("Location: adminlogin.php");
    exit();
}

$query = "SELECT * FROM payment";
$result = mysqli_query($connect, $query);

// Calculate sales statistics per month for paid payments
if (isset($_GET['month'])) {
    $selectedMonth = $_GET['month'];
    $queryPaid = "SELECT MONTH(payment_date) AS month, SUM(amount) AS total_sales FROM payment WHERE payment_status = 'completed' AND MONTH(payment_date) = ? GROUP BY MONTH(payment_date)";
    $stmtPaid = mysqli_prepare($connect, $queryPaid);
    mysqli_stmt_bind_param($stmtPaid, 'i', $selectedMonth);
} else {
    $queryPaid = "SELECT MONTH(payment_date) AS month, SUM(amount) AS total_sales FROM payment WHERE payment_status = 'completed' GROUP BY MONTH(payment_date)";
    $stmtPaid = mysqli_prepare($connect, $queryPaid);
}
mysqli_stmt_execute($stmtPaid);
$resultPaid = mysqli_stmt_get_result($stmtPaid);
$salesStatistics = array();
while ($rowPaid = mysqli_fetch_assoc($resultPaid)) {
    $month = $rowPaid['month'];
    $totalSales = $rowPaid['total_sales'];
    $salesStatistics[$month] = $totalSales;
}

// Fetch all orders with product details
$query = "SELECT p.name AS product_name, py.amount, py.payment_date, py.payment_status, py.payment_method, py.payment_id, py.email, py.quantity AS product_quantity
          FROM payment py 
          JOIN product p ON py.product_id = p.product_id 
          LEFT JOIN cart c ON py.product_id = c.product_id AND py.email = c.email";
$result = mysqli_query($connect, $query);

// Update payment status if form is submitted
if (isset($_POST['update_status'])) {
    $paymentId = $_POST['payment_id'];
    $newStatus = $_POST['payment_status'];
    $queryUpdate = "UPDATE payment SET payment_status = ? WHERE payment_id = ?";
    $stmtUpdate = mysqli_prepare($connect, $queryUpdate);
    mysqli_stmt_bind_param($stmtUpdate, 'si', $newStatus, $paymentId);
    mysqli_stmt_execute($stmtUpdate);
    // Redirect to refresh the page after update
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Intanz Qalesh Shop - Customer Orders</title>
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
		<h1>Sales Statistics</h1>
        <form action="" method="GET">
            <label for="month">Select Month:</label>
            <select name="month" id="month">
                <option value="">All</option>
                <?php for ($i = 1; $i <= 12; $i++) : ?>
                    <option value="<?php echo $i; ?>" <?php if (isset($_GET['month']) && $_GET['month'] == $i) echo 'selected'; ?>><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit">Show Sales</button>
        </form><br>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Sales</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salesStatistics as $month => $totalSales) : ?>
                    <tr>
                        <td><?php echo date('F', mktime(0, 0, 0, $month, 1)); ?></td>
                        <td>$<?php echo $totalSales; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table><br>
        <h1>Customer Orders</h1>
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Email</th>
                    <th>Product</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['payment_id']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['product_quantity'] . ' (' . $row['product_name'] . ', ' . $row['payment_method'] . ')'; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['payment_date']; ?></td>
                        <td><?php echo $row['payment_status']; ?></td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="payment_id" value="<?php echo $row['payment_id']; ?>">
                                <select name="payment_status">
                                    <option value="pending" <?php if($row['payment_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="completed" <?php if($row['payment_status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                    <option value="failed" <?php if($row['payment_status'] == 'failed') echo 'selected'; ?>>Failed</option>
                                </select>
                                <button type="submit" name="update_status">Update Status</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>