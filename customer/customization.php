<?php
session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../account/login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$isEdit = false;
$cartData = null;

if (isset($_GET['cart_id'])) {
    $isEdit = true;
    $cart_id = $_GET['cart_id'];

    $sqlCart = "SELECT cart.*, menu.name, menu.price, menu.category
                FROM cart
                INNER JOIN menu ON cart.menu_id = menu.menu_id
                WHERE cart.cart_id='$cart_id' AND cart.customer_id='$customer_id'";
    $resultCart = mysqli_query($conn, $sqlCart);

    if (mysqli_num_rows($resultCart) == 0) {
        echo "<script>alert('Cart item not found'); window.location.href='cart.php';</script>";
        exit();
    }

    $cartData = mysqli_fetch_assoc($resultCart);
    $menu_id = $cartData['menu_id'];

    $food = [
        "name" => $cartData['name'],
        "price" => $cartData['price'],
        "category" => $cartData['category']
    ];
} else {
    if (!isset($_GET['menu_id'])) {
        header("Location: menu.php");
        exit();
    }

    $menu_id = $_GET['menu_id'];

    $sql = "SELECT * FROM menu WHERE menu_id = '$menu_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "<script>alert('Menu item not found'); window.location.href='menu.php';</script>";
        exit();
    }

    $food = mysqli_fetch_assoc($result);
}

$showFoodOptions = ($food['category'] == "Rice" || $food['category'] == "Noodles");
$showDrinkOptions = ($food['category'] == "Drinks");

if (isset($_POST['add_cart'])) {
    $spicy = isset($_POST['spicy']) ? $_POST['spicy'] : "";
    $drink = isset($_POST['drink']) ? $_POST['drink'] : "";
    $note = $_POST['note'];
    $quantity = $_POST['quantity'];

    $addons = isset($_POST['addons']) ? $_POST['addons'] : [];
    $addons_text = implode(", ", $addons);

    $subtotal = $food['price'] * $quantity;

    if ($drink == "Cold (+RM0.50)") {
        $subtotal += 0.50 * $quantity;
    }

    foreach ($addons as $addon) {
        if ($addon == "Egg (+RM2)") {
            $subtotal += 2.00 * $quantity;
        }

        if ($addon == "Chicken (+RM3)") {
            $subtotal += 3.00 * $quantity;
        }
    }

    $subtotal = number_format($subtotal, 2, '.', '');

    if ($isEdit) {
        $sqlCart = "UPDATE cart
                    SET quantity='$quantity',
                        spicy='$spicy',
                        drink='$drink',
                        addons='$addons_text',
                        note='$note',
                        subtotal='$subtotal'
                    WHERE cart_id='$cart_id' AND customer_id='$customer_id'";
    } else {
        $sqlCart = "INSERT INTO cart 
                    (customer_id, menu_id, quantity, spicy, drink, addons, note, subtotal)
                    VALUES 
                    ('$customer_id', '$menu_id', '$quantity', '$spicy', '$drink', '$addons_text', '$note', '$subtotal')";
    }

    if (mysqli_query($conn, $sqlCart)) {
        echo "<script>alert('Cart updated successfully'); window.location.href='cart.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

$currentSpicy = $isEdit ? $cartData['spicy'] : "No Spicy";
$currentDrink = $isEdit ? $cartData['drink'] : "Hot";
$currentAddons = $isEdit ? explode(", ", $cartData['addons']) : [];
$currentNote = $isEdit ? $cartData['note'] : "";
$currentQuantity = $isEdit ? $cartData['quantity'] : 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customize Order - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/custom.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container">

    <h1><?php echo $food['name']; ?></h1>
    <p class="subtitle">Base Price: RM <?php echo number_format($food['price'], 2); ?></p>

    <form method="POST">

        <?php if ($showFoodOptions): ?>
        <div class="form-group">
            <label>Spicy Level</label>
            <select name="spicy">
                <option value="No Spicy" <?php if ($currentSpicy == "No Spicy") echo "selected"; ?>>No Spicy</option>
                <option value="Mild" <?php if ($currentSpicy == "Mild") echo "selected"; ?>>Mild</option>
                <option value="Medium" <?php if ($currentSpicy == "Medium") echo "selected"; ?>>Medium</option>
                <option value="Hot" <?php if ($currentSpicy == "Hot") echo "selected"; ?>>Hot</option>
            </select>
        </div>
        <?php endif; ?>

        <?php if ($showDrinkOptions): ?>
        <div class="form-group">
            <label>Drink Temperature</label>
            <select name="drink">
                <option value="Hot" <?php if ($currentDrink == "Hot") echo "selected"; ?>>Hot</option>
                <option value="Cold (+RM0.50)" <?php if ($currentDrink == "Cold (+RM0.50)") echo "selected"; ?>>Cold (+RM0.50)</option>
            </select>
        </div>
        <?php endif; ?>

        <?php if ($showFoodOptions): ?>
        <div class="form-group">
            <label>Add-ons</label>

            <div class="option">
                <label>
                    <input type="checkbox" name="addons[]" value="Egg (+RM2)" <?php if (in_array("Egg (+RM2)", $currentAddons)) echo "checked"; ?>>
                    Egg (+RM2.00)
                </label>
            </div>

            <div class="option">
                <label>
                    <input type="checkbox" name="addons[]" value="Chicken (+RM3)" <?php if (in_array("Chicken (+RM3)", $currentAddons)) echo "checked"; ?>>
                    Chicken (+RM3.00)
                </label>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label>Special Note</label>
            <textarea name="note" placeholder="Any special request..."><?php echo $currentNote; ?></textarea>
        </div>

        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" value="<?php echo $currentQuantity; ?>" min="1" required>
        </div>

        <button type="submit" name="add_cart">
            <?php echo $isEdit ? "Update Cart" : "Add to Cart"; ?>
        </button>

    </form>

    <br>
    <a href="cart.php" class="btn">← Back to Cart</a>

</div>

</body>
</html>