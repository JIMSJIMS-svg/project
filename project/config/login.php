<?php
session_start();
require '../db/db.php';



$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login($identifier, $password);
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = trim($_POST['password'] ?? '');

    if ($identifier === '' || $password === '') {
        $errors[] = "All fields are required.";
    } else {
        $stmt = $pdo->prepare(
            "SELECT id, name, email, password 
            FROM users 
            WHERE name = :identifier OR email = :identifier 
            LIMIT 1"
        );
        $stmt->execute(['identifier' => $identifier]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['email']    = $user['email'];

            header("Location: ../index.php");
            exit;
        } else {
            $errors[] = "Invalid username/email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Log In</title>
    <link rel="stylesheet" href="">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <?php foreach ($errors as $e): ?>
                <p class="error"><?php echo $e; ?></p>
            <?php endforeach; ?>

            <form method="post" action="login.php">
                <input type="text" name="identifier" placeholder="Username or Email">
                <input type="password" name="password" placeholder="Enter password">
                <br>
                <button type="submit">Login</button>
            </form>
            <p>Not registered? <a href="register.php">Sign up here</a></p>
        </div>
    </div>
</body>

</html>