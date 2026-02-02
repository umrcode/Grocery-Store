<?php
// include('../includes/connect.php'); // Connect file to MySQL - already did in common_function.php and including that file
include ('../functions/common_function.php'); // Common functions file

if (isset ($_GET['customer_id'])) {
    $cust_id = $_GET['customer_id'];
    $get_data = "SELECT * FROM customer WHERE customerID = $cust_id;";
    $result_get = mysqli_query($con, $get_data);
    $row_data = mysqli_fetch_assoc($result_get);
    $cust_id = $row_data["customerID"];
    $cust_fname = $row_data['first_name'];
    $cust_lname = $row_data['last_name'];
    // Check if last_name is null
    if ($cust_lname === null) {
        $cust_name = $cust_fname;
    } else {
        $cust_name = $cust_fname . ' ' . $cust_lname;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickCart</title>
    <!-- bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS file link -->
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
        }
        .navbar-top {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-top .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: white !important;
            letter-spacing: 1px;
        }
        .navbar-top .navbar-brand i {
            margin-right: 8px;
        }
        .navbar-top .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
            border-radius: 5px;
            padding: 8px 15px !important;
        }
        .navbar-top .nav-link:hover {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        .navbar-top .fa-shopping-cart {
            font-size: 18px;
        }
        .navbar-top sup {
            background-color: #ff6b6b;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
        }
        .navbar-bottom {
            background-color: #343a40;
            padding: 12px 0;
        }
        .navbar-bottom .nav-link {
            color: white;
            font-weight: 500;
        }
        .cart-dropdown-menu {
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border-radius: 10px;
            border: none;
        }
        .cart-dropdown-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            font-weight: 600;
        }
        .cart-dropdown-body {
            padding: 10px;
        }
        .cart-dropdown-footer {
            padding: 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
            border-radius: 0 0 10px 10px;
        }
        .btn-success {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3) !important;
        }
    </style>
</head>

<body>

    <!-- responsive navbar - container fluid is a bootstrap class which takes complete 100% width -->
    <div class="container-fluid p-0">
        <!-- first child - TOP NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-top">
            <div class="container-fluid px-4">
                <a class="navbar-brand" href="index.php?customer_id=<?php echo $cust_id; ?>">
                    <i class="fa fa-shopping-basket"></i> QuickCart
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href='profile_page.php?customer_id=<?php echo "$cust_id"; ?>'>
                                <i class="fas fa-user-circle"></i> My Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href='index.php?customer_id=<?php echo "$cust_id"; ?>'>
                                <i class="fas fa-home"></i> Products
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-shopping-cart"></i> Cart
                                <sup><?php cart_item($cust_id); ?></sup>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end cart-dropdown-menu" aria-labelledby="navbarDropdown">
                                <div class="cart-dropdown-header">
                                    <i class="fas fa-shopping-bag"></i> My Shopping Cart
                                </div>
                                <div class="cart-dropdown-body"
                                    style="max-height: 50vh; overflow-y: auto; width: 40vh;">
                                    <?php show_cart($cust_id); ?>
                                </div>
                                <div class="cart-dropdown-footer d-flex align-items-center justify-content-between">
                                    <?php
                                    $totalPrice = total_cart($cust_id); // Get the total cart price
                                    $walletBalance = wallet($cust_id); // Get the wallet balance
                                    $valid_order = check_stock($cust_id);
                                    $total_items = cart_total_item($cust_id);

                                    if ($totalPrice <= $walletBalance) {
                                        $next_page = "checkout.php?customer_id=" . $cust_id;
                                    } else {
                                        // Set the next page as an empty string
                                        $next_page = "";
                                    }
                                    ?>

                                    <a href="<?php echo $next_page; ?>" onclick="return confirmCheckout()"
                                        class="btn btn-success mt-2 mx-2">
                                        <i class="fas fa-credit-card"></i> Checkout
                                    </a>

                                    <script>
                                        // JavaScript function to confirm checkout
                                        function confirmCheckout() {
                                            // Check if the next page is empty (insufficient funds)
                                            if ("<?php echo $next_page; ?>" === "") {
                                                // Display an alert
                                                alert("Insufficient funds in wallet, cannot checkout! Please top up your wallet in the 'My Profile' section.");
                                                // Prevent the default action (following the link)
                                                return false;
                                            }
                                            if ("<?php echo $valid_order; ?>" != true) {
                                                // Display an alert
                                                alert("<?php echo "$valid_order is out of stock! Please reduce the quantity ordered or try again later."; ?>");
                                                // Prevent the default action (following the link)
                                                return false;
                                            }
                                            if ("<?php echo $total_items; ?>" == 0) {
                                                // Display an alert
                                                alert("<?php echo "You have no products added in the cart! Kindly add some products to proceed."; ?>");
                                                // Prevent the default action (following the link)
                                                return false;
                                            }
                                            // Proceed to the checkout page
                                            return true;
                                        }
                                    </script>

                                    <strong class="mx-4">PKR <?php echo total_cart($cust_id); ?></strong>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="d-flex ms-auto align-items-center gap-3">
                        <div class="wallet-display">
                            <i class="fas fa-wallet" style="color: #ffd700;"></i>
                            <strong class="text-white" style="margin-left: 5px;">Wallet: PKR <?php echo wallet($cust_id); ?></strong>
                        </div>
                        <form class="d-flex" role="search" action="search_bar.php" method="get" style="margin: 0;">
                            <input type="hidden" name="customer_id" value='<?php echo "$cust_id"; ?>'>
                            <input class="form-control form-control-sm me-2" type="search" placeholder="Search products..." 
                                aria-label="Search" name="search_bar" style="width: 200px;">
                            <input type="submit" value="Search" class="btn btn-outline-light btn-sm" name="search_data">
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- second child - BOTTOM NAVBAR -->
        <nav class="navbar navbar-bottom">
            <div class="container-fluid px-4">
                <span class="navbar-text text-light">
                    <i class="fas fa-user-circle"></i> Welcome, <strong><?php echo "$cust_name"; ?></strong>
                </span>
                <div class="ms-auto">
                    <a class="nav-link text-light d-inline" href="../start.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <?php
        add_to_Cart($cust_id);
        remove_cart($cust_id);
        updateCart($cust_id);
        ?>


        <!-- second child -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="">Welcome
                        <?php echo "$cust_name"; ?>
                    </a> <!-- will be changed to customer's name -->
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../start.php">Logout</a>
                </li>
            </ul>
        </nav>

        <!-- third child -->
        <div class="bg-light p-2 mt-2">
            <p class="text-center">Splash into Savings this Holi Season with QuickCart!!!</p>
        </div>

        <!-- fourth child -->

        <div class="row">
            <div class="col-md-10">
                <!-- products -->
                <div class="row">
                    <!-- fetching products from the database -->
                    <?php
                    display_products($cust_id);
                    display_cat_products($cust_id);
                    ?>
                </div>
            </div>
            <div class="col-md-2 bg-secondary p-0">
                <!-- sidenav - categories to be listed -->
                <ul class="navbar-nav me-auto text-center">
                    <li class="nav-item bg-info">
                        <a href="#" class="nav-link text-light">
                            <h4>Categories</h4>
                        </a>
                    </li>
                    <?php
                    display_categories($cust_id);
                    ?>
                </ul>
            </div>
        </div>




        <!-- last child - footer -->
        <?php
        include ("../includes/footer.php");
        ?>
    </div>




    <!-- bootstrap JS link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- JS file link -->
    <!-- <script src="script.js"></script> -->
</body>

</html>