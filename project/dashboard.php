<?php
require 'db/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link rel="stylesheet" href="styles/dash.css">
</head>

<body>
    <header>
        <div class="head">
            <h1>SHJCS Inventory</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="index.php">Rooms</a></li>
                    <li><a href="reports.php">Reports</a></li>
                    <li><a href="config/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div id="body">
            <section id="essentials">
                <div class="calendar">
                    
                </div>
                <div class="line-graph">
    
                </div>
            </section>
            
            <section id="hero">
                <div class="history">

                </div>
            </section>

            <section id="data">
                <div class="total">

                </div>
                <div class="available">

                </div>
                <div class="damaged">

                </div>
            </section>
        </div>
    </main>
</body>

</html>