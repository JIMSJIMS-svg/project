<?php
require 'db/db.php';

$id = $_POST['id'];

$stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$conn->query("ALTER TABLE items AUTO_INCREMENT = 1");
$stmt->close();
$conn->close();

header("Location: index.php");