<?php
session_start();
// Accept customer_id via GET and store in session for persistent actions
if (isset($_GET['customer_id'])) {
    $_SESSION['customer_id'] = intval($_GET['customer_id']);
}
$customer_id = $_SESSION['customer_id'] ?? null;
// Try to get cart count and wallet if logged in
$cart_count = 0;
$wallet = 0;
if ($customer_id) {
    include __DIR__ . '/../../includes/connect.php';
    $q = "SELECT COUNT(*) as cnt FROM addstocart WHERE customerID = '" . intval($customer_id) . "'";
    $r = mysqli_query($con, $q);
    if ($r) {
        $row = mysqli_fetch_assoc($r);
        $cart_count = intval($row['cnt']);
    }
    $wq = "SELECT balance FROM wallet WHERE customerID = '" . intval($customer_id) . "'";
    $wr = mysqli_query($con, $wq);
    if ($wr && mysqli_num_rows($wr)) {
        $wrow = mysqli_fetch_assoc($wr);
        $wallet = number_format($wrow['balance'], 2);
    }
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="index.php<?php echo $customer_id ? '?customer_id='.$customer_id : ''; ?>">QuickCart</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCollapse" aria-controls="navCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php<?php echo $customer_id ? '?customer_id='.$customer_id : ''; ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php<?php echo $customer_id ? '?customer_id='.$customer_id : ''; ?>">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php<?php echo $customer_id ? '?customer_id='.$customer_id : ''; ?>">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php<?php echo $customer_id ? '?customer_id='.$customer_id : ''; ?>">Contact</a></li>
      </ul>
      <div class="d-flex align-items-center">
        <?php if ($customer_id): ?>
          <div class="me-3">Wallet: <strong>PKR <?php echo $wallet; ?></strong></div>
        <?php endif; ?>
        <a class="btn btn-outline-primary me-2" href="cart.php<?php echo $customer_id ? '?customer_id='.$customer_id : ''; ?>">Cart <span class="badge bg-primary cart-count"><?php echo $cart_count; ?></span></a>
        <?php if ($customer_id): ?>
          <a class="btn btn-outline-secondary" href="../customer_mode/profile_page.php?customer_id=<?php echo $customer_id; ?>">My Account</a>
        <?php else: ?>
          <a class="btn btn-primary" href="../customer_mode/customer_login.php">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>