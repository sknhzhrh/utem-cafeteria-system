<?php

session_start();
include("../connect.php");

header("Content-Type: application/json");

if (!isset($_SESSION['operator_id']))
{
    echo json_encode(["success" => false, "message" => "Not authenticated"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['action']))
{
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$action = $data['action'];

if ($action === "add")
{
    $name = mysqli_real_escape_string($conn, $data['name']);
    $price = floatval($data['price']);
    $category = mysqli_real_escape_string($conn, $data['category']);
    
    $sql = "INSERT INTO menu (name, price, category) VALUES ('$name', $price, '$category')";
    
    if (mysqli_query($conn, $sql))
    {
        echo json_encode(["success" => true, "message" => "Menu item added"]);
    }
    else
    {
        echo json_encode(["success" => false, "message" => "Failed to add item"]);
    }
}
elseif ($action === "update")
{
    $menu_id = intval($data['menu_id']);
    $name = mysqli_real_escape_string($conn, $data['name']);
    $price = floatval($data['price']);
    $category = mysqli_real_escape_string($conn, $data['category']);
    
    $sql = "UPDATE menu SET name='$name', price=$price, category='$category' WHERE menu_id=$menu_id";
    
    if (mysqli_query($conn, $sql))
    {
        echo json_encode(["success" => true, "message" => "Menu item updated"]);
    }
    else
    {
        echo json_encode(["success" => false, "message" => "Failed to update item"]);
    }
}
elseif ($action === "delete")
{
    $menu_id = intval($data['menu_id']);
    
    $sql = "DELETE FROM menu WHERE menu_id=$menu_id";
    
    if (mysqli_query($conn, $sql))
    {
        echo json_encode(["success" => true, "message" => "Menu item deleted"]);
    }
    else
    {
        echo json_encode(["success" => false, "message" => "Failed to delete item"]);
    }
}
else
{
    echo json_encode(["success" => false, "message" => "Unknown action"]);
}

?>
