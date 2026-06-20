<?php

session_start();
include("../connect.php");

if (isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM cafeteria_operator
            WHERE username='$username'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1)
    {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password']))
        {
            $_SESSION['operator_id'] = $row['operator_id'];
            $_SESSION['operator_name'] = $row['name'];

            header("Location: operator-dashboard.php");
            exit();
        }
        else
        {
            echo "<script>alert('Invalid Username or Password');</script>";
        }
    }
    else
    {
        echo "<script>alert('Invalid Username or Password');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Login</title>
    <link rel="stylesheet" href="../css/sakinah.css">
</head>

<body>

    <div class="logo">
        <h2>UTeM Campus Food Ordering System</h2>
    </div>

    <div class="container">

        <h1>Operator Login</h1>

        <p class="subtitle">
            Login to manage cafeteria menu and orders
        </p>

        <form method="POST">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter operator username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" name="login">Login</button>

        </form>

        <p class="link-text">
            Customer login?
            <a href="../account/login.php">Click here</a>
        </p>

    </div>

</body>
</html>