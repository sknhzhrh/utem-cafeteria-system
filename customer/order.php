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
    <title>My Orders - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/order.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container">

    <div class="welcome-card">
        <h1>Order Tracking & History</h1>
        <p>Manage your active orders and view order history.</p>
        <div style="margin-top:15px;">
            <a href="order_history.php">
                <button class="add-to-cart-btn" style="background:#e1ecf4; color:#1a4380; width:auto; padding:8px 15px;">
                    📜 View Full Order History
                </button>
            </a>
        </div>
    </div>

    <div class="menu-grid">

        <div class="menu-card">
            <h3>Order #UTEM-991</h3>
            <p class="order-items">Kampung Fried Rice x2<br>Fresh Lemonade x1</p>
            <p class="order-price">Total: RM 20.50</p>
            <div style="display:flex; flex-direction:column; gap:8px;">
                <button class="status-btn yellow">Status: Preparing</button>
                <a href="order_status.php"><button class="add-to-cart-btn">Track Live Status</button></a>
            </div>
        </div>

        <div class="menu-card">
            <h3>Order #UTEM-985</h3>
            <p class="order-items">Mee Goreng x1</p>
            <p class="order-price">Total: RM 5.00</p>
            <div style="display:flex; flex-direction:column; gap:8px;">
                <button class="status-btn green">Status: Ready to Pick Up ✓</button>
                <a href="order_status.php"><button class="add-to-cart-btn">Track Live Status</button></a>
            </div>
        </div>

    </div>

</div>

<div id="foodready">🍽️ Food is ready! Please pick up your order.</div>

<script>
window.onload = function()
{
    const toast = document.getElementById('foodready');
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 5000);
}
</script>

</body>
</html>
