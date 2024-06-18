<?php
include 'config.php';
session_start();

$message = '';

if (isset($_GET['message']) && $_GET['message'] == 'success') {
    $message = 'Rejestracja udana. Możesz się teraz zalogować.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password, $role);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        if ($role == 'admin') {
            header("Location: admin_panel.php");
        } else {
            header("Location: user_panel.php");
        }
        exit();
    } else {
        echo "Invalid username or password";
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
    <title>Gymownia | Logowanie</title>
</head>
<body>
    <header>
        <h1 class="header-title">
            <span>Logowanie</span> 
        </h1>
    <div class="auth-buttons">
        <button class="auth-btn" onclick="window.location.href='cart.html'">Koszyk</button>
        <button class="auth-btn" onclick="window.location.href='register.php'">Zarejestruj</button>
        <button class="auth-btn" onclick="window.location.href='index.html'">Strona Główna</button>
        <button class="auth-btn" onclick="window.location.href='contact.html'">Kontakt</button>     
    </div>
    </header>
        <section class="auth-form">
        <?php if ($message): ?>
            <p class="success-message"><?php echo $message; ?></p>
        <?php endif; ?>
        <h1 class="auth-title">Wprowadź swoje dane</h1>
            <form class="form" method="post" action="login.php">
                <input type="text" name="username" placeholder="Nazwa użytkownika" class="form-input" required>
                <input type="password" name="password" placeholder="Hasło" class="form-input" required>
                <button type="submit" class="form-button">Zaloguj</button>
            </form>
        </section>
    </main>
</body>
</html>
