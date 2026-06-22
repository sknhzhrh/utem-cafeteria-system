<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

$cart = isset($_SESSION['temp_cart']) ? $_SESSION['temp_cart'] : [];

if (empty($cart))
{
    header("Location: ../customer/menu.php");
    exit();
}

$total = 0;
foreach ($cart as $item)
{
    $total += floatval($item['subtotal'] ?? 0);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Checkout - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/cart.css">
    <style>
        .summary-box { border:1px solid #E3E7EB; border-radius:12px; padding:20px; margin-bottom:25px; background:#FAFBFC; }
        .summary-box h3 { color:#274472; margin-bottom:12px; }
        .item-row { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:15px; font-size:15px; }
        .item-name { font-weight:600; color:#333; }
        .item-customization { font-size:12px; color:#666; margin-top:4px; line-height:1.5; }
        .item-note { font-style:italic; color:#C0392B; font-weight:500; }
        .divider { border-top:1px solid #E3E7EB; margin:15px 0; }
        .total-row { display:flex; justify-content:space-between; font-size:18px; font-weight:bold; color:#163B73; margin-top:10px; }
        .radio-group { display:flex; flex-direction:column; gap:12px; margin-top:10px; }
        .radio-label { display:flex; align-items:center; gap:12px; background:#F4F6F8; padding:14px; border-radius:10px; cursor:pointer; border:1px solid #E3E7EB; transition:0.2s; }
        .radio-label:hover { background:#EAEFF5; }
        .radio-label input { width:auto; margin:0; }
        .btn-pay { background:#163B73; color:white; border:none; border-radius:12px; font-weight:bold; width:100%; padding:14px; font-size:16px; cursor:pointer; margin-top:25px; }
        .btn-pay:hover { background:#0F2C57; }
    </style>
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container">

    <h1>Checkout</h1>
    <p class="subtitle">Review your order and complete payment.</p>

    <div class="summary-box">
        <h3>Order Summary</h3>
        <div class="divider"></div>
        <div id="checkoutItemsContainer">
            <?php foreach ($cart as $item): ?>
                <?php
                    $sub = floatval($item['subtotal'] ?? 0);
                    $custom = '';
                    if (!empty($item['spicy']))
                        $custom .= '<div>• Spicy: ' . htmlspecialchars($item['spicy']) . '</div>';
                    if (!empty($item['drink']))
                        $custom .= '<div>• Beverage: ' . htmlspecialchars($item['drink']) . '</div>';
                    if (!empty($item['addons']))
                        $custom .= '<div>• Add-ons: ' . htmlspecialchars(implode(", ", (array)$item['addons'])) . '</div>';
                    if (!empty($item['note']))
                        $custom .= '<div class="item-note">• Note: "' . htmlspecialchars($item['note']) . '"</div>';
                ?>
                <div class="item-row">
                    <div style="flex:1;">
                        <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                        <?php if ($custom !== ''): ?>
                            <div class="item-customization"><?php echo $custom; ?></div>
                        <?php endif; ?>
                    </div>
                    <div style="width:50px; text-align:center; color:#555;">x<?php echo (int)$item['quantity']; ?></div>
                    <div style="width:80px; text-align:right; font-weight:bold; color:#274472;">RM <?php echo number_format($sub, 2); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="divider"></div>
        <div class="total-row">
            <span>Grand Total</span>
            <span>RM <?php echo number_format($total, 2); ?></span>
        </div>
    </div>

    <form method="POST" action="../api/process-payment.php">
        <input type="hidden" name="total" value="<?php echo number_format($total, 2, '.', ''); ?>">

        <div class="form-group">
            <label>Choose Payment Method</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="paymentMethod" value="fpx" checked>
                    <strong>FPX Online Banking</strong>
                </label>
                <label class="radio-label">
                    <input type="radio" name="paymentMethod" value="ewallet">
                    <strong>E-Wallet (Touch 'n Go / GrabPay)</strong>
                </label>
            </div>
        </div>

        <button class="btn-pay" type="submit">Confirm & Pay Now</button>
    </form>

    <div style="text-align:center; margin-top:20px;">
        <a href="../customer/cart.php" class="btn">← Back to Cart</a>
    </div>

</div>

<script>

</script>

</body>
</html>
