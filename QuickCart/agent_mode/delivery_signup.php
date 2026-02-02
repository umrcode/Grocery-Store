<?php
include ('../includes/connect.php');

$error_msg = "";
$success_msg = "";

if (isset ($_POST['delivery_signup'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_no = $_POST['phone_no'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conf_password = $_POST['conf_agent_password'];

    // Validation
    if (empty($first_name) || empty($phone_no) || empty($email) || empty($password)) {
        $error_msg = "All required fields must be filled!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please enter a valid email address!";
    } else if (strlen($password) < 6) {
        $error_msg = "Password must be at least 6 characters long!";
    } else if ($password != $conf_password) {
        $error_msg = "Passwords do not match. Please try again.";
    } else {
        $select_total = "SELECT * FROM deliveryAgent;";
        $result_total = mysqli_query($con, $select_total);
        $row_total = mysqli_num_rows($result_total); 
        $select_query = "SELECT * FROM deliveryAgent WHERE email='$email' OR phone_no='$phone_no';";
        $result = mysqli_query($con, $select_query);
        $rows_count = mysqli_num_rows($result);
        
        if ($rows_count > 0) {
            $error_msg = "Email or Phone Number already registered. Please login or use different credentials.";
        } else {
            $insert_query = "INSERT INTO deliveryAgent (first_name, last_name, availabilityStatus, phone_no, email, password, dob, age) values ('$first_name','$last_name', DEFAULT, '$phone_no','$email','$password', DEFAULT, DEFAULT);";
            $sql_execute = mysqli_query($con, $insert_query);

            if ($sql_execute) {
                $new_cust = $row_total+1;
                $upi_id = 'agent'. $new_cust . '@upi';

                $current_date = date("d-m-Y");
                $trans = "$current_date^ You Created QuickCart Wallet Account!^ 0^ 0^ 0";

                $insert_wallet = "INSERT INTO delivery_agent_wallet (agentID, earning_balance, earning_paid, earning_total, Transaction_history, upiID) VALUES
                ('$new_cust', DEFAULT, DEFAULT, DEFAULT, '$trans', '$upi_id');";
                $sql_wallet = mysqli_query($con, $insert_wallet);
                
                if ($sql_wallet) {
                    $success_msg = "Registration successful! Redirecting to login...";
                    echo "<script>setTimeout(function(){ window.location.href = 'delivery_login.php'; }, 2000);</script>";
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
    <title>Delivery Agent Registration</title>
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
        
        .signup-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }
        
        .signup-container {
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
        
        .signup-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .signup-header h2 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            margin-bottom: 10px;
            font-size: 32px;
        }
        
        .signup-header i {
            font-size: 28px;
            margin-right: 8px;
        }
        
        .signup-header p {
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
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            color: #333;
        }
        
        .form-control:focus {
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
    <div class="signup-wrapper">
        <div class="signup-container">
            <div class="signup-header">
                <h2><i class="fas fa-truck"></i> Register as Delivery Agent</h2>
                <p>Join our delivery team and start earning</p>
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
                    <label for="first_name" class="form-label">First Name *</label>
                    <input type="text" id="first_name" class="form-control" placeholder="Enter your first name"
                        autocomplete="off" required="required" name="first_name" />
                </div>
                
                <div class="form-group">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" id="last_name" class="form-control" placeholder="Enter your last name"
                        autocomplete="off" name="last_name" />
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" id="email" class="form-control" placeholder="Enter your email"
                        autocomplete="off" required="required" name="email" />
                </div>
                
                <div class="form-group">
                    <label for="phone_no" class="form-label">Phone Number *</label>
                    <input type="tel" id="phone_no" class="form-control" placeholder="Enter your phone number"
                        autocomplete="off" required="required" name="phone_no" />
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password *</label>
                    <input type="password" id="password" class="form-control" placeholder="Enter password (min. 6 chars)"
                        autocomplete="off" required="required" name="password" />
                </div>
                
                <div class="form-group">
                    <label for="conf_agent_password" class="form-label">Confirm Password *</label>
                    <input type="password" id="conf_agent_password" class="form-control" placeholder="Confirm password"
                        autocomplete="off" required="required" name="conf_agent_password" />
                </div>
                
                <button type="submit" name="delivery_signup" class="btn btn-register">
                    <i class="fas fa-user-check"></i> Create Account
                </button>
                
                <div class="login-link">
                    <p>Already have an account? <a href="delivery_login.php">Login here</a></p>
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