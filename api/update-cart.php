<?php

session_start();

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

if (!isset($_POST['action']) || !isset($_POST['index']))
{
    header("Location: ../customer/cart.php");
    exit();
}

$action = $_POST['action'];
$index = intval($_POST['index']);

if (!isset($_SESSION['temp_cart'][$index]))
{
    header("Location: ../customer/cart.php");
    exit();
}

if ($action === "increase")
{
    $_SESSION['temp_cart'][$index]['quantity']++;
    $_SESSION['temp_cart'][$index]['subtotal'] = $_SESSION['temp_cart'][$index]['price'] * $_SESSION['temp_cart'][$index]['quantity'];
}
elseif ($action === "decrease")
{
    $_SESSION['temp_cart'][$index]['quantity']--;
    if ($_SESSION['temp_cart'][$index]['quantity'] <= 0)
    {
        unset($_SESSION['temp_cart'][$index]);
        $_SESSION['temp_cart'] = array_values($_SESSION['temp_cart']); // Re-index array
    }
    else
    {
        $_SESSION['temp_cart'][$index]['subtotal'] = $_SESSION['temp_cart'][$index]['price'] * $_SESSION['temp_cart'][$index]['quantity'];
    }
}
elseif ($action === "remove")
{
    unset($_SESSION['temp_cart'][$index]);
    $_SESSION['temp_cart'] = array_values($_SESSION['temp_cart']); // Re-index array
}

header("Location: ../customer/cart.php");
exit();

?>
