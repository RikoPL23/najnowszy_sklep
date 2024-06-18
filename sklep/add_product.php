<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['product_name'];
    $description = $_POST['product_description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $_POST['image_path'];
    $sale = isset($_POST['sale']) ? 1 : 0;
    $saleAmount = $_POST['sale_amount'] ?? 0;
    $stock = $_POST['stock'];

    $sql = "INSERT INTO products (name, description, category, price, image, sale, saleAmount, stock) VALUES ('$name', '$description', '$category', '$price', '$image', '$sale', '$saleAmount', '$stock')";

    if ($conn->query($sql) === TRUE) {
        echo "Nowy produkt został dodany pomyślnie";
    } else {
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
