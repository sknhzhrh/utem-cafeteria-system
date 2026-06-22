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

if (!$data || !isset($data['order_id']) || !isset($data['status']))
{
    echo json_encode(["success" => false, "message" => "Invalid data"]);
    exit;
}

$order_id = intval($data['order_id']);
$status = mysqli_real_escape_string($conn, $data['status']);

$sql = "UPDATE orders SET status='$status' WHERE order_id=$order_id";

if (mysqli_query($conn, $sql))
{
    echo json_encode(["success" => true, "message" => "Order updated"]);
}
else
{
    echo json_encode(["success" => false, "message" => "Failed to update order"]);
}

?>
