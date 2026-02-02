<?php
include ('deleteProductQty_trigger.php');

if (isset ($_GET['admin_id'])) {
    $admin_id = $_GET['admin_id'];
}
$fetch_products = "SELECT * FROM product;";
$result_fetch = mysqli_query($con, $fetch_products);
$num_of_rows = mysqli_num_rows($result_fetch);
if ($num_of_rows == 0) {
    echo "<h2 class = 'text-center text-danger'> No Products available in Store right now! </h2>";
} else {
    ?>
    <h2 class="text-center text-success mb-4"><i class='fas fa-box'></i> All Products</h2>
    <div class="admin-card">
        <table class="table table-hover">
            <thead class="text-center">
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Product Image</th>
                    <th>Product Price</th>
                    <th>Stock</th>
                    <th>Brand Name</th>
                    <th>Qty Bought</th>
                    <th>Category</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetch_products = "SELECT * FROM product;";
                $result_fetch = mysqli_query($con, $fetch_products);
                while ($fetch_row = mysqli_fetch_assoc($result_fetch)) {
                    $product_id = $fetch_row["productID"];
                    $product_name = $fetch_row["name"];
                    $product_image = $fetch_row["prod_image"];
                    $product_price = $fetch_row["price"];
                    $product_stock = $fetch_row["stock"];
                    $product_brand = $fetch_row["brand"];
                    $product_bought = $fetch_row["qty_bought"];
                    $product_cat = $fetch_row["categoryID"];
                    ?>
                    <tr class='align-middle'>
                        <td class="text-center"><strong>#<?php echo "$product_id"; ?></strong></td>
                        <td><?php echo "$product_name"; ?></td>
                        <td class="text-center"><img src='../images/<?php echo "$product_image"; ?>' alt='$product_name image' class='prod_image' /></td>
                        <td class="text-center"><span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">Rs. <?php echo $product_price; ?></span></td>
                        <td class="text-center">
                            <?php 
                            if ($product_stock > 20) {
                                echo "<span style='color: #28a745; font-weight: 600;'><i class='fas fa-check-circle'></i> " . $product_stock . "</span>";
                            } elseif ($product_stock > 0) {
                                echo "<span style='color: #ff9800; font-weight: 600;'><i class='fas fa-exclamation-triangle'></i> " . $product_stock . "</span>";
                            } else {
                                echo "<span style='color: #ff6b6b; font-weight: 600;'><i class='fas fa-times-circle'></i> Out of Stock</span>";
                            }
                            ?>
                        </td>
                        <td><?php echo "$product_brand"; ?></td>
                        <td class="text-center"><span class="badge bg-secondary"><?php echo "$product_bought"; ?></span></td>
                        <td>
                            <?php
                            $get_cat = "SELECT * FROM productCategory WHERE categoryID = $product_cat;";
                            $result_get = mysqli_query($con, $get_cat);
                            $row_cat = mysqli_fetch_assoc($result_get);
                            $cat_name = $row_cat['name'];
                            echo "<span style='color: #667eea; font-weight: 600;'>$cat_name</span>";
                            ?>
                        </td>
                        <td class="text-center"><a href='index.php?admin_id=<?php echo "$admin_id"; ?>&edit_product=<?php echo "$product_id"; ?>' class='btn btn-sm btn-info'><i class='fa-solid fa-pen-to-square'></i> Edit</a></td>
                        <td class="text-center"><a href='index.php?admin_id=<?php echo "$admin_id"; ?>&delete_product=<?php echo "$product_id"; ?>' class='btn btn-sm btn-danger' onclick="return confirm('Are you sure?');"><i class='fa-solid fa-trash'></i> Delete</a></td>
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