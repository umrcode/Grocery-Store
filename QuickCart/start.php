<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to QuickCart Pakistan - Online Shopping</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-RE7UfBeNBJvXd/9SBwzDWjuQFMJFGF9ILrji6TwRVzhZdY9/G4M+n3W57sJa0K/dgnALyi4XubYjxLmVl4OeOg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            text-align: center;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        h1 {
            color: white;
            margin-top: 20px;
            font-size: 45px;
            font-weight: 700;
        }
        .subtitle {
            color: #f0f0f0;
            font-size: 18px;
            margin-bottom: 30px;
        }
        h2 {
            color: #343a40;
            font-size: 25px;
        }
        .options {
            list-style-type: none;
            padding: 0;
            margin-top: 30px;
        }
        .option {
            display: inline-block;
            margin: 20px;
        }
        .option a {
            text-decoration: none;
            color: #fff;
            font-size: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: block;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .option a:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }
    </style>
</head>
<body>
    <h1><i class="fas fa-shopping-basket"></i> QuickCart Pakistan</h1>
    <p class="subtitle">Your Trusted Online Shopping Platform</p>
    <h2 style="color: white; font-size: 20px;">Select Your Role:</h2>
    <ul class="options">
        <li class="option">
            <a href="./customer_mode/customer_login.php">
                <i class="fas fa-shopping-cart"></i> Customer
            </a>
        </li>
        <li class="option">
            <a href="./admin_mode/admin_login.php">
                <i class="fas fa-user-shield"></i> Admin
            </a>
        </li>
        <li class="option">
            <a href="./agent_mode/delivery_login.php">
                <i class="fas fa-truck"></i> Delivery Agent
            </a>
        </li>
    </ul>
</body>
</html>
