<?php

session_start();
include("../connect.php");

if (!isset($_SESSION['operator_id']))
{
    header("Location: operator-login.php");
    exit();
}

// Fetch all menu items
$sql = "SELECT * FROM menu ORDER BY category, name";
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
            <?php if (empty($menuItems)): ?>
                <tr><td colspan="5" style="text-align:center; padding:20px; color:#888;">No menu items yet. Add one above.</td></tr>
            <?php else: ?>
                <?php foreach ($menuItems as $index => $item): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><span class="category-badge category-<?php echo strtolower($item['category']); ?>"><?php echo $item['category']; ?></span></td>
                        <td>RM <?php echo number_format($item['price'], 2); ?></td>
                        <td class="action-buttons">
                            <a href="#" class="btn-edit" onclick="editItem(<?php echo $item['menu_id']; ?>)">✏️ Edit</a>
                            <button class="btn-delete" onclick="deleteItem(<?php echo $item['menu_id']; ?>, '<?php echo addslashes($item['name']); ?>')">🗑 Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>

        <p class="no-items" id="noItems" style="display:none;">No menu items yet. Add one above.</p>

    </div>

</div>

<script>

let editingId = null;

function submitForm()
{
    const name     = document.getElementById("inputName").value.trim();
    const price    = parseFloat(document.getElementById("inputPrice").value);
    const category = document.getElementById("inputCategory").value;

    if (!name || isNaN(price) || price < 0 || !category) { alert("Please fill in all fields."); return; }

    const action = editingId ? "update" : "add";
    
    fetch("../api/manage-menu.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action, menu_id: editingId, name, price, category })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success)
        {
            showAlert("✓ " + (editingId ? "Item updated" : "Item added") + " successfully");
            clearForm();
            cancelEdit();
            setTimeout(() => location.reload(), 1000);
        }
        else
        {
            alert("Error: " + (data.message || "Unknown error"));
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("Error processing request");
    });
}

function editItem(id)
{
    fetch(`../api/get-menu-item.php?menu_id=${id}`)
        .then(response => response.json())
        .then(item => {
            if (!item || !item.menu_id) { alert("Item not found"); return; }
            editingId = id;
            document.getElementById("inputName").value         = item.name;
            document.getElementById("inputPrice").value        = item.price;
            document.getElementById("inputCategory").value     = item.category;
            document.getElementById("formTitle").textContent   = "Edit Menu Item";
            document.getElementById("submitBtn").textContent   = "💾 Save Changes";
            document.getElementById("cancelBtn").style.display = "inline";
            document.querySelector(".form-card").scrollIntoView({ behavior:"smooth" });
        })
        .catch(err => {
            console.error("Error:", err);
            alert("Error loading item");
        });
}

function cancelEdit()
{
    editingId = null; clearForm();
    document.getElementById("formTitle").textContent   = "Add New Menu Item";
    document.getElementById("submitBtn").textContent   = "+ Add Item";
    document.getElementById("cancelBtn").style.display = "none";
    return false;
}

function deleteItem(id, name)
{
    if (!confirm("Delete " + name + "?")) return;
    
    fetch("../api/manage-menu.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "delete", menu_id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success)
        {
            showAlert("✓ Item deleted");
            setTimeout(() => location.reload(), 1000);
        }
        else
        {
            alert("Error: " + (data.message || "Unknown error"));
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("Error deleting item");
    });
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

</script>

</body>
</html>
