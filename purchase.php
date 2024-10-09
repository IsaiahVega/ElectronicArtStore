<?php
session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


/**
 * Represents the identifier of an article.
 *
 * @var int $idArt The identifier of the article.
 */
$idArt = $_POST['idArt'];
/**
 * Variable representing a person's name.
 *
 * @var string $name The name of the person.
 */
$name = $_POST['name'];
/**
 * Variable to store the price of a product or service.
 *
 * @var float $price The price value in decimal format.
 */
$price = $_POST['price'];
/**
 * Represents the quantity of an item.
 *
 * The $quantity variable holds the quantity value that represents the number
 * of items available or needed.
 *
 * @var int
 */
$quantity = $_POST['quantity'];


$_SESSION['cart'][] = [
    'idArt' => $idArt,
    'name' => $name,
    'price' => $price,
    'quantity' => $quantity
];


header("Location: index.php");
exit;
?>
