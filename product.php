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

<h2><?= htmlspecialchars($product['name']) ?></h2>

<div class="product-detail">
    <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="300">
    <p><strong>Price:</strong> $<?= htmlspecialchars($product['price']) ?></p>
    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

    <form method="post" action="cart.php">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="number" name="quantity" value="1" min="1">
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
