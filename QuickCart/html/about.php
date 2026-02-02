<?php session_start(); if (isset($_GET['customer_id'])) $_SESSION['customer_id'] = intval($_GET['customer_id']); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>QuickCart — About</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include __DIR__ . '/inc/header.php'; ?>

  <main class="container mt-4">
    <h1>About QuickCart</h1>
    <p>QuickCart is a demo online retail project created for demonstration purposes. This site demonstrates a small e-commerce flow including products, cart, checkout and a basic administration backend included in the repository.</p>
  </main>

  <?php include __DIR__ . '/../includes/footer.php'; ?>
  <script src="assets/js/bootstrap-loader.js"></script>
  <script src="assets/js/site.js"></script>
</body>
</html>