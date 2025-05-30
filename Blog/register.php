<?php
include 'koneksi.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);
    $email = trim($_POST['email']);
    $namalengkap = trim($_POST['namalengkap']);
    $level = trim($_POST['level']);
    if ($username && $password && $password === $confirm && $email && $namalengkap && $level) {
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);
        $namalengkap = mysqli_real_escape_string($conn, $namalengkap);
        $level = mysqli_real_escape_string($conn, $level);
        $cek = mysqli_query($conn, "SELECT * FROM register WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($cek) > 0) {
            $message = 'Username atau email sudah terdaftar!';
        } else {
            $q = "INSERT INTO register (username, password, email, namalengkap, level) VALUES ('$username', '$password', '$email', '$namalengkap', '$level')";
            if (mysqli_query($conn, $q)) {
                $message = 'Registrasi berhasil! Silakan login.';
            } else {
                $message = 'Registrasi gagal.';
            }
        }
    } else {
        $message = 'Data tidak lengkap atau password tidak cocok!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Gen Z Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>Registrasi Akun</h2>
        <?php if($message) echo '<div class="msg">'.htmlspecialchars($message).'</div>'; ?>
        <form method="post" class="register-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="namalengkap" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm" placeholder="Konfirmasi Password" required>
            <select name="level" required>
                <option value="">Pilih Level</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
