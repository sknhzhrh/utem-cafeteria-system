<?php

include("../connect.php");

if (isset($_POST['register']))
{
    $name     = $_POST['name'];
    $phone    = $_POST['phone'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO customer (name, phone, email, password)
            VALUES ('$name', '$phone', '$email', '$password')";

    if (mysqli_query($conn, $sql))
    {
        echo "<script>
                alert('Registration Successful');
                window.location='login.php';
              </script>";
    }
    else
    {
        echo "<script>alert('Registration Failed');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Register - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
</head>
<body>

<div class="navbar">
    <div class="logo">
        <h2>UTeM Campus Food Ordering System</h2>
    </div>
</div>

<div class="container">

    <h1>Customer Register</h1>
    <p class="subtitle">Create customer account before ordering</p>

    <form method="POST">

        <div class="form-group">
            <label>Customer Name</label>
            <input type="text" name="name" placeholder="Enter customer name" required>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="Enter phone number" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter email address" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Create password" required>
        </div>

        <button type="submit" name="register">Register</button>

    </form>

    <p class="link-text">Already have an account? <a href="login.php">Login here</a></p>

</div>

</body>
</html>
