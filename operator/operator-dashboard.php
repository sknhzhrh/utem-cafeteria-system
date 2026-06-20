<?php

session_start();

if (!isset($_SESSION['operator_id']))
{
    header("Location: operator-login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Dashboard - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
</head>
<body>

<?php include("../includes/operator-head.php"); ?>

<div class="dashboard">

    <div class="welcome-box">
        <h1>Welcome, <?php echo $_SESSION['operator_name']; ?>!</h1>
        <p>Manage cafeteria orders, menu items and sales reports.</p>
    </div>

    <div class="card-row">

        <div class="card">
            <img src="../images/order.png" class="card-image">
            <h3>Manage Orders</h3>
            <p>View and update customer orders.</p>
            <br>
            <a href="order.php" class="btn">View Orders</a>
        </div>

        <div class="card">
            <img src="../images/menu.png" class="card-image">
            <h3>Manage Menu</h3>
            <p>Add, edit and remove menu items.</p>
            <br>
            <a href="manage-menu.php" class="btn">Manage Menu</a>
        </div>

        <div class="card">
            <img src="../images/report.png" class="card-image">
            <h3>Sales Reports</h3>
            <p>View daily cafeteria sales reports.</p>
            <br>
            <a href="operator-report.php" class="btn">View Reports</a>
        </div>

    </div>

</div>

</body>
</html>
