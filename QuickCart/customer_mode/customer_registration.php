<?php
include ('../includes/connect.php');
include ('age_trigger.php');

$error_msg = "";
$success_msg = "";

if (isset ($_POST['customer_register'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $address_street = trim($_POST['address_street']);
    $address_city = trim($_POST['address_city']);
    $address_state = trim($_POST['address_state']);
    $pincode = trim($_POST['pincode']);
    $phone_no = trim($_POST['phone_no']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $conf_password = $_POST['conf_customer_password'];
    $gender = $_POST['gender'];

    // Validation
    if (empty($first_name) || empty($address_street) || empty($address_city) || empty($address_state) || 
        empty($pincode) || empty($phone_no) || empty($email) || empty($password) || empty($gender)) {
        $error_msg = "All fields are required!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please enter a valid email address!";
    } else if (strlen($password) < 6) {
        $error_msg = "Password must be at least 6 characters long!";
    } else if ($password != $conf_password) {
        $error_msg = "Passwords do not match!";
    } else if (!preg_match('/^[0-9]{10}$/', $phone_no)) {
        $error_msg = "Phone number must be 10 digits!";
    } else if (!preg_match('/^[0-9]{6}$/', $pincode)) {
        $error_msg = "Pincode must be 6 digits!";
    } else {
        $select_query = "SELECT * FROM customer WHERE email='$email' OR phone_no='$phone_no';";
        $result = mysqli_query($con, $select_query);
        $rows_count = mysqli_num_rows($result);
        
        if ($rows_count > 0) {
            $error_msg = "Email or Phone Number already exists! Please use a different one.";
        } else {
            $select_total = "SELECT * FROM customer;";
            $result_total = mysqli_query($con, $select_total);
            $new_cust = mysqli_num_rows($result_total) + 1;
            
            $insert_query = "INSERT INTO customer (first_name, last_name, address_street, address_city, address_state, pincode, phone_no, email, password, dob, age, gender) 
            VALUES ('$first_name','$last_name','$address_street','$address_city','$address_state','$pincode','$phone_no','$email','$password',DEFAULT,DEFAULT,'$gender');";
            $sql_execute = mysqli_query($con, $insert_query);

            if ($sql_execute) {
                $upi_id = 'customer' . $new_cust . '@upi';
                $insert_wallet = "INSERT INTO wallet (customerID, balance, upiID, rewardPoints) VALUES ('$new_cust', 1000, '$upi_id', DEFAULT);";
                $sql_wallet = mysqli_query($con, $insert_wallet);
                
                if ($sql_wallet) {
                    $success_msg = "Registration successful! Redirecting to login...";
                    echo "<script>setTimeout(function(){ window.location.href = 'customer_login.php'; }, 2000);</script>";
                } else {
                    $error_msg = "Error creating wallet. Please try again.";
                }
            } else {
                $error_msg = "Registration failed! Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration - QuickCart</title>
    <!-- bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #a855f7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
            padding: 20px 0;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        body::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(30px); }
        }
        
        .registration-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 520px;
            padding: 20px;
        }
        
        .registration-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 50px 45px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .registration-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .registration-header h2 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            margin-bottom: 10px;
            font-size: 32px;
        }
        
        .registration-header i {
            font-size: 28px;
            margin-right: 8px;
        }
        
        .registration-header p {
            color: #888;
            font-size: 14px;
            font-weight: 500;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            display: block;
            font-size: 14px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            color: #333;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background: white;
            color: #333;
        }
        
        .form-control::placeholder {
            color: #999;
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 14px 28px;
            font-weight: 700;
            border-radius: 10px;
            color: white;
            transition: all 0.3s ease;
            margin-top: 10px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .btn-register:active {
            transform: translateY(0);
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
        }
        
        .login-link p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }
        
        .login-link a {
            color: #667eea;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .login-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 25px;
            font-weight: 500;
        }
        
        .alert-danger {
            background: #fee;
            color: #c33;
        }
        
        .alert-success {
            background: #efe;
            color: #3c3;
        }
    </style>
</head>

<body>
    <div class="registration-wrapper">
        <div class="registration-container">
            <div class="registration-header">
                <h2><i class="fas fa-user-plus"></i> Register</h2>
                <p>Join QuickCart and start shopping with us today</p>
            </div>
            
            <?php if(!empty($error_msg)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($success_msg)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" id="first_name" class="form-control" placeholder="Enter your first name"
                        autocomplete="off" required="required" name="first_name" />
                </div>
                
                <div class="form-group">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" id="last_name" class="form-control" placeholder="Enter your last name"
                        autocomplete="off" name="last_name" />
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" class="form-control" placeholder="Enter your email"
                        autocomplete="off" required="required" name="email" />
                </div>
                
                <div class="form-group">
                    <label for="phone_no" class="form-label">Phone Number</label>
                    <input type="tel" id="phone_no" class="form-control" placeholder="Enter your phone number"
                        autocomplete="off" required="required" name="phone_no" />
                </div>
                
                <div class="form-group">
                    <label for="address_street" class="form-label">Street Address</label>
                    <input type="text" id="address_street" class="form-control" placeholder="Enter street address"
                        autocomplete="off" required="required" name="address_street" />
                </div>
                
                <div class="form-group">
                    <label for="address_city" class="form-label">City</label>
                    <input type="text" id="address_city" class="form-control" placeholder="Enter your city"
                        autocomplete="off" required="required" name="address_city" />
                </div>
                
                <div class="form-group">
                    <label for="address_state" class="form-label">State</label>
                    <input type="text" id="address_state" class="form-control" placeholder="Enter your state"
                        autocomplete="off" required="required" name="address_state" />
                </div>
                
                <div class="form-group">
                    <label for="pincode" class="form-label">Postal Code</label>
                    <input type="text" id="pincode" class="form-control" placeholder="Enter postal code"
                        autocomplete="off" required="required" name="pincode" />
                </div>
                
                <div class="form-group">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" class="form-select" required="required" name="gender">
                        <option value="" disabled selected>Select your gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Enter password (min. 6 chars)"
                        autocomplete="off" required="required" name="password" />
                </div>
                
                <div class="form-group">
                    <label for="conf_customer_password" class="form-label">Confirm Password</label>
                    <input type="password" id="conf_customer_password" class="form-control" placeholder="Confirm password"
                        autocomplete="off" required="required" name="conf_customer_password" />
                </div>
                
                <button type="submit" name="customer_register" class="btn btn-register">
                    <i class="fas fa-user-check"></i> Create Account
                </button>
                
                <div class="login-link">
                    <p>Already have an account? <a href="customer_login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- bootstrap JS link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>
    