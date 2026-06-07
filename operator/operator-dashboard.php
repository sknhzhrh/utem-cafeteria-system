<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTeM Cafeteria Operator</title>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>

<body>

<div class="navbar">

    <div class="logo">
        <h2>UTeM Campus Food Ordering System</h2>
    </div>

    <div class="nav-links">
        <a href="operator-dashboard.php">Dashboard</a>
        <a href="#">Orders</a>
        <a href="#">Menu</a>
        <a href="report.php">Reports</a>
        <a href="../operator/operator-login.php">Logout</a>
    </div>

</div>

<div class="dashboard">

    <div class="welcome-box">

        <h1>Welcome, Cafeteria Operator!</h1>

    </div>

    <div class="card-row">

        <div class="card">

            <img src="../images/order.png" class="card-image">

            <h3>Manage Orders</h3>

            <p>
                View and update customer orders.
            </p>

            <br>

            <a href="#" class="btn">View Orders</a>

        </div>

        <div class="card">

            <img src="../images/menu.png" class="card-image">

            <h3>Manage Menu</h3>

            <p>
                Add, edit and remove menu items.
            </p>

            <br>

            <a href="#" class="btn">Manage Menu</a>

        </div>

        <div class="card">

            <img src="../images/report.png" class="card-image">

            <h3>Sales Reports</h3>

            <p>
                View daily cafeteria sales reports.
            </p>

            <br>

            <a href="report.php" class="btn">View Reports</a>

        </div>

    </div>

</div>

</body>
</html>