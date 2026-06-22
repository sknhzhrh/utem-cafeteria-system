<?php
session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

if (isset($_POST['remove']))
{
    $cart_id = $_POST['cart_id'];
    mysqli_query($conn, "DELETE FROM cart WHERE cart_id='$cart_id' AND customer_id='$customer_id'");
    header("Location: cart.php");
    exit();
}

$sql = "SELECT cart.*, menu.name, menu.price
        FROM cart
        INNER JOIN menu ON cart.menu_id = menu.menu_id
        WHERE cart.customer_id='$customer_id'";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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

    <?php if (mysqli_num_rows($result) == 0): ?>

        <div style="text-align:center; padding:40px;">
            <p style="font-size:18px; color:#888;">Your cart is empty.</p>
            <a href="menu.php" class="btn">Browse Menu</a>
        </div>

    <?php else: ?>

        <?php
        $total = 0;
        while ($row = mysqli_fetch_assoc($result)):
            $subtotal = $row['subtotal'];
            $total += $subtotal;
        ?>

        <div class="profile-info" style="margin:15px 0;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px;">

                <div>
                    <strong style="font-size:16px; color:#274472;">
                        <?php echo $row['name']; ?>
                    </strong>

                    <div style="font-size:12px; color:#666; margin-top:5px;">
                        <?php if (!empty($row['spicy'])) echo "<div>• Spicy: " . $row['spicy'] . "</div>"; ?>
                        <?php if (!empty($row['drink'])) echo "<div>• Drink: " . $row['drink'] . "</div>"; ?>
                        <?php if (!empty($row['addons'])) echo "<div>• Add-ons: " . $row['addons'] . "</div>"; ?>
                        <?php if (!empty($row['note'])) echo "<div style='color:#c0392b; font-style:italic;'>• Note: \"" . $row['note'] . "\"</div>"; ?>
                    </div>
                </div>

                <div style="text-align:right;">
                    <div style="font-size:14px; color:#888;">
                        Customized Price
                    </div>

                    <div style="font-weight:bold; color:#163B73; font-size:16px;">
                        RM <?php echo number_format($subtotal, 2); ?>
                    </div>
                </div>

            </div>

            <div style="display:flex; align-items:center; gap:10px; margin-top:12px;">

                <span style="font-weight:bold; color:#163B73;">
                    Quantity: <?php echo $row['quantity']; ?>
                </span>

                <form method="POST" style="display:inline;">
                    <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                    <button name="remove" onclick="return confirm('Remove this item?')" style="background:#fde8e8; color:#c0392b; border:none; padding:8px 14px; border-radius:8px; font-weight:bold;">
                        Remove
                    </button>
                </form>

                <a href="customization.php?cart_id=<?php echo $row['cart_id']; ?>" style="background:#A7C7E7; color:#274472; padding:8px 14px; border-radius:8px; font-weight:bold; text-decoration:none;">
    Edit
</a>

            </div>
        </div>

        <?php endwhile; ?>

        <div style="text-align:right; font-size:20px; font-weight:bold; color:#163B73; margin:20px 0;">
            Total: RM <?php echo number_format($total, 2); ?>
        </div>

        <a href="../payment/payment.php" class="btn" style="display:block; text-align:center; padding:14px; background:#163B73; color:white;">
            Proceed to Checkout
        </a>

        <br>

        <a href="menu.php" class="btn" style="display:block; text-align:center; margin-top:10px;">
            ← Continue Shopping
        </a>

    <?php endif; ?>

</div>

</body>
</html>