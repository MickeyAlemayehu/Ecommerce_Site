<?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

<h2>Featured Products</h2>

<div class="product-grid">
    <?php
    $sql = "SELECT * FROM products LIMIT 4";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
        while($row = $result->fetch_assoc()):
    ?>
    <div class="product-card">
        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        <h3><?= htmlspecialchars($row['name']) ?></h3>
        <p>$<?= htmlspecialchars($row['price']) ?></p>
        <a href="product.php?id=<?= $row['id'] ?>">View Details</a>
    </div>
    <?php endwhile; else: ?>
    <p>No products found.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
