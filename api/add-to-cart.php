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

if (!$data || !isset($data['menu_id']))
{
    echo json_encode(["success" => false, "message" => "Invalid data"]);
    exit;
}

// Initialize temp cart if doesn't exist
if (!isset($_SESSION['temp_cart']))
{
    $_SESSION['temp_cart'] = [];
}

$item = [
    'menu_id'  => intval($data['menu_id']),
    'name'     => $data['name'] ?? '',
    'price'    => floatval($data['price']),
    'quantity' => intval($data['quantity']),
    'spicy'    => $data['spicy'] ?? '',
    'drink'    => $data['drink'] ?? '',
    'addons'   => $data['addons'] ?? [],
    'note'     => $data['note'] ?? '',
    'subtotal' => floatval($data['subtotal'])
];

// Check if item already exists
$found = false;
foreach ($_SESSION['temp_cart'] as &$cartItem)
{
    if ($cartItem['menu_id'] == $item['menu_id'])
    {
        $cartItem['quantity'] += $item['quantity'];
        $cartItem['subtotal'] += $item['subtotal'];
        $found = true;
        break;
    }
}

if (!$found)
{
    $_SESSION['temp_cart'][] = $item;
}

echo json_encode(["success" => true, "message" => "Item added to cart"]);

?>
