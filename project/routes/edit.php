<?php
require 'db/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $qty = $_POST['quantity'];
    $type = $_POST['type'];
    $issue = $_POST['issue'];
    $conditions = $_POST['conditions'];
    $room = $_POST['room'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    $stmt = $conn->prepare(
        "UPDATE items SET name = ?, quantity = ?, type = ?, issue = ?, conditions = ?, room = ?, description = ?, date = ? WHERE id = ?"
    );

    // Type string:  s = string, i = integer
    $stmt->bind_param(
        "sissssssi", // 9 letters for 9 variables
        $name,       // s
        $qty,        // i
        $type,       // s
        $issue,      // s
        $conditions, // s
        $room,       // s
        $description,// s
        $date,       // s (date as string)
        $id          // i
    );

    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit();
}