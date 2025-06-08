<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

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

    echo "<p>Product added to cart.</p>";
}

// Handle remove item
if (isset($_GET['remove'])) {
    $removeId = intval($_GET['remove']);
    unset($_SESSION['cart'][$removeId]);
    echo "<p>Item removed from cart.</p>";
}
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
                <td>\$$price</td>
                <td>$qty</td>
                <td>\$$subtotal</td>
                <td><a href='cart.php?remove=$id'>Remove</a></td>
              </tr>";
    }

    echo "<tr><td colspan='3'><strong>Total</strong></td><td colspan='2'><strong>\$$total</strong></td></tr>";
    echo '</table>';
    echo '<br><a href="checkout.php"><button>Proceed to Checkout</button></a>';
}
?>

<?php include 'includes/footer.php'; ?>
