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

$sql = "SELECT * FROM orders
        WHERE order_id = $order_id
        AND customer_id = $customer_id";

$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);

if (!$order)
{
    header("Location: order.php");
    exit();
}

$statuses = [
    "Received" => false,
    "Preparing" => false,
    "Completed" => false
];

if ($order['status'] == "Pending" || $order['status'] == "Preparing" || $order['status'] == "Completed")
{
    $statuses["Received"] = true;
}

if ($order['status'] == "Preparing" || $order['status'] == "Completed")
{
    $statuses["Preparing"] = true;
}

if ($order['status'] == "Completed")
{
    $statuses["Completed"] = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Order Tracking - UTeM Cafeteria</title>

    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/order.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container">

    <div class="welcome-card">
        <h1>Live Order Tracking</h1>

        <p>
            Checking real-time progress for Order
            <strong>
                #UTEM-<?php echo str_pad($order['order_id'], 3, '0', STR_PAD_LEFT); ?>
            </strong>
        </p>
    </div>

    <?php if ($order['status'] == "Preparing"): ?>

        <div class="info-box">
            Your order is being prepared.
        </div>

    <?php elseif ($order['status'] == "Pending"): ?>

        <div class="info-box">
            Your order has been received.
        </div>

    <?php elseif ($order['status'] == "Completed"): ?>

        <div class="success-box">
            Your order has been completed.
        </div>

    <?php endif; ?>

    <div style="margin:15px 0; text-align:center;">
        <a href="order.php">
            <button class="add-to-cart-btn" style="width:auto; padding:10px 30px;">
                Back to Orders
            </button>
        </a>
    </div>

    <div class="menu-card" style="text-align:left; max-width:600px; margin:0 auto;">

        <h3>Order Progress Timeline</h3>

        <div class="status-step <?php echo $statuses['Received'] ? 'done' : 'next'; ?>">
            <strong>Order Received:</strong>
            Your order has been received by the cafeteria.
        </div>

        <div class="status-step <?php echo $statuses['Preparing'] ? 'done' : 'next'; ?>">
            <strong>Preparing:</strong>
            Your food is being prepared in the kitchen.
        </div>

        <div class="status-step <?php echo $statuses['Completed'] ? 'done' : 'next'; ?>">
            <strong>Completed:</strong>
            Your order has been completed.
        </div>

    </div>

</div>

</body>
</html>