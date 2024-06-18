<?php
include 'config.php';
session_start();

$message = '';

if (isset($_GET['message']) && $_GET['message'] == 'success') {
    $message = 'Rejestracja udana. Możesz się teraz zalogować.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        header("Location: login.php?message=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./images/favicon.png" />
    <link rel="stylesheet" href="style.css">
    <title>Gymownia | Rejestracja</title>
</head>
<body>
    <header>
        <h1 class="header-title">
            <span>Rejestracja</span> 
        </h1>
        <div class="auth-buttons">
            <button class="auth-btn" onclick="window.location.href='cart.html'">Koszyk</button>
            <button class="auth-btn" onclick="window.location.href='index.html'">Strona Główna</button>
            <button class="auth-btn" onclick="window.location.href='login.html'">Zaloguj</button>
            <button class="auth-btn" onclick="window.location.href='contact.html'">Kontakt</button>
        </div>
    </header>
    <main class="container">
        <section class="auth-form">
        <h1 class="auth-title">Wprowadź swoje dane</h1>
            <form class="form" method="post" action="register.php">
                <input type="text" name="username" placeholder="Nazwa użytkownika" class="form-input" required>
                <input type="password" name="password" placeholder="Hasło" class="form-input" required>
                <button type="submit" class="form-button">Zarejestruj</button>
            </form>
        </section>
    </main>
</body>
</html>

