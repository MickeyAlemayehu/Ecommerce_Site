<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart
if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['image'])) {
    $_SESSION['cart'][] = [
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'image' => $_POST['image']
    ];
    
    // Return the new cart count
    echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
} else {
    echo json_encode(['success' => false]);
}
?> 