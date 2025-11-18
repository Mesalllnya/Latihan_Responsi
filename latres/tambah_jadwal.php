<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['Role'] != 'admin') {
echo "<script>
            alert('anda bukan admin!');
            window.location='dashboard.php';
          </script>";    
    exit;
}

if (isset($_POST['submit'])) {
    $idjadwal = $_POST['IDJadwal'];
    $pesawat  = $_POST['NamaPesawat'];
    $awal     = $_POST['KotaAwal'];
    $akhir    = $_POST['KotaAkhir'];
    $berangkat= $_POST['WaktuBerangkat'];
    $sampai   = $_POST['WaktuSampai'];
    $jml_kursi= $_POST['JumlahKursi'];
    $harga    = $_POST['Harga'];

    // Insert data, KursiTerisi default 0, Status default tersedia
    $query = "INSERT INTO jadwal (IDJadwal, NamaPesawat, KotaAwal, KotaAkhir, WaktuBerangkat, WaktuSampai, JumlahKursi, KursiTerisi, Harga, Status)
              VALUES ('$idjadwal', '$pesawat', '$awal', '$akhir', '$berangkat', '$sampai', '$jml_kursi', 0, '$harga', 'tersedia')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Jadwal Berhasil Ditambah'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Tambah Jadwal</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container mt-5">
    <div class="card shadow">
        <div class="card-header"><h3>Tambah Jadwal Penerbangan</h3></div>
        <div class="card-body">
            <form method="post">
                <div class="row mb-3">
                    <div class="col"><label>ID Jadwal (Misal: BA202)</label><input type="text" name="IDJadwal" class="form-control" required></div>
                    <div class="col"><label>Nama Pesawat</label><input type="text" name="NamaPesawat" class="form-control" required></div>
                </div>
                <div class="row mb-3">
                    <div class="col"><label>Kota Awal</label><input type="text" name="KotaAwal" class="form-control" required></div>
                    <div class="col"><label>Kota Akhir</label><input type="text" name="KotaAkhir" class="form-control" required></div>
                </div>
                <div class="row mb-3">
                    <div class="col"><label>Waktu Berangkat</label><input type="datetime-local" name="WaktuBerangkat" class="form-control" required></div>
                    <div class="col"><label>Waktu Sampai</label><input type="datetime-local" name="WaktuSampai" class="form-control" required></div>
                </div>
                <div class="row mb-3">
                    <div class="col"><label>Jumlah Kursi</label><input type="number" name="JumlahKursi" class="form-control" required></div>
                    <div class="col"><label>Harga</label><input type="number" name="Harga" class="form-control" required></div>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <a href="dashboard.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>