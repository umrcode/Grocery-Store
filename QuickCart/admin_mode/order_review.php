<?php
$fetch_oreview = "SELECT * FROM ProductReview;";
$result_fetch = mysqli_query($con, $fetch_oreview);
$num_of_rows = mysqli_num_rows($result_fetch);
if ($num_of_rows == 0) {
    echo "<h2 class = 'text-center text-danger'><i class='fas fa-comment-slash'></i> No Order Reviews have been submitted by the customers!</h2>";
} else {
    ?>
    <h2 class="text-center text-success mb-4"><i class='fas fa-comments'></i> All Order Reviews</h2>
    <div class="admin-card">
        <table class="table table-hover">
            <thead class="text-center">
                <tr>
                    <th>S.No.</th>
                    <th>Customer Name</th>
                    <th>Order ID</th>
                    <th>Comment</th>
                    <th>Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetch_oreview = "SELECT * FROM ProductReview ORDER BY orderID DESC;";
                $result_fetch = mysqli_query($con, $fetch_oreview);
                while ($fetch_row = mysqli_fetch_assoc($result_fetch)) {
                    $cust_id = $fetch_row["customerID"];
                    $review_id = $fetch_row["productReviewID"];
                    $order_id = $fetch_row["orderID"];
                    $comment = $fetch_row['comment'];
                    $rating = $fetch_row['rating'];
                    ?>
                    <tr class='align-middle'>
                        <td class="text-center"><strong>#<?php echo "$review_id"; ?></strong></td>
                        <td>
                            <?php
                            $get_cust = "SELECT * FROM customer WHERE customerID = $cust_id;";
                            $result_get = mysqli_query($con, $get_cust);
                            $row_cust = mysqli_fetch_assoc($result_get);
                            $cust_fname = $row_cust['first_name'];
                            $cust_lname = $row_cust['last_name'];
                            // Check if last_name is null
                            if ($cust_lname === null) {
                                $cust_name = $cust_fname;
                            } else {
                                $cust_name = $cust_fname . ' ' . $cust_lname;
                            }
                            echo "<span style='color: #667eea; font-weight: 600;'>" . $cust_name . "</span>";
                            ?>
                        </td>
                        <td class="text-center"><strong>#<?php echo "$order_id"; ?></strong></td>
                        <td>
                            <small><?php echo "$comment"; ?></small>
                        </td>
                        <td class="text-center">
                            <?php
                            $stars = '';
                            $filledStars = intval($rating); // Number of filled stars
                            $emptyStars = 5 - $filledStars; // Number of empty stars
                            // Add filled stars
                            for ($i = 0; $i < $filledStars; $i++) {
                                $stars .= '<span style="color: #ffc107;">★</span>'; // Filled star
                            }
                            // Add empty stars
                            for ($i = 0; $i < $emptyStars; $i++) {
                                $stars .= '<span style="color: #ddd;">☆</span>'; // Empty star
                            }
                            echo $stars;
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