<?php
session_start();
include 'C:\xampp\htdocs\project\database\db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';


    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM admins WHERE name=? OR email=? LIMIT 1");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Username or Email already exists.";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $passwordHash);

            if ($stmt->execute()) {
                header("Location: /project/user/admin.php");
                exit();
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="../theme/register.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Register</h2>
            <?php foreach ($errors as $e): ?>
                <p class="error"><?php echo $e; ?></p>
            <?php endforeach; ?>

            <form method="post" action="register.php">
                <input type="text" name="username" placeholder="Enter username">
                <input type="email" name="email" placeholder="Enter email">
                <input type="password" name="password" placeholder="Enter password"><br>
                <button type="submit">Sign Up</button>
            </form>
            <p>Already registered? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>