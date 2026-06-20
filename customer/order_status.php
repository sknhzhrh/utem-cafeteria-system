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
    <title>Live Order Tracking - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/order.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container">

    <div class="welcome-card">
        <h1>Live Order Tracking</h1>
        <p>Checking real-time progress for Order <strong>#UTM-991</strong></p>
    </div>

    <div style="margin:15px 0; text-align:center;">
        <a href="order.php">
            <button class="add-to-cart-btn" style="width:auto; padding:10px 30px;">← Back to Orders</button>
        </a>
    </div>

    <div class="menu-card" style="text-align:left; max-width:600px; margin:0 auto;">
        <h3>Order Progress Timeline</h3>

        <div class="status-step done">✓ <b>Order Received:</b> Your order has been received by the cafeteria.</div>
        <div class="status-step done">✓ <b>Preparing:</b> Your food is being cooked in the kitchen.</div>
        <div class="status-step active">⏳ <b>Ready:</b> Food is ready at the pickup counter!</div>
        <div class="status-step next">⚪ <b>Completed:</b> Awaiting student collection.</div>

        <div style="margin-top:25px;">
            <button class="add-to-cart-btn" onclick="checkNotification()">🔔 Check For Live Notification</button>
        </div>
    </div>

</div>

<script>
function checkNotification()
{
    alert("Alert: Your order #UTM-991 is ready! Please pick it up at the cafeteria counter.");
}
</script>

</body>
</html>
