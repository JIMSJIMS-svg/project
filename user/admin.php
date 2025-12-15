<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: /project/config/login.php");
  exit();
}

include '../database/db.php';

// ======================= ADD ITEM =======================
if (isset($_POST['submit'])) {
  $name = $_POST['itemName'];
  $quantity = intval($_POST['qty']);
  $type = $_POST['type'];
  $room = $_POST['room'] ?? '';
  $issue = $_POST['issue'];
  $condition = $_POST['condition'];
  $desc = $_POST['desc'] ?? '';
  $date = $_POST['dateAdded'];

  $stmt = $conn->prepare("INSERT INTO inventory (item, quantity, type, room, issue, conditions, description, date)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sissssss", $name, $quantity, $type, $room, $issue, $condition, $desc, $date);
  $stmt->execute();
  $stmt->close();

  header("Location: admin.php");
  exit();
}

// ======================= EDIT MODE =======================
$edit_mode = false;
$edit_name = $edit_qty = $edit_condition = $edit_desc = '';

if (isset($_POST['edit'])) {
  $edit_mode = true;
  $edit_id = intval($_POST['eid']);

  $result = mysqli_query($conn, "SELECT * FROM inventory WHERE id = $edit_id LIMIT 1");
  if ($item = mysqli_fetch_assoc($result)) {
    $edit_name = $item['item'];
    $edit_qty = $item['quantity'];
    $edit_condition = $item['conditions'];
    $edit_desc = $item['description'];
  }
}

