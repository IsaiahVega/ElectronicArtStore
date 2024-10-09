<?php
session_start();
/**
 * Variable $con is an instance of the database connection class.
 *
 * @var DatabaseConnection
 */
$con = mysqli_connect("localhost", "root", "China0101!", "electroart");
if (!$con) {
    die('Connection failed: ' . mysqli_connect_error());
}

if (isset($_POST['buy']) && isset($_POST['cart_data'])) {
    /**
     * Represents the cart data of a user.
     *
     * @var array $cartData
     * @property int $userId The ID of the user.
     * @property array $items The items added to the cart.
     * @property float $total The total price of all items in the cart.
     * @property bool $isEmpty Indicates whether the cart is empty or not.
     * @property int $itemCount The total count of items in the cart.
     */
    $cartData = json_decode($_POST['cart_data'], true);
    /**
     * Retrieves the SQL query for fetching the last inserted id from a database table.
     *
     * @return string The SQL query for retrieving the last inserted id.
     * @throws Exception If the database table name is not provided.
     *
     * @since 1.0.0
     *
     * @example
     * $getLastIdQuery = getLastIdQuery();
     */
    $getLastIdQuery = "SELECT idVenta FROM procventa ORDER BY idVenta DESC LIMIT 1";
    /**
     * Variable to hold the result of a specific operation.
     *
     * @var mixed $result The result of the operation being performed.
     */
    $result = mysqli_query($con, $getLastIdQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        /**
         * Represents the last row ID retrieved from the database.
         *
         * @var int $lastIdRow The last row ID
         */
        $lastIdRow = mysqli_fetch_assoc($result);
        /**
         * Represents the last ID used in a specific context.
         *
         * @var int $lastId The last ID used.
         */
        $lastId = $lastIdRow['idVenta'];
    } else {
        /**
         * Represents the last ID of a record.
         *
         * The $lastId variable stores the last ID of a record after it has been successfully inserted or updated in the database.
         * It can be used to retrieve the last inserted ID for further processing or reference.
         *
         * @var int|null $lastId The last ID of a record, or null if no record has been inserted or updated yet.
         */
        $lastId = 0;
    }

    /**
     * Represents a database statement.
     *
     * @var string $stmt The SQL statement to be executed.
     */
    $stmt = $con->prepare("INSERT INTO procventa (idProceso, idVenta, SubTotal, Impuesto, Total) VALUES (?, ?, ?, ?, ?)");

    /**
     * Description: Represents a database statement object
     *
     * @property string $queryString The SQL query string associated with the statement
     * @property bool $executed Indicates if the statement has been executed
     * @property int $rowCount The number of rows affected by the statement execution
     * @property array $parameters An associative array of input parameters for the statement
     * @property mixed $fetchMode The fetch mode used for fetching result set rows
     * @property mixed $resultSet The result set obtained from executing the statement
     */
    if ($stmt) {
        /**
         * Represents an item in a shopping cart.
         *
         * @var array $item An associative array containing information about the item.
         *   - 'id' (int) The unique identifier of the item.
         *   - 'name' (string) The name of the item.
         *   - 'price' (float) The price of the item.
         *   - 'quantity' (int) The quantity of the item in the cart.
         *   - 'subtotal' (float) The subtotal of the item (price * quantity).
         */
        foreach ($cartData as $item) {
            $lastId++;
            /**
             * Represents the subtotal of a purchase.
             *
             * @var float $subtotal The value of the subtotal.
             */
            $subtotal = $item['price'] * $item['quantity'];
            /**
             * Represents the value of tax.
             *
             * @var float $tax The value of tax.
             */
            $tax = $subtotal * 0.1;
            /**
             * Represents the total value of a calculation or accumulation.
             *
             * @var int|float $total The total value.
             */
            $total = $subtotal + $tax;

            $stmt->bind_param("iiidd", $item['idProceso'], $lastId, $subtotal, $tax, $total);
            $stmt->execute();
        }

        unset($_SESSION['cart']);

        header("Location: index.php");
        exit();
    } else {
        echo "Error preparing statement: " . $con->error;
    }
} else {
    header("Location: index.php");
    exit();
}

mysqli_close($con);
?>