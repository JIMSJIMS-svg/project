<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

include '../database/db.php';
header("Content-Type: application/json");

$query = "SELECT id, item, quantity, room, type, issue, conditions, description, dateAdded FROM inventory ORDER BY id DESC";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
