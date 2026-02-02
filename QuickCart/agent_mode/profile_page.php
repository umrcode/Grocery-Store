<?php
include ('../includes/connect.php'); // Connect file to MySQL - already did in common_function.php and including that file
// include ('../functions/common_function.php'); // Common functions file

if (isset ($_GET['agent_id'])) {
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
    $dob = $row_data['dob'];
    $age = $row_data['age'];
    $agent_status = $row_data['availabilityStatus'];

    // Check if last_name is null
    if ($agent_lname === null) {
        $agent_name = $agent_fname;
    } else {
        $agent_name = $agent_fname . ' ' . $agent_lname;
    }

    $get_data = "SELECT * FROM delivery_agent_wallet WHERE agentID = $agent_id;";
    $result_get = mysqli_query($con, $get_data);
    $row_data = mysqli_fetch_assoc($result_get);
    $earning_balance = $row_data["earning_balance"];
    $earning_paid = $row_data["earning_paid"];
    $earning_total = $row_data["earning_total"];

    // setting next page link
    if ($agent_status == "Offline") {
        $nextPage = "offlineHome";
    } else {
        $nextPage = "home";
    }

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <!-- bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS file link -->
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            overflow-x: hidden;
            background: #f8f9fa;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
            color: #667eea !important;
        }

        .nav-link {
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #764ba2 !important;
        }

        .button {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 20px 0;
        }

        .button .nav-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border-radius: 10px;
            padding: 10px 20px !important;
            min-height: auto;
            max-width: 150px;
            text-align: center;
            white-space: normal;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 14px;
        }

        .button .nav-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .profile-header {
            text-align: center;
            margin: 30px 0;
        }

        .profile-header h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 40px;
            margin: 30px auto;
            max-width: 700px;
        }

        .profile-field {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 0;
            border-bottom: 1px solid #e8e8e8;
            font-size: 16px;
        }

        .profile-field:last-child {
            border-bottom: none;
        }

        .profile-label {
            font-weight: 700;
            color: #667eea;
            min-width: 150px;
        }

        .profile-value {
            color: #333;
            font-weight: 500;
            text-align: right;
            flex: 1;
            word-break: break-word;
        }

        .edit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 40px;
            border-radius: 10px;
            font-weight: 700;
            margin-top: 30px;
            transition: all 0.3s ease;
            cursor: pointer;
            width: 100%;
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .footer {
            margin-top: 50px;
            background: #f0f2f5;
            padding: 30px 0;
        }
    </style>
</head>

<body>

    <!-- responsive navbar - container fluid is a bootstrap class which takes complete 100% width -->
    <div class="container-fluid p-0">

        <nav class="navbar navbar-expand-lg bg-info">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><i class="fa fa-shopping-basket" aria-hidden="true"> QuickCart</i></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">

                            <a class="nav-link active" aria-current="page"
                                href='profile_page.php?agent_id=<?php echo "$agent_id"; ?>'>My Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page"
                                href="index.php?agent_id=<?php echo "$agent_id" . '&' . $nextPage; ?>">Home</a>
                        </li>

                </div>
                <nav class="navbar navbar-expand-lg">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a href="" class="nav-link text-dark">Welcome
                                <?php echo "$agent_name"; ?>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </nav>

        <!-- second child -->

        <div class="row">
            <div class="col-md-12 bg-secondary p-1 align-items-center">
                <div class="button text-center">
                    <button class="my-3 mx-2"><a
                            href="profile_page.php?agent_id=<?php echo "$agent_id"; ?>&view_profile"
                            class="nav-link text-dark bg-info my-1 mx-1">View Profile</a></button>
                    <button class="my-3 mx-2"><a
                            href="profile_page.php?agent_id=<?php echo "$agent_id"; ?>&wallet_history"
                            class="nav-link text-dark bg-info my-1 mx-1">Your Wallet History</a></button>
                    <button class="my-3 mx-2"><a href="profile_page.php?agent_id=<?php echo "$agent_id"; ?>&top_up"
                            class="nav-link text-dark bg-info my-1 mx-1">Withdraw Money</a></button>
                    <button class="my-3 mx-2"><a href="profile_page.php?agent_id=<?php echo "$agent_id"; ?>&history"
                            class="nav-link text-dark bg-info my-1 mx-1">Delivery History</a></button>
                    <button class="my-3 mx-2"><a
                            href="profile_page.php?agent_id=<?php echo "$agent_id"; ?>&view_reviews"
                            class="nav-link text-dark bg-info my-1 mx-1">My Reviews</a></button>
                    <button class="my-3 mx-2"><a href="../start.php"
                            class="nav-link text-dark bg-info my-1 mx-1">Logout</a></button>
                </div>
            </div>
        </div>



        <!-- third child -->
        <div class="container my-3">
            <?php
            if (isset ($_GET['view_profile'])) {
                include ('view_profile.php');
            }
            if (isset ($_GET['edit_profile'])) {
                include ('edit_profile.php');
            }
            if (isset ($_GET['history'])) {
                include ('agent_history.php');
            }
            if (isset ($_GET['top_up'])) {
                include ('top_up.php');
            }
            if (isset ($_GET['wallet_history'])) {
                include ('wallet_history.php');
            }
            if (isset ($_GET['view_reviews'])) {
                include ('agent_reviews.php');
            }
            ?>
        </div>



        <!-- last child - footer -->
        <?php
        include ("../includes/footer.php");
        ?>
    </div>




    <!-- bootstrap JS link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- JS file link -->
    <!-- <script src="script.js"></script> -->
</body>

</html>