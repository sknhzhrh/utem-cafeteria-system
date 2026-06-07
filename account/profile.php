<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTeM Cafeteria</title>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>

<body onload="displayProfile()">

<?php include("../includes/head.php"); ?>


<div class="profile-box">

    <div class="profile-icon">
        C
    </div>

    <h1>Customer Profile</h1>

    <div class="profile-info">
        <strong>Name:</strong>
        <p id="profileName"></p>
    </div>

    <div class="profile-info">
        <strong>Phone:</strong>
        <p id="profilePhone"></p>
    </div>

    <div class="profile-info">
        <strong>Email:</strong>
        <p id="profileEmail"></p>
    </div>

    <a href="dashboard.php" class="btn">Back to Home</a>

</div>

<script>

function displayProfile()
{
    document.getElementById("profileName").innerHTML =
    localStorage.getItem("customerName");

    document.getElementById("profilePhone").innerHTML =
    localStorage.getItem("customerPhone");

    document.getElementById("profileEmail").innerHTML =
    localStorage.getItem("customerEmail");
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