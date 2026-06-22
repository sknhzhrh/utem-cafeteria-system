<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['operator_id']))
{
    header("Location: operator-login.php");
    exit();
}

/* Total Revenue */
$sqlRevenue    = "SELECT SUM(amount) AS totalRevenue FROM payment";
$resultRevenue = mysqli_query($conn, $sqlRevenue);
$rowRevenue    = mysqli_fetch_assoc($resultRevenue);
$totalRevenue  = $rowRevenue['totalRevenue'] ?? 0;

/* Total Orders */
$sqlOrders    = "SELECT COUNT(order_id) AS totalOrders FROM orders";
$resultOrders = mysqli_query($conn, $sqlOrders);
$rowOrders    = mysqli_fetch_assoc($resultOrders);
$totalOrders  = $rowOrders['totalOrders'];

/* Total Menu Items */
$sqlMenu    = "SELECT COUNT(menu_id) AS totalMenu FROM menu";
$resultMenu = mysqli_query($conn, $sqlMenu);
$rowMenu    = mysqli_fetch_assoc($resultMenu);
$totalMenu  = $rowMenu['totalMenu'];

/* Top Selling Menu */
$sqlTopMenu = "
    SELECT menu.name,
    SUM(order_menu.quantity) AS totalSold,
    SUM(order_menu.subtotal) AS totalRevenue
    FROM order_menu
    INNER JOIN menu ON order_menu.menu_id = menu.menu_id
    GROUP BY menu.menu_id, menu.name
    ORDER BY totalSold DESC
";
$resultTopMenu = mysqli_query($conn, $sqlTopMenu);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
</head>
<body>

<?php include("../includes/operator-head.php"); ?>

<div class="dashboard">

    <div class="welcome-box">
        <h1>Sales Reports</h1>
    </div>

    <div class="card-row">

        <div class="card">
            <h3>Total Revenue</h3>
            <p>RM <?php echo number_format($totalRevenue, 2); ?></p>
        </div>

        <div class="card">
            <h3>Total Orders</h3>
            <p><?php echo $totalOrders; ?> Orders</p>
        </div>

        <div class="card">
            <h3>Total Menu Items</h3>
            <p><?php echo $totalMenu; ?> Items</p>
        </div>

    </div>

    <div class="report-box">

        <h2>Top Selling Menu Items</h2>

        <table class="report-table">
            <tr>
                <th>Menu Name</th>
                <th>Total Sold</th>
                <th>Revenue</th>
                <th>Status</th>
            </tr>

            <?php if (mysqli_num_rows($resultTopMenu) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resultTopMenu)): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['totalSold']; ?></td>
                    <td>RM <?php echo number_format($row['totalRevenue'], 2); ?></td>
                    <td>
                        <?php if ($row['totalSold'] >= 50): ?>
                            <span class="high-demand">High Demand</span>
                        <?php else: ?>
                            <span class="normal-demand">Normal</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No sales data available</td></tr>
            <?php endif; ?>

        </table>

    </div>

    <div class="report-box">

        <h2>Sales Graph</h2>

        <div class="graph">
            <?php
            mysqli_data_seek($resultTopMenu, 0);
            if (mysqli_num_rows($resultTopMenu) > 0):
                while ($row = mysqli_fetch_assoc($resultTopMenu)):
                    $barWidth = min($row['totalSold'], 100);
            ?>
            <div class="graph-row">
                <span><?php echo $row['name']; ?></span>
                <div class="graph-bar" style="width:<?php echo $barWidth; ?>%;">
                    <?php echo $row['totalSold']; ?> sold
                </div>
            </div>
            <?php
                endwhile;
            else:
                echo "<p>No sales data available</p>";
            endif;
            ?>
        </div>

    </div>

</div>

</body>
</html>
