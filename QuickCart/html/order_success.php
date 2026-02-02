<?php
include __DIR__ . '/../includes/connect.php';
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order = null;
if ($order_id) {
    $q = "SELECT * FROM `order` WHERE orderID = $order_id";
    $r = mysqli_query($con, $q);
    if ($r && mysqli_num_rows($r)) $order = mysqli_fetch_assoc($r);
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><title>Order placed</title>
<link rel="stylesheet" href="assets/css/bootstrap.css">
<link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include __DIR__ . '/inc/header.php'; ?>
<main class="container mt-4">
  <?php if (!$order): ?>
    <div class="alert alert-warning">Order not found.</div>
  <?php else: ?>
    <h1>Thank you! Your order has been placed.</h1>
    <p>Order ID: <strong>#<?php echo $order['orderID']; ?></strong></p>
    <p>Total: <strong>PKR <?php echo number_format($order['total_price'],2); ?></strong></p>
    <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
    <a href="index.php" class="btn btn-primary">Continue shopping</a>
  <?php endif; ?>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>