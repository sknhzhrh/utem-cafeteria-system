<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
}

// Fetch menu items from database
$sql = "SELECT menu_id, name, price, category FROM menu ORDER BY category, name";
$result = mysqli_query($conn, $sql);
$menuItems = [];
while ($row = mysqli_fetch_assoc($result))
{
    $category = $row['category'];
    $emojis = [
        "Rice" => "🍛",
        "Noodles" => "🍜",
        "Snacks" => "🫓",
        "Drinks" => "🧋",
        "Desserts" => "🍧"
    ];
    $row['emoji'] = $emojis[$category] ?? "🍽️";
    $menuItems[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/menu.css">
</head>
<body>

<?php include("../includes/head.php"); ?>

<div class="menu-wrapper">

    <div class="menu-header">
        <h1>Our Menu</h1>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search food or drinks..." oninput="filterMenu()">
            <button onclick="filterMenu()">Search</button>
        </div>
    </div>

    <div class="category-tabs">
        <button class="tab-btn active" onclick="setCategory('All', this)">All</button>
        <button class="tab-btn" onclick="setCategory('Rice', this)">Rice</button>
        <button class="tab-btn" onclick="setCategory('Noodles', this)">Noodles</button>
        <button class="tab-btn" onclick="setCategory('Snacks', this)">Snacks</button>
        <button class="tab-btn" onclick="setCategory('Drinks', this)">Drinks</button>
        <button class="tab-btn" onclick="setCategory('Desserts', this)">Desserts</button>
    </div>

    <div class="menu-grid" id="menuGrid">
        <?php foreach ($menuItems as $item): ?>
            <div class="menu-card" data-category="<?php echo htmlspecialchars($item['category']); ?>">
                <div class="menu-card-img"><?php echo htmlspecialchars($item['emoji']); ?></div>
                <div class="menu-card-body">
                    <div class="menu-card-category"><?php echo htmlspecialchars($item['category']); ?></div>
                    <div class="menu-card-name"><?php echo htmlspecialchars($item['name']); ?></div>
                    <div class="menu-card-price">RM <?php echo number_format($item['price'], 2); ?></div>
                    <button class="add-to-cart-btn" onclick="goCustomize(<?php echo (int)$item['menu_id']; ?>)">Customize & Add</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<div class="toast" id="toast"></div>

<script>

let activeCategory = "All";

function setCategory(category, btn)
{
    activeCategory = category;
    document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    filterMenu();
}

function filterMenu()
{
    const search = document.getElementById("searchInput").value.toLowerCase();
    const cards = document.querySelectorAll(".menu-card");

    cards.forEach(card =>
    {
        const category = card.getAttribute("data-category");
        const name = card.querySelector(".menu-card-name").textContent.toLowerCase();
        const matchesCategory = activeCategory === "All" || category === activeCategory;
        const matchesSearch = name.includes(search);
        card.style.display = matchesCategory && matchesSearch ? "" : "none";
    });
}

function goCustomize(menuId)
{
    window.location.href = "customization.php?menu_id=" + menuId;
}

</script>

</body>
</html>
