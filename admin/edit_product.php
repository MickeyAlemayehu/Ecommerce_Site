<?php
include 'auth.php';
require_admin_login();
include '../includes/db.php';
include 'header.php';

if (!isset($_GET['id'])) {
    echo "<p>Product ID missing. <a href='dashboard.php'>Back to dashboard</a></p>";
    include '../includes/footer.php';
    exit;
}

$id = intval($_GET['id']);
$errors = [];

// Fetch existing product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "<p>Product not found. <a href='dashboard.php'>Back to dashboard</a></p>";
    include '../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);

    if ($name === '') $errors[] = "Product name is required.";
    if ($price <= 0) $errors[] = "Valid price is required.";

    $image_name = $product['image']; // default existing image

    // Handle new image upload if provided
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $allowed_types)) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }

        if (empty($errors)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_name = basename($_FILES['image']['name']);
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $image_name, $id);
        if ($stmt->execute()) {
            echo "<p>Product updated successfully. <a href='dashboard.php'>Back to dashboard</a></p>";
            $stmt->close();
            include '../includes/footer.php';
            exit;
        } else {
            $errors[] = "Error updating product.";
        }
        $stmt->close();
    }
}
?>

<h2>Edit Product</h2>

<?php if ($errors): ?>
    <div style="color:red;">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form d="productForm" method="post" action="edit_product.php?id=<?= $id ?>" enctype="multipart/form-data">
    <label>Name:<br>
        <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $product['name']) ?>" required>
    </label><br><br>

    <label>Description:<br>
        <textarea name="description"><?= htmlspecialchars($_POST['description'] ?? $product['description']) ?></textarea>
    </label><br><br>

    <label>Price:<br>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($_POST['price'] ?? $product['price']) ?>" required>
    </label><br><br>

    <label>Current Image:<br>
        <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" style="max-width:150px;">
    </label><br><br>

    <label>Change Image:<br>
        <input type="file" name="image" accept="image/*">
    </label><br><br>

    <button type="submit">Update Product</b
