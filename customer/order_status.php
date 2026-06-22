<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Fetch order details
$sql = "SELECT * FROM orders WHERE order_id = $order_id AND customer_id = $customer_id";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);

if (!$order)
{
    header("Location: order.php");
    exit();
}

// Determine status steps
$statuses = ["Received" => false, "Preparing" => false, "Ready" => false, "Completed" => false];
if ($order['status'] == "Pending" || $order['status'] == "Preparing" || $order['status'] == "Ready" || $order['status'] == "Completed")
{
    $statuses["Received"] = true;
}
if ($order['status'] == "Preparing" || $order['status'] == "Ready" || $order['status'] == "Completed")
{
    $statuses["Preparing"] = true;
}
if ($order['status'] == "Ready" || $order['status'] == "Completed")
{
    $statuses["Ready"] = true;
}
if ($order['status'] == "Completed")
{
    $statuses["Completed"] = true;
}

$currentStatus = array_search(true, $statuses) === false ? 0 : array_search(true, $statuses) + 1;

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
        <p>Checking real-time progress for Order <strong>#UTEM-<?php echo str_pad($order['order_id'], 3, '0', STR_PAD_LEFT); ?></strong></p>
    </div>

    <div style="margin:15px 0; text-align:center;">
        <a href="order.php">
            <button class="add-to-cart-btn" style="width:auto; padding:10px 30px;">← Back to Orders</button>
        </a>
    </div>

    <div class="menu-card" style="text-align:left; max-width:600px; margin:0 auto;">
        <h3>Order Progress Timeline</h3>

        <div class="status-step <?php echo $statuses['Received'] ? 'done' : 'next'; ?>">
            <?php echo $statuses['Received'] ? '✓' : '⚪'; ?> <b>Order Received:</b> Your order has been received by the cafeteria.
        </div>
        <div class="status-step <?php echo $statuses['Preparing'] ? 'done' : 'next'; ?>">
            <?php echo $statuses['Preparing'] ? '✓' : '⚪'; ?> <b>Preparing:</b> Your food is being cooked in the kitchen.
        </div>
        <div class="status-step <?php echo $statuses['Ready'] ? ($order['status'] == 'Ready' ? 'active' : 'done') : 'next'; ?>">
            <?php echo $statuses['Ready'] ? ($order['status'] == 'Ready' ? '⏳' : '✓') : '⚪'; ?> <b>Ready:</b> Food is ready at the pickup counter!
        </div>
        <div class="status-step <?php echo $statuses['Completed'] ? 'done' : 'next'; ?>">
            <?php echo $statuses['Completed'] ? '✓' : '⚪'; ?> <b>Completed:</b> Awaiting student collection.
        </div>

        <div style="margin-top:25px;">
            <button class="add-to-cart-btn" onclick="checkNotification()">🔔 Check For Live Notification</button>
        </div>
    </div>

</div>

<script>
function checkNotification()
{
    const orderId = "<?php echo str_pad($order['order_id'], 3, '0', STR_PAD_LEFT); ?>";
    const status = "<?php echo $order['status']; ?>";
    
    if (status === "Ready")
    {
        alert("Alert: Your order #UTEM-" + orderId + " is ready! Please pick it up at the cafeteria counter.");
    }
    else if (status === "Preparing")
    {
        alert("Alert: Your order #UTEM-" + orderId + " is still being prepared. Please check back soon!");
    }
    else if (status === "Completed")
    {
        alert("Alert: Your order #UTEM-" + orderId + " has been completed. Thank you!");
    }
    else
    {
        alert("Alert: Your order #UTEM-" + orderId + " has been received. Status: " + status);
    }
}
</script>

</body>
</html>
