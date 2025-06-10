<?php
session_start();
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bogue Store</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <script src="js/script.js" defer></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header>
    <nav>
        <a href="index.php"><h1>Bogue</h1></a>
        <div>
            <a href="products.php">Products</a>
            <a href="blog.php">Blog</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="cart.php" class="cart-icon">
                    <i class='bx bx-cart'></i>
                    <span class="cart-count"><?= $cartCount ?></span>
                </a>
                <a href="dashboard.php" style="color: white;"><i class="fa-solid fa-user fa-2xl" style="color: #574747;"></i></a>
                <button id="loginButton"><a href="logout.php" style="color: white;">Logout</a></button>
            <?php else: ?>
                <a href="login.php" class="cart-icon">
                    <i class='bx bx-cart'></i>
                    <span class="cart-count"><?= $cartCount ?></span>
                </a>
                <button id="loginButton"><a href="login.php" style="color: white;">Login</a></button>
            <?php endif; ?>
        </div>
    </nav>
</header>

