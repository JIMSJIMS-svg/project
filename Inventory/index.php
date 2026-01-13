<?php
require 'config/auth.php';
require 'config/db.php';

// Require authentication
requireLogin();

$csrf_token = generateCSRFToken();

// Define allowed sort options
$allowedSorts = [
    'id_asc' => 'id ASC',
    'name_asc' => 'name ASC',
    'name_desc' => 'name DESC',
    'quantity_asc' => 'quantity ASC',
    'quantity_desc' => 'quantity DESC',
    'date_asc' => 'date ASC',
    'date_desc' => 'date DESC'
];

// Get sort parameter safely
$sort = $_POST['sort'] ?? 'id_asc';
$orderBy = $allowedSorts[$sort] ?? 'id ASC';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHJCS Inventory System</title>
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/addItemForm.css">
</head>
<body>
    <!-- Add Item Modal -->
    <div id="addItemModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add New Item</h2>
            <form id="addItemForm" method="POST" action="routes/addItem.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                
                <div id="flex">
                    <div>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>

                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" required min="1">

                        <label for="type">Type:</label>
                        <input type="text" id="type" name="type" required>

                        <label for="issue">Issue:</label>
                        <input type="text" id="issue" name="issue">
                    </div>

                    <div>
                        <label for="conditions">Condition:</label>
                        <select name="conditions" required>
                            <option value="" disabled selected>Select condition</option>
                            <option value="Available">Available</option>
                            <option value="Damaged">Damaged</option>
                            <option value="Under Repair">Under Repair</option>
                        </select>

                        <label for="room">Room:</label>
                        <input type="text" id="room" name="room">

                        <label for="description">Description:</label>
                        <textarea id="description" name="description"></textarea>

                        <label for="date">Date:</label>
                        <input type="datetime-local" id="date" name="date" required>
                    </div>
                </div>

                <button type="submit">Add Item</button>
            </form>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="editItemModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Item</h2>
            <form id="editItemForm" method="POST" action="routes/editItem.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="hidden" name="id" id="edit-id">

                <div id="flex">
                    <div>
                        <label for="edit-name">Name:</label>
                        <input type="text" id="edit-name" name="name" required>

                        <label for="edit-quantity">Quantity:</label>
                        <input type="number" id="edit-quantity" name="quantity" required min="1">

                        <label for="edit-type">Type:</label>
                        <input type="text" id="edit-type" name="type" required>

                        <label for="edit-issue">Issue:</label>
                        <input type="text" id="edit-issue" name="issue">
                    </div>

                    <div>
                        <label for="edit-conditions">Condition:</label>
                        <select name="conditions" id="edit-conditions" required>
                            <option value="" disabled>Select condition</option>
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

    <div class="layout">
        <aside>
            <nav>
                <h1>Navigation</h1>
                <ul>
                    <li><a href="views/dashboard.php">Dashboard</a></li>
                    <li><a href="routes/getReport.php">Get Report</a></li>
                    <li><a href="views/reports.php">Reports</a></li>
                    <li><strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></li>
                    <li><a href="config/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <div class="main-wrapper">
            <header>
                <h1>SHJCS Inventory System</h1>
                <div>
                    <button onclick="addItem()">+ Add Item</button>
                </div>
            </header>

            <main>
                <section id="genList">
                    <div id="box-cards">
                        <div class="box-card">
                            <h3>Total Items</h3>
                            <?php
                            $query = "SELECT SUM(quantity) AS total FROM items";
                            $result = $conn->query($query);
                            if ($result) {
                                $data = $result->fetch_assoc();
                                $totalItems = $data['total'] ?? 0;
                            } else {
                                $totalItems = 0;
                                error_log("Database error: " . $conn->error);
                            }
                            ?>
                            <p><?php echo $totalItems; ?></p>
                        </div>

                        <div class="box-card dmgItems">
                            <h3>Damaged Items</h3>
                            <?php
                            $query = "SELECT SUM(quantity) AS damaged FROM items WHERE conditions = 'Damaged'";
                            $result = $conn->query($query);
                            if ($result) {
                                $data = $result->fetch_assoc();
                                $damagedItems = $data['damaged'] ?? 0;
                            } else {
                                $damagedItems = 0;
                            }
                            ?>
                            <p><?php echo $damagedItems; ?></p>
                        </div>

                        <div class="box-card availItems">
                            <h3>Available Items</h3>
                            <?php
                            $query = "SELECT SUM(quantity) AS available FROM items WHERE conditions = 'Available'";
                            $result = $conn->query($query);
                            if ($result) {
                                $data = $result->fetch_assoc();
                                $availableItems = $data['available'] ?? 0;
                            } else {
                                $availableItems = 0;
                            }
                            ?>
                            <p><?php echo $availableItems; ?></p>
                        </div>

                        <div class="box-card undpairItems">
                            <h3>Under Repair Items</h3>
                            <?php
                            $query = "SELECT SUM(quantity) AS under_repair FROM items WHERE conditions = 'Under Repair'";
                            $result = $conn->query($query);
                            if ($result) {
                                $data = $result->fetch_assoc();
                                $underRepairItems = $data['under_repair'] ?? 0;
                            } else {
                                $underRepairItems = 0;
                            }
                            ?>
                            <p><?php echo $underRepairItems; ?></p>
                        </div>
                    </div>
                </section>

                <section id="inventory-list">
                    <form action="index.php" method="POST">
                        <select id="sort" name="sort" onchange="this.form.submit()">
                            <option value="id_asc" <?php echo $sort === 'id_asc' ? 'selected' : ''; ?>>Default</option>
                            <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Name (A–Z)</option>
                            <option value="name_desc" <?php echo $sort === 'name_desc' ? 'selected' : ''; ?>>Name (Z–A)</option>
                            <option value="quantity_asc" <?php echo $sort === 'quantity_asc' ? 'selected' : ''; ?>>Quantity (Low to High)</option>
                            <option value="quantity_desc" <?php echo $sort === 'quantity_desc' ? 'selected' : ''; ?>>Quantity (High to Low)</option>
                            <option value="date_asc" <?php echo $sort === 'date_asc' ? 'selected' : ''; ?>>Date Added (Oldest First)</option>
                            <option value="date_desc" <?php echo $sort === 'date_desc' ? 'selected' : ''; ?>>Date Added (Newest First)</option>
                        </select>
                    </form>

                    <table border="1">
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
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM items ORDER BY $orderBy";
                            $result = $conn->query($sql);
                            
                            if (!$result) {
                                echo "<tr><td colspan='10'><center>Error loading items.</center></td></tr>";
                                error_log("Database error: " . $conn->error);
                            } elseif ($result->num_rows === 0) {
                                echo "<tr><td colspan='10'><center>No items found.</center></td></tr>";
                            } else {
                                $i = 1;
                                while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['issue']); ?></td>
                                    <td><?php echo htmlspecialchars($row['conditions']); ?></td>
                                    <td><?php echo htmlspecialchars($row['room']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td>
                                        <button type="button" class="edit-btn" onclick="openEditModal(
                                            '<?php echo $row['id']; ?>',
                                            '<?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?>',
                                            '<?php echo $row['quantity']; ?>',
                                            '<?php echo htmlspecialchars($row['type'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($row['issue'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($row['conditions'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($row['room'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($row['description'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($row['date'], ENT_QUOTES); ?>'
                                        )">Edit</button>

                                        <form action="routes/deleteItem.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <input type="submit" class="delete-btn" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                            <?php
                                endwhile;
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </main>
        </div>
    </div>

    <script src="js/index.js"></script>
</body>
</html>