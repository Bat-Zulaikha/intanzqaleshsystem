<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: adminlogin.php");
    exit();
}

include 'access.php';

// Retrieve admin details from the session
$email = $_SESSION['admin_email'];

// Delete customer and related data if the delete request is received
if (isset($_POST['delete_customer'])) {
    $emailToDelete = $_POST['email'];
    
    // Start a transaction to ensure data consistency across multiple tables
    mysqli_begin_transaction($connect);
    
    try {
        // Delete related data from the customer_log table
        $deleteLogQuery = "DELETE FROM customer_log WHERE email='$emailToDelete'";
        if (!mysqli_query($connect, $deleteLogQuery)) {
            throw new Exception(mysqli_error($connect));
        }

        // Delete related data from the payment table
        $deletePaymentQuery = "DELETE FROM payment WHERE email='$emailToDelete'";
        if (!mysqli_query($connect, $deletePaymentQuery)) {
            throw new Exception(mysqli_error($connect));
        }
        
        // Delete related data from the cart table
        $deleteCartQuery = "DELETE FROM cart WHERE email='$emailToDelete'";
        if (!mysqli_query($connect, $deleteCartQuery)) {
            throw new Exception(mysqli_error($connect));
        }

        // Delete customer from the customer table
        $deleteCustomerQuery = "DELETE FROM customer WHERE email='$emailToDelete'";
        if (!mysqli_query($connect, $deleteCustomerQuery)) {
            throw new Exception(mysqli_error($connect));
        }
        
        // Commit the transaction if all queries succeed
        mysqli_commit($connect);
    } catch (Exception $e) {
        // Rollback the transaction if any query fails
        mysqli_rollback($connect);
        echo "An error occurred: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Intanz Qalesh Shop - Customer Information</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="style1.css">

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Ensure the button takes up full width of its cell */
        button[name="delete_customer"] {
            width: 100%;
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
        <h2>Customer Information</h2>
        <form method="GET">
            <label>Sort By:</label>
            <select name="order_by">
                <option value="cl.login_date DESC" <?php echo (isset($_GET['order_by']) && $_GET['order_by'] == 'cl.login_date DESC') ? 'selected' : ''; ?>>Latest to Older</option>
                <option value="cl.login_date ASC" <?php echo (isset($_GET['order_by']) && $_GET['order_by'] == 'cl.login_date ASC') ? 'selected' : ''; ?>>Older to Latest</option>
            </select>
            <button type="submit">Sort</button>
        </form><br>
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Login Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Get the sort order from the URL parameter or default to descending order by login date
                $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'cl.login_date DESC';
                
                // Query to fetch customer information joined with customer_log to get the latest login date
                $query = "SELECT c.email, c.full_name, c.phone_number, c.address, cl.login_date 
                          FROM customer c
                          LEFT JOIN (
                              SELECT email, MAX(login_date) AS login_date
                              FROM customer_log
                              GROUP BY email
                          ) cl ON c.email = cl.email
                          ORDER BY $order_by";
                
                // Execute the query
                $result = mysqli_query($connect, $query);
                
                // Loop through the results and display each customer's information
                while ($row = mysqli_fetch_assoc($result)) :
                ?>
                    <tr>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['phone_number']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['login_date']; ?></td>
                        <td>
                            <form action="" method="POST" onsubmit="return confirmDelete()">
                                <input type="hidden" name="email" value="<?php echo $row['email']; ?>">
                                <button type="submit" name="delete_customer">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <script>
                    function confirmDelete() {
                        return confirm("Are you sure you want to delete this customer?");
                    }
                    </script>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>