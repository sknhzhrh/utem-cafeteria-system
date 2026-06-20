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
    <title>Payment Receipt - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/cart.css">
    <style>
        .status-badge { background:#D4EDDA; color:#155724; padding:8px 16px; border-radius:20px; font-weight:bold; font-size:13px; display:inline-block; margin-bottom:20px; }
        .meta-box { background:#F8F9FA; padding:15px; border-radius:12px; font-size:13.5px; color:#555; margin-bottom:25px; border-left:5px solid #163B73; text-align:left; }
        .meta-box div { margin-bottom:6px; }
        .receipt-bill { border:1px dashed #B8C4D1; background:#FFF; border-radius:12px; padding:20px; margin-bottom:25px; }
        .item-row { display:flex; justify-content:space-between; margin-bottom:12px; font-size:14.5px; }
        .item-name { font-weight:600; color:#333; }
        .item-customization { font-size:12px; color:#777; margin-top:3px; line-height:1.4; }
        .item-note { font-style:italic; color:#C0392B; }
        .divider { border-top:1px dashed #B8C4D1; margin:15px 0; }
        .total-row { display:flex; justify-content:space-between; font-weight:bold; font-size:18px; color:#163B73; }
    </style>
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="container" style="text-align:center;">

    <h1>Transaction Receipt</h1>
    <div class="status-badge">✔ Payment Successful</div>

    <div class="meta-box">
        <div><strong>Transaction ID:</strong> <span id="recId">Loading...</span></div>
        <div><strong>Date & Time:</strong> <span id="recDate">Loading...</span></div>
        <div><strong>Payment Via:</strong> <span id="recMethod">Loading...</span></div>
    </div>

    <div class="receipt-bill">
        <div id="receiptItemsContainer"></div>
        <div class="divider"></div>
        <div class="total-row">
            <span>Amount Paid</span>
            <span id="recTotal">RM 0.00</span>
        </div>
    </div>

    <p class="subtitle" style="font-style:italic; font-size:14px;">
        Thank you! Your order has been submitted to the kitchen.
    </p>

    <a href="../account/dashboard.php" class="btn" style="display:block; text-align:center; padding:14px; background:#163B73; color:white; margin-top:20px;">Back to Home</a>

</div>

<script>

function loadReceiptInfo()
{
    const lastOrder     = JSON.parse(localStorage.getItem("lastOrder"));
    const receiptId     = localStorage.getItem("receiptId");
    const receiptDate   = localStorage.getItem("receiptDate");
    const paymentMethod = localStorage.getItem("paymentMethodUsed");

    document.getElementById("recId").innerText     = receiptId   || "TXN-764912";
    document.getElementById("recDate").innerText   = receiptDate || "06/05/2026, 10:19 PM";
    document.getElementById("recMethod").innerText = paymentMethod || "FPX Online Banking";

    const items = lastOrder || [
        { name:"Kampung Fried Rice", price:7.00, quantity:2, spicy:"Medium", addons:["Extra Egg (+RM2)"], note:"No onions please", subtotal:18.00 },
        { name:"Fresh Lemonade",     price:2.00, quantity:1, drink:"Cold (+RM0.50)", addons:[], note:"", subtotal:2.50 }
    ];

    let total = 0, html = "";

    items.forEach(item =>
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
                <div style="text-align:left; flex:1;">
                    <div class="item-name">${item.name}</div>
                    ${custom ? `<div class="item-customization">${custom}</div>` : ""}
                </div>
                <div style="width:50px; text-align:center; color:#555;">x${item.quantity}</div>
                <div style="width:80px; text-align:right; font-weight:500;">RM ${sub.toFixed(2)}</div>
            </div>`;
    });

    document.getElementById("receiptItemsContainer").innerHTML = html;
    document.getElementById("recTotal").innerText = "RM " + total.toFixed(2);
}

loadReceiptInfo();

</script>

</body>
</html>
