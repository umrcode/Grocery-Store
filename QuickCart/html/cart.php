<?php
include __DIR__ . '/../includes/connect.php';
session_start();
if (isset($_SESSION['customer_id'])) $cust_id = $_SESSION['customer_id']; elseif (isset($_GET['customer_id'])) { $_SESSION['customer_id'] = intval($_GET['customer_id']); $cust_id = $_SESSION['customer_id']; } else $cust_id = null;
// If logged in, show DB based cart, else show guest cart (localStorage will populate)
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>QuickCart — Cart</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include __DIR__ . '/inc/header.php'; ?>
  <main class="container mt-4">
    <h1>Your Cart</h1>
    <div id="cart-items">
      <?php if ($cust_id):
        // Use existing DB-based cart rendering
        include __DIR__ . '/../functions/common_function.php';
        show_cart($cust_id);
      else: ?>
        <p>Your cart (guest):</p>
        <div id="guest-cart" class="mt-3"></div>
        <p class="text-muted">Sign in to save your cart and proceed to checkout.</p>
      <?php endif; ?>
    </div>
    <?php if ($cust_id): ?>
      <a href="checkout.php?customer_id=<?php echo $cust_id; ?>" class="btn btn-success mt-3">Proceed to Checkout</a>
    <?php else: ?>
      <a href="../customer_mode/customer_login.php" class="btn btn-primary mt-3">Login to Checkout</a>
    <?php endif; ?>
  </main>

  <?php include __DIR__ . '/../includes/footer.php'; ?>
  <script src="assets/js/bootstrap-loader.js"></script>
  <script src="assets/js/site.js"></script>
  <script>
    // Render guest cart from localStorage
    (function(){
      const target = document.getElementById('guest-cart');
      if(!target) return;
      const items = JSON.parse(localStorage.getItem('qc_cart')||'[]');
      if(!items.length){ target.innerHTML = '<p>Your cart is empty.</p>'; return; }
      target.innerHTML = '<ul class="list-group">' + items.map(i=>`<li class="list-group-item d-flex justify-content-between align-items-center">${i.title} <span>Qty: ${i.qty} — PKR ${(i.price*i.qty).toFixed(2)}</span></li>`).join('') + '</ul>';
    })();
  </script>
</body>
</html>