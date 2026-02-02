<?php
// include DB connection
include('../includes/connect.php');
// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $update_query = "UPDATE `order` SET status = '$new_status' WHERE orderID = $order_id;";
    mysqli_query($con, $update_query);
    echo "<script>alert('Order status updated successfully!');</script>";
    echo "<script>window.location.href='index.php?view_orders';</script>";
}

// Handle agent assignment
if (isset($_POST['assign_agent'])) {
    $order_id = $_POST['order_id'];
    $agent_id = $_POST['agent_id'];
    $update_query = "UPDATE `order` SET agentID = $agent_id WHERE orderID = $order_id;";
    mysqli_query($con, $update_query);
    echo "<script>alert('Order assigned to agent successfully!');</script>";
    echo "<script>window.location.href='index.php?view_orders';</script>";
}

$fetch_orders = "SELECT * FROM `order`;";
$result_fetch = mysqli_query($con, $fetch_orders);
$num_of_rows = mysqli_num_rows($result_fetch);
if ($num_of_rows == 0) {
    echo "<h2 class = 'text-center text-danger'><i class='fas fa-inbox'></i> No Orders have been placed!</h2>";
} else {
    ?>
    <h2 class="text-center text-success mb-4"><i class='fas fa-shopping-cart'></i> All Orders</h2>
    <div class="admin-card">
        <table class="table table-hover">
            <thead class="text-center">
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Order Cost</th>
                    <th>Delivery Address</th>
                    <th>Order Placed on</th>
                    <th>Delivery Agent</th>
                    <th>Agent Payment</th>
                    <th>Payment Method</th>
                    <th>Order Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetch_orders = "SELECT * FROM `order` ORDER BY orderID DESC;";
                $result_fetch = mysqli_query($con, $fetch_orders);
                while ($fetch_row = mysqli_fetch_assoc($result_fetch)) {
                    $order_id = $fetch_row["orderID"];
                    $order_location = $fetch_row["location"];
                    $order_price = $fetch_row["total_price"];
                    $order_time = $fetch_row["time"];
                    $order_custID = $fetch_row["customerID"];
                    $order_agentID = $fetch_row["agentID"];
                    $order_agentPayment = $fetch_row["agentPayment"];
                    $order_status = $fetch_row["status"];
                    $order_paymentMethod = isset($fetch_row["payment_method"]) ? $fetch_row["payment_method"] : "Wallet";
                    ?>
                    <tr class='align-middle'>
                        <td class="text-center"><strong>#<?php echo "$order_id"; ?></strong></td>
                        <td>
                            <?php
                            $get_cust = "SELECT * FROM customer WHERE customerID = $order_custID;";
                            $result_get = mysqli_query($con, $get_cust);
                            $row_cust = mysqli_fetch_assoc($result_get);
                            $cust_fname = $row_cust['first_name'];
                            $cust_lname = $row_cust['last_name'];
                            if ($cust_lname === null) {
                                $cust_name = $cust_fname;
                            } else {
                                $cust_name = $cust_fname . ' ' . $cust_lname;
                            }
                            echo "$cust_name";
                            ?>
                        </td>
                        <td class="text-center"><span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">Rs. <?php echo $order_price; ?></span></td>
                        <td><?php echo "$order_location"; ?></td>
                        <td class="text-center"><?php echo date('d M Y', strtotime($order_time)); ?></td>
                        <td>
                            <?php
                            $get_agent = "SELECT * FROM deliveryAgent WHERE agentID = $order_agentID;";
                            $result_get = mysqli_query($con, $get_agent);
                            $row_agent = mysqli_fetch_assoc($result_get);
                            if ($row_agent) {
                                $agent_fname = $row_agent['first_name'];
                                $agent_lname = $row_agent['last_name'];
                                if ($agent_lname === null) {
                                    $agent_name = $agent_fname;
                                } else {
                                    $agent_name = $agent_fname . ' ' . $agent_lname;
                                }
                                echo "<span style='color: #667eea; font-weight: 600;'>$agent_name</span>";
                            } else {
                                echo "<span style='color: #ff6b6b;'><i class='fas fa-times-circle'></i> Not Assigned</span>";
                            }
                            ?>
                        </td>
                        <td class="text-center">Rs.<?php echo $order_agentPayment; ?></td>
                        <td class="text-center">
                            <?php 
                            $payment_icon = '';
                            $payment_color = '';
                            if ($order_paymentMethod == 'Wallet') {
                                $payment_icon = 'fa-wallet';
                                $payment_color = '#667eea';
                            } elseif ($order_paymentMethod == 'Credit Card') {
                                $payment_icon = 'fa-credit-card';
                                $payment_color = '#764ba2';
                            } elseif ($order_paymentMethod == 'Debit Card') {
                                $payment_icon = 'fa-credit-card';
                                $payment_color = '#ff6b6b';
                            } elseif ($order_paymentMethod == 'Cash on Delivery') {
                                $payment_icon = 'fa-money-bill-wave';
                                $payment_color = '#28a745';
                            }
                            ?>
                            <span style="color: <?php echo $payment_color; ?>; font-weight: 600;">
                                <i class="fas <?php echo $payment_icon; ?>"></i> <?php echo $order_paymentMethod; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php 
                            $status_color = '';
                            $status_icon = '';
                            if ($order_status == 'Confirmed') {
                                $status_color = '#667eea';
                                $status_icon = 'fa-check-circle';
                            } elseif ($order_status == 'Packed and Shipped') {
                                $status_color = '#ffa500';
                                $status_icon = 'fa-box';
                            } elseif ($order_status == 'Delivered') {
                                $status_color = '#28a745';
                                $status_icon = 'fa-check-double';
                            } elseif ($order_status == 'On the Way') {
                                $status_color = '#ff9800';
                                $status_icon = 'fa-truck';
                            }
                            ?>
                            <span style="color: <?php echo $status_color; ?>; font-weight: 600;">
                                <i class="fas <?php echo $status_icon; ?>"></i> <?php echo $order_status; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $order_id; ?>">
                                <i class='fas fa-edit'></i> Update
                            </button>
                        </td>
                    </tr>

                    <!-- Update Modal -->
                    <div class="modal fade" id="updateModal<?php echo $order_id; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                    <h5 class="modal-title"><i class='fas fa-edit'></i> Update Order #<?php echo $order_id; ?></h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form method="POST">
                                                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                                <label class="form-label"><strong><i class='fas fa-user-tie'></i> Assign Delivery Agent</strong></label>
                                                <select class="form-select" name="agent_id" required>
                                                    <option value="">-- Select Agent --</option>
                                                    <?php
                                                    $agent_query = "SELECT agentID, first_name, last_name FROM deliveryAgent;";
                                                    $agent_result = mysqli_query($con, $agent_query);
                                                    while ($agent_row = mysqli_fetch_assoc($agent_result)) {
                                                        $agent_id = $agent_row['agentID'];
                                                        $agent_f = $agent_row['first_name'];
                                                        $agent_l = $agent_row['last_name'];
                                                        $agent_full_name = $agent_l ? "$agent_f $agent_l" : $agent_f;
                                                        $selected = ($agent_id == $order_agentID) ? 'selected' : '';
                                                        echo "<option value='$agent_id' $selected>$agent_full_name</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <button type="submit" name="assign_agent" class="btn btn-info mt-3 w-100">
                                                    <i class='fas fa-check'></i> Assign Agent
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <form method="POST">
                                                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                                <label class="form-label"><strong><i class='fas fa-info-circle'></i> Update Order Status</strong></label>
                                                <select class="form-select" name="status" required>
                                                    <option value="Confirmed" <?php echo ($order_status == 'Confirmed') ? 'selected' : ''; ?>>
                                                        <i class='fas fa-check-circle'></i> Confirmed
                                                    </option>
                                                    <option value="Packed and Shipped" <?php echo ($order_status == 'Packed and Shipped') ? 'selected' : ''; ?>>
                                                        <i class='fas fa-box'></i> Packed and Shipped
                                                    </option>
                                                    <option value="On the Way" <?php echo ($order_status == 'On the Way') ? 'selected' : ''; ?>>
                                                        <i class='fas fa-truck'></i> On the Way
                                                    </option>
                                                    <option value="Delivered" <?php echo ($order_status == 'Delivered') ? 'selected' : ''; ?>>
                                                        <i class='fas fa-check-double'></i> Delivered
                                                    </option>
                                                </select>
                                                <button type="submit" name="update_status" class="btn btn-info mt-3 w-100">
                                                    <i class='fas fa-check'></i> Update Status
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>

            </tbody>
        </table>
    </div>
    <?php
}
?>