<?php

include 'config.php'; // Pastikan config.php mengarah ke database yang benar

// 2. Data dari Excel (dikonversi ke array PHP)
// Data diambil dari file Excel yang Anda unggah.
$data_siswa = [
    ['nipd' => '25.26.10.001', 'nama_siswa' => 'Airin Tazkiatul Ummah', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.002', 'nama_siswa' => 'Alin Nur Aprilia', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.003', 'nama_siswa' => 'Andita Al-Fitriani', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.004', 'nama_siswa' => 'Anggraini', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.005', 'nama_siswa' => 'Atina Nahya Arumwangi', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.006', 'nama_siswa' => 'Dinda Khoirunnisa Hidayat', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.007', 'nama_siswa' => 'Elia Aprilia', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.008', 'nama_siswa' => 'Fidya Aura Pebrina', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.009', 'nama_siswa' => 'Fiska Agustin', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.010', 'nama_siswa' => 'Greace Febrisela Aring', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.012', 'nama_siswa' => 'Imellia Nur Bakhri', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.013', 'nama_siswa' => 'Indira Chandra Maulida', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.014', 'nama_siswa' => 'Jessica Ami Nugraha', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.015', 'nama_siswa' => 'Jihan Aulya Putri', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.016', 'nama_siswa' => 'Kamelia Agustin', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.017', 'nama_siswa' => 'Kayla Mutia Handayani', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.018', 'nama_siswa' => 'Kinanti Airrani Purwanto', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.019', 'nama_siswa' => 'Marsya Dwi Riyanti', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.020', 'nama_siswa' => 'Muhamad Alpin', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.021', 'nama_siswa' => 'Nabila Az Zahra', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.022', 'nama_siswa' => 'Nadiva', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.023', 'nama_siswa' => 'Najwatun Nafis', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.024', 'nama_siswa' => 'Nazwa Aprilia', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.025', 'nama_siswa' => 'Nazwa Putri Aisyah', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.026', 'nama_siswa' => 'Neysha Andriani', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.027', 'nama_siswa' => 'Niki Dhitia Handayani', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.028', 'nama_siswa' => 'Oktavia Amalia', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.029', 'nama_siswa' => 'Putri Syakila', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.030', 'nama_siswa' => 'Revanza Cristiano Ndraha', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.031', 'nama_siswa' => 'Rezky Angraeni Panjaitan', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.032', 'nama_siswa' => 'Silvy Aulia Rahma', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.033', 'nama_siswa' => 'Suci Elka Kirana', 'program' => 'X Kesehatan'],
    ['nipd' => '25.26.10.034', 'nama_siswa' => 'Vanesya Putri Amandari', 'program' => 'X Kesehatan'],
    ['nipd' => '24.25.10.001', 'nama_siswa' => 'A\'inun Latifah', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.005', 'nama_siswa' => 'Dinda Julliantiny Wijaya', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.007', 'nama_siswa' => 'Dwi Sarah', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.010', 'nama_siswa' => 'Hurul A\'ini Putri', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.014', 'nama_siswa' => 'Keiyla Utami Lutfifah', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.017', 'nama_siswa' => 'Khoirun Nisa', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.021', 'nama_siswa' => 'Nabila Khairani Ramadhanti', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.022', 'nama_siswa' => 'Nayzira Yona Vortuna Putri', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.023', 'nama_siswa' => 'Neisya Nuraulia', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.031', 'nama_siswa' => 'Silviatu Assyifa', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.035', 'nama_siswa' => 'Tiara Muslimah', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.036', 'nama_siswa' => 'Ummu Aulia Rahma', 'program' => 'XI Farmasi'],
    ['nipd' => '24.25.10.002', 'nama_siswa' => 'Alin Putri Utami', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.004', 'nama_siswa' => 'Dewi Habsyah', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.006', 'nama_siswa' => 'Dira Pratiwi', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.008', 'nama_siswa' => 'Erlin', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.009', 'nama_siswa' => 'Hilma Aulia', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.012', 'nama_siswa' => 'Jihan Julistia', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.013', 'nama_siswa' => 'Joy Ulina Stevany Munthe', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.015', 'nama_siswa' => 'Keizha Septiani', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.016', 'nama_siswa' => 'Keysha Putri', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.018', 'nama_siswa' => 'Masayu Alma Qvira', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.019', 'nama_siswa' => 'Melisa Andini', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.020', 'nama_siswa' => 'Munawaroh', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.024', 'nama_siswa' => 'Nesa Anggraeni', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.025', 'nama_siswa' => 'Ninda Aulia', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.026', 'nama_siswa' => 'Nurul Maulinda Putri', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.028', 'nama_siswa' => 'Putri Anugrah Khumaeroh', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.029', 'nama_siswa' => 'Raficca Rahma', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.030', 'nama_siswa' => 'Rahma Listiani', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.032', 'nama_siswa' => 'Siti Zahra Yulianingsih', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.033', 'nama_siswa' => 'Sri Mustika Rahayu', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '24.25.10.034', 'nama_siswa' => 'Tazkiatul Aulia', 'program' => 'XI Asisten Keperawatan'],
    ['nipd' => '23.24.10.001', 'nama_siswa' => 'Anindita Mutiara Putri', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.002', 'nama_siswa' => 'Anisa', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.003', 'nama_siswa' => 'Asyifah Nur Fadillah', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.006', 'nama_siswa' => 'Iqlima Fauziah Afdah', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.008', 'nama_siswa' => 'Meylani Arthia Cahyani', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.009', 'nama_siswa' => 'Nadine Putri Tirani', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.010', 'nama_siswa' => 'Nazwa Syawaltu Purnama', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.012', 'nama_siswa' => 'Putry Lettysya Imanuela Pelealu', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.013', 'nama_siswa' => 'Salwa Azzahra Yusrizal', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.014', 'nama_siswa' => 'Silvia Rahmadani', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.016', 'nama_siswa' => 'Siti Nur Alfia', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.017', 'nama_siswa' => 'Syahila Ayu Lesmana', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.018', 'nama_siswa' => 'Syeba Dara Mizola', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.019', 'nama_siswa' => 'Tiara Oktavia', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.020', 'nama_siswa' => 'Wildatul Solihah', 'program' => 'XII Farmasi'],
    ['nipd' => '24.25.11.038', 'nama_siswa' => 'Zakiyah Kamilah', 'program' => 'XII Farmasi'],
    ['nipd' => '23.24.10.021', 'nama_siswa' => 'Almira Nency Agustin', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.022', 'nama_siswa' => 'Alwa Herawati', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.023', 'nama_siswa' => 'Annisa Agustin', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.024', 'nama_siswa' => 'Deliva Bunga Safira', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.025', 'nama_siswa' => 'Destrina Adha Lestari', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.026', 'nama_siswa' => 'Dinda Siti Munawaroh', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.027', 'nama_siswa' => 'Febrina Riani Putri', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.028', 'nama_siswa' => 'Gheaniya Early Putri', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.029', 'nama_siswa' => 'Gita Mutiara Reflesia', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.030', 'nama_siswa' => 'Intan Puspitasari', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.007', 'nama_siswa' => 'Maesya Maelani', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.031', 'nama_siswa' => 'Marsha Mauliddya', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.032', 'nama_siswa' => 'Mela Yuliasari', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.033', 'nama_siswa' => 'Monica', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.034', 'nama_siswa' => 'Nabila Kirani Sutarto', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.037', 'nama_siswa' => 'Novia Adira', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.038', 'nama_siswa' => 'Nuri Awalia', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.039', 'nama_siswa' => 'Nurmaulina', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.041', 'nama_siswa' => 'Rahmania Nihayatu Arsyam', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.042', 'nama_siswa' => 'Rasya Wulandari', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.043', 'nama_siswa' => 'Rina Helviana', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.044', 'nama_siswa' => 'Sinta Sidiani', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.045', 'nama_siswa' => 'Siti Aisah', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.047', 'nama_siswa' => 'Siti Nurmila', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.048', 'nama_siswa' => 'Suci Ramadani', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.049', 'nama_siswa' => 'Tiara Anggra Naeni', 'program' => 'XII Asisten Keperawatan'],
    ['nipd' => '23.24.10.050', 'nama_siswa' => 'Tiara Joe Vanka', 'program' => 'XII Asisten Keperawatan']
];

echo "<h2>Memulai Impor Data Siswa...</h2>";

try {
    // 3. Siapkan statement untuk memasukkan data
    $stmt = $pdo->prepare("INSERT INTO siswa (nipd, nama_siswa, program, kelas) VALUES (?, ?, ?, ?) 
                           ON DUPLICATE KEY UPDATE nama_siswa=VALUES(nama_siswa), program=VALUES(program), kelas=VALUES(kelas)");

    $jumlah_berhasil = 0;
    $jumlah_gagal = 0;
    $gagal_list = [];

    foreach ($data_siswa as $siswa) {
        // Ekstrak kelas dari program (ambil karakter pertama)
        $kelas = '';
        if (strpos($siswa['program'], 'X ') === 0) {
            $kelas = '10'; // Kelas 10
        } elseif (strpos($siswa['program'], 'XI ') === 0) {
            $kelas = '11'; // Kelas 11
        } elseif (strpos($siswa['program'], 'XII ') === 0) {
            $kelas = '12'; // Kelas 12
        } else {
            // Jika format tidak sesuai, coba ambil angka romawi
            if (strpos($siswa['program'], 'X') !== false && strpos($siswa['program'], 'XI') === false) {
                 $kelas = '10'; // Anggap X = Kelas 10
            } elseif (strpos($siswa['program'], 'XI') !== false && strpos($siswa['program'], 'XII') === false) {
                 $kelas = '11'; // Anggap XI = Kelas 11
            } elseif (strpos($siswa['program'], 'XII') !== false) {
                 $kelas = '12'; // Anggap XII = Kelas 12
            }
        }

        // Jika tidak bisa menentukan kelas, lewati
        if (empty($kelas)) {
            $jumlah_gagal++;
            $gagal_list[] = "NIPD: {$siswa['nipd']}, Nama: {$siswa['nama_siswa']} - Kelas tidak dikenali dari program '{$siswa['program']}'";
            continue;
        }

        try {
            $stmt->execute([
                $siswa['nipd'],
                $siswa['nama_siswa'],
                $siswa['program'],
                $kelas
            ]);
            $jumlah_berhasil++;
        } catch (PDOException $e) {
            $jumlah_gagal++;
            $gagal_list[] = "NIPD: {$siswa['nipd']}, Nama: {$siswa['nama_siswa']} - Error: " . $e->getMessage();
        }
    }

    echo "<p style='color:green;'><strong>✅ Impor Selesai!</strong></p>";
    echo "<p><strong>Jumlah Berhasil:</strong> $jumlah_berhasil</p>";
    echo "<p><strong>Jumlah Gagal:</strong> $jumlah_gagal</p>";

    if (!empty($gagal_list)) {
        echo "<h3>Daftar yang Gagal:</h3><ul>";
        foreach ($gagal_list as $gagal) {
            echo "<li style='color:red;'>$gagal</li>";
        }
        echo "</ul>";
    }


} catch (PDOException $e) {
    die("Koneksi atau query error: " . $e->getMessage());
}

echo "<br><a href='index.php'>Kembali ke Beranda</a>";

?>