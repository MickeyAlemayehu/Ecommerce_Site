<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($username === '') $errors[] = "Username is required.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        // Check if username or email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email already taken.";
        } else {
            // Insert new user with hashed password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $hashed_password);
            if ($insert->execute()) {
                $success = true;
            } else {
                $errors[] = "Error creating account.";
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>

<h2>Register</h2>

<?php if ($success): ?>
    <p>Registration successful! <a href="login.php">Login here</a>.</p>
<?php else: ?>
    <?php if ($errors): ?>
        <div style="color:red;">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="registerForm" method="post" action="register.php">
        <label>Username:<br>
            <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        </label><br><br>

        <label>Email:<br>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </label><br><br>

        <label>Password:<br>
            <input type="password" name="password" required>
        </label><br><br>

        <label>Confirm Password:<br>
            <input type="password" name="confirm_password" required>
        </label><br><br>

        <button type="submit">Register</button>
    </form>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