// ======================= UPDATE ITEM =======================
if (isset($_POST['update'])) {
  $id = intval($_POST['update_id']);
  $name = $_POST['itemName'];
  $quantity = intval($_POST['qty']);
  $type = $_POST['type'];
  $room = $_POST['room'];
  $issue = $_POST['issue'];
  $condition = $_POST['condition'];
  $desc = $_POST['desc'] ?? '';
  $date = $_POST['dateAddedS'];

  $stmt = $conn->prepare("UPDATE inventory SET item=?, quantity=?, type=?, room=?, issue=?, 
                            conditions=?, description=?, date=? WHERE id=?");
  $stmt->bind_param(
    "sissssssi",
    $name,
    $quantity,
    $type,
    $room,
    $issue,
    $condition,
    $desc,
    $date,
    $id
  );
  $stmt->execute();
  $stmt->close();

  header("Location: admin.php");
  exit();
}

// ======================= DELETE ITEM =======================
if (isset($_POST['delete'])) {
  $delete = intval($_POST['did']);

  $stmt = $conn->prepare("DELETE FROM inventory WHERE id=?");
  $stmt->bind_param("i", $delete);
  $stmt->execute();
  $stmt->close();

  header("Location: admin.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>School Inventory</title>
  <link rel="stylesheet" href="../theme/Admin.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <ul>
      <li><a href="">Dashboard</a></li>
      <li><button class="as" href="#" onclick="generateReport(); return false;">Generate Report</button></li>
      <li><a href='../config/logout.php'>Log Out</a></li>
    </ul>
  </div>

  <!-- CONTENT -->
  <div class="content">
    <nav class="navbar mb-4 rounded d-flex justify-content-around align-items-center">
      <h2>Sacred Heart of Jesus Catholic School</h2>
      <button type="button" class="btn btn-light text-danger fw-bold" data-bs-toggle="modal" data-bs-target="#addItemModal">
        + Add Item
      </button>
    </nav>

    <!-- INFO CARDS -->
    <div class="info-cards">
      <div class="info-card">
        <h5>Total Items</h5>
        <?php
        $q = mysqli_query($conn, "SELECT SUM(quantity) AS total FROM inventory");
        $qtyt = mysqli_fetch_assoc($q);
        ?>
        <p><?= $qtyt['total'] ?? 0 ?></p>
      </div>

      <div class="info-card">
        <h5>Available</h5>
        <?php
        $q = mysqli_query($conn, "SELECT SUM(quantity) AS total FROM inventory WHERE conditions='Available'");
        $qtyt = mysqli_fetch_assoc($q);
        ?>
        <p><?= $qtyt['total'] ?? 0 ?></p>
      </div>

      <div class="info-card">
        <h5>Borrowed</h5>
        <?php
        $q = mysqli_query($conn, "SELECT SUM(quantity) AS total FROM inventory WHERE conditions='Borrowed'");
        $qtyt = mysqli_fetch_assoc($q);
        ?>
        <p><?= $qtyt['total'] ?? 0 ?></p>
      </div>

      <div class="info-card">
        <h5>Damaged</h5>
        <?php
        $q = mysqli_query($conn, "SELECT SUM(quantity) AS total FROM inventory WHERE conditions='Damaged'");
        $qtyt = mysqli_fetch_assoc($q);
        ?>
        <p><?= $qtyt['total'] ?? 0 ?></p>
      </div>
    </div>

    <!-- INVENTORY TABLE -->
    <div class="table-container">
      <h4 class="mb-3">Inventory List</h4>
      <div class="table-responsive">

        <table class="table align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Item Name</th>
              <th>Quantity</th>
              <th>Room</th>
              <th>Type</th>
              <th>Condition</th>
              <th>Issue</th>
              <th>Description</th>
              <th>Date Added</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            <?php
            $query = mysqli_query($conn, "SELECT * FROM inventory ORDER BY id DESC");
            if (mysqli_num_rows($query) > 0):
              $i = 1;
              while ($row = mysqli_fetch_assoc($query)):
            ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($row['item']) ?></td>
                  <td><?= htmlspecialchars($row['quantity']) ?></td>
                  <td><?= htmlspecialchars($row['room']) ?></td>
                  <td><?= htmlspecialchars($row['type']) ?></td>
                  <td><?= htmlspecialchars($row['conditions']) ?></td>
                  <td><?= htmlspecialchars($row['issue']) ?></td>
                  <td><?= htmlspecialchars($row['description']) ?></td>
                  <td><?= htmlspecialchars($row['date']) ?></td>

                  <td>
                    <form action="" method="POST" style="display:inline-block;">
                      <input type="hidden" name="did" value="<?= $row['id'] ?>">
                      <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                    </form>

                    <form action="" method="POST" style="display:inline-block;">
                      <input type="hidden" name="eid" value="<?= $row['id'] ?>">
                      <button type="submit" name="edit" class="btn btn-sm btn-warning">Edit</button>
                    </form>
                  </td>
                </tr>
              <?php endwhile;
            else: ?>
              <tr>
                <td colspan="10" class="text-center">No Records Found</td>
              </tr>
            <?php endif; ?>
          </tbody>

        </table>

      </div>
    </div>
  </div>

  <!-- ADD ITEM MODAL -->
  <div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="admin.php" method="POST">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">Add New Item</h5>
            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body row">
            <div class="col">
              <label class="form-label">Item Name:</label>
              <input type="text" name="itemName" class="form-control">

              <label class="form-label mt-3">Quantity:</label>
              <input type="number" name="qty" class="form-control">

              <label class="form-label mt-3">Room:</label>
              <input type="text" name="room" class="form-control">

              <label class="form-label mt-3">Issue:</label>
              <select class="form-select" name="issue">
                <option>Excellent</option>
                <option>Good</option>
                <option>Fair</option>
                <option>Poor</option>
              </select>
            </div>

            <div class="col">
              <label class="form-label">Condition:</label>
              <select class="form-select" name="condition">
                <option>Available</option>
                <option>Borrowed</option>
                <option>Damaged</option>
              </select>

              <label class="form-label mt-3">Type:</label>
              <select class="form-select" name="type">
                <option value="">Select</option>
                <option value="Equipment">Equipment</option>
                <option value="Furniture">Furniture</option>
                <option value="Electronics">Electronics</option>
              </select>

              <label class="form-label mt-3">Description:</label>
              <input type="text" name="desc" class="form-control">

              <label class="form-label mt-3">Date Added:</label>
              <input type="date" name="dateAdded" class="form-control">
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="submit" class="btn btn-danger">Save Item</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- REPORT MODAL -->
  <div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Inventory Report</h5>
          <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-outline-success btn-sm me-2" onclick="downloadCSV()">Download CSV</button>
            <button class="btn btn-outline-danger btn-sm" onclick="downloadPDF()">Download PDF</button>
          </div>

          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="reportTable">
              <thead class="table-success">
                <tr>
                  <th>ID</th>
                  <th>Item</th>
                  <th>Quantity</th>
                  <th>Room</th>
                  <th>Type</th>
                  <th>Condition</th>
                  <th>Issue</th>
                  <th>Description</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = mysqli_query($conn, "SELECT * FROM inventory ASC");
                if (mysqli_num_rows($query) > 0):
                  $i = 0;
                  while ($row = mysqli_fetch_assoc($query)):
                ?>
                    <tr>
                      <td><?= $i++ ?></td>
                      <td><?= htmlspecialchars($row['item']) ?></td>
                      <td><?= htmlspecialchars($row['quantity']) ?></td>
                      <td><?= htmlspecialchars($row['type']) ?></td>
                      <td><?= htmlspecialchars($row['room']) ?></td>
                      <td><?= htmlspecialchars($row['issue']) ?></td>
                      <td><?= htmlspecialchars($row['conditions']) ?></td>
                      <td><?= htmlspecialchars($row['description']) ?></td>
                      <td><?= htmlspecialchars($row['date']) ?></td>
                    </tr>
                <?php
                  endwhile;
                endif;
                ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

  <script>
    async function generateReport() {
      const response = await fetch("config/get_report.php");
      const data = await response.json();

      const tbody = document.querySelector("#reportTable tbody");
      tbody.innerHTML = "";

      if (data.length === 0) {
        tbody.innerHTML = "<tr><td colspan='9' class='text-center'>No Data Found</td></tr>";
      } else {
        data.forEach(item => {
          tbody.innerHTML += `
                <tr>
                  <td>${item.id}</td>
                  <td>${item.item}</td>
                  <td>${item.quantity}</td>
                  <td>${item.room}</td>
                  <td>${item.type}</td>
                  <td>${item.conditions}</td>
                  <td>${item.issue}</td>
                  <td>${item.description}</td>
                  <td>${item.date}</td>
                </tr>
            `;
        });
      }

      new bootstrap.Modal(document.getElementById("reportModal")).show();
    }

    function downloadCSV() {
      const rows = document.querySelectorAll("#reportTable tr");
      const csv = [...rows].map(row => [...row.querySelectorAll("th,td")].map(col => `"${col.innerText}"`).join(","));

      const blob = new Blob([csv.join("\n")], {
        type: "text/csv"
      });
      const a = document.createElement("a");
      a.href = URL.createObjectURL(blob);
      a.download = "inventory_report.csv";
      a.click();
    }

    async function downloadPDF() {
      const {
        jsPDF
      } = window.jspdf;
      const doc = new jsPDF();

      doc.text("Inventory Report", 14, 16);

      const rows = [];
      document.querySelectorAll("#reportTable tbody tr").forEach(tr => {
        rows.push([...tr.children].map(td => td.innerText));
      });

      doc.autoTable({
        head: [
          ["ID", "Item", "Qty", "Room", "Type", "Condition", "Issue", "Description", "Date"]
        ],
        body: rows,
        startY: 20
      });

      doc.save("inventory_report.pdf");
    }
  </script>

</body>

</html>