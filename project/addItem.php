<?php
require 'db/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $type = $_POST['type'];
    $issue = $_POST['issue'];
    $conditions = $_POST['conditions'];
    $room = $_POST['room'];
    $description = $_POST['description'];
    $date = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("
    INSERT INTO items
    (name, quantity, type, issue, conditions, room, description, date)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  ");

    $stmt->bind_param(
        "sissssss",
        $name,
        $quantity,
        $type,
        $issue,
        $conditions,
        $room,
        $description,
        $date
    );

    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit();
}
