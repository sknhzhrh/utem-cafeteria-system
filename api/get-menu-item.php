<?php

include("../connect.php");

header("Content-Type: application/json");

if (!isset($_GET['menu_id']))
{
    echo json_encode(null);
    exit;
}

$menu_id = intval($_GET['menu_id']);
$sql = "SELECT menu_id, name, price, category FROM menu WHERE menu_id = $menu_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo json_encode($row);

?>
