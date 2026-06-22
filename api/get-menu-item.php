<?php

include("../connect.php");

if (!isset($_GET['menu_id']))
{
    echo 'menu_id=';
    exit;
}

$menu_id = intval($_GET['menu_id']);
$sql = "SELECT menu_id, name, price, category FROM menu WHERE menu_id = $menu_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row)
{
    echo 'menu_id=';
    exit;
}

echo http_build_query($row);

?>
