<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

$search = "";
$category = "All";

if (isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if (isset($_GET['category']))
{
    $category = $_GET['category'];
}

$sql = "SELECT * FROM menu WHERE 1=1";

if ($category != "All")
{
    $sql .= " AND category='$category'";
}

if (!empty($search))
{
    $sql .= " AND name LIKE '%$search%'";
}

$sql .= " ORDER BY category, name";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu - UTeM Cafeteria</title>

    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/menu.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="menu-wrapper">

    <div class="menu-header">
        <h1>Our Menu</h1>

        <form method="GET" class="search-bar">
            <input
                type="text"
                name="search"
                placeholder="Search food or drinks..."
                value="<?php echo htmlspecialchars($search); ?>">

            <input type="hidden" name="category" value="<?php echo $category; ?>">

            <button type="submit">Search</button>
        </form>
    </div>

    <div class="category-tabs">
        <a href="?category=All" class="tab-btn <?php if($category=="All") echo "active"; ?>">All</a>
        <a href="?category=Rice" class="tab-btn <?php if($category=="Rice") echo "active"; ?>">Rice</a>
        <a href="?category=Noodles" class="tab-btn <?php if($category=="Noodles") echo "active"; ?>">Noodles</a>
        <a href="?category=Snacks" class="tab-btn <?php if($category=="Snacks") echo "active"; ?>">Snacks</a>
        <a href="?category=Drinks" class="tab-btn <?php if($category=="Drinks") echo "active"; ?>">Drinks</a>
        <a href="?category=Desserts" class="tab-btn <?php if($category=="Desserts") echo "active"; ?>">Desserts</a>
    </div>

    <div class="menu-grid">

        <?php if (mysqli_num_rows($result) == 0): ?>

            <div class="no-results">No items found.</div>

        <?php else: ?>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                <?php
                    $emoji = "🍽️";

                    if ($row['category'] == "Rice")
                    {
                        $emoji = "🍛";
                    }
                    elseif ($row['category'] == "Noodles")
                    {
                        $emoji = "🍜";
                    }
                    elseif ($row['category'] == "Snacks")
                    {
                        $emoji = "🫓";
                    }
                    elseif ($row['category'] == "Drinks")
                    {
                        $emoji = "🧋";
                    }
                    elseif ($row['category'] == "Desserts")
                    {
                        $emoji = "🍧";
                    }
                ?>

                <div class="menu-card">

                    <div class="menu-card-img">
                        <?php echo $emoji; ?>
                    </div>

                    <div class="menu-card-body">

                        <div class="menu-card-category">
                            <?php echo $row['category']; ?>
                        </div>

                        <div class="menu-card-name">
                            <?php echo $row['name']; ?>
                        </div>

                        <div class="menu-card-price">
                            RM <?php echo number_format($row['price'], 2); ?>
                        </div>

                        <a href="customization.php?menu_id=<?php echo $row['menu_id']; ?>">
                            <button class="add-to-cart-btn">
                                Customize & Add
                            </button>
                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php endif; ?>

    </div>

</div>

</body>
</html>