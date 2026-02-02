<?php
include __DIR__ . '/../includes/connect.php';
session_start();
if (isset($_SESSION['customer_id'])) $cust_id = $_SESSION['customer_id']; elseif (isset($_GET['customer_id'])) { $_SESSION['customer_id'] = intval($_GET['customer_id']); $cust_id = $_SESSION['customer_id']; } else $cust_id = null;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;
if ($id) {
    $q = "SELECT * FROM product WHERE productID = $id";
    $r = mysqli_query($con, $q);
    if ($r && mysqli_num_rows($r)) $product = mysqli_fetch_assoc($r);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>QuickCart — Product</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include __DIR__ . '/inc/header.php'; ?>

  <main class="container mt-4">
    <a class="btn btn-link mb-2" href="products.php<?php echo $cust_id ? '?customer_id='.$cust_id : ''; ?>">← Back to products</a>
    <?php if (!$product): ?>
      <div class="alert alert-warning">Product not found.</div>
    <?php else: ?>
      <div class="row">
        <div class="col-md-5">
          <img src="../images/<?php echo htmlspecialchars($product['prod_image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="col-md-7">
          <h2><?php echo htmlspecialchars($product['name']); ?></h2>
          <p class="text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
          <h4>PKR <?php echo number_format($product['price'],2); ?></h4>
          <p>Stock: <?php echo intval($product['stock']); ?></p>
          <?php if ($cust_id): ?>
            <a href="index.php?add_to_cart=<?php echo $product['productID']; ?>&customer_id=<?php echo $cust_id; ?>" class="btn btn-primary">Add to cart</a>
          <?php else: ?>
            <button class="btn btn-primary" id="jsAddToCart">Add to cart</button>
            <small class="d-block mt-2 text-muted">Sign in to persist your cart to your account. Guest cart is stored locally.</small>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </main>

  <?php include __DIR__ . '/../includes/footer.php'; ?>
  <script src="assets/js/bootstrap-loader.js"></script>
  <script src="assets/js/site.js"></script>
  <script>
    // Local add-to-cart support for guests
    document.getElementById('jsAddToCart')?.addEventListener('click', function(){
      const item = { id: '<?php echo $product ? $product['productID'] : '';?>', title: '<?php echo $product ? addslashes($product['name']) : ''; ?>', price: <?php echo $product ? $product['price'] : 0; ?> };
      const cart = JSON.parse(localStorage.getItem('qc_cart')||'[]');
      const found = cart.find(i=>i.id==item.id);
      if(found) found.qty += 1; else cart.push({...item, qty:1});
      localStorage.setItem('qc_cart', JSON.stringify(cart)); updateCartBadge(); alert('Added to cart (guest)');
    });
  </script>
</body>
</html>