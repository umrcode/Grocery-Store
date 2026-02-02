<?php
// Get admin dashboard statistics
$admin_id = isset($_GET['admin_id']) ? $_GET['admin_id'] : 1;

// Get admin name
if ($admin_id == 1) {
    $admin_name = "Ali Hassan";
} elseif ($admin_id == 2) {
    $admin_name = "Faizan Ali";
} else {
    $admin_name = "Admin";
}

// Get total products count
$products_query = "SELECT COUNT(*) as total_products FROM product";
$products_result = mysqli_query($con, $products_query);
$products_row = mysqli_fetch_assoc($products_result);
$total_products = $products_row['total_products'];

// Get total categories count
$categories_query = "SELECT COUNT(*) as total_categories FROM productCategory";
$categories_result = mysqli_query($con, $categories_query);
$categories_row = mysqli_fetch_assoc($categories_result);
$total_categories = $categories_row['total_categories'];

// Get total customers count
$customers_query = "SELECT COUNT(*) as total_customers FROM customer";
$customers_result = mysqli_query($con, $customers_query);
$customers_row = mysqli_fetch_assoc($customers_result);
$total_customers = $customers_row['total_customers'];

// Get total delivery agents count
$agents_query = "SELECT COUNT(*) as total_agents FROM deliveryAgent";
$agents_result = mysqli_query($con, $agents_query);
$agents_row = mysqli_fetch_assoc($agents_result);
$total_agents = $agents_row['total_agents'];

// Get total orders count
$orders_query = "SELECT COUNT(*) as total_orders FROM `order`";
$orders_result = mysqli_query($con, $orders_query);
$orders_row = mysqli_fetch_assoc($orders_result);
$total_orders = $orders_row['total_orders'];

// Get total revenue
$revenue_query = "SELECT SUM(total_price) as total_revenue FROM `order`";
$revenue_result = mysqli_query($con, $revenue_query);
$revenue_row = mysqli_fetch_assoc($revenue_result);
$total_revenue = $revenue_row['total_revenue'] ? $revenue_row['total_revenue'] : 0;
?>

<h2 class="text-center text-success mb-4"><i class='fas fa-chart-line'></i> Admin Dashboard</h2>
<h4 class="text-center text-muted mb-4">Welcome, <strong><?php echo $admin_name; ?></strong></h4>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="admin-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
            <div style="text-align: center;">
                <i class="fas fa-box" style="font-size: 40px; margin-bottom: 15px;"></i>
                <h5 style="margin-bottom: 10px;">Total Products</h5>
                <h2 style="font-weight: 700; margin: 0;"><?php echo $total_products; ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="admin-card" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: white; border: none;">
            <div style="text-align: center;">
                <i class="fas fa-list" style="font-size: 40px; margin-bottom: 15px;"></i>
                <h5 style="margin-bottom: 10px;">Total Categories</h5>
                <h2 style="font-weight: 700; margin: 0;"><?php echo $total_categories; ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="admin-card" style="background: linear-gradient(135deg, #ffa500 0%, #ff8c00 100%); color: white; border: none;">
            <div style="text-align: center;">
                <i class="fas fa-shopping-cart" style="font-size: 40px; margin-bottom: 15px;"></i>
                <h5 style="margin-bottom: 10px;">Total Orders</h5>
                <h2 style="font-weight: 700; margin: 0;"><?php echo $total_orders; ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="admin-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none;">
            <div style="text-align: center;">
                <i class="fas fa-users" style="font-size: 40px; margin-bottom: 15px;"></i>
                <h5 style="margin-bottom: 10px;">Total Customers</h5>
                <h2 style="font-weight: 700; margin: 0;"><?php echo $total_customers; ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="admin-card" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; border: none;">
            <div style="text-align: center;">
                <i class="fas fa-truck" style="font-size: 40px; margin-bottom: 15px;"></i>
                <h5 style="margin-bottom: 10px;">Delivery Agents</h5>
                <h2 style="font-weight: 700; margin: 0;"><?php echo $total_agents; ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="admin-card" style="background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%); color: white; border: none;">
            <div style="text-align: center;">
                <i class="fas fa-rupee-sign" style="font-size: 40px; margin-bottom: 15px;"></i>
                <h5 style="margin-bottom: 10px;">Total Revenue</h5>
                <h2 style="font-weight: 700; margin: 0;">Rs.<?php echo number_format($total_revenue, 2); ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="admin-card mt-5">
    <h4 class="mb-4"><i class='fas fa-clock'></i> Recent Orders</h4>
    <?php viewPendingOrders($admin_id); ?>
</div>