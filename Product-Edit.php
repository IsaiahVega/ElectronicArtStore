<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and include database connection
session_start();
require 'dbconn.php';

// Check if product ID is provided in the URL
if (isset($_GET['idArt'])) {
    /**
     * Represents the unique identifier of a product.
     *
     * The $product_id variable stores the unique identifier of a product. This variable is typically assigned by the system
     * when a new product is created. It is used to uniquely identify a product within the system.
     *
     * @var int $product_id The unique identifier of a product.
     */
    $product_id = mysqli_real_escape_string($con, $_GET['idArt']);

    // Query to fetch product data
    /**
     * This variable represents a database query string.
     *
     * @var string $query
     */
    $query = "SELECT * FROM articulos WHERE idArt='$product_id' ";
    /**
     * Represents the query_run variable.
     *
     * @var mixed $query_run This variable stores the result of a database query execution.
     */
    $query_run = mysqli_query($con, $query);

    // Check if product data is found
    if (mysqli_num_rows($query_run) > 0) {
        /**
         * Represents a product.
         *
         * @property int $id         The product ID.
         * @property string $name       The product name.
         * @property float $price      The product price.
         * @property int $quantity   The available quantity of the product.
         * @property bool $isFeatured Whether the product is featured or not.
         */
        $product = mysqli_fetch_array($query_run);
    } else {
        // Redirect or show an error message if product is not found
        $_SESSION['message'] = 'No such product ID found.';
        $_SESSION['success'] = false;
        header('Location: index.php'); // Change the URL as needed
        exit();
    }
} else {
    // Redirect or show an error message if product ID is not provided
    $_SESSION['message'] = 'Product ID is required.';
    $_SESSION['success'] = false;
    header('Location: index.php'); // Change the URL as needed
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c313c;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <!-- Display success or error message -->
    <?php if (isset($_SESSION['message'])) : ?>
        <div class="alert alert-<?= $_SESSION['success'] ? 'success' : 'danger' ?>">
            <?= $_SESSION['message'] ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Product Edit
                        <a href="index.php" class="btn btn-danger float-end">BACK</a>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Product Edit Form -->
                    <form action="code.php" method="POST">
                        <input type="hidden" name="idArt" value="<?= $product['idArt']; ?>">
                        <div class="mb-3">
                            <label for="NombreArt" class="form-label">Product Name:</label>
                            <input type="text" class="form-control" id="NombreArt" name="NombreArt"
                                   value="<?= $product['NombreArt']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="DescripcionArt" class="form-label">Product Description:</label>
                            <textarea class="form-control" id="DescripcionArt" name="DescripcionArt"
                                      required><?= $product['DescripcionArt']; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="ImageArt" class="form-label">Product Image URL:</label>
                            <input type="text" class="form-control" id="ImageArt" name="ImageArt"
                                   value="<?= $product['ImageArt']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="IdCat" class="form-label">Category:</label>
                            <select class="form-select" id="IdCat" name="IdCat" required>
                                <option value="1" <?= $product['IdCat'] == 1 ? 'selected' : ''; ?>>Category 1</option>
                                <option value="2" <?= $product['IdCat'] == 2 ? 'selected' : ''; ?>>Category 2</option>
                                <option value="3" <?= $product['IdCat'] == 3 ? 'selected' : ''; ?>>Category 3</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="Precio" class="form-label">Price:</label>
                            <input type="text" class="form-control" id="Precio" name="Precio"
                                   value="<?= $product['Precio']; ?>" required>
                        </div>
                        <button type="submit" name="update_product" class="btn btn-primary">Update</button>
                    </form>
                    <!-- End Product Edit Form -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
