<?php
// include('../includes/connect.php'); // Connect file to MySQL - already did in common_function.php and including that file
include ('../functions/common_function.php'); // Common functions file
include ('busystatus_trigger.php');

if (isset ($_GET['customer_id'])) {
    $cust_id = $_GET['customer_id'];
    $get_data = "SELECT * FROM customer WHERE customerID = $cust_id;";
    $result_get = mysqli_query($con, $get_data);
    $row_data = mysqli_fetch_assoc($result_get);
    $cust_id = $row_data["customerID"];
    $cust_fname = $row_data['first_name'];
    $cust_lname = $row_data['last_name'];
    $street = $row_data['address_street'];
    $city = $row_data['address_city'];
    $state = $row_data['address_state'];
    $pincode = $row_data['pincode'];
    // Check if last_name is null
    if ($cust_lname === null) {
        $cust_name = $cust_fname;
    } else {
        $cust_name = $cust_fname . ' ' . $cust_lname;
    }
    $totalPrice = total_cart($cust_id);
    $total_items = cart_total_item($cust_id);
    $cust_address = $street . ', ' . $city . ', ' . $state . ', Pincode - ' . $pincode;
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
            background: #f8f9fa;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
            color: #667eea !important;
        }

        .nav-link {
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #764ba2 !important;
        }

        .checkout-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 30px;
            margin: 20px 0;
        }

        .checkout-header {
            color: #667eea;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .order-summary {
            background: #f0f2f5;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .order-line {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .order-line:last-child {
            border-bottom: none;
        }

        .order-label {
            font-weight: 600;
            color: #333;
        }

        .order-value {
            font-weight: 700;
            color: #667eea;
        }

        .btn-place-order {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 14px 40px;
            border-radius: 10px;
            font-weight: 700;
            margin-top: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .btn-place-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .alert-info {
            background: #e7f3ff;
            border: none;
            color: #0056b3;
            border-radius: 10px;
        }

        .alert-danger {
            background: #ffe7e7;
            border: none;
            color: #c33;
            border-radius: 10px;
        }

        .info-box {
            background: white;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .info-label {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 5px;
        }

        .info-value {
            color: #333;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <!-- responsive navbar - container fluid is a bootstrap class which takes complete 100% width -->
    <div class="container-fluid p-0">
        <!-- first child -->
        <nav class="navbar navbar-expand-lg bg-info">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><i class="fa fa-shopping-basket" aria-hidden="true"> QuickCart</i></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <!-- Here, it will redirect to profile page of the customer -->
                            <a class="nav-link active" aria-current="page"
                                href='profile_page.php?customer_id=<?php echo "$cust_id"; ?>'>My Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active"
                                href='index.php?customer_id=<?php echo "$cust_id"; ?>'>Products</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#">Reviews</a>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-shopping-cart"></i> My Cart </a>
                        </li> -->

                    </ul>
                </div>
            </div>
        </nav>


        <!-- second child -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link text-light" href="">Welcome
                        <?php echo "$cust_name"; ?>
                    </a> <!-- will be changed to customer's name -->
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="../start.php">Logout</a>
                </li>
            </ul>
        </nav>

        <!-- third child -->
        <div class="container my-3">
            <h2 class="text-center text-success mt-3">Order's details</h2>
            
            <?php 
            $walletBalance = wallet($cust_id);
            // Inform the user if wallet balance is insufficient, but allow other payment methods
            if ($totalPrice > $walletBalance) {
                echo "<div class='alert alert-info' role='alert'>
                    <strong><i class='fas fa-info-circle'></i> Note:</strong>
                    <span>Your wallet balance (Rs.$walletBalance) is less than the total order amount (Rs.$totalPrice). You can choose another payment method or top up your wallet.</span>
                </div>";
            }
            ?>
            
            <h4>Order Summary :</h4>
            <table class="table table-bordered mt-3">
                <thead class="table-info text-center text-white">
                    <tr>
                        <th>S.No.</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody class="table-secondary text-white text-center">
                    <?php
                    $get_cart = "SELECT * FROM addsToCart WHERE customerID = '$cust_id';";
                    $result_get = mysqli_query($con, $get_cart);
                    $number = 0;
                    while ($row_get = mysqli_fetch_assoc($result_get)) {
                        $prodID = $row_get["productID"];
                        $q = $row_get["quantity"];
                        $get_prod = "SELECT * FROM product WHERE productID = '$prodID';";
                        $result_prod = mysqli_query($con, $get_prod);
                        $row_prod = mysqli_fetch_assoc($result_prod);
                        $prod_name = $row_prod["name"];
                        $prod_price = $row_prod["price"];
                        $number++ ;
                        ?>
                        <tr>
                            <td>
                                <?php echo "$number"; ?>
                            </td>
                            <td>
                                <?php echo "$prod_name"; ?>
                            </td>
                            <td>
                                <?php echo "$q"; ?>
                            </td>
                            <td>
                                <?php echo "Rs. " . $prod_price; ?>
                            </td>
                        </tr>
                    <?php }

                    ?>
                </tbody>
            </table>

            <div class="order-summary">
                <div class="order-line">
                    <span class="order-label"><i class='fas fa-rupee-sign'></i> Grand Total</span>
                    <span class="order-value"><?php echo "Rs. " . $totalPrice; ?></span>
                </div>
            </div>

            <h4><i class='fas fa-map-marker-alt'></i> Delivery Address</h4>
            <div class="info-box">
                <p class="info-value mb-0"><?php echo $cust_address; ?></p>
            </div>

            <h4><i class='fas fa-credit-card'></i> Select Payment Method</h4>
            <div class="info-box">
                <form action="place_order.php?customer_id=<?php echo $cust_id; ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #667eea;">Choose your payment method:</label>
                        <div class="payment-options">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_wallet" value="Wallet" checked style="width: 20px; height: 20px; cursor: pointer;">
                                <label class="form-check-label" for="payment_wallet" style="cursor: pointer; font-weight: 500;">
                                    <i class='fas fa-wallet' style="color: #667eea;"></i> <strong>Wallet</strong>
                                    <small style="display: block; color: #666; margin-left: 25px;">Pay from your QuickCart wallet balance</small>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_credit" value="Credit Card" style="width: 20px; height: 20px; cursor: pointer;">
                                <label class="form-check-label" for="payment_credit" style="cursor: pointer; font-weight: 500;">
                                    <i class='fas fa-credit-card' style="color: #764ba2;"></i> <strong>Credit Card</strong>
                                    <small style="display: block; color: #666; margin-left: 25px;">Secure credit card payment</small>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_debit" value="Debit Card" style="width: 20px; height: 20px; cursor: pointer;">
                                <label class="form-check-label" for="payment_debit" style="cursor: pointer; font-weight: 500;">
                                    <i class='fas fa-credit-card' style="color: #ff6b6b;"></i> <strong>Debit Card</strong>
                                    <small style="display: block; color: #666; margin-left: 25px;">Secure debit card payment</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="Cash on Delivery" style="width: 20px; height: 20px; cursor: pointer;">
                                <label class="form-check-label" for="payment_cod" style="cursor: pointer; font-weight: 500;">
                                    <i class='fas fa-money-bill-wave' style="color: #28a745;"></i> <strong>Cash on Delivery</strong>
                                    <small style="display: block; color: #666; margin-left: 25px;">Pay when your order is delivered</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="confirm_order" class="btn-place-order">
                        <i class='fas fa-check-circle'></i> Confirm and Place Order
                    </button>
                </form>
                <a href='index.php?customer_id=<?php echo "$cust_id"; ?>' class="btn btn-secondary btn-lg w-100 mt-2"><i class='fas fa-arrow-left'></i> Go Back</a>
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