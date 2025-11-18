<?php
require 'koneksi.php';

if (isset($_POST['register'])) {
    // Ambil data dari form
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];         
    $confirm  = $_POST['confirm_password']; 
    
    if ($password !== $confirm) {
        echo "<script>alert('Registrasi Gagal: Password dan Konfirmasi Password tidak sama!');</script>";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (FullName, Username, Role, Email, Password) 
                  VALUES ('$fullname', '$username', 'user', '$email', '$password_hash')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registrasi Berhasil! Silakan Login'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal: ".mysqli_error($conn)."');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { width: 400px; }
    </style>
</head>
<body>
    <div class="card p-4 shadow">
        <h3 class="text-center mb-4">REGISTER</h3>
        <form action="" method="post">
            <div class="mb-3">
                <label>Nama Lengkap (FullName)</label>
                <input type="text" name="fullname" class="form-control" required value="<?= isset($_POST['fullname']) ? $_POST['fullname'] : '' ?>">
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>">
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            
            <button type="submit" name="register" class="btn btn-primary w-100">Daftar</button>
        </form>
        <p class="mt-3 text-center">Sudah punya akun? <a href="index.php">Login</a></p>
        <div class="text-center mt-3">
            <a href="dashboard.php" class="text-decoration-none text-secondary">Masuk sebagai Tamu (Guest)</a>
        </div>
    </div>
</body>
</html>