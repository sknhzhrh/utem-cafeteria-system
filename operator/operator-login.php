<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>

    <div class="logo">
        <h2>UTeM Campus Food Ordering System</h2>
    </div>

    <div class="container">
        <h1>Operator Login</h1>
        <p class="subtitle">Login to manage cafeteria menu and orders</p>

        <form onsubmit="operatorLogin(event)">
            <div class="form-group">
                <label>Username</label>
                <input type="text" id="username" placeholder="Enter operator username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" id="password" placeholder="Enter password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <p class="link-text">
            Customer login? <a href="../account/login.php">Click here</a>
        </p>
    </div>

    <script>
        function operatorLogin(event) {
            event.preventDefault();

            let username = document.getElementById("username").value;
            let password = document.getElementById("password").value;

            if (username === "operator" && password === "12345") {
                localStorage.setItem("operatorLoggedIn", "true");
                localStorage.setItem("operatorName", "Cafeteria Operator");
                window.location.href = "../operator/operator-dashboard.php";
            } else {
                alert("Invalid operator username or password.");
            }
        }
    </script>

</body>
</html>