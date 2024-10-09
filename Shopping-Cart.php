<?php
session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (isset($_GET['remove']) && array_key_exists($_GET['remove'], $_SESSION['cart'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
}

require 'dbconn.php';

/**
 * SQL statement
 *
 * This variable holds the SQL statement to be executed.
 *
 * @var string $sql
 */
$sql = "SELECT idCliente, CONCAT(Nombre, ' ', Apellidos) AS ClienteName FROM cliente";
/**
 * Variable representing the result of a computation or operation.
 *
 * @var mixed $result The computed result or output of an operation.
 */
$result = $con->query($sql);


if ($result->num_rows > 0) {
    /**
     * Represents a collection of clients.
     *
     * @property-read int $count The number of clients in the collection.
     * @property-read Client[] $items An array of Client objects representing the clients in the collection.
     */
    $clients = $result->fetch_all(MYSQLI_ASSOC);
} else {
    /**
     * This variable represents an array of client objects.
     *
     * @var array $clients An array containing client objects.
     */
    $clients = [];
}


/**
 * Calculates the subtotal for a given cart.
 *
 * @param array $cart An array representing the cart. Each element of the array should be an associative array with the keys 'price' and 'quantity'.
 * @return int The subtotal of the cart, calculated by summing the product of each item's price and quantity.
 */
function calculateSubtotal($cart)
{
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    return $subtotal;
}


/**
 * Calculates the total amount including tax.
 *
 * @param float $subtotal The subtotal amount.
 * @param float $taxRate The tax rate as a decimal number.
 * @return float The total amount including tax.
 */
function calculateTotal($subtotal, $taxRate)
{
    $tax = $subtotal * $taxRate;
    $total = $subtotal + $tax;
    return $total;
}

/**
 * Represents the tax rate for a specific region or product.
 *
 * @var float $taxRate The tax rate as a decimal value.
 */
$taxRate = 0.115;
/**
 * Represents a message2 variable.
 *
 * @var string $message2 The value of the message2 variable. (Please replace this with an appropriate description)
 */
$message2 = '';
/**
 * Represents a message variable.
 *
 * This variable is used to store messages, such as error messages or notifications.
 * It provides methods to set and retrieve the message string.
 *
 * @var string $message The message string.
 *
 */
$message = '';
if (isset($_POST['buy'])) {
    /**
     * Client ID represents the unique identifier for a client in the system.
     *
     * @var int
     */
    $clientID = $_POST['client'];
    /**
     * @var array $cartData
     *
     * This variable represents the cart data, which stores information about the products added to the cart.
     * It is an associative array, where the keys are the product IDs and the values are the corresponding product details.
     *
     * The structure of the $cartData array is as follows:
     * [
     *     'productId1' => [
     *         'name' => 'Product 1',
     *         'price' => 19.99,
     *         'quantity' => 2,
     *         'subtotal' => 39.98
     *     ],
     *     'productId2' => [
     *         'name' => 'Product 2',
     *         'price' => 9.99,
     *         'quantity' => 1,
     *         'subtotal' => 9.99
     *     ],
     *     // More products can be added to the cart...
     * ]
     *
     * The $cartData array can be accessed and modified throughout the application to manage the shopping cart.
     * Each product is represented by a unique product ID, and the associated array contains the product details.
     * The 'name' key holds the name of the product, the 'price' key stores the price of the product,
     * the 'quantity' key represents the quantity of the product in the cart,
     * and the 'subtotal' key stores the subtotal (price * quantity) of the product.
     *
     * Example usage:
     * $cartData['productId1']['quantity']++; // Increase the quantity of product with ID 'productId1' by 1
     * $cartData['productId2']['quantity']--; // Decrease the quantity of product with ID 'productId2' by 1
     * $cartData['productId1']['subtotal'] = $cartData['productId1']['price'] * $cartData['productId1']['quantity'];
     * // Update the subtotal of product with ID 'productId1' based on the new quantity
     *
     * Note: This variable is used as an example and does not contain any specific implementation.
     */
    $cartData = $_POST['cart_data'];

    /**
     * Variable representing the purchase date.
     *
     * @var string $purchaseDate The purchase date in the format "YYYY-MM-DD".
     */
    $purchaseDate = date('Y-m-d');
    /**
     * Represents a SQL query string for inserting data into a database.
     *
     * @var string $insertSQL The SQL query for inserting data into a database.
     */
    $insertSQL = "INSERT INTO venta (idVenta, FechaCompra, idArt, idCliente, Cantidad) VALUES ";
    /**
     * Represents a variable key.
     *
     * @var mixed $key The value of the key.
     */
    foreach ($_SESSION['cart'] as $key => $item) {
        /**
         * Holds the SQL query for inserting data into the database.
         *
         * @var string $insertSQL
         */
        $insertSQL .= "(NULL, '$purchaseDate', {$item['idArt']}, $clientID, {$item['quantity']}),";
    }
    /**
     * @var string $insertSQL
     *
     * This variable stores the SQL statement for inserting data into a database table.
     * The SQL statement should be written as a string.
     *
     * Example usage:
     *
     * $insertSQL = "INSERT INTO users (name, email, password)
     *               VALUES ('John Doe', 'johndoe@example.com', 'password123')";
     */
    $insertSQL = rtrim($insertSQL, ',');

    if ($con->query($insertSQL)) {
        /**
         * Represents the last ID of a record in the database.
         *
         * @var int $lastID
         */
        $lastID = $con->insert_id;
        /**
         * Represents a variable "message".
         *
         * @var string or null $message The message value.
         */
        $message = "<div class='alert alert-success' role='alert'>
        New record created successfully in the venta table. Last inserted ID is: " . $lastID . "
        </div>";

        /**
         * Represents the subtotal of a transaction.
         *
         * @var float $subtotal The total amount of the transaction before taxes or discounts.
         *
         * @since 1.0.0
         */
        $subtotal = calculateSubtotal($_SESSION['cart']);
        /**
         * Represents the value of the tax.
         *
         * @var int|float $impuesto The value of the tax.
         */
        $impuesto = $subtotal * $taxRate;
        /**
         * The total variable represents the sum of a series of numerical values.
         *
         * @var float|null The total sum of the numerical values.
         */
        $total = $subtotal + $impuesto;

        /**
         * Variable: insertProcVentaSQL
         *
         * Description: Contains the SQL query statement for inserting a record into the "ProcVenta" table.
         *
         * -> Usage:
         *    - This variable can be used to execute the SQL query using a database connection and add a new record to the "ProcVenta" table.
         *
         * -> Note:
         *    - The exact structure and fields of the "ProcVenta" table may vary depending on the database schema.
         *    - The values for the fields should be passed as parameters to the query, rather than directly appending them to the query string, to prevent SQL injection.
         *
         * -> Example usage:
         *
         *    $dbHost = 'localhost';
         *    $dbUser = 'username';
         *    $dbPass = 'password';
         *    $dbName = 'database_name';
         *
         *    // Create a PDO database connection
         *    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
         *
         *    // Prepare the SQL statement
         *    $stmt = $pdo->prepare($insertProcVentaSQL);
         *
         *    // Bind parameters to the statement
         *    $stmt->bindParam(':field1', $value1);
         *    $stmt->bindParam(':field2', $value2);
         *
         *    // Execute the statement
         *    $stmt->execute();
         *
         */
        $insertProcVentaSQL = "INSERT INTO procventa (idVenta, SubTotal, Impuesto, Total) VALUES ($lastID, $subtotal, $impuesto, $total)";

        if ($con->query($insertProcVentaSQL)) {
            /**
             * Represents a string variable containing a message.
             *
             * @var string $message2
             */
            $message2 = "<div class='alert alert-success' role='alert'>
            New record created successfully in the procventa table.
            </div>";
            $_SESSION['cart'] = [];
        } else {
            echo "Error: " . $insertProcVentaSQL . "<br>" . $con->error;
        }
    } else {
        echo "Error: " . $insertSQL . "<br>" . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Shopping Cart</title>
    <style>

        body {
            background-color: #2c313c;

        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-md bg-dark sticky-top border-bottom" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand d-md-none" href="#">
            <svg class="bi" width="24" height="24">
                <use xlink:href="#aperture"/>
            </svg>
            Aperture
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"
                aria-controls="offcanvas" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasLabel">Aperture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav flex-grow-1 justify-content-between">
                    <li class="nav-item"><a class="nav-link" href="#">
                            <svg class="bi" width="24" height="24">
                                <use xlink:href="#aperture"/>
                            </svg>
                        </a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Front Cover</a></li>
                    <li class="nav-item"><a class="nav-link" href="Product-Create.php">Add Product</a></li>
                    <li class="nav-item"><a class="nav-link" href="Create-Client.php">Add Client</a></li>
                    <li class="nav-item"><a class="nav-link" href="Create-Sale.php">Create Sale</a></li>

                    <li class="nav-item"><a class="nav-link" href="Lista-Ventas.php">List of Sales</a></li>
                    <li class="nav-item"><a class="nav-link" href="Shopping-Cart.php">
                            <svg class="bi" width="24" height="24">
                                <use xlink:href="#cart"/>
                            </svg>
                        </a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<?php echo $message; ?>
<?php echo $message2; ?>
<div class="background-color">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Shopping cart
                            <a href="index.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php /**
                         * Variable $key stores the key used for accessing a value in an associative array or object.
                         *
                         * @var string|int $key The key used for accessing a value.
                         */
                        foreach ($_SESSION['cart'] as $key => $item) : ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td><a href="?remove=<?php echo $key; ?>" class="btn btn-danger">Remove</a></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end">Subtotal:</td>
                            <td>$<?php echo number_format(calculateSubtotal($_SESSION['cart']), 2); ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Tax (<?php echo($taxRate * 100); ?>%):</td>
                            <td>$<?php echo number_format(calculateSubtotal($_SESSION['cart']) * $taxRate, 2); ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Total:</td>
                            <td>
                                $<?php echo number_format(calculateTotal(calculateSubtotal($_SESSION['cart']), $taxRate), 2); ?>
                            </td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="card-footer text-end">
                        <!-- Moved the buttons to card-footer -->
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <!-- Added action attribute -->
                            <div class="mb-3">
                                <label for="client">Select Client:</label>
                                <select class="form-select" id="client" name="client" required>
                                    <option value="">Select Client</option>
                                    <?php /**
                                     * Description: This variable represents a client object
                                     *
                                     * @var Client $client An instance of the Client class representing a client
                                     */
                                    foreach ($clients as $client) : ?>
                                        <option value="<?php echo $client['idCliente']; ?>"><?php echo $client['ClienteName']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <input type="hidden" name="cart_data"
                                   value="<?php echo htmlspecialchars(json_encode($_SESSION['cart'])); ?>">
                            <button type="submit" name="buy" class="btn btn-primary">Buy</button>
                            <a href="index.php" class="btn btn-secondary">Return to Main Menu</a>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
