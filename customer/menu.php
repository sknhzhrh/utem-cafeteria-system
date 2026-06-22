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

    <div class="menu-grid" id="menuGrid"></div>

</div>

<div class="toast" id="toast"></div>

<script>

const menuItems = <?php
    $itemsWithEmoji = array_map(function($item) {
        $emojis = [
            "Rice" => "🍛",
            "Noodles" => "🍜",
            "Snacks" => "🫓",
            "Drinks" => "🧋",
            "Desserts" => "🍧"
        ];
        $item['emoji'] = $emojis[$item['category']] ?? "🍽️";
        return $item;
    }, $menuItems);
    echo json_encode($itemsWithEmoji);
?>;

let activeCategory = "All";

function renderMenu()
{
    const search   = document.getElementById("searchInput").value.toLowerCase();
    const grid     = document.getElementById("menuGrid");
    const filtered = menuItems.filter(item =>
        (activeCategory === "All" || item.category === activeCategory) &&
        item.name.toLowerCase().includes(search)
    );

    if (filtered.length === 0)
    {
        grid.innerHTML = `<div class="no-results">No items found.</div>`;
        return;
    }

    grid.innerHTML = filtered.map(item => `
        <div class="menu-card">
            <div class="menu-card-img">${item.emoji}</div>
            <div class="menu-card-body">
                <div class="menu-card-category">${item.category}</div>
                <div class="menu-card-name">${item.name}</div>
                <div class="menu-card-price">RM ${parseFloat(item.price).toFixed(2)}</div>
                <button class="add-to-cart-btn" onclick="goCustomize(${item.menu_id})">Customize & Add</button>
            </div>
        </div>
    `).join("");
}

function setCategory(category, btn)
{
    activeCategory = category;
    document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    renderMenu();
}

function filterMenu() { renderMenu(); }

function goCustomize(menuId)
{
    window.location.href = "customization.php?menu_id=" + menuId;
}

renderMenu();

</script>

</body>
</html>
