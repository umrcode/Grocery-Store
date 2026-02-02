<?php
if (isset ($_GET['edit_profile']) and isset($_GET['customer_id'])) {
    $cust_id = $_GET['customer_id'];
    $get_data = "SELECT * FROM customer WHERE customerID = $cust_id;";
    $result_get = mysqli_query($con, $get_data);
    $row_data = mysqli_fetch_assoc($result_get);
    $cust_id = $row_data["customerID"];
    $cust_fname = $row_data['first_name'];
    $cust_lname = $row_data['last_name'];
    $pass = $row_data['password'];
    $email = $row_data['email'];
    $phone = $row_data['phone_no'];
    $street = $row_data['address_street'];
    $city = $row_data['address_city'];
    $state = $row_data['address_state'];
    $pincode = $row_data['pincode'];
    $dob = $row_data['dob'];
    $gender = $row_data['gender'];

    // Check if last_name is null
    if ($cust_lname === null) {
        $cust_name = $cust_fname;
    } else {
        $cust_name = $cust_fname . ' ' . $cust_lname;
    }
    $cust_address = $street . ', ' . $city . ', ' . $state. ', Pincode - ' . $pincode;
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4" style="color: #667eea; font-weight: 700;">
                        <i class="fas fa-user-edit"></i> Edit Profile
                    </h2>
                    
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="first_name" class="form-label fw-bold">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="<?php echo "$cust_fname"; ?>"
                                class="form-control" placeholder="Enter first name" autocomplete="off" required="required" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
                        </div>
                        
                        <div class="mb-3">
                            <label for="last_name" class="form-label fw-bold">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="<?php echo "$cust_lname"; ?>" class="form-control"
                                placeholder="Enter last name" autocomplete="off" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" id="email" value="<?php echo "$email"; ?>" class="form-control"
                                placeholder="Enter email" autocomplete="off" required="required" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone_no" class="form-label fw-bold">Phone Number</label>
                            <input type="tel" name="phone_no" id="phone_no" value="<?php echo "$phone"; ?>" class="form-control"
                                placeholder="Enter phone number" autocomplete="off" required="required" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
                        </div>
                        
                        <div class="mb-3">
                            <label for="address_street" class="form-label fw-bold">Street Address</label>
                            <input type="text" name="address_street" id="address_street" value="<?php echo "$street"; ?>"
                                class="form-control" placeholder="Enter street address" autocomplete="off" required="required" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
                        </div>
                        
                        <div class="mb-3">
                            <label for="address_city" class="form-label fw-bold">City</label>
                            <input type="text" name="address_city" id="address_city" value="<?php echo "$city"; ?>" class="form-control"
                                placeholder="Enter city" autocomplete="off" required="required" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
                        </div>
                        
                        <div class="mb-3">
                            <label for="address_state" class="form-label fw-bold">State</label>
                            <input type="text" name="address_state" id="address_state" value="<?php echo "$state"; ?>"
                                class="form-control" placeholder="Enter state" autocomplete="off" required="required" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
                        </div>
                        
                        <div class="mb-3">
                            <label for="pincode" class="form-label fw-bold">Postal Code</label>
                            <input type="text" name="pincode" id="pincode" value="<?php echo "$pincode"; ?>" class="form-control"
                                placeholder="Enter postal code" autocomplete="off" required="required" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
                        </div>
                        
                        <div class="mb-3">
                            <label for="gender" class="form-label fw-bold">Gender</label>
                            <select id="gender" class="form-select" required="required" name="gender" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;">
                                <option value="" disabled>Select your gender</option>
                                <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo ($gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" id="password" class="form-control" placeholder="Enter password" autocomplete="off"
                                required="required" name="password" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
                        </div>
                        
                        <div class="mb-4">
                            <label for="conf_customer_password" class="form-label fw-bold">Confirm Password</label>
                            <input type="password" id="conf_customer_password" class="form-control" placeholder="Confirm password"
                                autocomplete="off" required="required" name="conf_customer_password" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
                        </div>
                        
                        <button type="submit" name="edit_prof" class="btn w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px; font-weight: 700; border-radius: 8px; border: none; transition: all 0.3s ease;">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- updating customer's details in the database -->
<?php
if (isset ($_POST['edit_prof'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address_street = $_POST['address_street'];
    $address_city = $_POST['address_city'];
    $address_state = $_POST['address_state'];
    $pincode = $_POST['pincode'];
    $phone_no = $_POST['phone_no'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conf_password = $_POST['conf_customer_password'];
    $gender = $_POST['gender'];

    $select_query = "SELECT * FROM customer WHERE (email='$email' OR phone_no='$phone_no') AND customerID != '$cust_id';";
    $result = mysqli_query($con, $select_query);
    $rows_count = mysqli_num_rows($result);
    if ($rows_count > 0) {
        echo "<script>alert('Customer with given Email or Phone Number already exist. Please enter again.')</script>";
    } else if ($password != $conf_password) {
        echo "<script>alert('Passwords do not match. Please try again.')</script>";
    } else {
        // update customer to table - SET safe mode OFF to update
        $safe_mode_query = "SET SQL_SAFE_UPDATES = 0;";
        $result_safe = mysqli_query($con, $safe_mode_query);
        if ($result_safe == 0) {
            echo "Couldn't turn safe mode OFF";
        }

        $update_query = "UPDATE customer SET first_name = '$first_name', last_name = '$last_name', address_street = '$address_street', address_city = '$address_city', address_state = '$address_state', pincode = '$pincode', phone_no = '$phone_no', email = '$email', password = '$password', gender = '$gender' WHERE customerID = '$cust_id';";
        $result_query = mysqli_query($con, $update_query);
        if ($result_query) {
            echo "<script>alert('Profile has been updated successfully.'); window.location.href = 'profile_page.php?customer_id=" . $cust_id . "&view_profile';</script>";
        }
    }
}
?>