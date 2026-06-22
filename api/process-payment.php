<?php

session_start();
include("../connect.php");

header("Content-Type: application/json");

if (!isset($_SESSION['customer_id']))
{
    echo json_encode(["success" => false, "message" => "Not authenticated"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['cart']) || !isset($data['paymentMethod']) || !isset($data['total']))
{
    echo json_encode(["success" => false, "message" => "Invalid data"]);
    exit;
}

$customer_id = $_SESSION['customer_id'];
$cart = $data['cart'];
$paymentMethod = $data['paymentMethod'];
$total = floatval($data['total']);

// Insert order
$orderStatus = "Pending";
$sqlOrder = "INSERT INTO orders (customer_id, total_amount, status) VALUES ($customer_id, $total, '$orderStatus')";
if (!mysqli_query($conn, $sqlOrder))
{
    echo json_encode(["success" => false, "message" => "Failed to create order"]);
    exit;
}

$orderId = mysqli_insert_id($conn);

// Insert order items
foreach ($cart as $item)
{
    $menuId = intval($item['menu_id']);
    $quantity = intval($item['quantity']);
    $subtotal = floatval($item['subtotal']);

    $sqlOrderMenu = "INSERT INTO order_menu (order_id, menu_id, quantity, subtotal) VALUES ($orderId, $menuId, $quantity, $subtotal)";
    mysqli_query($conn, $sqlOrderMenu);
}

// Insert payment record
$sqlPayment = "INSERT INTO payment (order_id, amount, method) VALUES ($orderId, $total, '$paymentMethod')";
mysqli_query($conn, $sqlPayment);

// Clear temporary cart from session
unset($_SESSION['temp_cart']);

echo json_encode([
    "success" => true,
    "orderId" => $orderId,
    "message" => "Payment processed successfully"
]);

?>

