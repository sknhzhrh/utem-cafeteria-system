<?php
session_start();
include("../connect.php");

if (!isset($_SESSION['operator_id']))
{
    header("Location: operator-login.php");
    exit();
}

$editMode = false;
$editItem = null;
$message = "";

/* ADD ITEM */
if (isset($_POST['add']))
{
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    if ($name != "" && $price != "" && $category != "")
    {
        $sql = "INSERT INTO menu (name, price, category) VALUES ('$name', '$price', '$category')";
        mysqli_query($conn, $sql);

        $message = "Item added successfully";
    }
}

/* UPDATE ITEM */
if (isset($_POST['update']))
{
    $menu_id = $_POST['menu_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $sql = "UPDATE menu 
            SET name='$name', price='$price', category='$category'
            WHERE menu_id='$menu_id'";

    mysqli_query($conn, $sql);

    $message = "Item updated successfully";
}

/* DELETE ITEM */
if (isset($_GET['delete']))
{
    $menu_id = $_GET['delete'];

    $sql = "DELETE FROM menu WHERE menu_id='$menu_id'";
    mysqli_query($conn, $sql);

    header("Location: manage-menu.php");
    exit();
}

/* EDIT ITEM */
if (isset($_GET['edit']))
{
    $editMode = true;
    $menu_id = $_GET['edit'];

    $sqlEdit = "SELECT * FROM menu WHERE menu_id='$menu_id'";
    $resultEdit = mysqli_query($conn, $sqlEdit);
    $editItem = mysqli_fetch_assoc($resultEdit);
}

/* FETCH MENU */
$sql = "SELECT * FROM menu ORDER BY category, name";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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

    <?php if ($message != ""): ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="form-card">

        <h2>
            <?php echo $editMode ? "Edit Menu Item" : "Add New Menu Item"; ?>
        </h2>

        <form method="POST">

            <?php if ($editMode): ?>
                <input type="hidden" name="menu_id" value="<?php echo $editItem['menu_id']; ?>">
            <?php endif; ?>

            <div class="form-row">

                <div class="form-group">
                    <label>Item Name</label>
                    <input type="text" name="name" required
                    value="<?php echo $editMode ? $editItem['name'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Price (RM)</label>
                    <input type="number" name="price" step="0.01" min="0" required
                    value="<?php echo $editMode ? $editItem['price'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="">-- Select Category --</option>

                        <?php
                        $categories = ["Rice", "Noodles", "Snacks", "Drinks", "Desserts"];

                        foreach ($categories as $category)
                        {
                            $selected = "";

                            if ($editMode && $editItem['category'] == $category)
                            {
                                $selected = "selected";
                            }

                            echo "<option value='$category' $selected>$category</option>";
                        }
                        ?>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                <?php if ($editMode): ?>
                    <button type="submit" name="update">💾 Save Changes</button>
                    <a href="manage-menu.php" class="btn-cancel">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="add">+ Add Item</button>
                <?php endif; ?>
            </div>

        </form>

    </div>

    <div class="report-box">

        <h2>Current Menu Items</h2>

        <table class="report-table">
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Action</th>
            </tr>

            <?php if (mysqli_num_rows($result) == 0): ?>

                <tr>
                    <td colspan="5" style="text-align:center; padding:20px; color:#888;">
                        No menu items yet. Add one above.
                    </td>
                </tr>

            <?php else: ?>

                <?php $no = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>
                            <span class="category-badge category-<?php echo strtolower($row['category']); ?>">
                                <?php echo $row['category']; ?>
                            </span>
                        </td>
                        <td>RM <?php echo number_format($row['price'], 2); ?></td>
                        <td class="action-buttons">
                            <a href="manage-menu.php?edit=<?php echo $row['menu_id']; ?>" class="btn-edit">
                                ✏️ Edit
                            </a>

                            <a href="manage-menu.php?delete=<?php echo $row['menu_id']; ?>"
                               class="btn-delete"
                               onclick="return confirm('Delete this item?');">
                                🗑 Delete
                            </a>
                        </td>
                    </tr>

                <?php endwhile; ?>

            <?php endif; ?>

        </table>

    </div>

</div>

</body>
</html>

