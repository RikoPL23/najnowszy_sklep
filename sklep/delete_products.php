<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];

    $sql = "DELETE FROM products WHERE product_id=$product_id";

    if ($conn->query($sql) === TRUE) {
        echo "Produkt został usunięty pomyślnie";
    } else {
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
