<?php
$host = 'localhost';
$root = 'root';
$pass = '';
$db = 'testdb';

$conn = mysqli_connect($host, $root, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
