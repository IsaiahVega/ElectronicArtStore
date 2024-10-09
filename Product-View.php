<?php
require 'dbconn.php';
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Product List</title>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Product List</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Image</th>
            <th scope="col">Category ID</th>
            <th scope="col">Price</th>
        </tr>
        </thead>
        <tbody>
        <?php
        /**
         * Represents a database query.
         *
         * @var string $query The SQL query string.
         */
        $query = "SELECT * FROM articulos";
        /**
         * Represents the query run object.
         *
         * This object is used to perform database queries and handle the results.
         *
         * Properties:
         * - $dbh (PDO): The PDO database connection object.
         * - $stmt (PDOStatement): The prepared statement object for executing queries.
         *
         * Public methods:
         * - __construct(PDO $dbh): Initializes the query run object with a given PDO connection.
         * - prepare(string $query): Prepares a query for execution.
         * - bindParam(mixed $param, mixed &$var, int $type = PDO::PARAM_STR): Binds a parameter to a variable.
         * - execute(): Executes the prepared query.
         * - fetchAll(int $fetchStyle = PDO::FETCH_ASSOC): Fetches all rows from the executed statement.
         * - fetch(int $fetchStyle = PDO::FETCH_ASSOC): Fetches the next row from the executed statement.
         * - rowCount(): Returns the number of rows affected by the executed statement.
         *
         */
        $query_run = mysqli_query($con, $query);
        /**
         * Represents a row in a data table.
         *
         * @var array $row The data contained in the row. It is an associative array
         *                 with column names as keys and corresponding values.
         */
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<tr>";
            echo "<td>" . $row['idArt'] . "</td>";
            echo "<td>" . $row['NombreArt'] . "</td>";
            echo "<td>" . $row['DescripcionArt'] . "</td>";
            echo "<td><img src='" . $row['ImageArt'] . "' width='100' height='100'></td>";
            echo "<td>" . $row['IdCat'] . "</td>";
            echo "<td>" . $row['Precio'] . "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>