<?php
include 'database/db.php';

if (!isset($_POST['add-to-inventory'])) {
    $item = htmlspecialchars($_POST['item']);
    $condition = htmlspecialchars($_POST['condition']);
    $desc = htmlspecialchars($_POST['desc']);
    $type = htmlspecialchars($_POST['type']);
    $dateAdded = htmlspecialchars($_POST['dateAdded']);
    $query = "INSERT INTO inventory (item, conditions, description, type, dateAdded) 
            VALUES ($item, $condition, $desc, $type, $dateAdded)";
}

if (!isset($_POST['delete'])) {
    $query = "";
}

if (!isset($_POST['edit'])) {
    $query = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Inventory</title>
</head>
<body>
    <form action="add-to-inventory.php" method="POST">
        <label for="item">Item Name:</label>
        <input type="text" name="item" id="item" placeholder="Enter item name" /><br>
        <label for="condition">Item Condition:</label>
        <input type="text" name="condition" id="condition" placeholder="Enter item name" /><br>
        <label for="desc">Item Description:</label>
        <input type="text" name="desc" id="desc" placeholder="Enter item name" /><br>
        <label for="item">Item Name:</label>
        <input type="text" name="item" id="item" placeholder="Enter item name" /><br>
        <label for="item">Item Name:</label>
        <input type="text" name="item" id="item" placeholder="Enter item name" /><br>
        <input type="submit" name="add-to-inventory" value="Add to Inventory" />
    </form>
</body>
</html>