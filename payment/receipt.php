<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($orderId == 0)
{
    header("Location: ../customer/menu.php");
    exit();
}

$orderSql = "SELECT * FROM orders 
             WHERE order_id = $orderId 
             AND customer_id = $customer_id";

$orderResult = mysqli_query($conn, $orderSql);
$order = mysqli_fetch_assoc($orderResult);

if (!$order)
{
    header("Location: ../customer/menu.php");
    exit();
}

$paymentSql = "SELECT * FROM payment 
               WHERE order_id = $orderId";

$paymentResult = mysqli_query($conn, $paymentSql);
$payment = mysqli_fetch_assoc($paymentResult);

$itemsSql = "SELECT menu.name, order_menu.quantity, order_menu.subtotal
             FROM order_menu
             INNER JOIN menu ON order_menu.menu_id = menu.menu_id
             WHERE order_menu.order_id = $orderId";

$itemsResult = mysqli_query($conn, $itemsSql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt - UTeM Cafeteria</title>

    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/cart.css">

    <style>
        .status-badge {
            background:#D4EDDA;
            color:#155724;
            padding:8px 16px;
            border-radius:20px;
            font-weight:bold;
            font-size:13px;
            display:inline-block;
            margin-bottom:20px;
        }

        .meta-box {
            background:#F8F9FA;
            padding:15px;
            border-radius:12px;
            font-size:13.5px;
            color:#555;
            margin-bottom:25px;
            border-left:5px solid #163B73;
            text-align:left;
        }

        .meta-box div {
            margin-bottom:6px;
        }

        .receipt-bill {
            border:1px dashed #B8C4D1;
            background:#FFF;
            border-radius:12px;
            padding:20px;
            margin-bottom:25px;
        }

        .item-row {
            display:flex;
            justify-content:space-between;
            margin-bottom:12px;
            font-size:14.5px;
        }

        .item-name {
            font-weight:600;
            color:#333;
        }

        .divider {
            border-top:1px dashed #B8C4D1;
            margin:15px 0;
        }

        .total-row {
            display:flex;
            justify-content:space-between;
            font-weight:bold;
            font-size:18px;
            color:#163B73;
        }
    </style>
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container" style="text-align:center;">

    <h1>Transaction Receipt</h1>

    <div class="status-badge">
        Payment Successful
    </div>

    <div class="meta-box">

        <div>
            <strong>Transaction ID:</strong>
            <?php
            if ($payment)
            {
                echo "PAY-" . $payment['payment_id'];
            }
            else
            {
                echo "Not available";
            }
            ?>
        </div>

        <div>
            <strong>Date:</strong>
            <?php echo date("d/m/Y", strtotime($order['order_date'])); ?>
        </div>

        <div>
            <strong>Payment Via:</strong>
            <?php
            if ($payment)
            {
                echo $payment['method'];
            }
            else
            {
                echo "Not available";
            }
            ?>
        </div>

    </div>

    <div class="receipt-bill">

        <?php if (mysqli_num_rows($itemsResult) > 0): ?>

            <?php while ($item = mysqli_fetch_assoc($itemsResult)): ?>

                <div class="item-row">

                    <div style="text-align:left; flex:1;">
                        <div class="item-name">
                            <?php echo $item['name']; ?>
                        </div>
                    </div>

                    <div style="width:50px; text-align:center; color:#555;">
                        x<?php echo $item['quantity']; ?>
                    </div>

                    <div style="width:80px; text-align:right; font-weight:500;">
                        RM <?php echo number_format($item['subtotal'], 2); ?>
                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p>No items found for this order.</p>

        <?php endif; ?>

        <div class="divider"></div>

        <div class="total-row">
            <span>Amount Paid</span>
            <span>
                RM <?php echo number_format($order['total_amount'], 2); ?>
            </span>
        </div>

    </div>

    <p class="subtitle" style="font-style:italic; font-size:14px;">
        Thank you. Your order has been submitted to the kitchen.
    </p>

    <a href="../account/dashboard.php" 
       class="btn" 
       style="display:block; text-align:center; padding:14px; background:#163B73; color:white; margin-top:20px;">
        Back to Home
    </a>

</div>

</body>
</html>