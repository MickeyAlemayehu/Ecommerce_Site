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

<div style="text-align: center;">
    <div style="display: inline-block; width: 300px; padding: 30px; vertical-align: top;">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
    </div>
    <div class="product-detail" style="display: inline-block; padding: 20px;">
        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="300">
        <p><strong>Price:</strong> $<?= htmlspecialchars($product['price']) ?></p>
    </div>
    <div style="display: inline-block; text-align: center; vertical-align: top; width: 250px; height: 100px; padding: 20px;">
        <section class="product">
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" action="cart.php">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                    Quantity: <br>
                    <input type="number" name="quantity" value="1" min="1" style="border-radius: 4px; height: 30px; text-align: center;"><br>
                    <button id="addCart" type="submit" name="add_to_cart">Add to Cart</button><br><br>
                </form>
            <?php else: ?>
                <p><strong>Please <a href="login.php?error=login_required&redirect=product.php?id=<?= $product['id'] ?>">log in</a> to add to cart.</strong></p>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
