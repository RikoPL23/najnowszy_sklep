<?php
include 'config.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<tr><th>ID</th><th>Nazwa produktu</th><th>Opis</th><th>Kategoria</th><th>Cena</th><th>Ścieżka do obrazka</th><th>Promocja</th><th>Kwota promocji</th><th>Stan magazynowy</th><th>Akcje</th></tr>';
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['product_id'] . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['description'] . '</td>';
        echo '<td>' . $row['category'] . '</td>';
        echo '<td>' . $row['price'] . '</td>';
        echo '<td>' . $row['image'] . '</td>';
        echo '<td>' . ($row['sale'] ? 'Tak' : 'Nie') . '</td>';
        echo '<td>' . $row['saleAmount'] . '</td>';
        echo '<td>' . $row['stock'] . '</td>';
        echo '<td><button class="delete-product-btn auth-btn" data-id="' . $row['product_id'] . '">Usuń</button></td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="10">Brak produktów</td></tr>';
}

$conn->close();
?>
