<?php
// include('../includes/connect.php'); // Connect file to MySQL - already did in common_function.php and including that file
include ("../functions/common_function.php"); // Common functions file

// Ensure admin id and display name have safe defaults
$admin_id = isset($_GET['admin_id']) ? $_GET['admin_id'] : '';
$name = 'Admin';
if ($admin_id == 1) {
    $name = "Ali Hassan";
} elseif ($admin_id == 2) {
    $name = "Faizan Ali";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Mode</title>
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 15px 0;
        }

        .navbar-brand {
            font-size: 26px;
            font-weight: 800;
            color: white !important;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            color: white !important;
            font-weight: 600;
            margin: 0 10px;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
        }

        .nav-link:hover {
            border-bottom-color: white;
        }

        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .admin-header h3 {
            font-weight: 700;
            margin: 0;
        }

        .prod_image {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 8px;
        }

        .edit_image {
            width: 100%;
            height: 100px;
            object-fit: contain;
            border-radius: 8px;
        }

        .button {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 20px 0;
            margin-bottom: 20px;
        }

        .button .nav-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border-radius: 10px;
            padding: 12px 20px !important;
            min-height: auto;
            max-width: 160px;
            text-align: center;
            white-space: normal;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 13px;
            border: none;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
            margin: 0;
        }

        .button .nav-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            border: none;
        }

        .admin-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .admin-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: #f0f2f5;
            font-weight: 700;
            color: #667eea;
        }

        .table tbody tr {
            border-bottom: 1px solid #e8e8e8;
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: #f9f9f9;
        }

        .btn-sm {
            border-radius: 6px;
            padding: 6px 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            border: none;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        }

        .btn-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .footer {
            background: #f0f2f5;
            padding: 20px 0;
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <div class="container-fluid p-0">
        <!-- first child -->
        <nav class="navbar navbar-expand-lg navbar-light bg-info">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><i class="fa fa-shopping-basket" aria-hidden="true"> QuickCart</i></a>
                <nav class="navbar navbar-expand-lg">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page"
                                href="index.php?admin_id=<?php echo "$admin_id"; ?>&home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link text-dark">Welcome
                                <?php echo "$name"; ?>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </nav>

        <!-- second child -->
        <div class="bg-light">
            <h3 class="text-center p-2">Manage Inventory/View Analysis</h3>
        </div>

        <!-- third child -->

        <div class="row">
            <div class="col-md-12 bg-secondary p-1 align-items-center">
                <div class="button text-center">
                    <button class="my-3 mx-2"><a href="index.php?admin_id=<?php echo "$admin_id"; ?>&insert_product"
                            class="nav-link text-dark bg-info my-1 mx-1">Insert
                            Products</a></button>
                    <button class="my-3 mx-2"><a href="index.php?admin_id=<?php echo "$admin_id"; ?>&view_product"
                            class="nav-link text-dark bg-info my-1 mx-1">View
                            Products</a></button>
                    <button class="my-3 mx-2"><a href="index.php?admin_id=<?php echo "$admin_id"; ?>&insert_category"
                            class="nav-link text-dark bg-info my-1 mx-1">Insert
                            Categories</a></button>
                    <button class="my-3 mx-2"><a href="index.php?admin_id=<?php echo "$admin_id"; ?>&view_category"
                            class="nav-link text-dark bg-info my-1 mx-1">View
                            Categories</a></button>
                    <button class="my-3 mx-2"><a href="index.php?admin_id=<?php echo "$admin_id"; ?>&view_orders"
                            class="nav-link text-dark bg-info my-1 mx-1">All
                            Orders</a></button>
                    <button class="my-3 mx-2"><a href="index.php?admin_id=<?php echo "$admin_id"; ?>&list_customers"
                            class="nav-link text-dark bg-info my-1 mx-1">List
                            Customers</a></button>
                    <button class="my-3 mx-2"><a href="index.php?admin_id=<?php echo "$admin_id"; ?>&list_agents"
                            class="nav-link text-dark bg-info my-1 mx-1">List Delivery
                            Agents</a></button>
                    <button class="my-3 mx-2"><a href="index.php?admin_id=<?php echo "$admin_id"; ?>&order_review"
                            class="nav-link text-dark bg-info my-1 mx-1">View Order Reviews</a></button>
                    <button class="my-3 mx-2"><a href="index.php?admin_id=<?php echo "$admin_id"; ?>&delivery_review"
                            class="nav-link text-dark bg-info my-1 mx-1">View Delivery Reviews</a></button>
                    <button class="my-3 mx-2"><a href="../start.php"
                            class="nav-link text-dark bg-info my-1 mx-1">Logout</a></button>
                </div>
            </div>
        </div>

        <!-- fourth child -->
        <div class="container my-3">
            <?php
            if (isset ($_GET['insert_category'])) {
                include ('insert_category.php');
            }
            if (isset ($_GET['insert_product'])) {
                include ('insert_product.php');
            }
            if (isset ($_GET['view_product'])) {
                include ('view_product.php');
            }
            if (isset ($_GET['edit_product'])) {
                include ('edit_product.php');
            }
            if (isset ($_GET['delete_product'])) {
                include ('delete_product.php');
            }
            if (isset ($_GET['view_category'])) {
                include ('view_category.php');
            }
            if (isset ($_GET['edit_category'])) {
                include ('edit_category.php');
            }
            if (isset ($_GET['delete_category'])) {
                include ('delete_category.php');
            }
            if (isset ($_GET['view_orders'])) {
                include ('view_orders.php');
            }
            if (isset ($_GET['list_customers'])) {
                include ('list_customers.php');
            }
            if (isset ($_GET['list_agents'])) {
                include ('list_agents.php');
            }
            if (isset ($_GET['order_review'])) {
                include ('order_review.php');
            }
            if (isset ($_GET['delivery_review'])) {
                include ('delivery_review.php');
            }
            if (isset ($_GET['home'])) {
                include ('home.php');
            }
            if (isset ($_GET['dispatch_order'])) {
                include ('dispatch_order.php');
            }
            ?>
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