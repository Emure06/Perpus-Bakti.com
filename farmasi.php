<?php
include 'config.php';

// Tentukan kategori
$kategori = "farmasi";
$judul_halaman = "Buku Program Keahlian Farmasi";
$deskripsi_halaman = "Koleksi buku spesifik untuk program studi Farmasi";
$ikon = "fas fa-mortar-pestle";
$warna_utama = "#fa709a"; // Pink
$warna_latar = "#fff3e0"; // Latar belakang oranye muda

// Query untuk mengambil buku berdasarkan kategori
$stmt = $pdo->prepare("SELECT * FROM buku WHERE kelas_tujuan = ?");
$stmt->execute([$kategori]);
$buku_list = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $judul_halaman; ?> - Perpustakaan SMK</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #e3f2fd;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            height: 100%;
            border: none; /* Hilangkan border default card */
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15); /* Bayangan lebih dalam saat hover */
        }
        .btn-pinjam {
            background-color: <?php echo $warna_utama; ?>;
            color: white;
            border: none;
        }
        .btn-pinjam:hover {
            background-color: #e95a88; /* Warna sedikit lebih gelap saat hover */
            color: white;
        }
        .modal-body label {
            font-weight: 500;
        }
        .stok-info {
            font-size: 0.9rem;
            color: #666;
        }
        .back-link {
            text-decoration: none;
            color: #0d6efd;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .mp-4{
            text-align : center;
            justify-content : center;
            margin-bottom : 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color: #0d6efd;">
            <i class="<?php echo $ikon; ?>"></i> <?php echo $judul_halaman; ?>
        </h2>
        <p class="text-center text-muted"><?php echo $deskripsi_halaman; ?></p>

        <div class="row">
            <?php if ($buku_list): ?>
                <?php foreach ($buku_list as $buku): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <!-- Info Buku -->
                                <h5 class="card-title"><?php echo htmlspecialchars($buku['judul']); ?></h5>
                                <p class="text-muted">
                                    <strong>Penulis:</strong> <?php echo htmlspecialchars($buku['penulis'] ?? 'Tidak diketahui'); ?>
                                </p>
                                <p class="stok-info">
                                    <strong>Stok Tersedia:</strong>
                                    <span class="badge bg-success"><?php echo $buku['stok']; ?></span>
                                </p>

                                <!-- Tombol Pinjam -->
                                <?php if ($buku['stok'] > 0): ?>
                                    <button
                                        class="btn btn-pinjam btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalPinjam"
                                        data-id="<?php echo $buku['id']; ?>"
                                        data-judul="<?php echo htmlspecialchars($buku['judul']); ?>"
                                        data-stok="<?php echo $buku['stok']; ?>">
                                        <i class="fas fa-plus-circle"></i> Pinjam Buku
                                    </button>
                                <?php else: ?>
                                    <span class="text-danger small">Stok habis</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">Tidak ada buku untuk kategori ini.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Peminjaman -->
    <div class="modal fade" id="modalPinjam" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formPinjam">
                    <div class="modal-header" style="background-color: <?php echo $warna_utama; ?>; color: white;">
                        <h5 class="modal-title"><i class="<?php echo $ikon; ?>"></i> Form Peminjaman Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_buku" id="id_buku">

                        <div class="mb-3">
                            <label><strong>Judul Buku</strong></label>
                            <p class="form-control-plaintext" id="judul_buku"></p>
                        </div>

                        <div class="mb-3">
                            <label>Nama Siswa <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Program <span class="text-danger">*</span></label>
                            <select name="kelas" class="form-control" required>
                                <option value="">-- Pilih Program --</option>
                                <option value="X Kesehatan">X Kesehatan</option>
                                <option value="XI Farmasi">XI Farmasi</option>
                                <option value="XI Asisten Keperawatan">XI Asisten Keperawatan</option>
                                <option value="XII Farmasi">XII Farmasi</option>
                                <option value="XII Asisten Keperawatan">XII Asisten Keperawatan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Jumlah Pinjam <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" class="form-control" id="inputJumlah" min="1" max="5" value="1" required>
                            <div class="form-text">Maksimal <span id="maxStok">?</span> buah (stok tersedia)</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" style="background-color: <?php echo $warna_utama; ?>; border: none;">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script: Bootstrap & Ajax -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Isi modal saat tombol "Pinjam" diklik
        document.querySelectorAll('.btn-pinjam').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');
                const stok = parseInt(this.getAttribute('data-stok'));

                document.getElementById('id_buku').value = id;
                document.getElementById('judul_buku').textContent = judul;
                const inputJumlah = document.getElementById('inputJumlah');
                const maxStokElement = document.getElementById('maxStok');
                if (inputJumlah && maxStokElement) {
                     inputJumlah.max = stok;
                     maxStokElement.textContent = stok;
                     inputJumlah.value = 1;
                }
            });
        });

        // Submit form via AJAX
        document.getElementById('formPinjam').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('simpan_pinjam.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (!data.includes("Error") && !data.includes("gagal") && !data.includes("tidak")) {
                     location.reload();
                }
            })
            .catch(err => {
                console.error('Fetch error:', err);
                alert("Terjadi kesalahan teknis.");
            });
        });
    </script>
    <div class="mp-4">
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
</body>
</html>