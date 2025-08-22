<?php
// File: impor_ulang_buku.php
// Tempatkan file ini di folder yang sama dengan config.php
// Jalankan via browser: http://localhost/nama_folder_anda/impor_ulang_buku.php
// Setelah selesai, HAPUS FILE INI untuk keamanan!

include 'config.php';

try {
    // Mulai transaksi untuk memastikan semua berhasil atau rollback
    $pdo->beginTransaction();

    // Hapus data lama
    echo "<h2>Menghapus data buku lama...</h2>";
    $stmt_delete = $pdo->prepare("DELETE FROM buku");
    $stmt_delete->execute();
    echo "<p style='color:orange;'>Data lama dihapus.</p>";

    // Siapkan statement insert
    $stmt_insert = $pdo->prepare("INSERT INTO buku (judul, penulis, kelas_tujuan, stok) VALUES (?, ?, ?, ?)");

    // --- DATA DARI EXCEL "Daftar Buku Perpustakaan (belum up).xlsx" ---
    // Format: array([judul], [penulis], [kategori_kelas_tujuan], [stok])
    // Kategori kelas_tujuan: '10', '11', '12', 'farmasi', 'asisten_keperawatan', 'novel'
    $data_buku = [
        // --- BUKU MAPEL (Kelas 10, 11, 12 Umum) ---
        ['Bahasa Indonesia', 'Maman Suryaman dkk', '12', 12],
        ['Bahasa Indonesia', '', '10', 53],
        ['Bahasa Indonesia ktsp', 'Budi wahyono', '11', 9],
        ['Bahasa Indonesia', 'Suherli dkk', '10', 53],
        ['Bahasa Indonesia', '', '11', 44],
        ['Bahasa Indonesia 3', 'Budi wahyono', '12', 9],
        ['Bahasa Inggris', 'Utami Widiati', '10', 58],
        ['Bahasa Inggris', 'Mahrukh bashir', '11', 55],
        ['Bahasa Inggris', 'Utami widiati', '12', 12],
        ['Biologi', '', '10', 27],
        ['Fisika', 'Tim Kompas Ilmu', '10', 31],
        ['Fisika jilid 1, 2, 3', '', '10', 3], // Dipetakan ke kelas 10 karena ada di kelas x
        ['Forward English', 'Shyla k lande dkk', '10', 26],
        ['Kimia', 'Tim Kompas Ilmu', '10', 38],
        ['Matematika', 'Kasmina dan Toali', '11', 1],
        ['Matematika', 'Kasmina dan Toali', '12', 1],
        ['Matematika', 'Bornok sinaga', '10', 43],
        ['Matematika', 'Sudianto Manulang', '11', 37],
        ['Matematika', 'Kasmina dan Toali', '10', 33],
        ['Pendidikan Agama Islam', '', '12', 5],
        ['Pendidikan Agama Islam', '', '11', 30],
        ['Pendidikan Agama Islam', '', '10', 41],
        ['Pendidikan Jasmani, Olahraga, dan Kesehatan', 'Sudrajat dkk', '10', 23],
        ['Pendidikan Jasmani, Olahraga, dan Kesehatan', 'sumaryoto dkk', '11', 16],
        ['Pendidikan Pancasila dan Kewarganegaraan', 'Nuryadi', '10', 55],
        ['Pendidikan Pancasila dan Kewarganegaraan', 'Yusnawan lubis', '12', 1], // Diasumsikan stok 1
        ['Pendidikan Pancasila dan Kewarganegaraan', '', '11', 1], // Diasumsikan stok 1
        ['Prakarya dan Kewirausahaan', 'Hendriana dkk', '10', 24],
        ['Prakarya dan Kewirausahaan', 'Hendriana dkk', '11', 26],
        ['Prakarya dan Kewirausahaan', 'Hendriana dkk', '12', 1],
        ['Produktif Berbahasa Indonesia', 'Yustinah', '10', 27],
        ['Sejarah Indonesia', 'Restu Gunawan', '10', 64],
        ['Sejarah Indonesia', '', '11', 37],
        ['Seni Budaya', '', '10', 30],
        ['Seni Budaya', '', '11', 21],
        ['Seni Budaya', 'Zackaria soeteja dkk', '10', 50],
        ['Seni Budaya', 'Sem Cornelyoes', '11', 65],
        ['Get Along With English 2', 'Entin sutinah', '11', 10],
        ['Get Along With English 3', 'Entin sutinah', '12', 8],
        ['Effective Communication', 'Agus Widyantoro', '11', 15],
        ['simulasi komunikasi digital', 'Andi novianto', '10', 52],
        ['seni rupa 1', '', '10', 3],
        ['seni rupa 2', '', '10', 3],
        ['seni teater 1', '', '10', 3],
        ['seni teater 2', '', '10', 3],
        ['seni musik 1', '', '10', 3],
        ['seni musik 2', '', '10', 3],
        ['seni tari 1', '', '10', 3],
        ['seni tari 2', '', '10', 1], // Diasumsikan stok 1
        ['ilmu pengetahuan alam', 'kemendikbud 2021', '10', 27],
        ['english', 'arnita nudi dkk', '11', 4],
        ['english couse book', 'arnita nudi dkk', '12', 4],
        ['akutansi', 'sutarmi', '12', 4],
        ['asisten teknik laboratorium medik', 'hendro prayitno', '12', 5],
        ['farmasi klinis dan komunitas', 'sekar wulan', '12', 5],
        ['dasar teknik laboratorium medik', 'yoki setyaji', '10', 2],
        ['berbahasa dan bersastra indonesia', 'cerdas cergas', '10', 20],
        ['ilmu pengetahuan sosial', 'kemendikbud 2021', '10', 10],
        ['Matematika', 'kemendikbud 2021', '11', 5],
        ['matematika', 'kemendikbud 2021', '12', 5],
        ['farmasi klinis dan komunitas', 'kemendikbud 2021', '11', 5],
        ['asisten teknik laboratorium medik', 'kemendikbud 2021', '11', 5],

        // --- BUKU KESEHATAN (Kejuruan: Farmasi & Asisten Keperawatan) ---
        // --- BUKU FARMASI ---
        ['Administrasi farmasi', 'Afrizal dkk', 'farmasi', 3],
        ['Bentuk Sediaan farmasetis', 'Loyd V allen', 'farmasi', 1],
        ['Farmakognosi', 'Muh.yani', 'farmasi', 3],
        ['Farmakognosi jilid 2', 'Muh.yani', 'farmasi', 4],
        ['Farmakognosi jilid 3', 'Muh.yani', 'farmasi', 3],
        ['Farmakologi', 'Aster nila', 'farmasi', 4],
        ['Farmakologi jilid 1', 'Aster nila', 'farmasi', 3],
        ['Farmakologi jilid 3', 'Aster nila', 'farmasi', 5],
        ['Pelayanan farmasi', 'Ai kuraesin', 'farmasi', 5],
        ['Pelayanan farmasi', 'Fitri zakiyah', 'farmasi', 1],
        ['Preformulasi', '', 'farmasi', 2],
        ['Tanaman Rempah dan Obat', '', 'farmasi', 3],
        ['Teknik Pembuatan Sediaan Obat jilid 1', 'R.A Rogayah', 'farmasi', 6],
        ['Teknik Pembuatan Sediaan Obat jilid 2', 'R.A Rogayah', 'farmasi', 5],
        ['Dasar Farmasi', 'Hendrik', 'farmasi', 3],
        ['Kimia Farmasi 1', 'Ardiyuli', 'farmasi', 4],
        ['Kimia Farmasi 2', 'Ardiyuli', 'farmasi', 4],
        ['Pelayanan Farmasi 1', 'Abdur rohman', 'farmasi', 3],
        ['Pelayanan Farmasi 2', 'Abdur rohman', 'farmasi', 3],
        ['Farmakognosi Dasar', 'Abdur rohman', 'farmasi', 4],
        ['Farmakognosi', 'Supriani', 'farmasi', 4],
        ['Farmakologi 1', 'Rahayu prihatin', 'farmasi', 3],
        ['Farmakologi 2', 'Nur salami', 'farmasi', 4],
        ['Manajemen farmasi', '', 'farmasi', 1],

        // --- BUKU ASISTEN KEperawatan ---
        ['Administrasi Keperawatan', 'Anggita puspasari', 'asisten_keperawatan', 9],
        ['Anatomi Fisiologi', 'Eni purwanti', 'asisten_keperawatan', 7],
        ['Anatomi Fisiologi dan Dasar-dasar Penyakit', 'Barbara dkk', 'asisten_keperawatan', 1],
        ['Ilmu Kesehatan Masyarakat', 'Hartati dkk', 'asisten_keperawatan', 2],
        ['Ilmu Kesehatan Masyarakat', 'Germas', 'asisten_keperawatan', 1],
        ['Ilmu Kesehatan Masyarakat', 'Faisal rohman', 'asisten_keperawatan', 8],
        ['K3LH', 'Hartati dkk', 'asisten_keperawatan', 6],
        ['K3LH', 'Barbara dkk', 'asisten_keperawatan', 1],
        ['Kebutuhan Dasar Manusia', 'Anggara dwi sulistianti dkk', 'asisten_keperawatan', 1],
        ['Kebutuhan Dasar Manusia', 'Lutfi nurjanah', 'asisten_keperawatan', 3],
        ['Kebutuhan Dasar Manusia Jilid 2', 'Dwi budi dkk', 'asisten_keperawatan', 1],
        ['Kebutuhan Dasar Manusia Jilid 4', 'Dwi budi dkk', 'asisten_keperawatan', 1],
        ['Keterampilan Dasar Praktik keperawatan', 'Siti nurmala dkk', 'asisten_keperawatan', 1],
        ['Keterampilan Dasar Tindakan Keperawatan', 'Prof.Dr Soekitjo', 'asisten_keperawatan', 1],
        ['Keterampilan Dasar Tindakan Keperawatan', 'Wira pratama', 'asisten_keperawatan', 2],
        ['Keterampilan Dasar Tindakan Keperawatan', 'Barbara R', 'asisten_keperawatan', 1],
        ['Modul Pembelajaran Tumbuh Kembang', 'Barbara dkk', 'asisten_keperawatan', 1],
        ['Pengantar kesehatan reproduksi', 'Najib zakaria', 'asisten_keperawatan', 3],
        ['Pengantar Psikologi keperawatan', 'Panti ambarwati', 'asisten_keperawatan', 1],
        ['Undang-undang Dasar Kesehatan', 'Kenti prahmanti', 'asisten_keperawatan', 1],
        ['Kesehatan Masyarakat', 'Prof.Dr Soekitjo', 'asisten_keperawatan', 1],
        ['Pilar Ilmu Kesehatan Masyarakat', 'Dedi alamsyah', 'asisten_keperawatan', 1],
        ['Komunikasi Keperawatan SIILVI : SIILVI: 1 dipinjam bu ega untuk ngajar', 'Supanjono', 'asisten_keperawatan', 3],
        ['Kebutuhan Dasar Manusia 1', 'Dewi puspitasari', 'asisten_keperawatan', 4],
        ['Kebutuhan Dasar Manusia 2', 'Dewi puspitasari', 'asisten_keperawatan', 2],
        ['Konsep Dasar Keperawatan 1', 'Endah nurkayah', 'asisten_keperawatan', 4],
        ['Konsep Dasar Keperawatan 2', 'Endah nurkayah', 'asisten_keperawatan', 4],
        ['Keterampilan Dasar Tindakan Keperawatan 1', 'Dhanik tri', 'asisten_keperawatan', 3],
        ['Keterampilan Dasar Tindakan Keperawatan 2', 'Dhanik tri', 'asisten_keperawatan', 4],
        ['Keterampilan Dasar Tindakan Keperawatan 3', 'Dhanik tri', 'asisten_keperawatan', 4],
        ['Ilmu Penyakit dan Penunjang Diagnosis 1', 'dr.anggraeni', 'asisten_keperawatan', 2],
        ['Ilmu Penyakit dan Penunjang Diagnosis 2', 'dr.anggraeni', 'asisten_keperawatan', 3],
        ['k3lh', 'Heru widianto', 'asisten_keperawatan', 2],
        ['Ilmu Kesehatan Masyarakat', 'Wahyu Sulistiani', 'asisten_keperawatan', 4],
        ['Anatomi Fisiologi', 'Ari setiawan', 'asisten_keperawatan', 4],
        ['UUD Kesehatan', 'Arip rubianto', 'asisten_keperawatan', 4],

        // --- Ini Novel Dan KAMUS ---
        ['Matahari', 'TereLiye', 'novel', 4],
        ['Bumi', 'TereLiye', 'novel', 2],
        ['Bulan', 'TereLiye', 'novel', 4],
        ['Bintang', 'TereLiye', 'novel', 4],
        ['Ceros dan Batozar', 'TereLiye', 'novel', 3],
        ['Hujan', 'TereLiye', 'novel', 4],
        ['Ayahku Bukan Pembohong', 'TereLiye', 'novel', 2],
        ['Kau,Aku dan Sepucuk Angpao Merah', 'TereLiye', 'novel', 5],
        ['Daun yang Jatuh Tak Pernah Membenci Angin', 'TereLiye', 'novel', 4],
        ['Ranah 3 Warna', 'A.Fuandi', 'novel', 5],
        ['Negeri 5 Menara', 'A.Fuandi', 'novel', 3],
        ['Jingga dan Senja', 'Esti Kinasih', 'novel', 4],
        ['Dan Hujan pun Berhenti', 'Farida susanti', 'novel', 4],
        ['5 cm', 'Donny dhirgantoro', 'novel', 1],
        ['Bukan Salah Hujan', 'Ummuchan', 'novel', 5],
        ['Hungout', 'Bella zmr', 'novel', 1],
        ['lovasket', 'Luna torashyngu', 'novel', 1],
        ['Kamus Indonesia Inggris Hardcase', '', 'novel', 5],
        ['Kamus Indonesia Inggris Softcase', '', 'novel', 5],
        ['Kamus Indonesia Inggris Hardcase jilid 3', '', 'novel', 5],
        ['Kamus Indonesia Inggris Softcase jilid 3', '', 'novel', 5],
        ['Kamus Bahasa Indonesia', '', 'novel', 1],
        ['Kamus 975 Triliyun', '', 'novel', 1],
        ['Kamus Bahasa Sunda', '', 'novel', 2],
        ['Atlas', 'BIP', 'novel', 5],
        ['gercep utbk snbt 2024', 'muhammad amien', 'novel', 10]
    ];

    echo "<h2>Memasukkan data buku baru...</h2>";
    $counter = 0;
    foreach ($data_buku as $buku) {
        $stmt_insert->execute($buku);
        $counter++;
        // Tampilkan progres setiap 20 buku
        if ($counter % 20 == 0) {
            echo "<p>$counter buku dimasukkan...</p>";
        }
    }

    // Commit transaksi
    $pdo->commit();
    echo "<h3 style='color:green;'>✅ SUKSES! Total $counter buku telah dimasukkan ulang ke database.</h3>";
    echo "<p>Silahkan hapus file <code>" . basename(__FILE__) . "</code> untuk keamanan.</p>";
    echo "<a href='index.php'>Kembali ke Beranda</a>";

} catch (Exception $e) {
    // Rollback jika terjadi kesalahan
    $pdo->rollBack();
    echo "<h3 style='color:red;'>❌ ERROR:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p><strong>Proses impor dibatalkan. Tidak ada perubahan pada database.</strong></p>";
}

?>