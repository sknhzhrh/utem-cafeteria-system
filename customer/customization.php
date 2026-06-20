<?php

session_start();

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
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

const menuItems = [
    { menu_id: 1,  name: "Nasi Lemak",   price: 4.50 },
    { menu_id: 2,  name: "Nasi Goreng",  price: 5.00 },
    { menu_id: 3,  name: "Nasi Ayam",    price: 5.50 },
    { menu_id: 4,  name: "Mee Goreng",   price: 4.50 },
    { menu_id: 5,  name: "Mee Rebus",    price: 4.50 },
    { menu_id: 6,  name: "Laksa",        price: 5.50 },
    { menu_id: 7,  name: "Roti Canai",   price: 2.00 },
    { menu_id: 8,  name: "Karipap",      price: 1.50 },
    { menu_id: 9,  name: "Teh Tarik",    price: 2.50 },
    { menu_id: 10, name: "Milo Ais",     price: 2.50 },
    { menu_id: 11, name: "Air Mineral",  price: 1.50 },
    { menu_id: 12, name: "Cendol",       price: 3.00 },
    { menu_id: 13, name: "Ais Kacang",   price: 3.50 },
    { menu_id: 14, name: "Puding Roti",  price: 2.50 },
];

let qty  = 1;
const id = localStorage.getItem("selectedFoodId");
const food = menuItems.find(m => m.menu_id == id);

if (!food) { alert("No item selected!"); window.location.href = "menu.php"; }

document.getElementById("foodName").innerText  = food.name;
document.getElementById("foodPrice").innerText = "Base Price: RM " + food.price.toFixed(2);

function plus()  { qty++; document.getElementById("qty").innerText = qty; }
function minus() { if (qty > 1) { qty--; document.getElementById("qty").innerText = qty; } }

function addToCart()
{
    let cart   = JSON.parse(localStorage.getItem("cart")) || [];
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

    const existing = cart.find(c => c.menu_id == food.menu_id);
    if (existing)
    {
        existing.quantity += qty;
        existing.subtotal  = +(existing.subtotal + subtotal).toFixed(2);
    }
    else
    {
        cart.push({
            menu_id:  food.menu_id,
            name:     food.name,
            price:    food.price,
            quantity: qty,
            spicy:    spicy,
            drink:    drink,
            addons:   addons,
            note:     document.getElementById("note").value,
            subtotal: +subtotal.toFixed(2)
        });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    alert(food.name + " added to cart!");
    window.location.href = "menu.php";
}

</script>

</body>
</html>
