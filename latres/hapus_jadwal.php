<?php
session_start();
require 'koneksi.php';
// if ($_SESSION['Role'] != 'admin') exit;

if (!isset($_SESSION['login']) || $_SESSION['Role'] != 'admin') {
echo "<script>
            alert('anda bukan admin!');
            window.location='dashboard.php';
          </script>";    
    exit;
}


$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM jadwal WHERE IDJadwal = '$id'");
header("Location: dashboard.php");
?>