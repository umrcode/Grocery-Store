<?php
$fetch_agents = "SELECT * FROM deliveryAgent;";
$result_fetch = mysqli_query($con, $fetch_agents);
$num_of_rows = mysqli_num_rows($result_fetch);
if ($num_of_rows == 0) {
    echo "<h2 class = 'text-center text-danger'><i class='fas fa-people-arrows'></i> No Delivery Agents have registered!</h2>";
} else {
    ?>
    <h2 class="text-center text-success mb-4"><i class='fas fa-truck'></i> All Delivery Agents</h2>
    <div class="admin-card">
        <table class="table table-hover">
            <thead class="text-center">
                <tr>
                    <th>Agent ID</th>
                    <th>Name</th>
                    <th>Email ID</th>
                    <th>Mobile No</th>
                    <th>Availability Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Update the age of agents as derived attribute - SET safe mode OFF to update
                $safe_mode_query = "SET SQL_SAFE_UPDATES = 0;";
                $result_safe = mysqli_query($con, $safe_mode_query);
                if ($result_safe == 0) {
                    echo "Couldn't turn safe mode OFF";
                }
                $fetch_agents = "SELECT * FROM deliveryAgent;";
                $result_fetch = mysqli_query($con, $fetch_agents);
                while ($fetch_row = mysqli_fetch_assoc($result_fetch)) {
                    $agent_id = $fetch_row["agentID"];
                    $agent_fname = $fetch_row['first_name'];
                    $agent_lname = $fetch_row['last_name'];
                    $agent_email = $fetch_row["email"];
                    $agent_mobile = $fetch_row["phone_no"];
                    $agent_status = $fetch_row["availabilityStatus"];
                    ?>
                    <tr class='align-middle'>
                        <td class="text-center"><strong>#<?php echo "$agent_id"; ?></strong></td>
                        <td>
                            <?php
                            // Check if last_name is null
                            if ($agent_lname === null) {
                                $agent_name = $agent_fname;
                            } else {
                                $agent_name = $agent_fname . ' ' . $agent_lname;
                            }
                            echo "<span style='color: #667eea; font-weight: 600;'><i class='fas fa-user-tie'></i> " . $agent_name . "</span>";
                            ?>
                        </td>
                        <td><?php echo "$agent_email"; ?></td>
                        <td class="text-center"><?php echo "$agent_mobile"; ?></td>
                        <td class="text-center">
                            <?php 
                            if ($agent_status == 'Online') {
                                echo "<span style='color: #28a745; font-weight: 600;'><i class='fas fa-check-circle'></i> Online</span>";
                            } elseif ($agent_status == 'Offline') {
                                echo "<span style='color: #ff6b6b; font-weight: 600;'><i class='fas fa-times-circle'></i> Offline</span>";
                            } else {
                                echo "<span style='color: #ff9800; font-weight: 600;'><i class='fas fa-exclamation-circle'></i> " . $agent_status . "</span>";
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