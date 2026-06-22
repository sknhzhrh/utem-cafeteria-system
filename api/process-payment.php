<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    echo http_build_query([
        'success' => '0',
        'message' => 'Not authenticated'
    ]);
    exit;
}

if (!isset($_POST['paymentMethod']) || !isset($_POST['total']))
{
    echo http_build_query([
        'success' => '0',
        'message' => 'Invalid data'
    ]);
    exit;
}

$customer_id = $_SESSION['customer_id'];
$cart = $_SESSION['temp_cart'] ?? [];
$paymentMethod = $_POST['paymentMethod'];
$total = floatval($_POST['total']);

if (empty($cart))
{
    echo http_build_query([
        'success' => '0',
        'message' => 'Cart is empty'
    ]);
    exit;
}

$orderStatus = 'Pending';
$sqlOrder = "INSERT INTO orders (customer_id, total_amount, status) VALUES ($customer_id, $total, '$orderStatus')";
if (!mysqli_query($conn, $sqlOrder))
{
    echo http_build_query([
        'success' => '0',
        'message' => 'Failed to create order'
    ]);
    exit;
}

$orderId = mysqli_insert_id($conn);

foreach ($cart as $item)
{
    $menuId = intval($item['menu_id']);
    $quantity = intval($item['quantity']);
    $subtotal = floatval($item['subtotal']);

    $sqlOrderMenu = "INSERT INTO order_menu (order_id, menu_id, quantity, subtotal) VALUES ($orderId, $menuId, $quantity, $subtotal)";
    mysqli_query($conn, $sqlOrderMenu);
}

$sqlPayment = "INSERT INTO payment (order_id, amount, method) VALUES ($orderId, $total, '$paymentMethod')";
mysqli_query($conn, $sqlPayment);

unset($_SESSION['temp_cart']);

echo http_build_query([
    'success' => '1',
    'orderId' => (string) $orderId,
    'message' => 'Payment processed successfully'
]);

?>

