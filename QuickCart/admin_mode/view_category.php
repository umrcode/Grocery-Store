<?php
if (isset ($_GET['admin_id'])) {
    $admin_id = $_GET['admin_id'];
}
$fetch_categories = "SELECT * FROM productCategory;";
$result_fetch = mysqli_query($con, $fetch_categories);
$num_of_rows = mysqli_num_rows($result_fetch);
if ($num_of_rows == 0) {
    echo "<h2 class='text-center text-danger'><i class='fas fa-inbox'></i> No Categories available in Store right now!</h2>";
} else {
    ?>

    <h2 class="text-center text-success mb-4"><i class='fas fa-list'></i> All Categories</h2>

    <div class="admin-card">
        <table class="table table-hover">
            <thead class="text-center">
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>No of Products</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Update the noOfProducts attribute in category table before it fetches data - SET safe mode OFF to update
                $safe_mode_query = "SET SQL_SAFE_UPDATES = 0;";
                $result_safe = mysqli_query($con, $safe_mode_query);
                if ($result_safe == 0) {
                    echo "Couldn't turn safe mode OFF";
                }
                $update_stock = "UPDATE productCategory AS pc
                SET pc.noOfProducts = COALESCE((
                    SELECT SUM(p.stock)
                    FROM product AS p
                    WHERE p.categoryID = pc.categoryID
                ), 0);";
                $result_update = mysqli_query($con, $update_stock);
                $fetch_categories = "SELECT * FROM productCategory;";
                $result_fetch = mysqli_query($con, $fetch_categories);
                while ($fetch_row = mysqli_fetch_assoc($result_fetch)) {
                    $cat_id = $fetch_row["categoryID"];
                    $cat_name = $fetch_row["name"];
                    $cat_noofp = $fetch_row["noOfProducts"];
                    ?>
                    <tr class='align-middle'>
                        <td class="text-center"><strong>#<?php echo "$cat_id"; ?></strong></td>
                        <td><span style='color: #667eea; font-weight: 600;'><i class='fas fa-folder'></i> <?php echo "$cat_name"; ?></span></td>
                        <td class="text-center"><span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><?php echo "$cat_noofp"; ?></span></td>
                        <td class="text-center"><a href='index.php?admin_id=<?php echo "$admin_id"; ?>&edit_category=<?php echo "$cat_id"; ?>' class='btn btn-sm btn-info'><i class='fa-solid fa-pen-to-square'></i> Edit</a></td>
                        <td class="text-center"><a href='index.php?admin_id=<?php echo "$admin_id"; ?>&delete_category=<?php echo "$cat_id"; ?>' class='btn btn-sm btn-danger' onclick="return confirm('Are you sure?');"><i class='fa-solid fa-trash'></i> Delete</a></td>
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
