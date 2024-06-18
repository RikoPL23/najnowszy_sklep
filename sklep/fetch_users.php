<?php
include 'config.php';

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<tr><th>ID</th><th>Nazwa użytkownika</th><th>Rola</th><th>Akcje</th></tr>';
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['user_id'] . '</td>';
        echo '<td>' . $row['username'] . '</td>';
        echo '<td>' . $row['role'] . '</td>';
        echo '<td><button class="delete-user-btn" data-id="' . $row['user_id'] . '">Usuń</button></td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4">Brak użytkowników</td></tr>';
}

$conn->close();
?>
