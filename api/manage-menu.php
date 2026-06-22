<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['operator_id']))
{
    echo http_build_query([
        'success' => '0',
        'message' => 'Not authenticated'
    ]);
    exit;
}

if (!isset($_POST['action']))
{
    echo http_build_query([
        'success' => '0',
        'message' => 'Invalid request'
    ]);
    exit;
}

$action = $_POST['action'];

if ($action === 'add')
{
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category = mysqli_real_escape_string($conn, $_POST['category'] ?? '');
    
    $sql = "INSERT INTO menu (name, price, category) VALUES ('$name', $price, '$category')";
    
    if (mysqli_query($conn, $sql))
    {
        echo http_build_query([
            'success' => '1',
            'message' => 'Menu item added'
        ]);
    }
    else
    {
        echo http_build_query([
            'success' => '0',
            'message' => 'Failed to add item'
        ]);
    }
}
elseif ($action === 'update')
{
    $menu_id = intval($_POST['menu_id'] ?? 0);
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category = mysqli_real_escape_string($conn, $_POST['category'] ?? '');
    
    $sql = "UPDATE menu SET name='$name', price=$price, category='$category' WHERE menu_id=$menu_id";
    
    if (mysqli_query($conn, $sql))
    {
        echo http_build_query([
            'success' => '1',
            'message' => 'Menu item updated'
        ]);
    }
    else
    {
        echo http_build_query([
            'success' => '0',
            'message' => 'Failed to update item'
        ]);
    }
}
elseif ($action === 'delete')
{
    $menu_id = intval($_POST['menu_id'] ?? 0);
    
    $sql = "DELETE FROM menu WHERE menu_id=$menu_id";
    
    if (mysqli_query($conn, $sql))
    {
        echo http_build_query([
            'success' => '1',
            'message' => 'Menu item deleted'
        ]);
    }
    else
    {
        echo http_build_query([
            'success' => '0',
            'message' => 'Failed to delete item'
        ]);
    }
}
else
{
    echo http_build_query([
        'success' => '0',
        'message' => 'Unknown action'
    ]);
}

?>
