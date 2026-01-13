<?php
$conn = new mysqli("localhost", "root", "", "testdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all items
$query = "SELECT * FROM items ORDER BY id ASC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Set headers to force download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="inventoryReport.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    // CSV Column headers
    fputcsv($output, ['ID', 'Name', 'Quantity', 'Type', 'Issue', 'Condition', 'Room', 'Description', 'Date']);

    // Output rows
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['quantity'],
            $row['type'],
            $row['issue'],
            $row['conditions'],
            $row['room'],
            $row['description'],
            $row['date']
        ]);
    }

    fclose($output);
    exit();
} else {
    echo "No items found to export.";
}