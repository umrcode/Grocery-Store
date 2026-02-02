<?php
include __DIR__ . '/../functions/common_function.php';
session_start();
if (isset($_SESSION['customer_id'])) $cust_id = $_SESSION['customer_id']; elseif (isset($_GET['customer_id'])) { $_SESSION['customer_id'] = intval($_GET['customer_id']); $cust_id = $_SESSION['customer_id']; } else $cust_id = null;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>QuickCart — Products</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include __DIR__ . '/inc/header.php'; ?>

  <main class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="mb-0">Products</h1>
      <a href="cart.php<?php echo $cust_id ? '?customer_id='.$cust_id : ''; ?>" class="btn btn-outline-primary">Cart <span class="badge bg-primary cart-count"><?php echo $cust_id ? cart_total_item($cust_id) : 0; ?></span></a>
    </div>
    <div class="row">
      <?php
        display_products($cust_id);
      ?>
    </div>
  </main>

  <?php include __DIR__ . '/../includes/footer.php'; ?>
  <script src="assets/js/bootstrap-loader.js"></script>
  <script src="assets/js/site.js"></script>
</body>
</html>