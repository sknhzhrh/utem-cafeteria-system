<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

// Get cart from session
$cart = isset($_SESSION['temp_cart']) ? $_SESSION['temp_cart'] : [];

if (empty($cart))
{
    header("Location: ../customer/menu.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Checkout - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/cart.css">
    <style>
        .summary-box { border:1px solid #E3E7EB; border-radius:12px; padding:20px; margin-bottom:25px; background:#FAFBFC; }
        .summary-box h3 { color:#274472; margin-bottom:12px; }
        .item-row { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:15px; font-size:15px; }
        .item-name { font-weight:600; color:#333; }
        .item-customization { font-size:12px; color:#666; margin-top:4px; line-height:1.5; }
        .item-note { font-style:italic; color:#C0392B; font-weight:500; }
        .divider { border-top:1px solid #E3E7EB; margin:15px 0; }
        .total-row { display:flex; justify-content:space-between; font-size:18px; font-weight:bold; color:#163B73; margin-top:10px; }
        .radio-group { display:flex; flex-direction:column; gap:12px; margin-top:10px; }
        .radio-label { display:flex; align-items:center; gap:12px; background:#F4F6F8; padding:14px; border-radius:10px; cursor:pointer; border:1px solid #E3E7EB; transition:0.2s; }
        .radio-label:hover { background:#EAEFF5; }
        .radio-label input { width:auto; margin:0; }
        .btn-pay { background:#163B73; color:white; border:none; border-radius:12px; font-weight:bold; width:100%; padding:14px; font-size:16px; cursor:pointer; margin-top:25px; }
        .btn-pay:hover { background:#0F2C57; }
    </style>
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container">

    <h1>Checkout</h1>
    <p class="subtitle">Review your order and complete payment.</p>

    <div class="summary-box">
        <h3>Order Summary</h3>
        <div class="divider"></div>
        <div id="checkoutItemsContainer"></div>
        <div class="divider"></div>
        <div class="total-row">
            <span>Grand Total</span>
            <span id="grandTotalPrice">RM 0.00</span>
        </div>
    </div>

    <div class="form-group">
        <label>Choose Payment Method</label>
        <div class="radio-group">
            <label class="radio-label">
                <input type="radio" name="paymentMethod" value="fpx" checked>
                <strong>FPX Online Banking</strong>
            </label>
            <label class="radio-label">
                <input type="radio" name="paymentMethod" value="ewallet">
                <strong>E-Wallet (Touch 'n Go / GrabPay)</strong>
            </label>
        </div>
    </div>

    <button class="btn-pay" onclick="processPayment()">Confirm & Pay Now</button>

    <div style="text-align:center; margin-top:20px;">
        <a href="../customer/cart.php" class="btn">← Back to Cart</a>
    </div>

</div>

<script>

// Get cart from PHP session
const cart = <?php echo json_encode($cart); ?>;

function loadCheckoutSummary()
{
    const container = document.getElementById("checkoutItemsContainer");
    let html = "", total = 0;

    cart.forEach(item =>
    {
        let sub = item.subtotal || (item.price * item.quantity);
        total  += sub;

        let custom = "";
        if (item.spicy)  custom += `<div>• Spicy: ${item.spicy}</div>`;
        if (item.drink)  custom += `<div>• Beverage: ${item.drink}</div>`;
        if (item.addons && item.addons.length) custom += `<div>• Add-ons: ${item.addons.join(", ")}</div>`;
        if (item.note)   custom += `<div class="item-note">• Note: "${item.note}"</div>`;

        html += `
            <div class="item-row">
                <div style="flex:1;">
                    <div class="item-name">${item.name}</div>
                    ${custom ? `<div class="item-customization">${custom}</div>` : ""}
                </div>
                <div style="width:50px; text-align:center; color:#555;">x${item.quantity}</div>
                <div style="width:80px; text-align:right; font-weight:bold; color:#274472;">RM ${sub.toFixed(2)}</div>
            </div>`;
    });

    container.innerHTML = html;
    document.getElementById("grandTotalPrice").innerText = "RM " + total.toFixed(2);
    window.orderTotal = total;
}

function processPayment()
{
    const method = document.querySelector('input[name="paymentMethod"]:checked').value;

    if (cart.length === 0)
    {
        alert("Your cart is empty!");
        return;
    }

    // Send payment data to server
    fetch("../api/process-payment.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            cart: cart,
            paymentMethod: method,
            total: window.orderTotal
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success)
        {
            alert("Payment authorized via " + method.toUpperCase() + "!");
            window.location.href = "receipt.php?order_id=" + data.orderId;
        }
        else
        {
            alert("Payment failed: " + (data.message || "Unknown error"));
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("Payment processing error. Please try again.");
    });
}

loadCheckoutSummary();

</script>

</body>
</html>
