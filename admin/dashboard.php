<?php
include 'auth.php';
require_admin_login();
include '../includes/db.php';
include 'header.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<h2>Admin Dashboard - Products</h2>

<a href="add_product.php">Add New Product</a>
<table border="1" cellpadding="10" style="margin-top:20px;">
    <tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>$<?= $row['price'] ?></td>
            <td>
                <a href="edit_product.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this product?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="logout.php" style="margin-top:20px; display:inline-block;">Logout</a>

<?php include '../includes/footer.php'; ?>
