<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['operator_id']))
{
    header("Location: operator-login.php");
    exit();
}

/* Total Revenue */
$sqlRevenue = "SELECT SUM(amount) AS totalRevenue FROM payment";
$resultRevenue = mysqli_query($conn, $sqlRevenue);
$rowRevenue = mysqli_fetch_assoc($resultRevenue);

$totalRevenue = $rowRevenue['totalRevenue'];

if ($totalRevenue == NULL)
{
    $totalRevenue = 0;
}

/* Total Orders */
$sqlOrders = "SELECT COUNT(order_id) AS totalOrders FROM orders";
$resultOrders = mysqli_query($conn, $sqlOrders);
$rowOrders = mysqli_fetch_assoc($resultOrders);

$totalOrders = $rowOrders['totalOrders'];

/* Total Menu */
$sqlMenu = "SELECT COUNT(menu_id) AS totalMenu FROM menu";
$resultMenu = mysqli_query($conn, $sqlMenu);
$rowMenu = mysqli_fetch_assoc($resultMenu);

$totalMenu = $rowMenu['totalMenu'];

/* Top Selling Menu */
$sqlTopMenu = "
    SELECT
        menu.name,
        SUM(order_menu.quantity) AS totalSold,
        SUM(order_menu.subtotal) AS totalRevenue
    FROM order_menu
    INNER JOIN menu
        ON order_menu.menu_id = menu.menu_id
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
    <title>Sales Report</title>
    <link rel="stylesheet" type="text/css" href="../css/sakinah.css">
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
            <h3>Total Menu</h3>
            <p><?php echo $totalMenu; ?> Menu Items</p>
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

            <?php
            if (mysqli_num_rows($resultTopMenu) > 0)
            {
                while ($rowTop = mysqli_fetch_assoc($resultTopMenu))
                {
            ?>

            <tr>
                <td><?php echo $rowTop['name']; ?></td>
                <td><?php echo $rowTop['totalSold']; ?></td>
                <td>RM <?php echo number_format($rowTop['totalRevenue'], 2); ?></td>
                <td>

                    <?php
                    if ($rowTop['totalSold'] >= 50)
                    {
                        echo "<span class='high-demand'>High Demand</span>";
                    }
                    else
                    {
                        echo "<span class='normal-demand'>Normal</span>";
                    }
                    ?>

                </td>
            </tr>

            <?php
                }
            }
            else
            {
            ?>

            <tr>
                <td colspan="4">No sales data available</td>
            </tr>

            <?php
            }
            ?>

        </table>

    </div>

    <div class="report-box">

        <h2>Sales Graph</h2>

        <div class="graph">

            <?php

            mysqli_data_seek($resultTopMenu, 0);

            if (mysqli_num_rows($resultTopMenu) > 0)
            {
                while ($rowGraph = mysqli_fetch_assoc($resultTopMenu))
                {
                    $totalSold = $rowGraph['totalSold'];

                    if ($totalSold > 100)
                    {
                        $barWidth = 100;
                    }
                    else
                    {
                        $barWidth = $totalSold;
                    }
            ?>

            <div class="graph-row">

                <span>
                    <?php echo $rowGraph['name']; ?>
                </span>

                <div class="graph-bar" style="width: <?php echo $barWidth; ?>%;">
                    <?php echo $totalSold; ?> sold
                </div>

            </div>

            <?php
                }
            }
            else
            {
            ?>

            <p>No sales data available</p>

            <?php
            }
            ?>

        </div>

    </div>

</div>

</body>

</html>