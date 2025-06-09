<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT username FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_query->bind_result($username);
$user_query->fetch();
$user_query->close();
$order_sql = "SELECT 
        orders.id AS id,
        products.name AS product_name,
        orders.total AS total,
        orders.status AS status,
        orders.customer_name AS customer_name,
        orders.customer_address AS customer_address,
        orders.created_at AS created_at
    FROM orders
    JOIN order_items ON orders.id = order_items.order_id
    JOIN products ON order_items.product_id = products.id
    WHERE orders.user_id = ?
    ORDER BY orders.created_at DESC
";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
    <h2>Welcome, <?php echo htmlspecialchars($username); ?></h2>
    <h3>Your Order History</h3>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>Amount</th>
            <th>Customer Name</th>
            <th>Customer Address</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td>$<?= number_format($row['total'], 2) ?></td>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= htmlspecialchars($row['customer_address']) ?></td>
            <td class="<?= strtolower($row['status']) ?>"><?= $row['status'] ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</html>
