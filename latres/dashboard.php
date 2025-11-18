<?php
session_start();
require 'koneksi.php';

// 1. Cek status login (True/False)
$is_login = isset($_SESSION['login']);

if ($is_login) {
    $role = $_SESSION['Role'];
    $nama_user = $_SESSION['FullName'];
    $user_id = $_SESSION['UserID'];
} else {
    $role = 'guest';      // Role default untuk tamu
    $nama_user = 'Tamu';  // Nama default
    $user_id = null;
}

// Ambil data jadwal (Semua orang bisa melihat jadwal)
$jadwal_query = mysqli_query($conn, "SELECT * FROM jadwal");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard - Airline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Airline Booking</a>
            <div class="d-flex text-white align-items-center">
                <?php if ($is_login) : ?>
                    <span class="me-3">Halo, <b><?= $nama_user; ?></b> (<?= strtoupper($role); ?>)</span>
                    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                <?php else : ?>
                    <span class="me-3">Halo, <b>Tamu</b></span>
                    <a href="index.php" class="btn btn-light btn-sm text-primary fw-bold">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        
        <?php if ($is_login && $role == 'admin') : ?>
            <div class="card shadow mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="m-0 text-primary">Manajemen Jadwal Penerbangan</h5>
                    <a href="tambah_jadwal.php" class="btn btn-success btn-sm">+ Tambah Jadwal</a>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Kode (ID)</th>
                                <th>Pesawat</th>
                                <th>Rute</th>
                                <th>Berangkat - Sampai</th>
                                <th>Kursi (Terisi)</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($jadwal_query)) : ?>
                            <tr>
                                <td><?= $row['IDJadwal']; ?></td>
                                <td><?= $row['NamaPesawat']; ?></td>
                                <td><?= $row['KotaAwal']; ?> &rarr; <?= $row['KotaAkhir']; ?></td>
                                <td>
                                    <?= date('d/m H:i', strtotime($row['WaktuBerangkat'])); ?><br>
                                    s/d <?= date('H:i', strtotime($row['WaktuSampai'])); ?>
                                </td>
                                <td class="text-center"><?= $row['JumlahKursi']; ?> (<?= $row['KursiTerisi']; ?>)</td>
                                <td>Rp <?= number_format($row['Harga']); ?></td>
                                <td class="text-center">
                                    <a href="edit_jadwal.php?id=<?= $row['IDJadwal']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="hapus_jadwal.php?id=<?= $row['IDJadwal']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus jadwal ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header bg-white"><h5>Daftar Semua Pemesanan</h5></div>
                <div class="card-body">
                    <table class="table table-sm table-striped">
                        <thead><tr><th>ID Tiket</th><th>Pemesan</th><th>Pesawat</th><th>Penumpang</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php
                            $all_tiket = mysqli_query($conn, "
                                SELECT t.*, j.NamaPesawat, u.FullName 
                                FROM tiket t 
                                JOIN jadwal j ON t.IDJadwal = j.IDJadwal 
                                JOIN users u ON t.UserID = u.UserID
                            ");
                            while($bk = mysqli_fetch_assoc($all_tiket)): ?>
                            <tr>
                                <td>#<?= $bk['IDTiket']; ?></td>
                                <td><?= $bk['FullName']; ?></td>
                                <td><?= $bk['NamaPesawat']; ?> (<?= $bk['IDJadwal']; ?>)</td>
                                <td><?= $bk['NamaPenumpang']; ?></td>
                                <td><span class="badge bg-success"><?= $bk['Status']; ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php else : ?>
            <h4 class="mb-3">Pilih Penerbangan</h4>
            
            <?php if(!$is_login): ?>
                <div class="alert alert-info">Silakan <b>Login</b> untuk memesan tiket pesawat.</div>
            <?php endif; ?>

            <div class="row">
                <?php 
                if(mysqli_num_rows($jadwal_query) > 0) {
                    mysqli_data_seek($jadwal_query, 0); 
                }
                
                while ($row = mysqli_fetch_assoc($jadwal_query)) : 
                    $sisa_kursi = $row['JumlahKursi'] - $row['KursiTerisi'];
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title text-primary"><?= $row['NamaPesawat']; ?></h5>
                                <small class="text-muted"><?= $row['IDJadwal']; ?></small>
                            </div>
                            <p class="card-text mt-2">
                                <strong><?= $row['KotaAwal']; ?></strong> <i class="bi bi-arrow-right"></i> <strong><?= $row['KotaAkhir']; ?></strong><br>
                                <span class="text-muted">Berangkat: <?= date('d M Y, H:i', strtotime($row['WaktuBerangkat'])); ?></span><br>
                                <span class="text-muted">Sampai: <?= date('d M Y, H:i', strtotime($row['WaktuSampai'])); ?></span>
                            </p>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-success">Rp <?= number_format($row['Harga']); ?></h5>
                                <small>Sisa: <?= $sisa_kursi; ?> Kursi</small>
                            </div>
                            

                            <?php 
                                if ($sisa_kursi <= 0) {
                                    echo '<button class="btn btn-secondary w-100 mt-3" disabled>Habis</button>';
                                } elseif ($is_login) {
                                    echo '<a h  ref="pesan_tiket.php?id='.$row['IDJadwal'].'" class="btn btn-primary w-100 mt-3">Pesan Sekarang</a>';
                                } else {
                                    // Jika Belum Login dan kursi lebih dari 0
                                    echo '<a href="index.php" class="btn btn-primary w-100 mt-3" onclick="return confirm(\'Anda harus login untuk memesan tiket. Lanjut ke Login?\')">Pesan Sekarang</a>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <?php if ($is_login) : ?>
            <div class="card mt-4 shadow mb-5">
                <div class="card-header bg-white"><h5>Tiket Saya</h5></div>
                <div class="card-body">
                    <table class="table">
                        <thead><tr><th>ID Jadwal</th><th>Maskapai</th><th>Rute</th><th>Penumpang</th><th>Tgl Pesan</th></tr></thead>
                        <tbody>
                            <?php
                            $my_tiket = mysqli_query($conn, "
                                SELECT t.*, j.NamaPesawat, j.KotaAwal, j.KotaAkhir 
                                FROM tiket t 
                                JOIN jadwal j ON t.IDJadwal = j.IDJadwal 
                                WHERE t.UserID = '$user_id'
                            ");
                            
                            if(mysqli_num_rows($my_tiket) == 0) echo "<tr><td colspan='5' class='text-center'>Belum ada tiket dipesan.</td></tr>";
                            
                            while($mt = mysqli_fetch_assoc($my_tiket)): ?>
                            <tr>
                                <td><?= $mt['IDJadwal']; ?></td>
                                <td><?= $mt['NamaPesawat']; ?></td>
                                <td><?= $mt['KotaAwal']; ?> - <?= $mt['KotaAkhir']; ?></td>
                                <td><?= $mt['NamaPenumpang']; ?></td>
                                <td><?= $mt['TanggalPesan']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif;  

        endif; ?>
    </div>
</body>
</html>