<?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

<h2>Sports Wear</h2>

<div class="product-grid">
    <?php
    $sql = "SELECT * FROM products where category = 'Sportswear'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
    <div class="product-card">
        <a href="product.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: black" ><img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        <h3><?= htmlspecialchars($row['name']) ?></h3>
        <p>$<?= htmlspecialchars($row['price']) ?></p></a>
        <a href="product.php?id=<?= $row['id'] ?>">View Details</a>
    </div>
    <?php endwhile; else: ?>
    <p>No products found.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>