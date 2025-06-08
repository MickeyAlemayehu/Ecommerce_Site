<?php
include 'auth.php';
require_admin_login();
include '../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$id = intval($_GET['id']);

// Optionally: delete product image file from uploads folder here

// Delete product record
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header('Location: dashboard.php');
exit;
