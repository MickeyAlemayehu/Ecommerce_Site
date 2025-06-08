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

<h2>Admin Login</h2>

<?php if ($errors): ?>
    <div style="color:red;">
        <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
    </div>
<?php endif; ?>

<form method="post" action="login.php">
    <label>Username:<br><input type="text" name="username" required></label><br><br>
    <label>Password:<br><input type="password" name="password" required></label><br><br>
    <button type="submit">Login</button>
</form>

<?php include '../includes/footer.php'; ?>
