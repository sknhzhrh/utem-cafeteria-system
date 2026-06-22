<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

// Get cart from session
$cart = isset($_SESSION['temp_cart']) ? $_SESSION['temp_cart'] : [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="dashboard">

    <div class="welcome-box">
        <h1>Your Cart 🛒</h1>
    </div>

    <div id="cartBox">
        <?php if (empty($cart)): ?>
            <div style="text-align:center; padding:40px;">
                <p style="font-size:18px; color:#888;">Your cart is empty.</p>
                <a href="menu.php" class="btn" style="margin-top:20px;">Browse Menu</a>
            </div>
        <?php else: ?>
            <?php 
                $total = 0;
                foreach ($cart as $index => $item):
                    $sub = $item['subtotal'];
                    $total += $sub;
            ?>
                <div class="profile-info" style="margin:15px 0;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px;">
                        <div>
                            <strong style="font-size:16px; color:#274472;"><?php echo $item['name']; ?></strong>
                            <div style="font-size:12px; color:#666; margin-top:5px;">
                                <?php if ($item['spicy']): ?><div>• Spicy: <?php echo $item['spicy']; ?></div><?php endif; ?>
                                <?php if ($item['drink']): ?><div>• Drink: <?php echo $item['drink']; ?></div><?php endif; ?>
                                <?php if (!empty($item['addons'])): ?><div>• Add-ons: <?php echo implode(", ", $item['addons']); ?></div><?php endif; ?>
                                <?php if ($item['note']): ?><div style="color:#c0392b; font-style:italic;">• Note: "<?php echo $item['note']; ?>"</div><?php endif; ?>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:14px; color:#888;">RM <?php echo number_format($item['price'], 2); ?> each</div>
                            <div style="font-weight:bold; color:#163B73; font-size:16px;">RM <?php echo number_format($sub, 2); ?></div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; margin-top:12px;">
                        <form method="POST" action="../api/update-cart.php" style="display:inline; margin:0;">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <input type="hidden" name="action" value="decrease">
                            <button type="submit" class="qty-btn" style="width:35px; height:35px; border-radius:50%; background:#163B73; color:white; border:none; font-size:18px; cursor:pointer;">-</button>
                        </form>
                        <span style="font-weight:bold; color:#163B73; min-width:25px; text-align:center;"><?php echo $item['quantity']; ?></span>
                        <form method="POST" action="../api/update-cart.php" style="display:inline; margin:0;">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <input type="hidden" name="action" value="increase">
                            <button type="submit" class="qty-btn" style="width:35px; height:35px; border-radius:50%; background:#163B73; color:white; border:none; font-size:18px; cursor:pointer;">+</button>
                        </form>
                        <form method="POST" action="../api/update-cart.php" style="display:inline; margin:0;">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" style="background:#fde8e8; color:#c0392b; border:none; padding:8px 14px; border-radius:8px; font-weight:bold; cursor:pointer; width:auto;">Remove</button>
                        </form>
                        <a href="customization.php?menu_id=<?php echo $item['menu_id']; ?>" style="background:#A7C7E7; color:#274472; border:none; padding:8px 14px; border-radius:8px; font-weight:bold; cursor:pointer; width:auto; text-decoration:none; display:inline-block;">Edit</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <div style="text-align:right; font-size:20px; font-weight:bold; color:#163B73; margin:20px 0;">
                Total: RM <?php echo number_format($total, 2); ?>
            </div>
            <a href="payment.php" class="btn" style="display:block; text-align:center; padding:14px; background:#163B73; color:white;">Proceed to Checkout</a>
            <br>
            <a href="menu.php" class="btn" style="display:block; text-align:center; margin-top:10px;">← Continue Shopping</a>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
