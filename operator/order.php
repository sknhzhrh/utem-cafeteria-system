<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['operator_id']))
{
    header("Location: operator-login.php");
    exit();
}

// Fetch all orders
$sql = "SELECT o.order_id, o.order_date, o.status, o.total_amount, c.name as customer_name 
        FROM orders o
        INNER JOIN customer c ON o.customer_id = c.customer_id
        ORDER BY o.order_date DESC
        LIMIT 50";
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
    <title>Manage Orders - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/order.css">
</head>
<body>

<?php include("../includes/operator-head.php"); ?>

<div class="container">

    <div class="card">
        <div class="title">
            <h2>Recent Orders</h2>
        </div>

        <table id="ordersTable">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <?php
                        // Fetch order items
                        $orderId = $order['order_id'];
                        $itemsSql = "SELECT m.name FROM order_menu om
                                     INNER JOIN menu m ON om.menu_id = m.menu_id
                                     WHERE om.order_id = $orderId
                                     LIMIT 1";
                        $itemsResult = mysqli_query($conn, $itemsSql);
                        $item = mysqli_fetch_assoc($itemsResult);
                        $itemName = $item ? $item['name'] : 'N/A';
                        
                        $statusClass = strtolower($order['status']);
                        $orderTime = date("h:i A", strtotime($order['order_date']));
                    ?>
                    <tr id="ORD<?php echo $orderId; ?>">
                        <td style="color:blue">ORD<?php echo str_pad($orderId, 3, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo $order['customer_name']; ?></td>
                        <td><?php echo $itemName; ?></td>
                        <td><span class="status <?php echo $statusClass; ?>"><?php echo $order['status']; ?></span></td>
                        <td><?php echo $orderTime; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card" style="margin-top:20px;">
        <h2>Update Order Status</h2>

        <table id="ordersStatus">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th class="center-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td style="color:blue">ORD<?php echo str_pad($order['order_id'], 3, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo $order['customer_name']; ?></td>
                        <td>
                            <select id="status_<?php echo $order['order_id']; ?>">
                                <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Preparing" <?php echo $order['status'] == 'Preparing' ? 'selected' : ''; ?>>Preparing</option>
                                <option value="Ready" <?php echo $order['status'] == 'Ready' ? 'selected' : ''; ?>>Ready</option>
                                <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </td>
                        <td class="center-action"><button onclick="updateStatus(<?php echo $order['order_id']; ?>)">Update</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
function updateStatus(orderId)
{
    const newStatus = document.getElementById("status_" + orderId).value;
    const formData = new URLSearchParams();
    formData.append("order_id", orderId);
    formData.append("status", newStatus);

    fetch("../api/update-order-status.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        const params = new URLSearchParams(text);
        const success = params.get("success") === "1";
        const message = params.get("message") || "Failed to update order";

        if (success)
        {
            const row = document.getElementById("ORD" + orderId);
            if (row)
            {
                const statusEl = row.querySelector(".status");
                if (statusEl)
                {
                    statusEl.className = "status " + newStatus.toLowerCase();
                    statusEl.textContent = newStatus;
                }
            }
            alert("Order #ORD" + String(orderId).padStart(3, '0') + " updated to: " + newStatus);
        }
        else
        {
            alert("Error: " + message);
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("Error updating order status");
    });
}
</script>

</body>
</html>
