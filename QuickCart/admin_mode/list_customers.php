<?php
$fetch_customers = "SELECT * FROM customer;";
$result_fetch = mysqli_query($con, $fetch_customers);
$num_of_rows = mysqli_num_rows($result_fetch);
if ($num_of_rows == 0) {
    echo "<h2 class = 'text-center text-danger'><i class='fas fa-users-slash'></i> No Customers have registered!</h2>";
} else {
    ?>
    <h2 class="text-center text-success mb-4"><i class='fas fa-users'></i> All Customers</h2>
    <div class="admin-card">
        <table class="table table-hover">
            <thead class="text-center">
                <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Email ID</th>
                    <th>Mobile No</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Update the age of customers as derived attribute - SET safe mode OFF to update
                $safe_mode_query = "SET SQL_SAFE_UPDATES = 0;";
                $result_safe = mysqli_query($con, $safe_mode_query);
                if ($result_safe == 0) {
                    echo "Couldn't turn safe mode OFF";
                }
                $fetch_customers = "SELECT * FROM customer;";
                $result_fetch = mysqli_query($con, $fetch_customers);
                while ($fetch_row = mysqli_fetch_assoc($result_fetch)) {
                    $cust_id = $fetch_row["customerID"];
                    $cust_fname = $fetch_row['first_name'];
                    $cust_lname = $fetch_row['last_name'];
                    $cust_street = $fetch_row["address_street"];
                    $cust_city = $fetch_row["address_city"];
                    $cust_state = $fetch_row["address_state"];
                    $cust_pincode = $fetch_row["pincode"];
                    $cust_email = $fetch_row["email"];
                    $cust_mobile = $fetch_row["phone_no"];
                    $cust_gender = $fetch_row["gender"];
                    ?>
                    <tr class='align-middle'>
                        <td class="text-center"><strong>#<?php echo "$cust_id"; ?></strong></td>
                        <td>
                            <?php
                            // Check if last_name is null
                            if ($cust_lname === null) {
                                $cust_name = $cust_fname;
                            } else {
                                $cust_name = $cust_fname . ' ' . $cust_lname;
                            }
                            echo "<span style='color: #667eea; font-weight: 600;'><i class='fas fa-user-circle'></i> " . $cust_name . "</span>";
                            ?>
                        </td>
                        <td>
                            <?php
                            $cust_addr = $cust_street . ', ' . $cust_city . ', ' . $cust_state . ' ' . $cust_pincode;
                            echo "$cust_addr";
                            ?>
                        </td>
                        <td><?php echo "$cust_email"; ?></td>
                        <td class="text-center"><?php echo "$cust_mobile"; ?></td>
                        <td class="text-center">
                            <?php 
                            if ($cust_gender == 'Male') {
                                echo "<span style='color: #667eea;'><i class='fas fa-mars'></i> Male</span>";
                            } elseif ($cust_gender == 'Female') {
                                echo "<span style='color: #ff69b4;'><i class='fas fa-venus'></i> Female</span>";
                            } else {
                                echo "<span style='color: #764ba2;'><i class='fas fa-genderless'></i> " . $cust_gender . "</span>";
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>