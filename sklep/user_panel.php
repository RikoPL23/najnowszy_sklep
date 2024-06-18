<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="./images/favicon.png" />
    <link rel="stylesheet" href="style.css" />
    <title>Gymownia | Panel użytkownika</title>
</head>
<body>
    <header>
        <h1 class="header-title">
            <span>Gymownia</span>
        </h1>
        <div class="search-bar">
            <input
                type="text"
                class="search-bar-input"
                placeholder="Szukaj w sklepie"
                autocomplete="off"
            />
        </div>
        <div class="header-basket" onclick="toggleCart()">
            <button class="basket-clear-btn">x</button>
            <span class="basket-amount" id="basket-amount">Koszyk (<span id="cart-count">0</span>)</span>
        </div>
        <div class="auth-buttons" id="auth-buttons" style="display: none;">
            <button class="auth-btn" onclick="window.location.href='cart.html'">Koszyk</button>
            <button class="auth-btn" onclick="window.location.href='register.php'">Zarejestruj</button>
            <button class="auth-btn" onclick="window.location.href='login.php'">Zaloguj</button>
            <button class="auth-btn" onclick="window.location.href='contact.html'">Kontakt</button>
        </div>
        <div class="auth-user" id="auth-user">
        <button class="auth-btn" onclick="window.location.href='cart.html'">Koszyk</button>
        <button class="auth-btn" onclick="window.location.href='contact.html'">Kontakt</button>
            <span id="username">Witaj<?php  echo $_SESSION['username']; ?></span>
            <button class="auth-btn" onclick="logout()">Wyloguj</button>
        </div>
    </header>
    <main class="container">
        <aside class="categories">
            <h1 class="categories-title">Kategorie</h1>
            <section class="categories-items"></section>
        </aside>
        <section class="products"></section>
        <p class="empty-state">Nie znaleziono żadnego produktu...</p>
    </main>
    <div class="footer-bg">
        <p class="footer-text">Gymownia to najlepszy wybór dla każdego miłośnika fitnessu! Oferujemy szeroki wybór sprzętu do ćwiczeń, który spełnia najwyższe standardy jakości! </p>
    </div>
    <div id="cart" class="cart" style="display: none;">
        <h2>Twój koszyk</h2>
        <ul id="cart-items"></ul>
        <button class="form-button" onclick="checkout()">Przejdź do kasy</button>
    </div>
    <form id="orderForm" action="order.php" method="post" style="display: none;">
        <input type="hidden" name="orderData" id="orderData" />
    </form>
    <script src="products.js"></script>
    <script src="main.js"></script>
</body>
</html>

<script>
    let cart = [];

    document.addEventListener("DOMContentLoaded", () => {
        fetch("session_status.php")
            .then(response => response.json())
            .then(data => {
                if (data.loggedin) {
                    document.getElementById("auth-buttons").style.display = "none";
                    document.getElementById("auth-user").style.display = "flex";
                    document.getElementById("username").innerText = data.username;
                }
            });

        fetch('products.php')
            .then(response => response.json())
            .then(data => {
                const productList = document.querySelector('.products');
                data.products.forEach(product => {
                    const productDiv = document.createElement('div');
                    productDiv.classList.add('product-item');
                    productDiv.innerHTML = `
                        <img src="images/${product.image}" alt="${product.name}">
                        <h3>${product.name}</h3>
                        <p>Cena: ${product.price} PLN</p>
                        <button onclick="addToCart(${product.id}, '${product.name}', ${product.price})">Dodaj do koszyka</button>
                    `;
                    productList.appendChild(productDiv);
                });
            });
    });

    function addToCart(id, name, price) {
        const item = cart.find(product => product.id === id);
        if (item) {
            item.quantity += 1;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        updateCart();
    }

    function updateCart() {
        const cartCount = document.getElementById('cart-count');
        const cartItems = document.getElementById('cart-items');
        cartCount.innerText = cart.reduce((acc, item) => acc + item.quantity, 0);

        cartItems.innerHTML = '';
        cart.forEach(item => {
            const li = document.createElement('li');
            li.innerHTML = `${item.name} - ${item.price} PLN (x${item.quantity}) <button onclick="removeFromCart(${item.id})">Usuń</button>`;
            cartItems.appendChild(li);
        });
    }

    function removeFromCart(id) {
        const itemIndex = cart.findIndex(product => product.id === id);
        if (itemIndex !== -1) {
            cart.splice(itemIndex, 1);
        }
        updateCart();
    }

    function toggleCart() {
        const cartDiv = document.getElementById('cart');
        if (cartDiv.style.display === 'none') {
            cartDiv.style.display = 'block';
        } else {
            cartDiv.style.display = 'none';
        }
    }

    function logout() {
        fetch('logout.php')
            .then(response => {
                if (response.ok) {
                    window.location.href = 'index.html';
                }
            });
    }

    function checkout() {
        const orderData = {
            items: cart
        };
        document.getElementById('orderData').value = JSON.stringify(orderData);
        document.getElementById('orderForm').submit();
    }
</script>