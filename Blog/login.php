<?php
session_start();
include 'koneksi.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if ($username && $password) {
        $username = mysqli_real_escape_string($conn, $username);
        $q = mysqli_query($conn, "SELECT * FROM register WHERE username='$username' AND password='$password' LIMIT 1");
        $user = mysqli_fetch_assoc($q);
        if ($user) {
            $_SESSION['user_id'] = $user['username'];
            $_SESSION['namalengkap'] = $user['namalengkap'];
            $_SESSION['level'] = $user['level'];
            header('Location: index.php');
            exit;
        } else {
            $message = 'Username atau password salah!';
        }
    } else {
        $message = 'Username dan password wajib diisi!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gen Z Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>Login Akun</h2>
        <?php if($message) echo '<div class="msg">'.htmlspecialchars($message).'</div>'; ?>
        <form method="post" class="register-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
