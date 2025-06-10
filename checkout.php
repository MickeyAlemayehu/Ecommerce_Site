<?php
include 'includes/auth.php';
include 'includes/db.php';
include 'includes/header.php';

require_login();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit;
}

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Get cart items
$cart_items = array();
$total = 0;

$ids = array_keys($_SESSION['cart']);
$ids_string = implode(',', $ids);
$sql = "SELECT * FROM products WHERE id IN ($ids_string)";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $row['quantity'] = $_SESSION['cart'][$row['id']];
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $total += $row['subtotal'];
    $cart_items[] = $row;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $card = trim($_POST['card_number']);
    $cvv = trim($_POST['cvv']);
    $expiry = trim($_POST['expiry']);

    // Basic validation
    /*if ($name === '') $errors[] = "Name is required.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if ($address === '') $errors[] = "Address is required.";
    if (!ctype_digit($card) || strlen($card) !== 16 || !ctype_digit($cvv) || strlen($cvv) != 3) {
        $errors[] = "Payment Failed. Invalid card or CVV.";
    }*/
    $cart = $_SESSION['cart'];
    if (empty($cart)) {
        $errors[] = "Your cart is empty.";
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

<div class="checkout-container">
    <h1 class="checkout-title">Checkout</h1>
    <div class="checkout-content">
        <div class="checkout-form">
            <?php if ($success): ?>
                <div class="form-section">
                    <h2>Order Placed!</h2>
                    <p>Thank you for your order, <?= htmlspecialchars($name) ?>!</p>
                    <a href="index.php" class="continue-shopping-btn">Continue Shopping</a>
                </div>
            <?php else: ?>
                <?php if (!empty($errors)): ?>
                    <div class="form-section" style="color: #b00020;">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="post" id="checkoutForm">
                    <div class="form-section">
                        <h2>Customer Information</h2>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="form-section">
                        <h2>Payment Details</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="card_number">Card Number</label>
                                <input type="text" id="card_number" name="card_number" maxlength="16" required>
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" maxlength="3" required>
                            </div>
                            <div class="form-group">
                                <label for="expiry">Expiry</label>
                                <input type="text" id="expiry" name="expiry" placeholder="MM/YY" maxlength="5" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="place-order-btn">Place Order</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="checkout-summary">
            <h2>Order Summary</h2>
            <div class="summary-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="summary-item">
                        <div class="item-info">
                            <div class="item-image">
                                <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>
                            <div class="item-details">
                                <h4><?= htmlspecialchars($item['name']) ?></h4>
                                <p>$<?= htmlspecialchars($item['price']) ?></p>
                            </div>
                        </div>
                        <div class="item-quantity">
                            x<?= $item['quantity'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="summary-totals">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>$<?= number_format($total, 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>Free</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>$<?= number_format($total, 2) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
