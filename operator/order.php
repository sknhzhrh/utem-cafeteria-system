<?php

session_start();

if (!isset($_SESSION['operator_id']))
{
    header("Location: operator-login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/order.css">
</head>
<body>

<?php include("../includes/operator-head.php"); ?>

<div class="container">

    <div class="card">
        <div class="title">
            <h2>Recent Orders</h2>
        </div>

        <table id="ordersTable">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr id="ORD001"><td style="color:blue">ORD001</td><td>Ali</td><td>Nasi Ayam</td><td><span class="status pending">Pending</span></td><td>10:30 AM</td></tr>
                <tr id="ORD002"><td style="color:blue">ORD002</td><td>Siti</td><td>Mee Goreng</td><td><span class="status preparing">Preparing</span></td><td>10:25 AM</td></tr>
                <tr id="ORD003"><td style="color:blue">ORD003</td><td>John</td><td>Nasi Lemak</td><td><span class="status completed">Completed</span></td><td>10:20 AM</td></tr>
                <tr id="ORD004"><td style="color:blue">ORD004</td><td>Aina</td><td>Chicken Chop</td><td><span class="status preparing">Preparing</span></td><td>10:15 AM</td></tr>
                <tr id="ORD005"><td style="color:blue">ORD005</td><td>Hafiz</td><td>Roti Canai</td><td><span class="status pending">Pending</span></td><td>10:10 AM</td></tr>
            </tbody>
        </table>
    </div>

    <div class="card" style="margin-top:20px;">
        <h2>Update Order Status</h2>

        <table id="ordersStatus">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th class="center-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr><td style="color:blue">ORD001</td><td>Ali</td><td><select id="status_ORD001"><option>Pending</option><option>Preparing</option><option>Completed</option></select></td><td class="center-action"><button onclick="updateStatus('ORD001')">Update</button></td></tr>
                <tr><td style="color:blue">ORD002</td><td>Siti</td><td><select id="status_ORD002"><option>Pending</option><option selected>Preparing</option><option>Completed</option></select></td><td class="center-action"><button onclick="updateStatus('ORD002')">Update</button></td></tr>
                <tr><td style="color:blue">ORD003</td><td>John</td><td><select id="status_ORD003"><option>Pending</option><option>Preparing</option><option selected>Completed</option></select></td><td class="center-action"><button onclick="updateStatus('ORD003')">Update</button></td></tr>
                <tr><td style="color:blue">ORD004</td><td>Aina</td><td><select id="status_ORD004"><option>Pending</option><option selected>Preparing</option><option>Completed</option></select></td><td class="center-action"><button onclick="updateStatus('ORD004')">Update</button></td></tr>
                <tr><td style="color:blue">ORD005</td><td>Hafiz</td><td><select id="status_ORD005"><option selected>Pending</option><option>Preparing</option><option>Completed</option></select></td><td class="center-action"><button onclick="updateStatus('ORD005')">Update</button></td></tr>
            </tbody>
        </table>
    </div>

</div>

<script>
function updateStatus(orderId)
{
    const newStatus = document.getElementById("status_" + orderId).value;
    const row       = document.getElementById(orderId);
    const statusEl  = row.querySelector(".status");

    statusEl.className = "status";
    statusEl.classList.add(newStatus.toLowerCase());
    statusEl.textContent = newStatus;

    alert("Order " + orderId + " updated to: " + newStatus);
}
</script>

</body>
</html>
