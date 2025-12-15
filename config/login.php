<?php
session_start();
include 'C:\xampp\htdocs\project\database\db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST['identifier']); // username or email
    $password   = trim($_POST['password']);

    if (empty($identifier) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password FROM admins WHERE name=? OR email=? LIMIT 1");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $username, $email, $hashedPassword);
        $stmt->fetch();

        if ($stmt->num_rows > 0 && password_verify($password, $hashedPassword)) {
            $_SESSION['user_id']  = $id;
            $_SESSION['username'] = $username;
            $_SESSION['email']    = $email;
            header("Location: /project/user/admin.php");
            exit();
        } else {
            $errors[] = "Invalid username/email or password.";
        }
        $stmt->close();
    }
}

if (!isset($_SESSION)) {
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log In</title>
    <link rel="stylesheet" href="../theme/login.css">
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
