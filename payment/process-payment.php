<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

if (!isset($_POST['pay']))
{
    header("Location: ../payment/payment.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$method = $_POST['method'];
$total = $_POST['total'];
$status = "Pending";

// Get cart items
$sqlCart = "SELECT * FROM cart WHERE customer_id='$customer_id'";
$resultCart = mysqli_query($conn, $sqlCart);

if (mysqli_num_rows($resultCart) == 0)
{
    echo "<script>
            alert('Your cart is empty');
            window.location.href='../customer/cart.php';
          </script>";
    exit();
}

// Insert order
$sqlOrder = "INSERT INTO orders (customer_id, total_amount, status)
             VALUES ('$customer_id', '$total', '$status')";

if (!mysqli_query($conn, $sqlOrder))
{
    echo "<script>
            alert('Failed to create order');
            window.location.href='../payment/payment.php';
          </script>";
    exit();
}

$order_id = mysqli_insert_id($conn);

// Insert order items
while ($row = mysqli_fetch_assoc($resultCart))
{
    $menu_id = $row['menu_id'];
    $quantity = $row['quantity'];
    $subtotal = $row['subtotal'];

    $sqlOrderMenu = "INSERT INTO order_menu (order_id, menu_id, quantity, subtotal)
                     VALUES ('$order_id', '$menu_id', '$quantity', '$subtotal')";

    mysqli_query($conn, $sqlOrderMenu);
}

// Insert payment
$sqlPayment = "INSERT INTO payment (order_id, amount, method)
             VALUES ('$order_id', '$total', '$method')";

mysqli_query($conn, $sqlPayment);

// Clear cart
$sqlDeleteCart = "DELETE FROM cart WHERE customer_id='$customer_id'";
mysqli_query($conn, $sqlDeleteCart);

// Go to receipt
header("Location: ../payment/receipt.php?order_id=$order_id");
exit();

?>