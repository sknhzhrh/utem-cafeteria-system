<?php

session_start();

if (!isset($_SESSION['customer_id']))
{
    header("Location: ../account/login.php");
    exit();
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

const menuItems = [
    { menu_id: 1,  name: "Nasi Lemak",   price: 4.50, category: "Rice",     emoji: "🍛" },
    { menu_id: 2,  name: "Nasi Goreng",  price: 5.00, category: "Rice",     emoji: "🍳" },
    { menu_id: 3,  name: "Nasi Ayam",    price: 5.50, category: "Rice",     emoji: "🍗" },
    { menu_id: 4,  name: "Mee Goreng",   price: 4.50, category: "Noodles",  emoji: "🍜" },
    { menu_id: 5,  name: "Mee Rebus",    price: 4.50, category: "Noodles",  emoji: "🍲" },
    { menu_id: 6,  name: "Laksa",        price: 5.50, category: "Noodles",  emoji: "🥣" },
    { menu_id: 7,  name: "Roti Canai",   price: 2.00, category: "Snacks",   emoji: "🫓" },
    { menu_id: 8,  name: "Karipap",      price: 1.50, category: "Snacks",   emoji: "🥟" },
    { menu_id: 9,  name: "Teh Tarik",    price: 2.50, category: "Drinks",   emoji: "🧋" },
    { menu_id: 10, name: "Milo Ais",     price: 2.50, category: "Drinks",   emoji: "🥤" },
    { menu_id: 11, name: "Air Mineral",  price: 1.50, category: "Drinks",   emoji: "💧" },
    { menu_id: 12, name: "Cendol",       price: 3.00, category: "Desserts", emoji: "🍧" },
    { menu_id: 13, name: "Ais Kacang",   price: 3.50, category: "Desserts", emoji: "🍨" },
    { menu_id: 14, name: "Puding Roti",  price: 2.50, category: "Desserts", emoji: "🍮" },
];

let activeCategory = "All";
let cart = JSON.parse(localStorage.getItem("cart")) || [];

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
                <div class="menu-card-price">RM ${item.price.toFixed(2)}</div>
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
    localStorage.setItem("selectedFoodId", menuId);
    window.location.href = "customization.php";
}

function updateCartCount()
{
    const total = cart.reduce((sum, c) => sum + c.quantity, 0);
    const el    = document.getElementById("cartCount");
    if (el) el.textContent = total;
}

renderMenu();
updateCartCount();

</script>

</body>
</html>
