<?php
// Set header JSON
header('Content-Type: application/json');
include 'config.php';

// Cek apakah ID buku dikirim
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode([]);
    exit;
}

$id_buku = (int)$_GET['id'];

try {
    // Query ambil peminjam
    $stmt = $pdo->prepare("
        SELECT nama_siswa, kelas, tanggal_pinjam 
        FROM peminjaman 
        WHERE id_buku = ? AND status = 'dipinjam'
        ORDER BY tanggal_pinjam DESC
    ");
    $stmt->execute([$id_buku]);
    $peminjam = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Kembalikan dalam format JSON
    echo json_encode($peminjam);

} catch (Exception $e) {
    // Jika error, tetap kembalikan array kosong
    echo json_encode([]);
}
?>