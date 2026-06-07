<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>

<body>

<div class="navbar">

    <div class="logo">
        <h2>UTeM Campus Food Ordering System</h2>
    </div>

    <div class="nav-links">
        <a href="operator-dashboard.php">Dashboard</a>
        <a href="#">Orders</a>
        <a href="#">Menu</a>
        <a href="report.php">Reports</a>
        <a href="../operator/operator-login.php">Logout</a>
    </div>

</div>

<div class="dashboard">

    <div class="welcome-box">
        <h1>Sales Reports</h1>
    </div>

    <div class="card-row">

        <div class="card">
            <h3>Total Revenue</h3>
            <p>RM 2,425.00</p>
        </div>

        <div class="card">
            <h3>Total Orders</h3>
            <p>145 Orders</p>
        </div>

        <div class="card">
            <h3>Best Selling Item</h3>
            <p>Nasi Ayam</p>
        </div>

    </div>

    <div class="report-box">

        <h2>Top Selling Menu Items</h2>

        <p class="report-desc">
            This report shows which food items are highly demanded by customers.
        </p>

        <table class="report-table">

            <tr>
                <th>Food Item</th>
                <th>Quantity Sold</th>
                <th>Revenue</th>
                <th>Status</th>
            </tr>

            <tr>
                <td>Nasi Ayam</td>
                <td>90</td>
                <td>RM 720.00</td>
                <td><span class="high-demand">High Demand</span></td>
            </tr>

            <tr>
                <td>Mee Goreng</td>
                <td>70</td>
                <td>RM 420.00</td>
                <td><span class="high-demand">High Demand</span></td>
            </tr>

            <tr>
                <td>Nasi Lemak</td>
                <td>55</td>
                <td>RM 275.00</td>
                <td><span class="high-demand">High Demand</span></td>
            </tr>

            <tr>
                <td>Roti Canai</td>
                <td>40</td>
                <td>RM 180.00</td>
                <td><span class="normal-demand">Normal</span></td>
            </tr>

        </table>

    </div>

    <div class="report-box">

        <h2>Sales Graph</h2>

        <div class="graph">

            <div class="graph-row">
                <span>Nasi Ayam</span>
                <div class="graph-bar" style="width:90%;">90 sold</div>
            </div>

            <div class="graph-row">
                <span>Mee Goreng</span>
                <div class="graph-bar" style="width:70%;">70 sold</div>
            </div>

            <div class="graph-row">
                <span>Nasi Lemak</span>
                <div class="graph-bar" style="width:55%;">55 sold</div>
            </div>

            <div class="graph-row">
                <span>Roti Canai</span>
                <div class="graph-bar" style="width:40%;">40 sold</div>
            </div>

        </div>

    </div>

</div>

</body>
</html>