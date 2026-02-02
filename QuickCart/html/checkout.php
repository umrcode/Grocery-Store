<?php
include __DIR__ . '/../includes/connect.php';
session_start();
if (isset($_SESSION['customer_id'])) $cust_id = $_SESSION['customer_id']; elseif (isset($_GET['customer_id'])) { $_SESSION['customer_id'] = intval($_GET['customer_id']); $cust_id = $_SESSION['customer_id']; } else $cust_id = null;
if (!$cust_id) {
    // require login
    header('Location: ../customer_mode/customer_login.php'); exit;
}
// Process checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = mysqli_real_escape_string($con, trim($_POST['location']));
    $payment_method = mysqli_real_escape_string($con, trim($_POST['payment_method']));
    // validate stock
    include __DIR__ . '/../functions/common_function.php';
    $check = check_stock($cust_id);
    if ($check !== true) {
        $error = "$check is out of stock. Please adjust quantity.";
    } else {
        $total = total_cart($cust_id);
        // pick a random available agent
        $aq = "SELECT agentID FROM deliveryAgent WHERE availabilityStatus IN ('Available','Offline','Busy') ORDER BY RAND() LIMIT 1";
        $ar = mysqli_query($con, $aq);
        $agentID = 1; // fallback
        if ($ar && mysqli_num_rows($ar)) { $agentRow = mysqli_fetch_assoc($ar); $agentID = intval($agentRow['agentID']); }
        // insert order
        $insertOrder = "INSERT INTO `order` (status, total_price, location, agentPayment, payment_method, customerID, agentID) VALUES ('Confirmed', $total, '$location', 50, '$payment_method', $cust_id, $agentID)";
        if (mysqli_query($con, $insertOrder)) {
            $orderID = mysqli_insert_id($con);
            // get items from addsToCart
            $sel = "SELECT * FROM addstocart WHERE customerID = $cust_id";
            $res = mysqli_query($con, $sel);
            while ($row = mysqli_fetch_assoc($res)) {
                $prodID = intval($row['productID']); $qty = intval($row['quantity']);
                $ins = "INSERT INTO orderConsistsProduct (orderID, productID, quantity) VALUES ($orderID, $prodID, $qty)";
                mysqli_query($con, $ins);
                // decrement stock
                mysqli_query($con, "UPDATE product SET stock = stock - $qty WHERE productID = $prodID");
            }
            // clear customer's addstocart
            mysqli_query($con, "DELETE FROM addstocart WHERE customerID = $cust_id");
            header('Location: order_success.php?order_id=' . $orderID);
            exit;
        } else {
            $error = 'Could not create order: ' . mysqli_error($con);
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>QuickCart — Checkout</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include __DIR__ . '/inc/header.php'; ?>
  <main class="container mt-4">
    <h1>Checkout</h1>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <div id="cart-items">
      <?php include __DIR__ . '/../functions/common_function.php';
        $sel = "SELECT a.productID, a.quantity, p.name, p.price, p.prod_image FROM addstocart a JOIN product p ON a.productID=p.productID WHERE a.customerID=$cust_id";
        $res = mysqli_query($con,$sel);
        if (mysqli_num_rows($res) == 0) {
            echo "<p>Your cart is empty.</p>";
        } else {
            echo "<table class='table'><thead><tr><th>Item</th><th>Qty</th><th>Price</th></tr></thead><tbody>";
            while ($r = mysqli_fetch_assoc($res)) {
                echo "<tr><td>{$r['name']}</td><td>{$r['quantity']}</td><td>PKR " . number_format($r['price'] * $r['quantity'], 2) . "</td></tr>";
            }
            echo "</tbody></table>";
            echo "<h4>Total: PKR " . number_format(total_cart($cust_id), 2) . "</h4>";
        }
      ?>
    </div>
    <form class="row g-3 mt-3" method="post">
      <div class="col-md-6"><label class="form-label">Full name</label><input name="name" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control" required></div>
      <div class="col-12"><label class="form-label">Shipping address</label><textarea name="location" class="form-control" required></textarea></div>
      <div class="col-md-6"><label class="form-label">Payment method</label>
        <select name="payment_method" class="form-select">
          <option>Wallet</option>
          <option>Credit Card</option>
          <option>Cash on Delivery</option>
        </select>
      </div>
      <div class="col-12"><button class="btn btn-success" type="submit">Place order</button></div>
    </form>
  </main>
  <?php include __DIR__ . '/../includes/footer.php'; ?>
  <script src="assets/js/bootstrap-loader.js"></script>
  <script src="assets/js/site.js"></script>
</body>
</html>