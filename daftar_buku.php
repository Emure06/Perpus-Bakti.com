<?php
include 'config.php';

try {
    // Query untuk mendapatkan semua buku beserta jumlah yang sedang dipinjam
    $stmt = $pdo->query("
        SELECT b.*,
               (SELECT COUNT(*) FROM peminjaman p WHERE p.id_buku = b.id AND p.status = 'dipinjam') AS dipinjam
        FROM buku b
        ORDER BY FIELD(b.kelas_tujuan, '10', '11', '12', 'farmasi', 'asisten_keperawatan', 'novel'), 
                 b.kelas_tujuan, 
                 b.judul
    ");
    $semua_buku = $stmt->fetchAll();

    // Pisahkan buku ke dalam kategori berdasarkan kelas_tujuan
    $kategori_buku = [
        '10' => [],
        '11' => [],
        '12' => [],
        'farmasi' => [],
        'asisten_keperawatan' => [],
        'novel' => [],
        'lainnya' => [] // Untuk buku dengan kelas_tujuan tidak terduga
    ];

    foreach ($semua_buku as $buku) {
        $kategori = $buku['kelas_tujuan'];
        if (array_key_exists($kategori, $kategori_buku)) {
            $kategori_buku[$kategori][] = $buku;
        } else {
            // Jika kelas_tujuan tidak sesuai kategori yang diharapkan
            $kategori_buku['lainnya'][] = $buku;
        }
    }

    // Fungsi untuk mendapatkan nama kategori yang lebih ramah
    function getNamaKategori($kode_kategori) {
        switch ($kode_kategori) {
            case '10': return 'Kelas 10';
            case '11': return 'Kelas 11';
            case '12': return 'Kelas 12';
            case 'farmasi': return 'Program Keahlian Farmasi';
            case 'asisten_keperawatan': return 'Program Keahlian Asisten Keperawatan';
            case 'novel': return 'Buku Novel';
            default: return $kode_kategori;
        }
    }

} catch (Exception $e) {
    die("Error mengambil data buku: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Semua Buku - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: none;
        }
        .kategori-header {
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            margin-top: 30px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table th {
            background-color: #e9ecef;
            border-top: none;
        }
        .badge-stok {
            font-size: 0.85em;
            padding: 0.5em 0.7em;
        }
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99;
            display: none;
            border: none;
            outline: none;
            background-color: #0d6efd;
            color: white;
            cursor: pointer;
            padding: 15px;
            border-radius: 50%;
            font-size: 18px;
        }
        .back-to-top:hover {
            background-color: #0b5ed7;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        .action-buttons .btn {
            flex: 1 1 auto;
            min-width: 100px;
        }
        @media (max-width: 575.98px) {
            .action-buttons {
                flex-direction: column;
            }
            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="text-center mb-4">
            <h2 class="text-primary"><i class="fas fa-book"></i> Daftar Semua Buku Tersedia</h2>
            <p class="text-muted">Lihat koleksi lengkap buku perpustakaan berdasarkan kategori</p>
        </div>

        <!-- Tombol Kembali ke Atas -->
        <button onclick="topFunction()" id="btnBackToTop" title="Kembali ke Atas" class="back-to-top">
            <i class="fas fa-arrow-up"></i>
        </button>

        <?php
        $kategori_ditampilkan = false;
        foreach ($kategori_buku as $kode_kategori => $buku_list):
            // Jangan tampilkan kategori jika kosong
            if (empty($buku_list)) continue;

            $kategori_ditampilkan = true;
            $nama_kategori = getNamaKategori($kode_kategori);
        ?>
        <!-- Header Kategori -->
        <div class="kategori-header" id="kategori-<?= $kode_kategori ?>">
            <h4 class="mb-0"><i class="fas fa-folder"></i> <?= $nama_kategori ?></h4>
            <span class="badge bg-light text-dark"><?= count($buku_list) ?> Buku</span>
        </div>

        <!-- Tabel Buku dalam Kategori -->
        <div class="card mb-5">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Judul Buku</th>
                            <th scope="col">Penulis</th>
                            <th scope="col" class="text-center">Stok Tersedia</th>
                            <th scope="col" class="text-center">Dipinjam</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($buku_list as $index => $b): ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= htmlspecialchars($b['judul']) ?></td>
                            <td><?= htmlspecialchars($b['penulis'] ?? '-') ?></td>
                            <td class="text-center">
                                <span class="badge bg-success badge-stok"><?= $b['stok'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info"><?= $b['dipinjam'] ?></span>
                                <?php if ($b['dipinjam'] > 0): ?>
                                    <button
                                        class="btn btn-sm btn-warning ms-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDipinjam"
                                        data-id="<?= $b['id'] ?>"
                                        data-judul="<?= htmlspecialchars($b['judul']) ?>">
                                        <i class="fas fa-users"></i> Lihat
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="action-buttons justify-content-center">
                                    <?php if ($b['stok'] > 0): ?>
                                        <button
                                            class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalPinjam"
                                            data-id="<?= $b['id'] ?>"
                                            data-judul="<?= htmlspecialchars($b['judul']) ?>"
                                            data-stok="<?= $b['stok'] ?>">
                                            <i class="fas fa-plus-circle"></i> Pinjam
                                        </button>
                                    <?php else: ?>
                                        <span class="text-danger small">Stok Habis</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (!$kategori_ditampilkan): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-box-open"></i> Belum ada buku yang tersedia.
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Modal Dipinjam oleh -->
    <div class="modal fade" id="modalDipinjam" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-users"></i> Dipinjam oleh:</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 id="judulBukuModal" class="text-primary"></h6>
                    <ul id="listPeminjam" class="list-group mt-3">
                        <!-- Diisi dengan AJAX -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Peminjaman -->
    <div class="modal fade" id="modalPinjam" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formPinjam">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-book"></i> Form Peminjaman Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_buku" id="id_buku">

                        <div class="mb-3">
                            <label class="form-label"><strong>Judul Buku</strong></label>
                            <p class="form-control-plaintext" id="judul_buku_modal"></p>
                        </div>

                        <div class="mb-3">
                            <label for="nama_peminjam" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" id="nama_peminjam" required>
                        </div>

                        <div class="mb-3">
                            <label for="kelas_peminjam" class="form-label">Program <span class="text-danger">*</span></label>
                            <select name="kelas" class="form-control" id="kelas_peminjam" required>
                                <option value="">-- Pilih Program --</option>
                                <option value="X Kesehatan">X Kesehatan</option>
                                <option value="XI Farmasi">XI Farmasi</option>
                                <option value="XI Asisten Keperawatan">XI Asisten Keperawatan</option>
                                <option value="XII Farmasi">XII Farmasi</option>
                                <option value="XII Asisten Keperawatan">XII Asisten Keperawatan</option>
                                <!-- Tambahkan opsi lain jika diperlukan -->
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah_pinjam" class="form-label">Jumlah Pinjam <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" class="form-control" id="jumlah_pinjam" min="1" max="5" value="1" required>
                            <div class="form-text">Maksimal <span id="maxStokModal">?</span> buah (stok tersedia)</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        // Fungsi untuk tombol kembali ke atas
        let mybutton = document.getElementById("btnBackToTop");

        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }

        // Saat tombol "Dipinjam oleh" diklik
        document.querySelectorAll('[data-bs-target="#modalDipinjam"]').forEach(button => {
            button.addEventListener('click', function () {
                const id_buku = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');

                document.getElementById('judulBukuModal').textContent = judul;

                const list = document.getElementById('listPeminjam');
                list.innerHTML = '<li class="list-group-item text-center">Memuat...</li>';

                fetch(`get_peminjam.php?id=${id_buku}`)
                    .then(response => response.json())
                    .then(data => {
                        list.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.className = 'list-group-item';
                                const nipd_info = item.nipd ? ` (NIPD: ${item.nipd})` : '';
                                li.innerHTML = `
                                    <strong>${item.nama_siswa}${nipd_info}</strong> (${item.kelas})<br>
                                    <small class="text-muted">Tanggal Pinjam: ${item.tanggal_pinjam}</small>
                                `;
                                list.appendChild(li);
                            });
                        } else {
                            list.innerHTML = '<li class="list-group-item text-center text-muted">Tidak ada data</li>';
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        list.innerHTML = '<li class="list-group-item text-danger">Error memuat data</li>';
                    });
            });
        });

        // Saat tombol "Pinjam Buku" diklik (isi modal)
        document.querySelectorAll('[data-bs-target="#modalPinjam"]').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');
                const stok = parseInt(this.getAttribute('data-stok'));

                document.getElementById('id_buku').value = id;
                document.getElementById('judul_buku_modal').textContent = judul;
                
                const inputJumlah = document.getElementById('jumlah_pinjam');
                const maxStokElement = document.getElementById('maxStokModal');
                if (inputJumlah && maxStokElement) {
                     inputJumlah.max = stok;
                     maxStokElement.textContent = stok;
                     inputJumlah.value = 1; // reset
                }
            });
        });

        // Submit form peminjaman via AJAX
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
                    location.reload(); // atau $('#modalPinjam').modal('hide'); jika tidak ingin refresh
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert("Terjadi kesalahan teknis.");
            });
        });
    </script>
</body>
</html>