<?php
if (isset ($_GET['edit_profile']) and isset($_GET['agent_id'])) {
    $agent_id = $_GET['agent_id'];
    $get_data = "SELECT * FROM deliveryAgent WHERE agentID = $agent_id;";
    $result_get = mysqli_query($con, $get_data);
    $row_data = mysqli_fetch_assoc($result_get);
    $agent_id = $row_data["agentID"];
    $agent_fname = $row_data['first_name'];
    $agent_lname = $row_data['last_name'];
    $pass = $row_data['password'];
    $email = $row_data['email'];
    $phone = $row_data['phone_no'];


    // Check if last_name is null
    if ($agent_lname === null) {
        $agent_name = $agent_fname;
    } else {
        $agent_name = $agent_fname . ' ' . $agent_lname;
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4" style="color: #667eea; font-weight: 700;">
                        <i class="fas fa-user-edit"></i> Edit Profile
                    </h2>
                    
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="first_name" class="form-label fw-bold">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="<?php echo "$agent_fname"; ?>"
                                class="form-control" placeholder="Enter first name" autocomplete="off" required="required" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;">
                        </div>
                        
                        <div class="mb-3">
                            <label for="last_name" class="form-label fw-bold">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="<?php echo "$agent_lname"; ?>" class="form-control"
                                placeholder="Enter last name" autocomplete="off" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" id="email" value="<?php echo "$email"; ?>" class="form-control"
                                placeholder="Enter email" autocomplete="off" required="required" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;">
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone_no" class="form-label fw-bold">Phone Number</label>
                            <input type="tel" name="phone_no" id="phone_no" value="<?php echo "$phone"; ?>" class="form-control"
                                placeholder="Enter phone number" autocomplete="off" required="required" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;"/>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" id="password" class="form-control" placeholder="Enter password" autocomplete="off"
                                required="required" name="password" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
                        </div>
                        
                        <div class="mb-4">
                            <label for="conf_agent_password" class="form-label fw-bold">Confirm Password</label>
                            <input type="password" id="conf_agent_password" class="form-control" placeholder="Confirm password"
                                autocomplete="off" required="required" name="conf_agent_password" style="border-radius: 8px; border: 2px solid #e0e0e0; padding: 10px 15px;" />
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

<!-- updating agent's details in the database -->
<?php
if (isset ($_POST['edit_prof'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_no = $_POST['phone_no'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conf_password = $_POST['conf_agent_password'];

    $select_query = "SELECT * FROM deliveryAgent WHERE (email='$email' OR phone_no='$phone_no') AND agentID != '$agent_id';";
    $result = mysqli_query($con, $select_query);
    $rows_count = mysqli_num_rows($result);
    if ($rows_count > 0) {
        echo "<script>alert('Agent with given Email or Phone Number already exist. Please enter again.')</script>";
    } else if ($password != $conf_password) {
        echo "<script>alert('Passwords do not match. Please try again.')</script>";
    } else {
        // update agent to table - SET safe mode OFF to update
        $safe_mode_query = "SET SQL_SAFE_UPDATES = 0;";
        $result_safe = mysqli_query($con, $safe_mode_query);
        if ($result_safe == 0) {
            echo "Couldn't turn safe mode OFF";
        }
        $update_query = "UPDATE deliveryAgent SET first_name = '$first_name', last_name = '$last_name', phone_no = '$phone_no', email = '$email', password = '$password' WHERE agentID = '$agent_id';";
        $result_query = mysqli_query($con, $update_query);
        if ($result_query) {
            echo "<script>alert('Profile has been updated successfully.'); window.location.href = 'profile_page.php?agent_id=" . $agent_id . "&view_profile';</script>";
        }
    }
}
?>