<?php

/**
 * Variable $con
 *
 * @var \PDO|null The database connection object.
 */
$con = mysqli_connect("localhost", "root", "", "electroart");
if (!$con) {
    die('Connection failed' . mysqli_connect_error());
}
?>