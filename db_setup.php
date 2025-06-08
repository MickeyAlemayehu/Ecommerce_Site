<?php
// db_setup.php

$servername = "localhost:3307";
$username = "root";       // Change as needed
$password = "";           // Change as needed
$dbname = "clothing_store";

// Create connection to MySQL server (without selecting DB yet)
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Database created or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create users table
$sqlUsers = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if ($conn->query($sqlUsers) === TRUE) {
    echo "Table 'users' created or already exists.<br>";
} else {
    die("Error creating users table: " . $conn->error);
}

// Create products table
$sqlProducts = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if ($conn->query($sqlProducts) === TRUE) {
    echo "Table 'products' created or already exists.<br>";
} else {
    die("Error creating products table: " . $conn->error);
}

// Create orders table
$sqlOrders = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB";

if ($conn->query($sqlOrders) === TRUE) {
    echo "Table 'orders' created or already exists.<br>";
} else {
    die("Error creating orders table: " . $conn->error);
}

// Create order_items table
$sqlOrderItems = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB";

if ($conn->query($sqlOrderItems) === TRUE) {
    echo "Table 'order_items' created or already exists.<br>";
} else {
    die("Error creating order_items table: " . $conn->error);
}

$conn->close();

echo "<br>Database setup complete.";
?>
