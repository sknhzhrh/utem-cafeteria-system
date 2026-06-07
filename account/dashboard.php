<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTeM Cafeteria</title>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>

<body onload="checkLogin()">

<?php include("../includes/head.php"); ?>

<div class="dashboard">

    <div class="welcome-box">

        <h1 id="welcomeName"></h1>

        <p>
            You are logged in.
            You can now browse menu and place orders.
        </p>

    </div>

    <div class="card-row">

        <div class="card">

            <img src="../images/profile.png" class="card-image">

            <h3>Customer Profile</h3>

            <p>View your customer details</p>

            <br><br>

            <a href="profile.php" class="btn">View Profile</a>

        </div>

        <div class="card">

            <img src="../images/menu.png" class="card-image">

            <h3>Menu</h3>

            <p>Customers can browse available food and drinks</p>

            <br>

            <a href="#" class="btn">Browse Menu</a>

        </div>

        <div class="card">

            <img src="../images/cart.png" class="card-image">

            <h3>Cart</h3>

            <p>Customers can view and manage cart items</p>

            <br>

            <a href="#" class="btn">View Cart</a>

        </div>

    </div>

</div>

<script>

function checkLogin()
{
    if(localStorage.getItem("loginStatus") !== "true")
    {
        alert("Please Login First");
        window.location.href = "login.php";
    }

    let name = localStorage.getItem("customerName");

    document.getElementById("welcomeName").innerHTML =
        "Welcome, " + name + "!";
}

function logoutCustomer()
{
    localStorage.setItem("loginStatus", "false");

    alert("Logout Successful");

    window.location.href = "login.php";
}

</script>

</body>
</html>