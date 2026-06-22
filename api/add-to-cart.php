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

if (!isset($_POST['menu_id']) || !isset($_POST['name']) || !isset($_POST['price']))
{
    echo http_build_query([
        'success' => '0',
        'message' => 'Invalid data'
    ]);
    exit;
}

if (!isset($_SESSION['temp_cart']))
{
    $_SESSION['temp_cart'] = [];
}

$item = [
    'menu_id'  => intval($_POST['menu_id']),
    'name'     => $_POST['name'] ?? '',
    'price'    => floatval($_POST['price']),
    'quantity' => intval($_POST['quantity'] ?? 1),
    'spicy'    => $_POST['spicy'] ?? '',
    'drink'    => $_POST['drink'] ?? '',
    'addons'   => isset($_POST['addons']) ? (array) $_POST['addons'] : [],
    'note'     => $_POST['note'] ?? '',
    'subtotal' => floatval($_POST['subtotal'] ?? 0)
];

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

echo http_build_query([
    'success' => '1',
    'message' => 'Item added to cart'
]);

?>
