<?php
include 'includes/db.php';
include 'includes/header.php';

// Get product ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Invalid product ID.</p>";
    include 'includes/footer.php';
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<p>Product not found.</p>";
    include 'includes/footer.php';
    exit;
}

$product = $result->fetch_assoc();
?>

<div class="product-container">
    <div class="product-image-section">
        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
    </div>
    
    <div class="product-details">
        <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
        <div class="product-price">$<?= htmlspecialchars($product['price']) ?></div>
        <div class="product-description">
            <?= nl2br(htmlspecialchars($product['description'])) ?>
        </div>
        
        <div class="product-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" action="cart.php" class="add-to-cart-form">
                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="10">
                    </div>
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                    <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                        <i class='bx bx-cart-add'></i> Add to Cart
                    </button>
                </form>
            <?php else: ?>
                <div class="login-prompt">
                    <p>Please <a href="login.php?error=login_required&redirect=product.php?id=<?= $product['id'] ?>">log in</a> to add to cart.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
