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

if (!isset($_POST['order_id']) || !isset($_POST['status']))
{
    echo http_build_query([
        'success' => '0',
        'message' => 'Invalid data'
    ]);
    exit;
}

$order_id = intval($_POST['order_id']);
$status = mysqli_real_escape_string($conn, $_POST['status']);

$sql = "UPDATE orders SET status='$status' WHERE order_id=$order_id";

if (mysqli_query($conn, $sql))
{
    echo http_build_query([
        'success' => '1',
        'message' => 'Order updated'
    ]);
}
else
{
    echo http_build_query([
        'success' => '0',
        'message' => 'Failed to update order'
    ]);
}

?>
