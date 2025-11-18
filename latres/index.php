<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek user berdasarkan Username
    $result = mysqli_query($conn, "SELECT * FROM users WHERE Username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        // Verifikasi password hash
        if (password_verify($password, $row['Password'])) {
            $_SESSION['login'] = true;
            $_SESSION['UserID'] = $row['UserID']; // Sesuai kolom DB
            $_SESSION['FullName'] = $row['FullName']; // Sesuai kolom DB
            $_SESSION['Role'] = $row['Role']; // Sesuai kolom DB
            header("Location: dashboard.php");
            exit;
        }
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - Airline System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { width: 400px; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="card p-4 shadow">
        <h3 class="text-center mb-4">LOGIN</h3>
        <?php if(isset($error)) : ?>
            <div class="alert alert-danger">Username atau Password salah!</div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3 text-center">Belum punya akun? <a href="register.php">Daftar</a></p>
        <div class="text-center mt-3">
            <a href="dashboard.php" class="text-decoration-none text-secondary">
                Masuk sebagai Tamu (Guest)
            </a>
        </div>
    </div>
</body>
</html>