<?php
require 'db/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHJCS Inventory System</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/addItemForm.css">
</head>

<body>
    
    <div id="addItemModal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add New Item</h2>
            <form id="addItemForm" method="POST" action="addItem.php">
                <div id="flex">
                    <div>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required><br>

                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" required><br>

                        <label for="type">Type:</label>
                        <input type="text" id="type" name="type" required><br>

                        <label for="issue">Issue:</label>
                        <input type="text" id="issue" name="issue"><br>
                    </div>

                    <div>
                        <label for="conditions">Condition:</label>
                        <select name="conditions">
                            <option value="" disabled selected>Select condition</option>
                            <option value="Available">Available</option>
                            <option value="Damaged">Damaged</option>
                            <option value="Under Repair">Under Repair</option>
                        </select><br>

                        <label for="room">Room:</label>
                        <input type="text" id="room" name="room"><br>

                        <label for="description">Description:</label>
                        <textarea id="description" name="description"></textarea><br>

                        <label for="date">Date:</label>
                        <input type="datetime-local" id="date" name="date" required><br>
                    </div>
                </div>

                <button type="submit">Add Item</button>
            </form>
        </div>
    </div>

    <div class="layout">
        <aside>
            <nav>
                <h1>Navigations</h1>
                <ul>
                    <li><a href="views/dashboard.php">Dashboard</a></li>
                    <li><a href="routes/getReport.php">Get Report</a></li>
                    <li><a href="views/reports.php">Reports</a></li>
                    <li><a href="routes/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <div class="main-wrapper">
            <header>
                <h1>SHJCS Inventory System</h1>
                <div>
                    <button onclick="addItem()">+</button>
                </div>
            </header>

            <main>
                <img src="asset/bg.png">
                <section id="genList">
                    <div id="box-cards">
                        <div class="box-card">
                            <h3>Total Items</h3>
                            <?php
                            $query = "SELECT SUM(quantity) AS total FROM items";
                            $result = mysqli_query($conn, $query);
                            $data = mysqli_fetch_assoc($result);
                            $totalItems = $data['total'];
                            ?>
                            <p><?php echo $totalItems; ?></p>
                        </div>

                        <div class="dmgItems">
                            <h3>Damaged Items</h3>
                            <?php
                            $query = "SELECT SUM(quantity) AS damaged FROM items WHERE conditions = 'Damaged'";
                            $result = mysqli_query($conn, $query);
                            $data = mysqli_fetch_assoc($result);
                            $damagedItems = $data['damaged'];
                            ?>
                            <p><?php echo $damagedItems; ?></p>
                        </div>

                        <div class="availItems">
                            <h3>Available Items</h3>
                            <?php
                            $query = "SELECT SUM(quantity) AS available FROM items WHERE conditions = 'Available'";
                            $result = mysqli_query($conn, $query);
                            $data = mysqli_fetch_assoc($result);
                            $availableItems = $data['available'];
                            ?>
                            <p><?php echo $availableItems; ?></p>
                        </div>

                        <div class="undpairItems">
                            <h3>Under Repair Items</h3>
                            <?php
                            $query = "SELECT SUM(quantity) AS under_repair FROM items WHERE conditions = 'Under Repair'";
                            $result = mysqli_query($conn, $query);
                            $data = mysqli_fetch_assoc($result);
                            $underRepairItems = $data['under_repair'];
                            ?>
                            <p><?php echo $underRepairItems; ?></p>
                        </div>
                    </div>
                </section>

                <section id="inventory-list">
                    <!-- Sort Items Form -->
                    <form action="index.php" method="POST">
                        <select id="sort" name="sort" onchange="this.form.submit()">
                            <option value="" disabled selected>Sort By</option>
                            <option value="id_asc">Default</option>
                            <option value="name_asc">Name (A–Z)</option>
                            <option value="name_desc">Name (Z–A)</option>
                            <option value="quantity_asc">Quantity (Low to High)</option>
                            <option value="quantity_desc">Quantity (High to Low)</option>
                            <option value="date_asc">Date Added (Oldest First)</option>
                            <option value="date_desc">Date Added (Newest First)</option>
                        </select>
                    </form>
                    <!-- Inventory items will be dynamically loaded here -->
                    <table border="3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Type</th>
                                <th>Issue</th>
                                <th>Condition</th>
                                <th>Room</th>
                                <th>Description</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="inventory-tbody">
                            <div id="editItemModal" style="display:none;">
                                <div class="modal-content">
                                    <span class="close" onclick="closeEditModal()">&times;</span>
                                    <h2>Edit Item</h2>

                                    <form id="editItemForm" method="POST" action="edit.php">
                                        <input type="hidden" name="id" id="edit-id">

                                        <div id="flex">
                                            <div>
                                                <label for="edit-name">Name:</label>
                                                <input type="text" id="edit-name" name="name" required>

                                                <label for="edit-quantity">Quantity:</label>
                                                <input type="number" id="edit-quantity" name="quantity" required>

                                                <label for="edit-type">Type:</label>
                                                <input type="text" id="edit-type" name="type">

                                                <label for="edit-issue">Issue:</label>
                                                <input type="text" id="edit-issue" name="issue">
                                            </div>

                                            <div>
                                                <label for="edit-conditions">Condition:</label>
                                                <select name="conditions" id="edit-conditions">
                                                    <option value="" disabled selected>Select condition</option>
                                                    <option value="Available">Available</option>
                                                    <option value="Damaged">Damaged</option>
                                                    <option value="Under Repair">Under Repair</option>
                                                </select>

                                                <label for="edit-room">Room:</label>
                                                <input type="text" id="edit-room" name="room">

                                                <label for="edit-description">Description:</label>
                                                <textarea id="edit-description" name="description"></textarea>

                                                <label for="edit-date">Date:</label>
                                                <input type="datetime-local" id="edit-date" name="date" required>
                                            </div>
                                        </div>

                                        <button type="submit">Save Changes</button>
                                    </form>
                                </div>
                            </div>


                            <?php
                            $sort = $_POST['sort'] ?? '';

                            $orderBy = "name ASC"; // default sort
                            
                            switch ($sort) {
                                case 'name_asc':
                                    $orderBy = "name ASC";
                                    break;
                                case 'name_desc':
                                    $orderBy = "name DESC";
                                    break;
                                case 'quantity_asc':
                                    $orderBy = "quantity ASC";
                                    break;
                                case 'quantity_desc':
                                    $orderBy = "quantity DESC";
                                    break;
                                case 'date_asc':
                                    $orderBy = "date ASC";
                                    break;
                                case 'date_desc':
                                    $orderBy = "date DESC";
                                    break;
                                default:
                                    $orderBy = "id ASC";
                            }

                            $sql = "SELECT * FROM items ORDER BY $orderBy";
                            $result = mysqli_query($conn, $sql);
                            $i = 1;
                            if (mysqli_num_rows($result) === 0):
                                ?>
                                <tr>
                                    <td colspan="10">
                                        <center>No items found.</center>
                                    </td>
                                </tr>
                                <?php
                            endif;
                            while ($row = mysqli_fetch_assoc($result)):
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                                    <td><?= htmlspecialchars($row['type']) ?></td>
                                    <td><?= htmlspecialchars($row['issue']) ?></td>
                                    <td><?= htmlspecialchars($row['conditions']) ?></td>
                                    <td><?= htmlspecialchars($row['room']) ?></td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td><?= htmlspecialchars($row['date']) ?></td>
                                    <td>
                                        <button type="button" class="edit-btn" onclick="openEditModal(
                                    '<?= $row['id'] ?>',
                                    '<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>',
                                    '<?= $row['quantity'] ?>',
                                    '<?= htmlspecialchars($row['type'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($row['issue'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($row['conditions']) ?>',
                                    '<?= htmlspecialchars($row['room'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($row['date']) ?>'
                                )">
                                            Edit
                                        </button>

                                        <form action="routes/delete.php" method="POST" style="display:inline;">
                                            <input type="submit" class="delete-btn" data-id="<?= $row['id'] ?>" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>
                </section>
            </main>
        </div>
    </div>
    </div>
    <script src="js/index.js"></script>
</body>

</html>