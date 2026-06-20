<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: login.php");
    exit();
}

$id     = $_SESSION['customer_id'];
$sql    = "SELECT * FROM customer WHERE customer_id='$id'";
$result = mysqli_query($conn, $sql);
$row    = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="profile-box">

    <div class="profile-icon">C</div>

    <h1>Customer Profile</h1>

    <div class="profile-info">
        <strong>Name:</strong>
        <p><?php echo $row['name']; ?></p>
    </div>

    <div class="profile-info">
        <strong>Phone:</strong>
        <p><?php echo $row['phone']; ?></p>
    </div>

    <div class="profile-info">
        <strong>Email:</strong>
        <p><?php echo $row['email']; ?></p>
    </div>

    <a href="dashboard.php" class="btn">Back to Home</a>

</div>

</body>
</html>
