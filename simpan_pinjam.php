<?php
include 'config.php';

// Cek apakah request-nya POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Terima dan bersihkan data dari form
    $nama = trim($_POST['nama'] ?? '');
    // $nipd TIDAK diterima dari form, sesuai permintaan
    // $kelas dari form sekarang berisi nama program, misal "XI Farmasi"
    $program = trim($_POST['kelas'] ?? ''); 
    $id_buku = isset($_POST['id_buku']) ? (int)$_POST['id_buku'] : 0;
    $jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 0;

    // 2. Validasi data dasar
    $errors = [];
    if (empty($nama)) {
        $errors[] = "Nama siswa harus diisi.";
    }
    // Validasi program berdasarkan data yang ada di Excel
    $daftar_program_valid = [
        'X Kesehatan', 'XI Farmasi', 'XI Asisten Keperawatan', 
        'XII Farmasi', 'XII Asisten Keperawatan'
        // Tambahkan program lain jika ada
    ];
    if (empty($program) || !in_array($program, $daftar_program_valid)) {
        // Izinkan nilai kelas numerik juga untuk kompatibilitas mundur jika diperlukan
        if (!in_array($program, ['10', '11', '12'])) {
             $errors[] = "Program tidak valid.";
        } else {
            // Jika yang dikirim adalah angka kelas, konversi ke nama program default
            // Misal, jika kelas 10, anggap X Kesehatan (sesuaikan logika jika perlu)
            // Untuk saat ini, kita tetap pakai nama program
            $errors[] = "Harap pilih program studi yang sesuai.";
        }
       
    }
    if ($id_buku <= 0) {
        $errors[] = "ID Buku tidak valid.";
    }
    if ($jumlah <= 0) {
        $errors[] = "Jumlah pinjam harus lebih dari 0.";
    }
    if ($jumlah > 5) { // Batas maksimal pinjam
        $errors[] = "Jumlah pinjam maksimal 5 buku.";
    }

    // Jika ada error validasi, tampilkan dan henti
    if (!empty($errors)) {
        echo "Error validasi:\n" . implode("\n", $errors);
        exit; // Hentikan eksekusi
    }

    try {
        // 3. 🔍 Cari NIPD dari tabel siswa berdasarkan nama dan program
        $stmt_nipd = $pdo->prepare("SELECT nipd FROM siswa WHERE nama_siswa = ? AND program = ?");
        $stmt_nipd->execute([$nama, $program]);
        $data_siswa = $stmt_nipd->fetch();
        $nipd = $data_siswa ? $data_siswa['nipd'] : ''; // Jika tidak ditemukan, tetap kosong

        // 4. Cek stok buku
        $stmt = $pdo->prepare("SELECT judul, stok FROM buku WHERE id = ?");
        $stmt->execute([$id_buku]);
        $buku = $stmt->fetch();

        if (!$buku) {
            echo "Error: Buku dengan ID $id_buku tidak ditemukan.";
            exit;
        }

        if ($buku['stok'] < $jumlah) {
            echo "Error: Stok tidak mencukupi. Tersedia: " . $buku['stok'] . ", Diminta: $jumlah.";
            exit;
        }

        // 5. Kurangi stok buku
        $stmt2 = $pdo->prepare("UPDATE buku SET stok = stok - ? WHERE id = ?");
        $result_update_stok = $stmt2->execute([$jumlah, $id_buku]);

        if (!$result_update_stok) {
            echo "Error: Gagal mengurangi stok buku.";
            exit;
        }

        // 6. Simpan data peminjaman
        //    - nipd diisi dengan nilai yang ditemukan atau kosong
        //    - program disimpan di kolom kelas untuk sementara, atau buat kolom baru jika perlu
        //    - tanggal_harus_kembali dihitung dari tanggal_pinjam + 3 hari
        $stmt3 = $pdo->prepare("
            INSERT INTO peminjaman (nama_siswa, nipd, kelas, id_buku, tanggal_pinjam, tanggal_harus_kembali, status)
            VALUES (?, ?, ?, ?, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 3 DAY), 'dipinjam')
            --                      ^^ nipd dari pencarian ^^
            --                            ^^ program dari form disimpan di kolom kelas ^^
        ");

        $sukses_simpan = 0;
        for ($i = 0; $i < $jumlah; $i++) {
            $result_insert = $stmt3->execute([$nama, $nipd, $program, $id_buku]);
            if ($result_insert) {
                $sukses_simpan++;
            }
        }

        // 7. Beri feedback ke pengguna
        if ($sukses_simpan == $jumlah) {
            $info_nipd = ($nipd !== '') ? " (NIPD: $nipd)" : " (NIPD tidak ditemukan, disimpan kosong)";
            echo "✅ Berhasil! $jumlah buku '{$buku['judul']}' telah dipinjam oleh $nama$info_nipd (Program: $program).";
            // Kolom NIPD dan Program akan muncul di admin/data_peminjaman.php
        } else {
            // Jika sebagian gagal (sangat jarang terjadi), tetap informasikan
            echo "⚠️ Sebagian data mungkin gagal disimpan. Berhasil: $sukses_simpan dari $jumlah.";
        }


    } catch (PDOException $e) {
        // Tangani error koneksi atau query database
        echo "Error database: " . $e->getMessage();
        // Opsional: Log error ke file untuk debugging
        // error_log("simpan_pinjam.php - Database Error: " . $e->getMessage());
    } catch (Exception $e) {
        // Tangani error lainnya
        echo "Error umum: " . $e->getMessage();
        // error_log("simpan_pinjam.php - General Error: " . $e->getMessage());
    }

} else {
    // Jika diakses langsung tanpa POST
    echo "Akses ditolak. Silakan gunakan form peminjaman.";
}
?>