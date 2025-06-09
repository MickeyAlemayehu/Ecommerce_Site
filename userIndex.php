<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Bogue Store</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="/js/scripts.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  
</head>
<body>
<header>
    <nav>
        <a href="index.php"><h1>Bogue</h1></a>
        <div>
        <a href="products.php">Products</a>
        <a href="blog.php">Blog</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="cart.php" class="cart-icon">
            <i class='bx bx-cart'></i>
            <span class="cart-count">0</span>
        </a>
        <a href="dashboard.php" style="color: white;"><i class="fa-solid fa-user fa-2xl" style="color: #a35c5c;"></i></a>
        <a href="logout.php">Logout</a>
        </div>
    </nav>
</header>
<main>


<section class="hero">
      <h2>Elevate your style with Us</h2>
      <p>We bring you a curated collection of stylish dresses, tops, suits, and more, designed for every occasion.</p>
  </section>

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

<section class="categories">
    <h2>Shop by Category</h2>
    <div class="category-grid">
      <a href="pants.php" class="category">
        <img src="images\t.jpg" alt="Trousers">
        <h3>Trousers & Pants</h3>
      </a>
      <a href="dresses.php" class="category">
        <img src="images\d.jpg" alt="Dresses">
        <h3>Dresses</h3>
      </a>
      <a href="shoes.php" class="category">
        <img src="images\s.jpg" alt="Shoes">
        <h3>Shoes</h3>
      </a>
      <a href="tops.php" class="category">
        <img src="images\bss.jpg" alt="Tops & Blouses">
        <h3>Tops & Blouses</h3>
      </a>
      <a href="sportswear.php" class="category">
        <img src="images\sw.jpg" alt="Sportswear">
        <h3>Sportswear</h3>
      </a>    
  </section>

  <section class="about" class="section-p1">
    <h2>About Us</h2>
    <div class="about-items">
      <div class="about-item">
        <img src="https://cdn-icons-png.flaticon.com/128/4753/4753367.png" alt="Free shipping icon" />
        <h3>Large Assortment</h3>
        <p>Different types of products with fewer variations</p>
      </div>
      <div class="about-item">
        <img src="https://cdn-icons-png.flaticon.com/128/7245/7245083.png" alt="Free shipping icon" />

        <h3>Fast & Free Shipping</h3>
        <p>4-day delivery time with expedited delivery options</p>
      </div>
      <div class="about-item">
        <img src="https://cdn-icons-png.flaticon.com/128/609/609437.png" alt="Free shipping icon" />
        <h3>24/7 Support</h3>
        <p>Business-related inquiries answered anytime</p>
      </div>
    </div>
  </section>


<?php include 'includes/footer.php'; ?>
