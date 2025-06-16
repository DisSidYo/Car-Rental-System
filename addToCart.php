<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vin'])) {
    $vin = $_POST['vin'];

    // Initialize the cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add VIN to cart (avoid duplicates)
    if (!in_array($vin, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $vin;
        echo json_encode(['status' => 'success', 'message' => 'Car added to cart']);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'Car already in cart']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
