<?php

session_start();

if (!isset($_SESSION['customer_id']))
{
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Home - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="dashboard">

    <div class="welcome-box">
        <h1>Welcome, <?php echo $_SESSION['customer_name']; ?>!</h1>
        <p>You are logged in. You can now browse menu and place orders.</p>
    </div>

    <div class="card-row">

        <div class="card">
            <img src="../images/profile.png" class="card-image">
            <h3>Customer Profile</h3>
            <p>View your customer details</p>
            <br>
            <a href="../account/profile.php" class="btn">View Profile</a>
        </div>

        <div class="card">
            <img src="../images/menu.png" class="card-image">
            <h3>Menu</h3>
            <p>Browse available food and drinks</p>
            <br>
            <a href="../customer/menu.php" class="btn">Browse Menu</a>
        </div>

        <div class="card">
            <img src="../images/cart.png" class="card-image">
            <h3>Cart</h3>
            <p>View and manage your cart items</p>
            <br>
            <a href="../customer/cart.php" class="btn">View Cart</a>
        </div>

        <div class="card">
            <img src="../images/order.png" class="card-image">
            <h3>My Orders</h3>
            <p>Track your current and past orders</p>
            <br>
            <a href="../customer/order.php" class="btn">View Orders</a>
        </div>

    </div>

</div>

</body>
</html>
