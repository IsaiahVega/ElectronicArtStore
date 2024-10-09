<?php
session_start();
require 'dbconn.php';

if (isset($_GET['id'])) {
    /**
     * Variable for storing the sale ID.
     *
     * @var int $saleId The unique identifier for each sale.
     */
    $saleId = $_GET['id'];
} else {
    die('No sale ID provided');
}

// Use a prepared statement to securely fetch the sale information from the joined procventa, venta and articulos tables.
// This expects that procventa, venta and articulos tables are related and venta.idVenta = procventa.idVenta and venta.idArt = articulos.idArt
/**
 * Variable to store the prepared statement for executing SQL queries.
 *
 * @var \PDOStatement $stmt
 */
$stmt = $con->prepare("SELECT procventa.SubTotal, procventa.Impuesto, procventa.Total, articulos.NombreArt, articulos.ImageArt, articulos.Precio, venta.Cantidad FROM procventa INNER JOIN venta ON procventa.idVenta = venta.idVenta INNER JOIN articulos ON venta.idArt = articulos.idArt WHERE procventa.idVenta = ?");
$stmt->bind_param('s', $saleId);

$stmt->execute();
/**
 * Holds the result of a computation or operation.
 *
 * @var mixed $result The result value.
 */
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html>
<head>
    <!-- CSS only -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #2c313c;
        }

        .sale-detail {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-top: 30px;
        }

        .return {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header text-center">
                <h4>Sale Details</h4>
            </div>
            <div class="container sale-detail">
                <?php
                if ($result->num_rows > 0) {
                    /**
                     * Represents a single row of data.
                     *
                     * @var array $row The data contained in the row.
                     */
                    while ($row = $result->fetch_assoc()) {
                        // Display the sale and product information
                        echo "<div><img src='" . $row['ImageArt'] . "' style='width:150px;height:auto;'></div>";
                        echo "<h3>" . $row['NombreArt'] . "</h3>";
                        echo "<div>Price: $" . $row['Precio'] . "</div>";
                        echo "<div>Quantity: " . $row['Cantidad'] . "</div>";
                        echo "<div>SubTotal: $" . $row['SubTotal'] . "</div>";
                        echo "<div>Impuesto: $" . $row['Impuesto'] . "</div>";
                        echo "<div>Total: $" . $row['Total'] . "</div>";
                    }
                } else {
                    echo "<div>No sale found with ID: $saleId</div>";
                }
                $stmt->close();
                ?>
                <button class="btn btn-danger return" onclick="window.history.back()">Back</button>
            </div>
        </div>
    </div>
</body>
</html>