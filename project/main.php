<?php
session_start();
require 'db/db.php';

$user = $_SESSION['user'] ?? null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <img src="asset/bg.png">
    <header>
        <ul>
            <?php if ($user === null): ?>
                <li><a href="config/login.php">Log In</a></li>
            <?php else: ?>
                <li>Welcome, <?= htmlspecialchars($user['username']) ?></li>
                <li><a href="config/logout.php">Log Out</a></li>
            <?php endif; ?>
        </ul>
    </header>
</body>


</html>