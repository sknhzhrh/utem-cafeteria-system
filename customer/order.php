<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Fetch active/pending orders for this customer
$sql = "SELECT orders.order_id, orders.order_date, orders.total_amount, orders.status 
        FROM orders 
        WHERE customer_id = $customer_id AND status IN ('Pending', 'Preparing', 'Ready') 
        ORDER BY order_date DESC 
        LIMIT 5";
$result = mysqli_query($conn, $sql);
$orders = [];
while ($row = mysqli_fetch_assoc($result))
{
    $orders[] = $row;
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

        <?php if (empty($orders)): ?>
            <div style="text-align:center; padding:40px; grid-column: 1/-1;">
                <p style="font-size:18px; color:#888;">No active orders yet.</p>
                <a href="menu.php" class="btn" style="margin-top:20px;">Start Ordering</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <?php
                    // Fetch order items
                    $orderId = $order['order_id'];
                    $itemsSql = "SELECT m.name, om.quantity FROM order_menu om 
                                 INNER JOIN menu m ON om.menu_id = m.menu_id 
                                 WHERE om.order_id = $orderId";
                    $itemsResult = mysqli_query($conn, $itemsSql);
                    $items = [];
                    while ($item = mysqli_fetch_assoc($itemsResult))
                    {
                        $items[] = $item;
                    }
                    
                    $statusClass = "yellow";
                    $statusText = "Pending";
                    if ($order['status'] == 'Ready') { $statusClass = "green"; $statusText = "Ready to Pick Up ✓"; }
                    elseif ($order['status'] == 'Preparing') { $statusClass = "yellow"; $statusText = "Preparing"; }
                ?>
                <div class="menu-card">
                    <h3>Order #UTEM-<?php echo str_pad($orderId, 3, '0', STR_PAD_LEFT); ?></h3>
                    <p class="order-items">
                        <?php
                            $itemsDisplay = [];
                            foreach ($items as $item)
                            {
                                $itemsDisplay[] = $item['name'] . " x" . $item['quantity'];
                            }
                            echo implode("<br>", $itemsDisplay);
                        ?>
                    </p>
                    <p class="order-price">Total: RM <?php echo number_format($order['total_amount'], 2); ?></p>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <button class="status-btn <?php echo $statusClass; ?>">Status: <?php echo $statusText; ?></button>
                        <a href="order_status.php?order_id=<?php echo $orderId; ?>"><button class="add-to-cart-btn">Track Live Status</button></a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

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
