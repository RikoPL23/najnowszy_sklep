<?php
include 'config.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die('Access denied');
}

$message = '';
if (isset($_GET['message']) && $_GET['message'] == 'product_added') {
    $message = 'Produkt został dodany pomyślnie.';
}

// Fetch users and products for display
$users = [];
$products = [];

// Upewnijmy się, że kolumny w tabeli odpowiadają tym używanym w zapytaniach SQL
$userResult = $conn->query("SELECT user_id, username, role FROM users");
while ($userRow = $userResult->fetch_assoc()) {
    $users[] = $userRow;
}

$productResult = $conn->query("SELECT product_id, name, price FROM products");
while ($productRow = $productResult->fetch_assoc()) {
    $products[] = $productRow;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymownia Panel Administracyjny</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .admin-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .section {
            width: 80%;
            margin-bottom: 40px;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #483D8B;
        }

        .form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .form-input, .form-select, .form-button {
            width: 80%;
            max-width: 400px;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        .users-table, .products-table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th, .users-table td, .products-table th, .products-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .users-table th, .products-table th {
            background-color: #483D8B;
            color: white;
        }

        .auth-btn.delete-user-btn, .auth-btn.delete-product-btn {
            background-color: red;
            color: white;
        }

    </style>
</head>
<body>
    <header>
        <h1 class="header-title"><span>Gymownia</span> Panel Administracyjny</h1>
        <div class="auth-buttons">
            <button class="auth-btn">admin</button>
            <button class="auth-btn" onclick="window.location.href='logout.php'">Wyloguj</button>
        </div>
    </header>
    <main>
        <div class="admin-container">
            <div class="section" id="user-management">
                <h2>Zarządzanie Użytkownikami</h2>
                <form id="add-user-form" class="form">
                    <input type="text" id="username" name="username" class="form-input" placeholder="Nazwa użytkownika" required>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Hasło" required>
                    <select id="role" name="role" class="form-select form-input">
                        <option value="user">User</option>
                        <option value="moderator">Moderator</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit" class="form-button">Dodaj użytkownika</button>
                </form>
                <h2>Lista użytkowników</h2>
                <div class="table-container">
                    <table id="users-table" class="users-table">
                        <!-- Użytkownicy będą ładowani tutaj dynamicznie -->
                    </table>
                </div>
            </div>
            <div class="section" id="product-management">
                <h2>Zarządzanie Produktami</h2>
                <form id="add-product-form" class="form">
                    <input type="text" id="product_name" name="product_name" class="form-input" placeholder="Nazwa produktu" required>
                    <textarea id="product_description" name="product_description" class="form-input" placeholder="Opis produktu" required></textarea>
                    <input type="text" id="category" name="category" class="form-input" placeholder="Kategoria" required>
                    <input type="number" id="price" name="price" class="form-input" placeholder="Cena" required>
                    <input type="text" id="image_path" name="image_path" class="form-input" placeholder="Ścieżka do obrazka" required>
                    <label class="form-input">
                        <input type="checkbox" id="sale" name="sale"> Promocja
                    </label>
                    <input type="number" id="sale_amount" name="sale_amount" class="form-input" placeholder="Kwota promocji">
                    <input type="number" id="stock" name="stock" class="form-input" placeholder="Stan magazynowy" required>
                    <button type="submit" class="form-button">Dodaj produkt</button>
                </form>
                <h2>Lista produktów</h2>
                <div class="table-container">
                    <table id="products-table" class="products-table">
                        <!-- Produkty będą ładowane tutaj dynamicznie -->
                    </table>
                </div>
            </div>
        </div>
    </main>
    <div class="footer-bg">
        <p class="footer-text">Gymownia to najlepszy wybór dla każdego miłośnika fitnessu! Oferujemy szeroki wybór sprzętu do ćwiczeń, który spełnia najwyższe standardy jakości!</p>
    </div>
    <script>
        $(document).ready(function() {
            // Funkcja do załadowania listy użytkowników
            function loadUsers() {
                $.ajax({
                    url: 'fetch_users.php',
                    method: 'GET',
                    success: function(data) {
                        $('#users-table').html(data);
                    }
                });
            }

            // Funkcja do załadowania listy produktów
            function loadProducts() {
                $.ajax({
                    url: 'fetch_products.php',
                    method: 'GET',
                    success: function(data) {
                        $('#products-table').html(data);
                    }
                });
            }

            // Załaduj użytkowników i produkty po załadowaniu strony
            loadUsers();
            loadProducts();

            // Obsługa dodawania użytkownika
            $('#add-user-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'add_user.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        loadUsers(); // Odśwież listę użytkowników
                    }
                });
            });

            // Obsługa dodawania produktu
            $('#add-product-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'add_product.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        loadProducts(); // Odśwież listę produktów
                    }
                });
            });

            // Obsługa usuwania użytkownika
            $(document).on('click', '.delete-user-btn', function() {
                const userId = $(this).data('id');
                if (confirm('Czy na pewno chcesz usunąć tego użytkownika?')) {
                    $.ajax({
                        url: 'delete_user.php',
                        method: 'POST',
                        data: { user_id: userId },
                        success: function(response) {
                            alert(response);
                            loadUsers(); // Odśwież listę użytkowników
                        }
                    });
                }
            });

            // Obsługa usuwania produktu
            $(document).on('click', '.delete-product-btn', function() {
                const productId = $(this).data('id');
                if (confirm('Czy na pewno chcesz usunąć ten produkt?')) {
                    $.ajax({
                        url: 'delete_products.php',
                        method: 'POST',
                        data: { product_id: productId },
                        success: function(response) {
                            alert(response);
                            loadProducts(); // Odśwież listę produktów
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
        </section>
    </main>
    <script>
        function logout() {
            fetch('logout.php').then(response => {
                if (response.ok) {
                    window.location.href = 'index.html';
                }
            });
        }
    </script>
</body>
</html>
