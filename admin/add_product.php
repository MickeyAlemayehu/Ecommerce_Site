<?php
include 'auth.php';
require_admin_login();
include '../includes/db.php';
include 'header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    
    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($_FILES['image']['name']);
        
        // Check file type
        $allowed_types = ['jpg','jpeg','png','gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
        
        if (empty($errors)) {
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $errors[] = "Failed to upload image.";
            }
        }
    } else {
        $errors[] = "Product image is required.";
    }

    if ($name === '') $errors[] = "Product name is required.";
    if ($price <= 0) $errors[] = "Valid price is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $image_name = basename($_FILES['image']['name']);
        $stmt->bind_param("ssds", $name, $description, $price, $image_name);
        if ($stmt->execute()) {
            echo "<p>Product added successfully. <a href='dashboard.php'>Back to dashboard</a></p>";
        } else {
            $errors[] = "Error adding product.";
        }
        $stmt->close();
    }
}
?>

<h2>Add New Product</h2>

<?php if ($errors): ?>
    <div style="color:red;">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form d="productForm" method="post" action="add_product.php" enctype="multipart/form-data">
    <label>Name:<br><input type="text" name="name" required></label><br><br>
    <label>Description:<br><textarea name="description"></textarea></label><br><br>
    <label>Price:<br><input type="number" step="0.01" name="price" required></label><br><br>
    <label>Image:<br><input type="file" name="image" accept="image/*" required></label><br><br>
    <button type="submit">Add Product</button>
</form>

<?php include '../includes/footer.php'; ?>
