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
    <title>Cart - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="dashboard">

    <div class="welcome-box">
        <h1>Your Cart 🛒</h1>
    </div>

    <div id="cartBox"></div>

</div>

<script>

let cart = JSON.parse(localStorage.getItem("cart")) || [];

function renderCart()
{
    const box = document.getElementById("cartBox");

    if (cart.length === 0)
    {
        box.innerHTML = `
            <div style="text-align:center; padding:40px;">
                <p style="font-size:18px; color:#888;">Your cart is empty.</p>
                <a href="menu.php" class="btn" style="margin-top:20px;">Browse Menu</a>
            </div>`;
        return;
    }

    let html  = "";
    let total = 0;

    cart.forEach((item, index) =>
    {
        let sub = item.subtotal || (item.price * item.quantity);
        total  += sub;

        let custom = "";
        if (item.spicy)  custom += `<div>• Spicy: ${item.spicy}</div>`;
        if (item.drink)  custom += `<div>• Drink: ${item.drink}</div>`;
        if (item.addons && item.addons.length) custom += `<div>• Add-ons: ${item.addons.join(", ")}</div>`;
        if (item.note)   custom += `<div style="color:#c0392b; font-style:italic;">• Note: "${item.note}"</div>`;

        html += `
        <div class="profile-info" style="margin:15px 0;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px;">
                <div>
                    <strong style="font-size:16px; color:#274472;">${item.name}</strong>
                    <div style="font-size:12px; color:#666; margin-top:5px;">${custom}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:14px; color:#888;">RM ${item.price.toFixed(2)} each</div>
                    <div style="font-weight:bold; color:#163B73; font-size:16px;">RM ${sub.toFixed(2)}</div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:10px; margin-top:12px;">
                <button class="qty-btn" style="width:35px; height:35px; border-radius:50%; background:#163B73; color:white; border:none; font-size:18px; cursor:pointer;" onclick="decrease(${index})">-</button>
                <span style="font-weight:bold; color:#163B73; min-width:25px; text-align:center;">${item.quantity}</span>
                <button class="qty-btn" style="width:35px; height:35px; border-radius:50%; background:#163B73; color:white; border:none; font-size:18px; cursor:pointer;" onclick="increase(${index})">+</button>
                <button onclick="removeItem(${index})" style="background:#fde8e8; color:#c0392b; border:none; padding:8px 14px; border-radius:8px; font-weight:bold; cursor:pointer; width:auto;">Remove</button>
                <button onclick="goCustomize(${index})" style="background:#A7C7E7; color:#274472; border:none; padding:8px 14px; border-radius:8px; font-weight:bold; cursor:pointer; width:auto;">Edit</button>
            </div>
        </div>`;
    });

    html += `
        <div style="text-align:right; font-size:20px; font-weight:bold; color:#163B73; margin:20px 0;">
            Total: RM ${total.toFixed(2)}
        </div>
        <a href="payment.php" class="btn" style="display:block; text-align:center; padding:14px; background:#163B73; color:white;">Proceed to Checkout</a>
        <br>
        <a href="menu.php" class="btn" style="display:block; text-align:center; margin-top:10px;">← Continue Shopping</a>`;

    box.innerHTML = html;
    localStorage.setItem("cart", JSON.stringify(cart));
}

function increase(i) { cart[i].quantity++; cart[i].subtotal = +(cart[i].price * cart[i].quantity).toFixed(2); renderCart(); }
function decrease(i) { cart[i].quantity--; if (cart[i].quantity <= 0) cart.splice(i, 1); else cart[i].subtotal = +(cart[i].price * cart[i].quantity).toFixed(2); renderCart(); }
function removeItem(i) { if (confirm("Remove " + cart[i].name + "?")) { cart.splice(i, 1); renderCart(); } }
function goCustomize(i) { localStorage.setItem("selectedFoodId", cart[i].menu_id); cart.splice(i, 1); localStorage.setItem("cart", JSON.stringify(cart)); window.location.href = "customization.php"; }

renderCart();

</script>

</body>
</html>
