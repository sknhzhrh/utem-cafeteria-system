<?php

session_start();

if (!isset($_SESSION['operator_id']))
{
    header("Location: operator-login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - UTeM Cafeteria</title>
    <link rel="stylesheet" href="../css/sakinah.css">
    <link rel="stylesheet" href="../css/manage-menu.css">
</head>
<body>

<?php include("../includes/operator-head.php"); ?>

<div class="dashboard">

    <div class="welcome-box">
        <h1>Manage Menu</h1>
        <p>Add, edit and remove menu items from the cafeteria.</p>
    </div>

    <div class="alert alert-success" id="alertBox" style="display:none;"></div>

    <div class="form-card">

        <h2 id="formTitle">Add New Menu Item</h2>

        <div class="form-row">
            <div class="form-group">
                <label>Item Name</label>
                <input type="text" id="inputName" placeholder="e.g. Nasi Lemak">
            </div>
            <div class="form-group">
                <label>Price (RM)</label>
                <input type="number" id="inputPrice" placeholder="e.g. 4.50" step="0.01" min="0">
            </div>
            <div class="form-group">
                <label>Category</label>
                <select id="inputCategory">
                    <option value="">-- Select Category --</option>
                    <option value="Rice">Rice</option>
                    <option value="Noodles">Noodles</option>
                    <option value="Snacks">Snacks</option>
                    <option value="Drinks">Drinks</option>
                    <option value="Desserts">Desserts</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button id="submitBtn" onclick="submitForm()">+ Add Item</button>
            <a href="#" class="btn-cancel" id="cancelBtn" style="display:none;" onclick="cancelEdit()">Cancel</a>
        </div>

    </div>

    <div class="report-box">

        <h2>Current Menu Items</h2>

        <table class="report-table" id="menuTable">
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </table>

        <p class="no-items" id="noItems" style="display:none;">No menu items yet. Add one above.</p>

    </div>

</div>

<script>

let menuItems = [
    { menu_id:1, name:"Nasi Lemak",  price:4.50, category:"Rice"    },
    { menu_id:2, name:"Nasi Goreng", price:5.00, category:"Rice"    },
    { menu_id:3, name:"Mee Goreng",  price:4.50, category:"Noodles" },
    { menu_id:4, name:"Teh Tarik",   price:2.50, category:"Drinks"  },
    { menu_id:5, name:"Roti Canai",  price:2.00, category:"Snacks"  },
];

let nextId = 6, editingId = null;

function renderTable()
{
    const table   = document.getElementById("menuTable");
    const noItems = document.getElementById("noItems");

    while (table.rows.length > 1) table.deleteRow(1);

    if (menuItems.length === 0) { noItems.style.display = "block"; return; }
    noItems.style.display = "none";

    menuItems.forEach((item, index) =>
    {
        const row = table.insertRow();
        row.innerHTML =
            `<td>${index + 1}</td>` +
            `<td>${item.name}</td>` +
            `<td><span class="category-badge category-${item.category.toLowerCase()}">${item.category}</span></td>` +
            `<td>RM ${item.price.toFixed(2)}</td>` +
            `<td class="action-buttons">
                <a href="#" class="btn-edit" onclick="editItem(${item.menu_id})">✏️ Edit</a>
                <button class="btn-delete" onclick="deleteItem(${item.menu_id})">🗑 Delete</button>
            </td>`;
    });
}

function submitForm()
{
    const name     = document.getElementById("inputName").value.trim();
    const price    = parseFloat(document.getElementById("inputPrice").value);
    const category = document.getElementById("inputCategory").value;

    if (!name || isNaN(price) || price < 0 || !category) { alert("Please fill in all fields."); return; }

    if (editingId !== null)
    {
        const item = menuItems.find(i => i.menu_id === editingId);
        item.name = name; item.price = price; item.category = category;
        showAlert("✓ Item updated successfully");
        cancelEdit();
    }
    else
    {
        menuItems.push({ menu_id: nextId++, name, price, category });
        showAlert("✓ Item added successfully");
        clearForm();
    }

    renderTable();
}

function editItem(id)
{
    const item = menuItems.find(i => i.menu_id === id);
    if (!item) return;
    editingId = id;
    document.getElementById("inputName").value         = item.name;
    document.getElementById("inputPrice").value        = item.price;
    document.getElementById("inputCategory").value     = item.category;
    document.getElementById("formTitle").textContent   = "Edit Menu Item";
    document.getElementById("submitBtn").textContent   = "💾 Save Changes";
    document.getElementById("cancelBtn").style.display = "inline";
    document.querySelector(".form-card").scrollIntoView({ behavior:"smooth" });
}

function cancelEdit()
{
    editingId = null; clearForm();
    document.getElementById("formTitle").textContent   = "Add New Menu Item";
    document.getElementById("submitBtn").textContent   = "+ Add Item";
    document.getElementById("cancelBtn").style.display = "none";
    return false;
}

function deleteItem(id)
{
    const item = menuItems.find(i => i.menu_id === id);
    if (!item || !confirm("Delete " + item.name + "?")) return;
    menuItems = menuItems.filter(i => i.menu_id !== id);
    showAlert("✓ Item deleted");
    renderTable();
}

function clearForm()
{
    document.getElementById("inputName").value     = "";
    document.getElementById("inputPrice").value    = "";
    document.getElementById("inputCategory").value = "";
}

function showAlert(msg)
{
    const box = document.getElementById("alertBox");
    box.textContent = msg; box.style.display = "block";
    setTimeout(() => box.style.display = "none", 3000);
}

renderTable();

</script>

</body>
</html>
