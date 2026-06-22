<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT order_id, order_date, total_amount, status
        FROM orders
        WHERE customer_id = $customer_id
        ORDER BY order_date DESC";

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
    <title>Order History - UTeM Cafeteria</title>

    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/order.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container">

    <div class="welcome-card">
        <h1>Your Past Orders</h1>
        <p>History of all completed meals.</p>
    </div>

    <div style="margin:15px 0; text-align:center;">
        <a href="order.php">
            <button class="add-to-cart-btn" style="width:auto; padding:10px 30px;">
                Back to Orders
            </button>
        </a>
    </div>

    <div class="menu-grid">

        <?php if (empty($orders)): ?>

            <div style="text-align:center; padding:40px; grid-column:1/-1;">
                <p style="font-size:18px; color:#888;">No order history yet.</p>
                <a href="menu.php" class="btn" style="margin-top:20px;">Start Ordering</a>
            </div>

        <?php else: ?>

            <?php foreach ($orders as $order): ?>

                <?php
                    $orderId = $order['order_id'];

                    $itemsSql = "SELECT menu.name, order_menu.quantity
                                 FROM order_menu
                                 INNER JOIN menu ON order_menu.menu_id = menu.menu_id
                                 WHERE order_menu.order_id = $orderId";

                    $itemsResult = mysqli_query($conn, $itemsSql);

                    $itemsDisplay = [];

                    while ($item = mysqli_fetch_assoc($itemsResult))
                    {
                        $itemsDisplay[] = $item['name'] . " x" . $item['quantity'];
                    }

                    $orderDate = date("d/m/Y", strtotime($order['order_date']));
                    $statusClass = "yellow";

                    if ($order['status'] == "Pending") {
                        $statusClass = "yellow";
                        $statusText = "Pending";
                    } elseif ($order['status'] == "Preparing") {
                        $statusClass = "blue";
                        $statusText = "Preparing";
                    } elseif ($order['status'] == "Completed") {
                        $statusClass = "green";
                        $statusText = "Completed";
                    } else {
                        $statusClass = "gray";
                        $statusText = $order['status'];
                    }
                ?>

                <div class="menu-card">

                    <h3>
                        Order #UTEM-<?php echo str_pad($orderId, 3, '0', STR_PAD_LEFT); ?>
                    </h3>

                    <p class="order-items">
                        <?php echo implode("<br>", $itemsDisplay); ?>
                    </p>

                    <p class="order-price">
                        Total Paid: RM <?php echo number_format($order['total_amount'], 2); ?>
                    </p>

                    <button class="status-btn <?php echo $statusClass; ?>">
                        <?php echo $statusText; ?> on <?php echo $orderDate; ?>
                    </button>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>

</body>
</html>