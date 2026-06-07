<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTeM Cafeteria</title>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>
<body>

<div class="navbar">

    <div class="logo">
        <h2>UTeM Campus Food Ordering System</h2>
    </div>

</div>

<div class="container">

    <h1>Customer Login</h1>

    <p class="subtitle">Login to access food ordering system</p>

    <form onsubmit="event.preventDefault(); loginCustomer();">

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" id="loginEmail" placeholder="Enter email address"required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" id="loginPassword" placeholder="Enter password" required>
        </div>

        <button type="submit">Login</button>

    </form>

    <p class="link-text">New customer? <a href="register.php">Register here</a></p>

</div>

<script>

function loginCustomer()
{
    let email = document.getElementById("loginEmail").value;

    let password = document.getElementById("loginPassword").value;

    let savedEmail = localStorage.getItem("customerEmail");

    let savedPassword = localStorage.getItem("customerPassword");

    if(email === savedEmail && password === savedPassword)
    {
        localStorage.setItem("loginStatus", "true");

        alert("Login Successful");

        window.location.href = "dashboard.php";
    }

    else
    {
        alert("Invalid Email or Password");
    }
}

</script>
    
</body>
</html>