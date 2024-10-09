<?php
session_start();
require 'dbconn.php';

if (isset($_POST['save_product'])) {

    /**
     * Represents a product.
     *
     * @property int $id The unique identifier of the product.
     * @property string $name The name of the product.
     * @property float $price The price of the product.
     * @property int $quantity The quantity available for the product.
     * @property array $attributes The attributes of the product (e.g., color, size).
     */
    $product = mysqli_real_escape_string($con, $_POST['NombreArt']);
    /**
     * @var string $description Description of the variable.
     */
    $description = mysqli_real_escape_string($con, $_POST['DescripcionArt']);
    /**
     * Represents an image object.
     *
     * @property string $path The file path of the image.
     * @property int $width The width of the image in pixels.
     * @property int $height The height of the image in pixels.
     */
    $image = mysqli_real_escape_string($con, $_POST['ImageArt']);
    /**
     * Represents a category
     *
     * This class provides a representation of a category, which can be used to organize
     * and classify different types of data or objects. The category can have various properties
     * such as a name, description, and an identifier.
     *
     * @package YourPackage
     *
     * @property int $id The unique identifier of the category
     * @property string $name The name of the category
     * @property string|null $description The description of the category (optional)
     */
    $category = mysqli_real_escape_string($con, $_POST['IdCat']);
    /**
     * Represents the price of a product or service.
     *
     * @var float $price The value of the price.
     */
    $price = mysqli_real_escape_string($con, $_POST["Precio"]);

    /**
     * Represents a database query.
     *
     * This class is responsible for encapsulating a database query and providing
     * methods to interact with the query parameters, execution and retrieval of results.
     *
     * @package YourPackage
     * @subpackage Database
     */
    $query = "INSERT INTO articulos(NombreArt,DescripcionArt,ImageArt,IdCat,Precio)  VALUES ('$product', '$description ', '$image', '$category', '$price')";


    /**
     * Represents the execution of a database query.
     *
     * @var \PDOStatement|null $query_run The executed database query.
     */
    $query_run = mysqli_query($con, $query);

    /**
     * Represents a query execution result.
     *
     * @var mixed $query_run The result of executing a database query.
     */
    if ($query_run) {
        $_SESSION['message'] = "Product Added Succesfully";
        header("Location: Product-Create.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Product Not Created";
        header("Location: Product-Create.php");
        exit(0);
    }
}

if (isset($_POST['delete_product'])) {
    /**
     * Represents the unique identifier of a product.
     *
     * @var int|string $product_id The product ID.
     */
    $product_id = mysqli_real_escape_string($con, $_POST['delete_product']);

    /**
     * Represents a query string used for database operations.
     *
     * @var string $query The query string
     */
    $query = "DELETE FROM articulos WHERE idArt='$product_id'";

    /**
     * Represents the result of a database query execution.
     *
     * @var mixed $query_run The result of a database query execution.
     */
    $query_run = mysqli_query($con, $query);

    /**
     * Represents a query execution result.
     *
     * @var mixed $query_run The variable holding the result of a query execution.
     */
    if ($query_run) {
        $_SESSION['message'] = "Product Deleted Successfully";
        header("Location: index.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Failed to Delete Product";
        header("Location: index.php");
        exit(0);
    }
}


if (isset($_POST['update_product'])) {
    /**
     * Represents the unique identifier for an article.
     *
     * @var int $idArt
     */
    $idArt = $_POST['idArt'];
    /**
     * Holds the name of an article.
     *
     * @var string $NombreArt
     */
    $NombreArt = $_POST['NombreArt'];
    /**
     * Represents a description of an article.
     *
     * @var string $DescripcionArt The description of the article.
     */
    $DescripcionArt = $_POST['DescripcionArt'];
    /**
     * ImageArt represents an image artwork.
     *
     * This class provides methods to manipulate and access various attributes of an image artwork.
     *
     * @property int $id The unique identifier of the image artwork.
     * @property string $title The title of the image artwork.
     * @property string $artist The name of the artist who created the image artwork.
     * @property string $medium The medium used for creating the image artwork.
     * @property string $description The description of the image artwork.
     * @property string $imageUrl The URL of the image file representing the image artwork.
     * @property int $width The width of the image artwork in pixels.
     * @property int $height The height of the image artwork in pixels.
     * @property string[] $tags An array of tags associated with the image artwork.
     * @property string|null $created_at The date and time when the image artwork was created.
     * @property string|null $updated_at The date and time when the image artwork was last updated.
     *
     * @package YourAppName\Models
     */
    $ImageArt = $_POST['ImageArt'];
    /**
     * Represents the identifier of a category.
     *
     * @var string $IdCat The unique identifier for a category.
     */
    $IdCat = $_POST['IdCat'];
    /**
     * PHPDoc for the variable $Precio
     *
     * @var float $Precio The price for a product
     */
    $Precio = $_POST['Precio'];

    /**
     * Represents a query used for SQL database operations.
     *
     * @property string $sql The SQL statement of the query.
     * @property array $parameters The parameters to bind to the query.
     */
    $query = "UPDATE articulos SET NombreArt='$NombreArt', DescripcionArt='$DescripcionArt', ImageArt='$ImageArt', IdCat='$IdCat', Precio='$Precio' WHERE idArt='$idArt'";
    /**
     * Variable: $query_run
     *
     * Description: This variable is used to store the query result after execution.
     *
     * Type: mixed
     *
     * @var mixed $query_run
     */
    $query_run = mysqli_query($con, $query);

    /**
     * Holds the value of the query execution result.
     *
     * @var mixed
     */
    if ($query_run) {
        $_SESSION['message'] = 'Product updated successfully';
        $_SESSION['success'] = true;
    } else {
        $_SESSION['message'] = 'Failed to update product';
        $_SESSION['success'] = false;
    }

    header('Location: index.php?idArt=' . $idArt);
    exit();
}
?>
