<?php

session_start();

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/order.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container">

    <div class="welcome-card">
        <h1>Your Past Orders</h1>
        <p>History of all completed and picked-up meals.</p>
    </div>

    <div style="margin:15px 0; text-align:center;">
        <a href="order.php">
            <button class="add-to-cart-btn" style="width:auto; padding:10px 30px;">← Back to Orders</button>
        </a>
    </div>

    <div class="menu-grid">

        <div class="menu-card">
            <h3>Order #UTM-910</h3>
            <p class="order-items">Nasi Ayam x1<br>Sirap Bandung x1</p>
            <p class="order-price">Total Paid: RM 8.50</p>
            <button class="status-btn green">✓ Completed on 04/06/2026</button>
        </div>

        <div class="menu-card">
            <h3>Order #UTM-854</h3>
            <p class="order-items">Burger Ramly x1<br>Milo Ais x1</p>
            <p class="order-price">Total Paid: RM 9.50</p>
            <button class="status-btn green">✓ Completed on 01/06/2026</button>
        </div>

    </div>

</div>

</body>
</html>
