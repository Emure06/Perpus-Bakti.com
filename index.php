<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan SMK Bakti Putra Mandiri</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Google Fonts: Poppins (lebih modern) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e3f2fd, #c8e6c9);
            color: #444;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-brand {
            font-weight: 700;
            color: #0d6efd !important;
            font-size: 1.3rem;
        }

        .logo {
            font-size: 1.5rem;
            color: #0d6efd;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        /* Warna latar belakang untuk setiap kategori card */
        .card-kelas-10 { background: linear-gradient(to right, #4facfe, #00f2fe); } /* Biru */
        .card-kelas-11 { background: linear-gradient(to right, #43e97b, #38f9d7); } /* Hijau */
        .card-kelas-12 { background: linear-gradient(to right, #667eea, #764ba2); } /* Ungu */
        .card-farmasi { background: linear-gradient(to right, #fa709a, #fee140); } /* Pink/Kuning */
        .card-keperawatan { background: linear-gradient(to right, #a18cd1, #fbc2eb); } /* Ungu Pastel ke Pink */
        .card-novel { background: linear-gradient(to right, #ff758c, #ff7eb3); } /* Merah Muda */
        /* Warna untuk fitur tambahan */
        .card-pinjaman { background: linear-gradient(to right, #ff9a9e, #fad0c4); } /* Oranye Muda */
        .card-semua-buku { background: linear-gradient(to right, #a1c4fd, #c2e9fb); } /* Biru Muda */

        .card-body {
            color: white;
            text-align: center;
            padding: 2rem 1.5rem;
        }

        .card h3 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.1rem; /* Ukuran font sedikit dikurangi */
        }

        .card p {
            font-size: 0.85rem; /* Ukuran font deskripsi dikurangi */
        }

        .btn-custom {
            background-color: white;
            color: #0d6efd;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.4rem 1rem; /* Padding tombol dikurangi */
            transition: all 0.3s;
            border: none;
            font-size: 0.8rem; /* Ukuran font tombol dikurangi */
        }

        .btn-custom:hover {
            background-color: #f0f8ff;
            transform: scale(1.05);
            color: #0b5ed7;
        }

        /* Hero Section - Mirip dengan sebelumnya */
        .hero {
            background: url('https://images.unsplash.com/photo-1507842286227-8a8a8a9a7a9a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80') no-repeat center center;
            background-size: cover;
            height: 300px;
            border-radius: 20px;
            position: relative;
            margin: 2rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 100, 200, 0.6);
            border-radius: 20px;
            z-index: 1;
        }

        .hero-content {
            z-index: 2;
            text-align: center;
            max-width: 800px;
            padding: 2rem;
        }

        /* Footer - Mirip dengan sebelumnya */
        footer {
            margin-top: auto;
            padding: 2rem 0;
            background-color: #0d6efd;
            color: white;
            text-align: center;
            border-radius: 12px 12px 0 0;
        }

        footer i {
            margin: 0 0.5rem;
        }

        /* Animasi */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Spasi antar baris */
        .menu-row {
             margin-bottom: 2rem;
        }

        /* Judul section */
        .section-title {
            text-align: center;
            margin: 2rem 0 1rem 0;
            font-weight: 600;
            color: #0d6efd;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-book-medical logo me-2"></i>
                Perpustakaan SMK Bakti Putra Mandiri
            </a>
            <div>
                <a href="admin/login.php" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-user-shield"></i> Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container mt-4">
        <div class="hero pulse">
            <div class="hero-content">
                <h1>📘 Selamat Datang di Perpustakaan Digital</h1>
                <p class="lead">Sumber ilmu untuk calon tenaga kesehatan masa depan</p>
            </div>
        </div>
    </div>

    <!-- Intro -->
    <div class="container text-center mb-4">
        <h2 class="text-primary"><i class="fas fa-graduation-cap"></i> Pilih Kategori Buku Anda</h2>
        <p class="lead text-muted">Temukan koleksi buku yang sesuai dengan kebutuhan Anda</p>
    </div>

    <!-- Cards Kategori Kelas -->
    <div class="container menu-row">
        <h3 class="section-title"><i class="fas fa-school"></i> Berdasarkan Kelas</h3>
        <div class="row g-4">
            <!-- Kelas 10 -->
            <div class="col-md-4">
                <div class="card card-kelas-10 h-100">
                    <div class="card-body">
                        <i class="fas fa-user-graduate fa-2x mb-3"></i>
                        <h3>Kelas 10</h3>
                        <p>Dasar-dasar Ilmu Kesehatan & Biologi</p>
                        <a href="kelas10.php" class="btn btn-custom">
                            <i class="fas fa-book-open"></i> Lihat Buku
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kelas 11 -->
            <div class="col-md-4">
                <div class="card card-kelas-11 h-100">
                    <div class="card-body">
                        <i class="fas fa-stethoscope fa-2x mb-3"></i>
                        <h3>Kelas 11</h3>
                        <p>Anatomi, Fisiologi & Praktik Lab</p>
                        <a href="kelas11.php" class="btn btn-custom">
                            <i class="fas fa-book-open"></i> Lihat Buku
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kelas 12 -->
            <div class="col-md-4">
                <div class="card card-kelas-12 h-100">
                    <div class="card-body">
                        <i class="fas fa-briefcase-medical fa-2x mb-3"></i>
                        <h3>Kelas 12</h3>
                        <p>Praktik Klinik & Persiapan Ujian Nasional</p>
                        <a href="kelas12.php" class="btn btn-custom">
                            <i class="fas fa-book-open"></i> Lihat Buku
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Kategori Kejuruan & Novel -->
    <div class="container menu-row">
        <h3 class="section-title"><i class="fas fa-user-md"></i> Program Keahlian & Novel</h3>
        <div class="row g-4">
            <!-- Farmasi -->
            <div class="col-md-4">
                <div class="card card-farmasi h-100">
                    <div class="card-body">
                        <i class="fas fa-mortar-pestle fa-2x mb-3"></i>
                        <h3>Farmasi</h3>
                        <p>Buku khusus program keahlian Farmasi</p>
                        <a href="farmasi.php" class="btn btn-custom">
                            <i class="fas fa-book-open"></i> Lihat Buku
                        </a>
                    </div>
                </div>
            </div>

            <!-- Asisten Keperawatan -->
            <div class="col-md-4">
                <div class="card card-keperawatan h-100">
                    <div class="card-body">
                        <i class="fas fa-user-nurse fa-2x mb-3"></i>
                        <h3>Asisten Keperawatan</h3>
                        <p>Buku khusus program keahlian Asisten Keperawatan</p>
                        <a href="asisten_keperawatan.php" class="btn btn-custom">
                            <i class="fas fa-book-open"></i> Lihat Buku
                        </a>
                    </div>
                </div>
            </div>

            <!-- Novel -->
            <div class="col-md-4">
                <div class="card card-novel h-100">
                    <div class="card-body">
                        <i class="fas fa-book-reader fa-2x mb-3"></i>
                        <h3>Novel</h3>
                        <p>Koleksi novel dan sastra untuk menambah wawasan</p>
                        <a href="novel.php" class="btn btn-custom">
                            <i class="fas fa-book-open"></i> Lihat Buku
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Fitur Tambahan -->
    <div class="container menu-row mb-5"> <!-- mb-5 untuk jarak bawah sebelum footer -->
        <h3 class="section-title"><i class="fas fa-star"></i> Fitur Tambahan</h3>
        <div class="row g-4 justify-content-center">
            <!-- Daftar Pinjaman -->
            <div class="col-md-5 col-lg-4">
                <div class="card card-pinjaman h-100">
                    <div class="card-body">
                        <i class="fas fa-search fa-2x mb-3"></i>
                        <h3>Lihat Buku Dipinjam</h3>
                        <p>Cek buku apa saja yang sedang Anda pinjam</p>
                        <a href="daftar_pinjaman.php" class="btn btn-custom">
                            <i class="fas fa-list"></i> Lihat Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Daftar Semua Buku -->
            <div class="col-md-5 col-lg-4">
                <div class="card card-semua-buku h-100">
                    <div class="card-body">
                        <i class="fas fa-list-alt fa-2x mb-3"></i>
                        <h3>Daftar Semua Buku</h3>
                        <p>Lihat koleksi lengkap buku perpustakaan</p>
                        <a href="daftar_buku.php" class="btn btn-custom">
                            <i class="fas fa-book"></i> Lihat Koleksi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>
                <i class="fas fa-book"></i> Perpustakaan SMK Bakti Putra Mandiri
                &copy; 2025 | Sekolah Kesehatan Unggulan
            </p>
            <p>
                <i class="fas fa-map-marker-alt"></i> Jl. Kesehatan No. 123, Kota Medika
                &nbsp;|&nbsp;
                <i class="fas fa-phone"></i> (021) 1234-5678
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>