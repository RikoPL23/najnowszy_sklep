<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Użytkownikami</title>
</head>
<body>
    <h1>Dodaj Użytkownika</h1>
    <form action="add_user.php" method="post">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Dodaj">
    </form>

    <h1>Usuń Użytkownika</h1>
    <form action="delete_user.php" method="post">
        <label for="user_id">ID Użytkownika:</label>
        <input type="text" id="user_id" name="user_id" required><br><br>
        <input type="submit" value="Usuń">
    </form>
</body>
</html>
