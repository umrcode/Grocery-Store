<?php
// Public index - shows products (uses existing functions where possible)
include __DIR__ . '/../functions/common_function.php';
session_start();
if (isset($_SESSION['customer_id'])) $cust_id = $_SESSION['customer_id']; elseif (isset($_GET['customer_id'])) { $_SESSION['customer_id'] = intval($_GET['customer_id']); $cust_id = $_SESSION['customer_id']; } else $cust_id = null;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>QuickCart — Home</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include __DIR__ . '/inc/header.php'; ?>
  <main class="container mt-4">
    <div class="p-4 bg-white rounded shadow-sm">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h1>Welcome to QuickCart</h1>
          <p class="lead">Fast and easy online shopping — demo store.</p>
          <a class="btn btn-primary" href="products.php<?php echo $cust_id ? '?customer_id='.$cust_id : ''; ?>">Browse Products</a>
        </div>
        <div class="col-md-6 text-center">
          <img src="assets/img/headphones.jpg" alt="Sample" class="img-fluid" style="max-height:240px;">
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <?php
      // Use existing display functions from common_function.php (they will echo correct HTML)
      display_products($cust_id);
      display_cat_products($cust_id);
      ?>
    </div>
  </main>
  <?php include __DIR__ . '/../includes/footer.php'; ?>
  <script src="assets/js/bootstrap-loader.js"></script>
  <script src="assets/js/site.js"></script>
</body>
</html>