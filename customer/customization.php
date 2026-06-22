<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

// Initialize cart in session if not exists
if (!isset($_SESSION['temp_cart']))
{
    $_SESSION['temp_cart'] = [];
}

// Get menu_id from URL parameter
$menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0;

if (!$menu_id)
{
    header("Location: menu.php");
    exit();
}

// Fetch menu item from database
$sql = "SELECT * FROM menu WHERE menu_id = $menu_id";
$result = mysqli_query($conn, $sql);
$menuItem = mysqli_fetch_assoc($result);

if (!$menuItem)
{
    header("Location: menu.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize Order - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/custom.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container">

    <h1 id="foodName"></h1>
    <p id="foodPrice" class="subtitle"></p>

    <form id="customizeForm" method="POST" action="../api/add-to-cart.php">
        <input type="hidden" name="menu_id" value="<?php echo (int)$menuItem['menu_id']; ?>">
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($menuItem['name']); ?>">
        <input type="hidden" name="price" value="<?php echo (float)$menuItem['price']; ?>">
        <input type="hidden" name="quantity" id="quantity" value="1">
        <input type="hidden" name="subtotal" id="subtotal" value="<?php echo number_format($menuItem['price'], 2, '.', ''); ?>">

        <div class="form-group">
            <label>Spicy Level</label>
            <select name="spicy" id="spicy">
                <option value="No Spicy">No Spicy</option>
                <option value="Mild">Mild</option>
                <option value="Medium">Medium</option>
                <option value="Hot">Hot</option>
            </select>
        </div>

        <div class="form-group">
            <label>Drink Temperature</label>
            <select name="drink" id="drink">
                <option value="Hot">Hot</option>
                <option value="Cold (+RM0.50)">Cold (+RM0.50)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Add-ons</label>
            <div class="option"><label><input type="checkbox" name="addons[]" value="Egg (+RM2)"> Egg (+RM2.00)</label></div>
            <div class="option"><label><input type="checkbox" name="addons[]" value="Chicken (+RM3)"> Chicken (+RM3.00)</label></div>
        </div>

        <div class="form-group">
            <label>Special Note</label>
            <textarea name="note" id="note" placeholder="Any special request..."></textarea>
        </div>

        <div class="form-group">
            <label>Quantity</label>
            <div class="quantity">
                <button type="button" class="qty-btn" onclick="minus()">-</button>
                <span id="qty">1</span>
                <button type="button" class="qty-btn" onclick="plus()">+</button>
            </div>
        </div>

        <button type="button" onclick="addToCart()">Add to Cart</button>
        <br><br>
        <a href="menu.php" class="btn">← Back to Menu</a>
    </form>

</div>

<script>

const food = {
    menu_id: <?php echo (int)$menuItem['menu_id']; ?>,
    name: "<?php echo htmlspecialchars($menuItem['name'], ENT_QUOTES); ?>",
    price: <?php echo (float)$menuItem['price']; ?>
};

document.getElementById("foodName").innerText  = food.name;
document.getElementById("foodPrice").innerText = "Base Price: RM " + parseFloat(food.price).toFixed(2);

let qty = 1;

function updateSubtotal()
{
    const drink = document.getElementById("drink").value;
    const addons = Array.from(document.querySelectorAll("input[name='addons[]']:checked"));

    let subtotal = food.price * qty;
    if (drink.includes("Cold")) subtotal += 0.50 * qty;

    addons.forEach(a => {
        const match = a.value.match(/\+RM(\d+(?:\.\d+)?)/);
        if (match) subtotal += parseFloat(match[1]) * qty;
    });

    document.getElementById("quantity").value = qty;
    document.getElementById("subtotal").value = subtotal.toFixed(2);
}

function plus()
{
    qty++;
    document.getElementById("qty").innerText = qty;
    updateSubtotal();
}

function minus()
{
    if (qty > 1)
    {
        qty--;
        document.getElementById("qty").innerText = qty;
        updateSubtotal();
    }
}

function addToCart()
{
    updateSubtotal();
    document.getElementById("customizeForm").submit();
}

updateSubtotal();

</script>

</body>
</html>
