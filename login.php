<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = "Username and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Login success
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                header('Location: index.php');
                exit;
            } else {
                $errors[] = "Invalid username or password.";
            }
        } else {
            $errors[] = "Invalid username or password.";
        }
        $stmt->close();
    }
}
?>

<h2>Login</h2>

<?php if ($errors): ?>
    <div style="color:red;">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form id="loginForm" method="post" action="login.php">
    <label>Username:<br>
        <input type="text" name="username" required>
    </label><br><br>

    <label>Password:<br>
        <input type="password" name="password" required>
    </label><br><br>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a>.</p>

<?php include 'includes/footer.php'; ?>
