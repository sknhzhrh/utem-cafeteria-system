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
        <div class="option"><label><input type="checkbox" value="Egg (+RM2)"> Egg (+RM2.00)</label></div>
        <div class="option"><label><input type="checkbox" value="Chicken (+RM3)"> Chicken (+RM3.00)</label></div>
    </div>

    <div class="form-group">
        <label>Special Note</label>
        <textarea id="note" placeholder="Any special request..."></textarea>
    </div>

    <div class="form-group">
        <label>Quantity</label>
        <div class="quantity">
            <button class="qty-btn" onclick="minus()">-</button>
            <span id="qty">1</span>
            <button class="qty-btn" onclick="plus()">+</button>
        </div>
    </div>

    <button onclick="addToCart()">Add to Cart</button>
    <br><br>
    <a href="menu.php" class="btn">← Back to Menu</a>

</div>

<script>

const food = {
    menu_id: <?php echo $menuItem['menu_id']; ?>,
    name: "<?php echo $menuItem['name']; ?>",
    price: <?php echo $menuItem['price']; ?>
};

document.getElementById("foodName").innerText  = food.name;
document.getElementById("foodPrice").innerText = "Base Price: RM " + parseFloat(food.price).toFixed(2);

let qty = 1;

function plus()  { qty++; document.getElementById("qty").innerText = qty; }
function minus() { if (qty > 1) { qty--; document.getElementById("qty").innerText = qty; } }

function addToCart()
{
    let spicy  = document.getElementById("spicy").value;
    let drink  = document.getElementById("drink").value;
    let addons = [];

    document.querySelectorAll("input[type=checkbox]:checked")
        .forEach(a => addons.push(a.value));

    let subtotal = food.price * qty;
    if (drink.includes("Cold")) subtotal += 0.50 * qty;
    addons.forEach(a => {
        let match = a.match(/\+RM(\d+(\.\d+)?)/);
        if (match) subtotal += parseFloat(match[1]) * qty;
    });

    const item = {
        menu_id:  food.menu_id,
        name:     food.name,
        price:    parseFloat(food.price),
        quantity: qty,
        spicy:    spicy,
        drink:    drink,
        addons:   addons,
        note:     document.getElementById("note").value,
        subtotal: +subtotal.toFixed(2)
    };

    // Send to PHP session via API
    fetch("../api/add-to-cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(item)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success)
        {
            alert(food.name + " added to cart!");
            window.location.href = "menu.php";
        }
        else
        {
            alert("Error: " + (data.message || "Failed to add item"));
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("Error adding to cart");
    });
}

</script>

</body>
</html>
