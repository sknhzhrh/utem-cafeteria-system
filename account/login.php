<?php

session_start();
include("../connect.php");

if (isset($_POST['login']))
{
    $email    = $_POST['email'];
    $password = $_POST['password'];

    if (!str_ends_with($email, "@student.utem.edu.my") && !str_ends_with($email, "@utem.edu.my"))
    {
        echo "<script>alert('Only UTeM email can login');</script>";
    }
    else
    {
        $sql = "SELECT * FROM customer
                WHERE email='$email'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1)
        {
            $row = mysqli_fetch_assoc($result);

            if (password_verify($password, $row['password']))
            {
                $_SESSION['customer_id'] = $row['customer_id'];
                $_SESSION['customer_name'] = $row['name'];

                header("Location: dashboard.php");
                exit();
            }
            else
            {
                echo "<script>alert('Invalid Email or Password');</script>";
            }
        }
        else
        {
            echo "<script>alert('Invalid Email or Password');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
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

    <form method="POST">

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter email address" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>
        </div>

        <button type="submit" name="login">Login</button>

    </form>

    <p class="link-text">
        New customer?
        <a href="register.php">Register here</a>
    </p>

</div>

</body>
</html>
