<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='products.php'>Shop now</a></p>";
    include 'includes/footer.php';
    exit;
}

// Check if user is logged in (assuming user ID is stored in session)
if (!isset($_SESSION['user_id'])) {
    echo "<p>Please <a href='login.php'>login</a> to checkout.</p>";
    include 'includes/footer.php';
    exit;
}

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $card = trim($_POST['card_number']);
    $cvv = trim($_POST['cvv']);
    $expiry = trim($_POST['expiry']);
    

    // Basic validation
    if ($name === '') $errors[] = "Name is required.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if ($address === '') $errors[] = "Address is required.";
    if (!ctype_digit($card) || strlen($card) !== 16 || !ctype_digit($cvv) || strlen($cvv) != 3) {
    $errors[] = "Payment Failed. Invalid card or CVV.";
    }
    $cart = $_SESSION['cart'];
    if (empty($cart)) {
        $errors[] = "Your cart is empty.";
    }

    if (empty($errors)) {
        // Calculate total price
        $total = 0;
        foreach ($cart as $product_id => $qty) {
            $result = $conn->query("SELECT price FROM products WHERE id = " . intval($product_id));
            if ($product = $result->fetch_assoc()) {
                $total += $product['price'] * $qty;
            } else {
                $errors[] = "Invalid product in cart.";
            }
        }
    }

    if (empty($errors)) {
        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total, status, customer_name, customer_email, customer_address) VALUES (?, ?, 'Paid', ?, ?, ?)");
        $stmt->bind_param("idsss", $user_id, $total, $name, $email, $address);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert order items
        $cart = $_SESSION['cart'];
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

        foreach ($cart as $product_id => $qty) {
            // Get product price from DB
            $result = $conn->query("SELECT price FROM products WHERE id = $product_id");
            $product = $result->fetch_assoc();
            $price = $product['price'];

            $stmt_item->bind_param("iiid", $order_id, $product_id, $qty, $price);
            $stmt_item->execute();
        }
        $stmt_item->close();

        // Clear cart
        unset($_SESSION['cart']);
        $success = true;
    }
}
?>

<h2>Checkout</h2>

<?php if ($success): ?>
    <p>Thank you for your order, <?= htmlspecialchars($name) ?>! Your order ID is <strong><?= $order_id ?></strong>.</p>
    <p><a href="index.php">Continue Shopping</a></p>
<?php else: ?>

    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="checkoutForm" method="post" action="checkout.php">
        <label>Name:<br>
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        </label><br><br>

        <label>Email:<br>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </label><br><br>

        <label>Address:<br>
            <textarea name="address" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
        </label><br><br>
        <label for="">Card Number: <br>
            <input type="text" name="card_number" placeholder="Card Number" required>
        </label> <br><br>
        <label for="">CVV: <br>
            <input type="text" name="cvv" placeholder="CVV" required>
        </label><br><br>
        <label for="">Expiry: <br>
            <input type="text" name="expiry" placeholder="MM/YY" required>
        </label>  <br><br>
        <button type="submit">Place Order</button>
    </form>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
