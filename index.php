<?php
include 'database/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHJCS Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="theme/index.css">
</head>
<body>
    <header>
        <a id="open" onclick="openNav()"><i class="fa-solid fa-bars"></i></a>
        <h1>Sacred Heart of Jesus Catholic School</h1>
        <div>
            <ul>
                <li><a href="config/login.php">Log In</a></li>
            </ul>
        </div>
    </header>

    <aside id="nav" style="width: 0; display: none;">
        <div>
            <h4>Inventory Report <span href="javascript:void(0)" class="btn" onclick="closeNav()">&times;</span></h4>
        </did>
    </aside>

    <main>
        
    </main>

    <script type="text/javascript">
        const div = document.getElementById("nav");
        const main = document.querySelector("main");
        function openNav() {
            div.style.width = "20%";
            div.style.display = "block"
            main.style.width = "80%";
        }

        function closeNav() {
            div.style.width = "0";
            div.style.display = "none";
            main.style.width = "100%";
        }
    </script>
</body>
</html>