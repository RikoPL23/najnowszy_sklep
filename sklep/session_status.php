<?php
session_start();

$response = [
    'loggedin' => false
];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $response['loggedin'] = true;
    $response['username'] = $_SESSION['username'];
}

echo json_encode($response);
?>
