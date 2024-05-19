<?php
session_start();

include 'access.php';

// Check if admin is not logged in, redirect to admin login page
if (!isset($_SESSION['admin_email'])) {
    header("Location: adminlogin.php");
    exit();
}

// Add Product
if (isset($_POST['add_product'])) {
    // File upload handling
    $targetDir = "uploads/";
    $targetFile = $targetDir . uniqid() . '_' . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["file"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowedExtensions = array("jpg", "jpeg", "png", "gif");
    if(!in_array($imageFileType, $allowedExtensions)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Move the file to the target directory
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // Fetch product details from form
            $name = mysqli_real_escape_string($connect, $_POST['name']);
            $description = mysqli_real_escape_string($connect, $_POST['description']);
            $price = mysqli_real_escape_string($connect, $_POST['price']);
            $stock = mysqli_real_escape_string($connect, $_POST['stock']);
            $category = mysqli_real_escape_string($connect, $_POST['category']);
            $sizeWear = isset($_POST['sizeWear']) ? 1 : 0; // Convert checkbox value to boolean

            // Insert product into database
            $insert_query = "INSERT INTO product (name, description, price, stock, category, image, sizeWear) 
                             VALUES ('$name', '$description', '$price', '$stock', '$category', '$targetFile', '$sizeWear')";
            if(mysqli_query($connect, $insert_query)) {
                echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
            } else {
                echo "Error: " . $insert_query . "<br>" . mysqli_error($connect);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Update Product
if (isset($_POST['update_product'])) {
    // Fetch product details from form
    $product_id = $_POST['product_id'];
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $description = mysqli_real_escape_string($connect, $_POST['description']);
    $price = mysqli_real_escape_string($connect, $_POST['price']);
    $stock = mysqli_real_escape_string($connect, $_POST['stock']);
    $category = mysqli_real_escape_string($connect, $_POST['category']);
    $sizeWear = isset($_POST['sizeWear']) ? 1 : 0; // Convert checkbox value to boolean

    // Check if a new image file is uploaded
    if ($_FILES['file']['size'] > 0) {
        // File upload handling
        $targetDir = "uploads/";
        $targetFile = $targetDir . uniqid() . '_' . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["file"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedExtensions = array("jpg", "jpeg", "png", "gif");
        if(!in_array($imageFileType, $allowedExtensions)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // Move the file to the target directory
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                // Update product in database with new image
                $update_query = "UPDATE product 
                                 SET name='$name', description='$description', price='$price', stock='$stock', category='$category', image='$targetFile', sizeWear='$sizeWear'
                                 WHERE product_id='$product_id'";
                if (mysqli_query($connect, $update_query)) {
                    echo "Product updated successfully.";
                } else {
                    echo "Error: " . $update_query . "<br>" . mysqli_error($connect);
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        // Update product in database without changing the image
        $update_query = "UPDATE product 
                         SET name='$name', description='$description', price='$price', stock='$stock', category='$category', sizeWear='$sizeWear'
                         WHERE product_id='$product_id'";
        if (mysqli_query($connect, $update_query)) {
            echo "Product updated successfully.";
        } else {
            echo "Error: " . $update_query . "<br>" . mysqli_error($connect);
        }
    }
}

// Delete Product
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    // Delete product from database
    $delete_query = "DELETE FROM product WHERE product_id='$product_id'";
    if(mysqli_query($connect, $delete_query)) {
        echo "Product deleted successfully.";
    } else {
        echo "Error: " . $delete_query . "<br>" . mysqli_error($connect);
    }
}

// Fetch all products
$query = "SELECT * FROM product";
$result = mysqli_query($connect, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Intanz Qalesh Shop</title>
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
    <h1>Product Management</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>SizeWear</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) :
            ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['sizeWear'] ? 'Yes' : 'No'; ?></td>
                    <td>
                        <?php if (file_exists($row['image'])) : ?>
                            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="width:100px;">
                        <?php else : ?>
                            <p>No Image</p>
                        <?php endif; ?>
                    </td>
                    <td>
                        <!-- Add buttons for updating and deleting products -->
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <input type="text" name="name" value="<?php echo $row['name']; ?>" placeholder="Name" required>
                            <textarea name="description" placeholder="Description" required><?php echo $row['description']; ?></textarea>
                            <input type="text" name="price" value="<?php echo $row['price']; ?>" placeholder="Price" required step="0.01">
                            <input type="text" name="stock" value="<?php echo $row['stock']; ?>" placeholder="Stock" required>
                            <select name="category">
                                <option value="menswear" <?php echo ($row['category'] == 'menswear') ? 'selected' : ''; ?>>Menswear</option>
                                <option value="womenswear" <?php echo ($row['category'] == 'womenswear') ? 'selected' : ''; ?>>Womenswear</option>
                                <option value="kidswear" <?php echo ($row['category'] == 'kidswear') ? 'selected' : ''; ?>>Kidswear</option>
                                <option value="kidtoys" <?php echo ($row['category'] == 'kidtoys') ? 'selected' : ''; ?>>Kid Toys</option>
                                <option value="drink" <?php echo ($row['category'] == 'drink') ? 'selected' : ''; ?>>Drinks</option>
                                <option value="cutupfruits" <?php echo ($row['category'] == 'cutupfruits') ? 'selected' : ''; ?>>Cut Up Fruits</option>
                            </select>
                            <label for="sizeWear">SizeWear:</label>
                            <input type="checkbox" name="sizeWear" <?php echo $row['sizeWear'] ? 'checked' : ''; ?>><br>
                            <input type="file" name="file" id="fileToUpload"><br>
                            <button type="submit" name="update_product">Update</button>
                            <button type="submit" name="delete_product">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php
            endwhile;
            ?>
        </tbody>
    </table>

    <article>
        <form action="custProducts.php" method="POST" enctype="multipart/form-data">
            <h2>Add New Product</h2>
            <label for="name">Product Name</label>
            <input type="text" name="name" placeholder="Name" required><br>
            <label for="description">Product Description</label>
            <textarea name="description" placeholder="Description" required></textarea><br>
            <label for="price">Product Price</label>
            <input type="text" name="price" placeholder="Price" required step="0.01"><br>
            <label for="category">Product Category</label>
            <select name="category">
                <option value="menswear">Menswear</option>
                <option value="womenswear">Womenswear</option>
                <option value="kidswear">Kidswear</option>
                <option value="kidtoys">Kid Toys</option>
                <option value="drink">Drinks</option>
                <option value="cutupfruits">Cut Up Fruits</option>
            </select><br>
            <label for="file">Product Img Upload</label>
            <input type="file" name="file" id="fileToUpload" required><br>
            <label for="stock">Product Stock</label>
            <input type="text" name="stock" placeholder="Stock" required><br>
            <label for="sizeWear">SizeWear:</label>
            <input type="checkbox" name="sizeWear"><br>
            <button type="submit" name="add_product">Add Product</button><br>
        </form>
    </article>
    </section>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>