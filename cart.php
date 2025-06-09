<?php

include 'includes/db.php';
include 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit;
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle "Add to Cart"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $id = intval($_POST['product_id']);
    $qty = max(1, intval($_POST['quantity']));

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }

    // Redirect to avoid form resubmission
    header("Location: cart.php?added=1");
    exit;
}

// Handle remove item
if (isset($_GET['remove'])) {
    $removeId = intval($_GET['remove']);
    unset($_SESSION['cart'][$removeId]);
    header("Location: cart.php?removed=1");
    exit;
}

// Count items in cart
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

<?php
if (isset($_GET['added'])) {
    echo "<p style='color: green;'>✅ Product added to cart!</p>";
} elseif (isset($_GET['removed'])) {
    echo "<p style='color: orange;'>🗑️ Item removed from cart.</p>";
}

// Display cart contents
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);

    $total = 0;

    echo '<table border="1" cellpadding="10">';
    echo '<tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Action</th></tr>';

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $name = htmlspecialchars($row['name']);
        $price = $row['price'];
        $qty = $_SESSION['cart'][$id];
        $subtotal = $price * $qty;
        $total += $subtotal;

        echo "<tr>
                <td>$name</td>
                <td>\$" . number_format($price, 2) . "</td>
                <td>$qty</td>
                <td>\$" . number_format($subtotal, 2) . "</td>
                <td><a href='cart.php?remove=$id'>Remove</a></td>
              </tr>";
    }

    echo "<tr><td colspan='3'><strong>Total</strong></td>
              <td colspan='2'><strong>\$" . number_format($total, 2) . "</strong></td></tr>";
    echo '</table>';

    echo '<br><a href="checkout.php"><button>Proceed to Checkout</button></a>';
}
?>

<?php include 'includes/footer.php'; ?>
