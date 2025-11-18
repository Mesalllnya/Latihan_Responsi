<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['login'])) {
    echo "<script>
            alert('Anda harus login untuk memesan tiket!');
            window.location='index.php';
          </script>";
    exit;
}
$id_jadwal = $_GET['id'];

// Ambil detail jadwal untuk ditampilkan
$q = mysqli_query($conn, "SELECT * FROM jadwal WHERE IDJadwal = '$id_jadwal'");
$data = mysqli_fetch_assoc($q);

if (isset($_POST['pesan'])) {
    $user_id = $_SESSION['UserID'];
    $penumpang = $_POST['nama_penumpang'];
    $identitas = $_POST['identitas'];

    $insert = "INSERT INTO tiket (UserID, IDJadwal, NamaPenumpang, NomorIdentitas) 
               VALUES ('$user_id', '$id_jadwal', '$penumpang', '$identitas')";
    
    if (mysqli_query($conn, $insert)) {
        // Update KursiTerisi di tabel jadwal
        mysqli_query($conn, "UPDATE jadwal SET KursiTerisi = KursiTerisi + 1 WHERE IDJadwal = '$id_jadwal'");
        
        echo "<script>alert('Tiket Berhasil Dipesan!'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Konfirmasi Pesanan</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container mt-5 bg-light">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white"><h4>Konfirmasi Booking</h4></div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong><?= $data['NamaPesawat']; ?> (<?= $data['IDJadwal']; ?>)</strong><br>
                        Rute: <?= $data['KotaAwal']; ?> ke <?= $data['KotaAkhir']; ?><br>
                        Harga: Rp <?= number_format($data['Harga']); ?>
                    </div>

                    <form method="post">
                        <div class="mb-3">
                            <label>Nama Penumpang</label>
                            <input type="text" name="nama_penumpang" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nomor KTP/Identitas</label>
                            <input type="text" name="identitas" class="form-control" required>
                        </div>
                        <button type="submit" name="pesan" class="btn btn-success w-100">Bayar & Pesan</button>
                        <a href="dashboard.php" class="btn btn-link d-block text-center mt-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>