<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$sqlCart = "SELECT cart.*, menu.name, menu.price
            FROM cart
            INNER JOIN menu ON cart.menu_id = menu.menu_id
            WHERE cart.customer_id = '$customer_id'";

$resultCart = mysqli_query($conn, $sqlCart);

$total = 0;

if (isset($_POST['pay']))
{
    $method = $_POST['method'];

    $sqlTotal = "SELECT SUM(subtotal) AS total
                 FROM cart
                 WHERE customer_id = '$customer_id'";
    $resultTotal = mysqli_query($conn, $sqlTotal);
    $rowTotal = mysqli_fetch_assoc($resultTotal);
    $total_amount = $rowTotal['total'];

    if ($total_amount <= 0)
    {
        echo "<script>alert('Cart is empty'); window.location.href='../customer/cart.php';</script>";
        exit();
    }

    $sqlOrder = "INSERT INTO orders (total_amount, status, customer_id)
                 VALUES ('$total_amount', 'Pending', '$customer_id')";

    if (mysqli_query($conn, $sqlOrder))
    {
        $order_id = mysqli_insert_id($conn);

        $sqlCartItems = "SELECT * FROM cart WHERE customer_id = '$customer_id'";
        $resultCartItems = mysqli_query($conn, $sqlCartItems);

        while ($item = mysqli_fetch_assoc($resultCartItems))
        {
            $menu_id = $item['menu_id'];
            $quantity = $item['quantity'];
            $subtotal = $item['subtotal'];

            $sqlOrderMenu = "INSERT INTO order_menu (order_id, menu_id, quantity, subtotal)
                             VALUES ('$order_id', '$menu_id', '$quantity', '$subtotal')";
            mysqli_query($conn, $sqlOrderMenu);
        }

        $sqlPayment = "INSERT INTO payment (amount, method, order_id)
                       VALUES ('$total_amount', '$method', '$order_id')";
        mysqli_query($conn, $sqlPayment);

        $sqlClearCart = "DELETE FROM cart WHERE customer_id = '$customer_id'";
        mysqli_query($conn, $sqlClearCart);

        echo "<script>alert('Payment successful'); window.location.href='receipt.php?order_id=$order_id';</script>";
        exit();
    }
    else
    {
        echo "<script>alert('Order failed');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Checkout - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container">

    <h1>Checkout</h1>
    <p class="subtitle">Review your order and complete payment.</p>

    <div class="summary-box">
        <h3>Order Summary</h3>

        <?php if (mysqli_num_rows($resultCart) > 0): ?>

            <?php while ($row = mysqli_fetch_assoc($resultCart)): ?>
                <?php $total += $row['subtotal']; ?>

                <div class="item-row">
                    <div>
                        <strong><?php echo $row['name']; ?></strong><br>
                        Quantity: <?php echo $row['quantity']; ?><br>

                        <?php if (!empty($row['spicy'])): ?>
                            Spicy: <?php echo $row['spicy']; ?><br>
                        <?php endif; ?>

                        <?php if (!empty($row['drink'])): ?>
                            Drink: <?php echo $row['drink']; ?><br>
                        <?php endif; ?>

                        <?php if (!empty($row['addons'])): ?>
                            Add-ons: <?php echo $row['addons']; ?><br>
                        <?php endif; ?>

                        <?php if (!empty($row['note'])): ?>
                            Note: <?php echo $row['note']; ?><br>
                        <?php endif; ?>
                    </div>

                    <div>
                        RM <?php echo number_format($row['subtotal'], 2); ?>
                    </div>
                </div>

                <hr>
            <?php endwhile; ?>

            <h2>Total: RM <?php echo number_format($total, 2); ?></h2>

            <form method="POST">

                <label>Choose Payment Method</label>

                <label class="payment-option">
                    <input type="radio" name="method" value="FPX Online Banking" checked>
                    FPX Online Banking
                </label>

                <label class="payment-option">
                    <input type="radio" name="method" value="E-Wallet">
                    E-Wallet
                </label>

                <button type="submit" name="pay" class="btn-pay">Confirm & Pay Now</button>
            </form>

        <?php else: ?>

            <p>Your cart is empty.</p>
            <a href="../customer/menu.php" class="btn">Browse Menu</a>

        <?php endif; ?>

    </div>

    <br>
    <a href="../customer/cart.php" class="btn">← Back to Cart</a>

</div>

</body>
</html>