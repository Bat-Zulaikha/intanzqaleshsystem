<?php
session_start();

include 'access.php';

$query = "SELECT * FROM product";
$result = mysqli_query($connect, $query);

if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['email'])) {
        header("Location: login.php?redirect=products.php");
        exit();
    } else {
        $product_id = mysqli_real_escape_string($connect, $_POST['product_id']);
        $customer_email = $_SESSION['email'];
        $quantity = mysqli_real_escape_string($connect, $_POST['quantity']); // Fetching quantity
        $size = isset($_POST['size']) ? mysqli_real_escape_string($connect, $_POST['size']) : ''; // Fetching size if available

        // Including size and quantity in the query
        $insert_query = "INSERT INTO cart (email, product_id, quantity, size) VALUES ('$customer_email', '$product_id', '$quantity', '$size')"; 
        if (mysqli_query($connect, $insert_query)) {
            echo "<script>alert('Product added to cart successfully.');</script>";
        } else {
            echo "<script>alert('Failed to add product to cart.');</script>";
        }
    }
}

function getProductStock($connect, $product_id) {
    $product_id = mysqli_real_escape_string($connect, $product_id);
    $stock_query = "SELECT stock FROM product WHERE product_id = '$product_id'";
    $stock_result = mysqli_query($connect, $stock_query);
    $stock_row = mysqli_fetch_assoc($stock_result);
    return $stock_row['stock'];
}

function sortProductsByCategory($connect, $category) {
    $category = mysqli_real_escape_string($connect, $category);
    $query = "SELECT * FROM product WHERE category = '$category'";
    $result = mysqli_query($connect, $query);
    return $result;
}

function sortProductsByPrice($connect, $order) {
    $order = mysqli_real_escape_string($connect, $order);
    $query = "SELECT * FROM product ORDER BY price $order";
    $result = mysqli_query($connect, $query);
    return $result;
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
    <style>
        .product-container {
            display: inline-block;
            width: 23%;
            margin: 1%;
            padding: 10px;
            border: 1px solid #ccc;
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
        <h2>Products</h2>
        <form method="post">
            <label>Sort by Category:</label>
            <select name="category">
                <option value="">All</option>
                <option value="menswear">Menswear</option>
                <option value="womenswear">Womenswear</option>
                <option value="kidswear">Kidswear</option>
                <option value="kidtoys">Kid Toys</option>
                <option value="drink">Drinks</option>
                <option value="cutupfruits">Cut Up Fruits</option>
            </select>
            <input type="submit" name="sort_category" value="Sort">
        </form>
        
        <form method="post">
            <label>Sort by Price:</label>
            <select name="price">
                <option value="asc">Low to High</option>
                <option value="desc">High to Low</option>
            </select>
            <input type="submit" name="sort_price" value="Sort">
        </form><br>
        
        <?php
        if(isset($_POST['sort_category'])) {
            $category = $_POST['category'];
            if(!empty($category)) {
                $result = sortProductsByCategory($connect, $category);
            }
        }
        if(isset($_POST['sort_price'])) {
            $order = ($_POST['price'] == 'asc') ? 'ASC' : 'DESC';
            $result = sortProductsByPrice($connect, $order);
        }
        
        while ($row = mysqli_fetch_assoc($result)) :
        ?>
            <div class="product-container">
                <h3><?php echo $row['name']; ?></h3>
                <p font-size="25px">Description: <?php echo $row['description']; ?></p>
                <p>Price: $<?php echo $row['price']; ?></p>
                <!-- Display product image -->
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="width:100px;">
                <!-- Display product stock -->
                <p>Stock: <?php echo getProductStock($connect, $row['product_id']); ?></p>
                <?php if ($row['sizeWear']) : ?>
                    <form action="" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                        <p for="size">Size:</p>
                        <select name="size">
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                        </select>
                        <p>Quantity: 
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo getProductStock($connect, $row['product_id']); ?>" required></p><br>
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                <?php else : ?>
                    <form action="" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                        <p>Quantity: 
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo getProductStock($connect, $row['product_id']); ?>" required></p><br>
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?><br><br>
        <p>Finished surveying for items? Check them out of <a href="cart.php">your cart</a> now!</p>
    </section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>