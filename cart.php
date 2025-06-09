<?php

include 'includes/auth.php';
include 'includes/db.php';
include 'includes/header.php';

require_login();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Process POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        
        // Add to cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    } elseif (isset($_POST['update_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    } elseif (isset($_POST['remove_item'])) {
        $product_id = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
    }
    
    // Redirect to prevent form resubmission
    header('Location: cart.php');
    exit;
}

// Get cart items
$cart_items = array();
$total = 0;

if (!empty($_SESSION['cart'])) {
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
}
?>

<div class="cart-container">
    <h1 class="cart-title">Shopping Cart</h1>
    
    <?php if (empty($cart_items)): ?>
        <div class="empty-cart">
            <i class='bx bx-cart'></i>
            <p>Your cart is empty</p>
            <a href="index.php" class="continue-shopping-btn">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-content">
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        </div>
                        <div class="item-details">
                            <h3 class="item-name"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="item-price">$<?= htmlspecialchars($item['price']) ?></p>
                        </div>
                        <div class="item-quantity">
                            <form method="post" class="quantity-form">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn minus">-</button>
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="10" class="quantity-input">
                                    <button type="button" class="quantity-btn plus">+</button>
                                </div>
                                <button type="submit" name="update_cart" class="update-btn">Update</button>
                            </form>
                        </div>
                        <div class="item-subtotal">
                            $<?= number_format($item['subtotal'], 2) ?>
                        </div>
                        <form method="post" class="remove-form">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" name="remove_item" class="remove-btn">
                                <i class='bx bx-trash'></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="cart-summary">
                <h2>Order Summary</h2>
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
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                <a href="index.php" class="continue-shopping-btn">Continue Shopping</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const minusButtons = document.querySelectorAll('.minus');
    const plusButtons = document.querySelectorAll('.plus');
    
    minusButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const input = quantityInputs[index];
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });
    
    plusButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const input = quantityInputs[index];
            if (input.value < 10) {
                input.value = parseInt(input.value) + 1;
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
