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

$id_jadwal = $_GET['id'];

$query = mysqli_query($conn, "SELECT * FROM jadwal WHERE IDJadwal = '$id_jadwal'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data jadwal tidak ditemukan!'); window.location='dashboard.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $pesawat    = $_POST['NamaPesawat'];
    $awal       = $_POST['KotaAwal'];
    $akhir      = $_POST['KotaAkhir'];
    $berangkat  = $_POST['WaktuBerangkat'];
    $sampai     = $_POST['WaktuSampai'];
    $jml_kursi  = $_POST['JumlahKursi'];
    $harga      = $_POST['Harga'];
    $status     = $_POST['Status']; 

    $query_update = "UPDATE jadwal SET 
        NamaPesawat = '$pesawat',
        KotaAwal = '$awal',
        KotaAkhir = '$akhir',
        WaktuBerangkat = '$berangkat',
        WaktuSampai = '$sampai',
        JumlahKursi = '$jml_kursi',
        Harga = '$harga',
        Status = '$status'
        WHERE IDJadwal = '$id_jadwal'";

    if (mysqli_query($conn, $query_update)) {
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location = 'dashboard.php';
              </script>";
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Jadwal Penerbangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Edit Jadwal Penerbangan</h4>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            
                            <div class="mb-3">
                                <label class="form-label">ID Jadwal</label>
                                <input type="text" class="form-control bg-light" value="<?= $data['IDJadwal']; ?>" readonly>
                                <small class="text-muted">ID Jadwal tidak dapat diubah.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Maskapai / Pesawat</label>
                                <input type="text" name="NamaPesawat" class="form-control" value="<?= $data['NamaPesawat']; ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kota Keberangkatan</label>
                                    <input type="text" name="KotaAwal" class="form-control" value="<?= $data['KotaAwal']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kota Tujuan</label>
                                    <input type="text" name="KotaAkhir" class="form-control" value="<?= $data['KotaAkhir']; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Waktu Berangkat</label>
                                    <input type="datetime-local" name="WaktuBerangkat" class="form-control" 
                                           value="<?= date('Y-m-d\TH:i', strtotime($data['WaktuBerangkat'])); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Waktu Sampai</label>
                                    <input type="datetime-local" name="WaktuSampai" class="form-control" 
                                           value="<?= date('Y-m-d\TH:i', strtotime($data['WaktuSampai'])); ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Total Kursi</label>
                                    <input type="number" name="JumlahKursi" class="form-control" value="<?= $data['JumlahKursi']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Harga Tiket (Rp)</label>
                                    <input type="number" name="Harga" class="form-control" value="<?= $data['Harga']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status Penerbangan</label>
                                    <select name="Status" class="form-select">
                                        <option value="tersedia" <?= ($data['Status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                                        <option value="delayed" <?= ($data['Status'] == 'delayed') ? 'selected' : ''; ?>>Delayed</option>
                                        <option value="cancelled" <?= ($data['Status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>