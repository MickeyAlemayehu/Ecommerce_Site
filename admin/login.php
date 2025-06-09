<?php
session_start();
include 'header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hardcoded admin user
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['is_admin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $errors[] = "Invalid admin credentials.";
    }
}
?>

<div class="admin-auth-container">
    <h2>Admin Login</h2>

    <?php if ($errors): ?>
        <div class="admin-auth-errors">
            <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="login.php" class="admin-auth-form">
        <label>Username:
            <input type="text" name="username" required>
        </label>
        <label>Password:
            <input type="password" name="password" required>
        </label>
        <button type="submit">Login</button>
    </form>
</div>

