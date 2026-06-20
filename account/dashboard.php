<?php
session_start();

if(!isset($_SESSION['customer_id']))
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
    <title>Customer Home</title>
    <link rel="stylesheet" type="text/css" href="../css/sakinah.css">
</head>

<body>

<?php include("../includes/head.php"); ?>

<div class="dashboard">

    <div class="welcome-box">

        <h1>Welcome, <?php echo $_SESSION['customer_name']; ?>!</h1>

    </div>

    <div class="card-row">

        <div class="card">

            <img src="../images/profile.png" class="card-image">

            <h3>Customer Profile</h3>

            <p>View your customer details</p>

            <br><br>

            <a href="profile.php" class="btn">View Profile</a>

        </div>

        <div class="card">

            <img src="../images/menu.png" class="card-image">

            <h3>Menu</h3>

            <p>Customers can browse available food and drinks</p>

            <br>

            <a href="#" class="btn">Browse Menu</a>

        </div>

        <div class="card">

            <img src="../images/cart.png" class="card-image">

            <h3>Cart</h3>

            <p>Customers can view and manage cart items</p>

            <br>

            <a href="#" class="btn">View Cart</a>

        </div>

    </div>

</div>

</body>
</html>