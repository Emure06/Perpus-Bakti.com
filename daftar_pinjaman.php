<?php
include 'config.php';

// Inisialisasi variabel
$hasil = null;
$error = "";
$nama = "";
$program = ""; // Gunakan $program alih-alih $kelas

// Daftar program studi yang valid (sesuai dengan data siswa dan pilihan di form kelas)
$daftar_program_valid = [
    'X Kesehatan', 'XI Farmasi', 'XI Asisten Keperawatan',
    'XII Farmasi', 'XII Asisten Keperawatan'
];

if ($_POST) {
    $nama = trim($_POST['nama']);
    $program = trim($_POST['program']); // Terima program dari form

    // Validasi input
    if (empty($nama)) {
        $error = "Nama siswa harus diisi.";
    } elseif (empty($program) || !in_array($program, $daftar_program_valid)) {
        $error = "Program studi tidak valid.";
    } else {
        try {
            // Query ambil data peminjaman berdasarkan nama dan program
            // Asumsi kolom `kelas` di tabel `peminjaman` menyimpan nama program
            $stmt = $pdo->prepare("
                SELECT p.*, b.judul, b.penulis
                FROM peminjaman p
                JOIN buku b ON p.id_buku = b.id
                WHERE p.nama_siswa = ?
                AND p.kelas = ? -- Kolom kelas menyimpan nama program
                AND p.status = 'dipinjam'
                ORDER BY p.tanggal_pinjam DESC
            ");
            $stmt->execute([$nama, $program]);
            $hasil = $stmt->fetchAll();

            // Jika tidak ada hasil
            if (empty($hasil)) {
                $error = "Tidak ada buku yang sedang dipinjam oleh <strong>" . htmlspecialchars($nama) . " (" . htmlspecialchars($program) . ")</strong>.";
            }
        } catch (Exception $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lihat Buku Dipinjam - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            border: none;
        }
        .form-card { background: white; }
        .result-card { background: white; border-left: 5px solid #0d6efd; }
        .btn-cari { background: #0d6efd; color: white; }
        .btn-cari:hover { background: #0b5ed7; }
        .table th { background: #0d6efd; color: white; }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5"> <!-- Tambahkan mb-5 untuk jarak bawah -->
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="text-primary">
                <i class="fas fa-book-reader"></i> Lihat Buku yang Sedang Dipinjam
            </h1>
            <p class="lead text-muted">
                Masukkan nama dan program studi untuk melihat daftar buku yang sedang Anda pinjam
            </p>
        </div>

        <!-- Form Pencarian -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card form-card">
                    <div class="card-header bg-primary text-white text-center">
                        <h5><i class="fas fa-search"></i> Cari Buku Dipinjam</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($error && !$hasil): ?>
                            <div class="alert alert-warning"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label"><strong>Nama Siswa</strong></label>
                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    placeholder="Masukkan nama lengkap"
                                    value="<?= htmlspecialchars($nama) ?>"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><strong>Program Studi</strong></label>
                                <select name="program" class="form-control" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    <?php foreach ($daftar_program_valid as $prog): ?>
                                        <option value="<?= $prog ?>" <?= $program == $prog ? 'selected' : '' ?>>
                                            <?= $prog ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-cari w-100">
                                <i class="fas fa-search"></i> Cari Buku Saya
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil Pencarian -->
        <?php if ($hasil !== null && !empty($hasil)): ?>
            <div class="card result-card mt-5">
                <div class="card-header bg-success text-white">
                    <h5>
                        <i class="fas fa-list"></i> Hasil Pencarian
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        Ditemukan <strong><?= count($hasil) ?></strong> buku yang sedang dipinjam oleh:
                        <br><strong><?= htmlspecialchars($nama) ?> (<?= htmlspecialchars($program) ?>)</strong>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Judul Buku</th>
                                    <th>Penulis</th>
                                    <th class="text-center">Tanggal Pinjam</th>
                                    <th class="text-center">Harus Kembali</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hasil as $index => $row): ?>
                                <?php
                                // Hitung status keterlambatan
                                $harus_kembali = new DateTime($row['tanggal_harus_kembali']);
                                $sekarang = new DateTime();
                                $lewat = $sekarang > $harus_kembali;
                                ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($row['judul']) ?></td>
                                    <td><?= htmlspecialchars($row['penulis'] ?? '-') ?></td>
                                    <td class="text-center"><?= $row['tanggal_pinjam'] ?></td>
                                    <td class="text-center">
                                        <span class="<?= $lewat ? 'text-danger fw-bold' : '' ?>">
                                            <?= $row['tanggal_harus_kembali'] ?>
                                            <?php if ($lewat): ?>
                                                <br><small>(Lewat <?= $harus_kembali->diff($sekarang)->days ?> hari)</small>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif ($hasil !== null && empty($hasil)): ?>
            <div class="alert alert-warning text-center mt-4">
                <i class="fas fa-info-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- Tombol Kembali -->
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
    <!-- Optional: Tambahkan padding bottom jika footer fixed -->
    <div style="height: 60px;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>