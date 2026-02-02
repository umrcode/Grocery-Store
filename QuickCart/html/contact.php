<?php session_start(); if (isset($_GET['customer_id'])) $_SESSION['customer_id'] = intval($_GET['customer_id']); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>QuickCart — Contact</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include __DIR__ . '/inc/header.php'; ?>

  <main class="container mt-4">
    <h1>Contact Us</h1>
    <form class="row g-3" action="contact.php" method="post">
      <div class="col-md-6">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>
      <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="col-12">
        <label for="message" class="form-label">Message</label>
        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-primary">Send Message</button>
      </div>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
      <div class="alert alert-success mt-3">Thank you, we received your message (demo).</div>
    <?php endif; ?>
  </main>

  <?php include __DIR__ . '/../includes/footer.php'; ?>
  <script src="assets/js/bootstrap-loader.js"></script>
  <script src="assets/js/site.js"></script>
</body>
</html>