<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['operator_id']))
{
    header("Location: operator-login.php");
    exit();
}

$message = "";

if (isset($_POST['update_status']))
{
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $sqlUpdate = "UPDATE orders 
                  SET status='$status' 
                  WHERE order_id='$order_id'";

    if (mysqli_query($conn, $sqlUpdate))
    {
        $message = "Order status updated successfully.";
    }
    else
    {
        $message = "Failed to update order status.";
    }
}

$sqlOrders = "
    SELECT orders.order_id, orders.order_date, orders.total_amount, orders.status,
           customer.name AS customer_name,
           GROUP_CONCAT(menu.name SEPARATOR ', ') AS items
    FROM orders
    INNER JOIN customer ON orders.customer_id = customer.customer_id
    INNER JOIN order_menu ON orders.order_id = order_menu.order_id
    INNER JOIN menu ON order_menu.menu_id = menu.menu_id
    GROUP BY orders.order_id, orders.order_date, orders.total_amount, orders.status, customer.name
    ORDER BY orders.order_date DESC
";

$resultOrders = mysqli_query($conn, $sqlOrders);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - UTeM Cafeteria</title>

    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/order.css">
</head>
<body>

<?php include("../includes/operator-head.php"); ?>

<div class="container">

    <div class="welcome-card">
        <h1>Manage Orders</h1>
        <p>View customer orders and update order status.</p>
    </div>

    <?php if (!empty($message)): ?>
        <div class="info-box">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="menu-grid">

        <?php if (mysqli_num_rows($resultOrders) > 0): ?>

            <?php while ($row = mysqli_fetch_assoc($resultOrders)): ?>

                <?php
                    $statusClass = "yellow";

                    if ($row['status'] == "Pending")
                    {
                        $statusClass = "yellow";
                    }
                    elseif ($row['status'] == "Preparing")
                    {
                        $statusClass = "blue";
                    }
                    elseif ($row['status'] == "Completed")
                    {
                        $statusClass = "green";
                    }
                ?>

                <div class="menu-card">

                    <h3>
                        Order #UTEM-<?php echo str_pad($row['order_id'], 3, '0', STR_PAD_LEFT); ?>
                    </h3>

                    <p class="order-items">
                        <strong>Customer:</strong> <?php echo $row['customer_name']; ?><br>
                        <strong>Items:</strong> <?php echo $row['items']; ?><br>
                        <strong>Date:</strong> <?php echo date("d/m/Y", strtotime($row['order_date'])); ?>
                    </p>

                    <p class="order-price">
                        Total: RM <?php echo number_format($row['total_amount'], 2); ?>
                    </p>

                    <button class="status-btn <?php echo $statusClass; ?>">
                        Status: <?php echo $row['status']; ?>
                    </button>

                    <form method="POST" style="margin-top:15px;">

                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">

                        <label><strong>Update Status</strong></label>

                        <select name="status" required style="width:100%; padding:10px; margin:8px 0; border-radius:8px; border:1px solid #ccc;">
                            <option value="Pending" <?php if ($row['status'] == "Pending") echo "selected"; ?>>Pending</option>
                            <option value="Preparing" <?php if ($row['status'] == "Preparing") echo "selected"; ?>>Preparing</option>
                            <option value="Completed" <?php if ($row['status'] == "Completed") echo "selected"; ?>>Completed</option>
                        </select>

                        <button type="submit" name="update_status" class="add-to-cart-btn">
                            Update Status
                        </button>

                    </form>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p>No orders found.</p>

        <?php endif; ?>

    </div>

</div>

</body>
</html>