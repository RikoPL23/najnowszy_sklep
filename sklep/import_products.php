<?php
// Konfiguracja bazy danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users_db";

// Utworzenie połączenia
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Dane produktów
$products = [
    [
        "id" => 0,
        "name" => "Hantla 20kg",
        "description" => "Hantle stanowią jeden z ważniejszych elementów każdego treningu. Lekkie obciążenia 20 kg stosowane są zarówno przez osoby ćwiczące typowo siłowo, jak i przez biegaczy czy kolarzy, którzy urozmaicają swoje plany treningowe o trening funkcjonalny.",
        "category" => "hantle",
        "price" => 260,
        "image" => "./images/hantla20kg.jpg",
        "sale" => true,
        "saleAmount" => 200,
        "stock" => 10 // Przykładowa wartość stock
    ],
    // Dodaj pozostałe produkty...
];

// Przygotowanie zapytania SQL
$stmt = $conn->prepare("INSERT INTO products (id, name, description, category, price, image, sale, saleAmount, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssdsdii", $id, $name, $description, $category, $price, $image, $sale, $saleAmount, $stock);

// Wstawianie produktów do bazy danych
foreach ($products as $product) {
    $id = $product["id"];
    $name = $product["name"];
    $description = $product["description"];
    $category = $product["category"];
    $price = $product["price"];
    $image = $product["image"];
    $sale = $product["sale"];
    $saleAmount = isset($product["saleAmount"]) ? $product["saleAmount"] : null;
    $stock = $product["stock"]; // Dodajemy stock
    $stmt->execute();
}

echo "Produkty zostały zaimportowane do bazy danych.";

$stmt->close();
$conn->close();
?>
