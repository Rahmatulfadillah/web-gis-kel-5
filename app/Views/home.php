<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemetaan Sekolah - Dinas Pendidikan Kabupaten Tanah Datar</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Poppins', sans-serif; 
            background: #f0f8ff; 
            color: #1e293b; 
            margin-top: 70px; 
            overflow-x: hidden;
        }
        
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #60a5fa;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --white: #ffffff;
            --shadow: 0 10px 40px rgba(0,0,0,0.08);
            --shadow-hover: 0 20px 60px rgba(37,99,235,0.15);
        }
        
        /* Navbar */
        .navbar {
            background: rgba(255,255,255,0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
            padding: 12px 0;
            transition: all 0.3s;
        }
        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary) !important;
            transition: all 0.3s;
        }
        .navbar-brand:hover {
            transform: scale(1.05);
        }
        .nav-link {
            font-weight: 500;
            margin: 0 8px;
            color: var(--text-dark) !important;
            transition: all 0.3s;
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: all 0.3s;
            transform: translateX(-50%);
        }
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 80%;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
        }
        .dropdown-menu {
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: none;
            margin-top: 10px;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dropdown-item:hover {
            background: #eff6ff;
            color: var(--primary);
        }
        
        /* Hero */
        .hero {
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
            min-height: 100vh;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(37,99,235,0.05) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
        }
        .hero-title span { 
            color: var(--primary);
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border: none;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(37,99,235,0.3);
        }
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(37,99,235,0.4);
            color: white;
        }
        .btn-outline-custom {
            background: transparent;
            border: 2px solid var(--primary);
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 600;
            color: var(--primary);
            transition: all 0.3s;
        }
        .btn-outline-custom:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(37,99,235,0.2);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation { animation: float 4s ease-in-out infinite; }
        
        /* Statistics */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 35px 20px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            transform: scaleX(0);
            transition: transform 0.4s;
        }
        .stat-card:hover::before {
            transform: scaleX(1);
        }
        .stat-card:hover { 
            transform: translateY(-10px); 
            box-shadow: var(--shadow-hover);
        }
        .stat-number { 
            font-size: 2.8rem; 
            font-weight: 800; 
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2rem;
        }
        .stat-icon.blue { background: #eff6ff; color: var(--primary); }
        .stat-icon.green { background: #ecfdf5; color: #10b981; }
        .stat-icon.red { background: #fef2f2; color: #dc2626; }
        .stat-icon.purple { background: #f5f3ff; color: #8b5cf6; }
        
        /* Features */
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 25px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .feature-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            transform: scaleX(0);
            transition: transform 0.4s;
        }
        .feature-card:hover::after {
            transform: scaleX(1);
        }
        .feature-card:hover { 
            transform: translateY(-10px); 
            box-shadow: var(--shadow-hover);
        }
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 2rem;
            transition: all 0.4s;
        }
        .feature-card:hover .feature-icon {
            transform: rotateY(180deg);
        }
        
        /* Map */
        .map-card-custom {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: all 0.3s;
        }
        .map-card-custom:hover {
            box-shadow: var(--shadow-hover);
        }
        .map-card-custom .card-body {
            padding: 20px;
        }
        #homeGeojsonMap {
            width: 100%;
            min-height: 450px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        
        /* Leaflet Popup - FIXED IMAGE */
        .leaflet-popup-content-wrapper {
            border-radius: 18px !important;
            box-shadow: 0 14px 40px rgba(0,0,0,0.16) !important;
            border: 1px solid rgba(37,99,235,0.10) !important;
            overflow: hidden !important;
        }
        .leaflet-popup-content {
            font-family: 'Poppins', sans-serif !important;
            font-size: 13px !important;
            margin: 0 !important;
            padding: 0 !important;
            min-width: 320px !important;
        }
        .school-popup { 
            min-width: 320px; 
            max-width: 390px; 
            background: #fff; 
            border-radius: 18px; 
            overflow: hidden; 
            box-shadow: 0 10px 30px rgba(15,23,42,0.12); 
        }
        .school-popup__image-wrapper {
            width: 100%;
            height: 160px;
            background: linear-gradient(135deg, #e0f2fe, #f8fafc);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .school-popup__image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .school-popup__image-placeholder {
            font-size: 3rem;
            color: #94a3b8;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .school-popup__image-placeholder i {
            font-size: 4rem;
            color: #cbd5e1;
        }
        .school-popup__image-placeholder span {
            font-size: 0.8rem;
            color: #94a3b8;
        }
        .school-popup__header { 
            padding: 14px 16px 12px; 
            color: white; 
            background: linear-gradient(135deg, #2563eb, #3b82f6); 
        }
        .school-popup__title { 
            font-size: 1rem; 
            font-weight: 700; 
            margin: 0; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
        }
        .school-popup__badges { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 8px; 
            margin-top: 8px; 
        }
        .school-popup__badge { 
            display: inline-block; 
            padding: 5px 10px; 
            border-radius: 999px; 
            font-size: 0.75rem; 
            font-weight: 700; 
            background: rgba(255,255,255,0.22); 
            color: #fff; 
        }
        .school-popup__body { 
            padding: 14px 16px 16px; 
            background: #fff; 
        }
        .school-popup__section { 
            margin-bottom: 10px; 
        }
        .school-popup__label { 
            display: flex; 
            align-items: center; 
            gap: 6px; 
            font-size: 0.75rem; 
            font-weight: 700; 
            color: #2563eb; 
            margin-bottom: 4px; 
            text-transform: uppercase; 
            letter-spacing: 0.04em; 
        }
        .school-popup__text { 
            font-size: 0.85rem; 
            color: #334155; 
            line-height: 1.45; 
        }
        .school-popup__actions { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 8px; 
            margin-top: 12px; 
        }
        .school-popup__btn { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            gap: 6px; 
            padding: 8px 10px; 
            border-radius: 999px; 
            font-size: 0.8rem; 
            font-weight: 600; 
            text-decoration: none; 
            color: #fff; 
            background: linear-gradient(135deg, #2563eb, #3b82f6); 
            transition: all 0.3s;
            border: none;
        }
        .school-popup__btn:hover { 
            color: #fff; 
            opacity: 0.9;
            transform: translateY(-2px);
        }
        .school-popup__btn.secondary { 
            background: linear-gradient(135deg, #0f766e, #14b8a6); 
        }
        
        /* Loading Spinner */
        .map-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background: rgba(255,255,255,0.9);
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }
        .map-wrapper {
            position: relative;
        }
        
        /* Footer */
        footer {
            background: var(--text-dark);
            padding: 50px 0 20px;
            color: white;
        }
        footer a:hover {
            color: var(--primary-light) !important;
            transition: all 0.3s;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title { font-size: 2rem; }
            body { margin-top: 60px; }
            #homeGeojsonMap { min-height: 320px; }
            .stat-number { font-size: 2rem; }
            .leaflet-popup-content { min-width: 280px !important; }
            .school-popup { min-width: 280px; }
        }
        @media (max-width: 576px) {
            .hero { padding: 100px 0 60px; }
            .hero-title { font-size: 1.8rem; }
            .btn-primary-custom, .btn-outline-custom { 
                padding: 10px 25px; 
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar Dinamis -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('/') ?>">
            <i class="fas fa-school me-2"></i><strong>EduMap Lintau Buo</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('/') ?>">
                        <i class="fas fa-home me-1"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/peta') ?>">
                        <i class="fas fa-map-marked-alt me-1"></i>Peta Sekolah
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/about') ?>">
                        <i class="fas fa-info-circle me-1"></i>Tentang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/kontak') ?>">
                        <i class="fas fa-envelope me-1"></i>Kontak
                    </a>
                </li>
                
                <?php if(session()->get('isLoggedIn')): ?>
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" style="gap: 5px;">
                            <i class="fas fa-user-circle me-1" style="font-size: 1.2rem;"></i>
                            <?= session()->get('nama_lengkap') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('/admin/dashboard') ?>">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= base_url('/auth/logout') ?>">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-2">
                        <a class="nav-link btn btn-primary text-white px-3 py-1 rounded-pill" href="<?= base_url('/auth/login') ?>" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                <span class="badge bg-primary mb-4 px-3 py-2">
                    <i class="fas fa-map-pin me-1"></i> Kabupaten Tanah Datar
                </span>
                <h1 class="hero-title mb-4">
                    Pemetaan Sekolah <br><span>TK, SD & SMP</span>
                </h1>
                <p class="mb-4 text-secondary" style="font-size: 1.1rem;">
                    Temukan lokasi sekolah terdekat, lihat distribusi pendidikan, dan dapatkan informasi lengkap tentang fasilitas pendidikan di Kabupaten Tanah Datar.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= base_url('/peta') ?>" class="btn btn-primary-custom">
                        <i class="fas fa-map me-2"></i>Lihat Peta
                    </a>
                    <a href="<?= base_url('/about') ?>" class="btn btn-outline-custom">
                        <i class="fas fa-info-circle me-2"></i>Pelajari
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <div class="float-animation">
                    <img src="https://cdn-icons-png.flaticon.com/512/3062/3062631.png" alt="Education" class="img-fluid" style="max-width: 350px; width: 80%;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="container my-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="fw-bold">Statistik Sekolah</h2>
        <p class="text-muted">Data terkini SD dan SMP di Kabupaten Tanah Datar</p>
        <div class="mx-auto" style="width: 60px; height: 3px; background: linear-gradient(90deg, var(--primary), var(--primary-light)); border-radius: 2px;"></div>
    </div>
    <div class="row g-4">
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="0">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-school"></i>
                </div>
                <div class="stat-number"><?= $total_sekolah ?? 0 ?></div>
                <h5 class="mt-2">Total Sekolah</h5>
                <p class="text-muted small">TK, SD & SMP</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-seedling"></i>
                </div>
                <div class="stat-number"><?= $total_tk ?? 0 ?></div>
                <h5 class="mt-2">Taman Kanak-Kanak</h5>
                <p class="text-muted small">Jumlah TK terdata</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-number"><?= $total_sd ?? 0 ?></div>
                <h5 class="mt-2">Sekolah Dasar</h5>
                <p class="text-muted small">Jumlah SD terdata</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-school"></i>
                </div>
                <div class="stat-number"><?= $total_smp ?? 0 ?></div>
                <h5 class="mt-2">SMP</h5>
                <p class="text-muted small">Jumlah SMP terdata</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">Fitur Unggulan</h2>
            <p class="text-muted">Kemudahan akses informasi pendidikan</p>
            <div class="mx-auto" style="width: 60px; height: 3px; background: linear-gradient(90deg, var(--primary), var(--primary-light)); border-radius: 2px;"></div>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-map-marked-alt"></i></div>
                    <h5>Peta Interaktif</h5>
                    <p class="text-muted">Lihat lokasi sekolah secara realtime dengan peta yang responsif dan mudah digunakan.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-filter"></i></div>
                    <h5>Filter Sekolah</h5>
                    <p class="text-muted">Filter berdasarkan tingkat pendidikan untuk menemukan sekolah yang Anda cari.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-info-circle"></i></div>
                    <h5>Informasi Lengkap</h5>
                    <p class="text-muted">Detail alamat, visi, misi, dan informasi penting lainnya tentang setiap sekolah.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="container my-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="fw-bold">🗺️ Peta Sebaran Sekolah</h2>
        <p class="text-muted">Lokasi sekolah TK, SD dan SMP di Kabupaten Tanah Datar</p>
        <div class="mx-auto" style="width: 60px; height: 3px; background: linear-gradient(90deg, var(--primary), var(--primary-light)); border-radius: 2px;"></div>
    </div>
    <div class="map-card-custom" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body">
            <div class="map-wrapper">
                <div id="homeGeojsonMap"></div>
                <div id="mapLoading" class="map-loading" style="display: none;">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 mb-0">Memuat peta...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <a class="text-white text-decoration-none fw-bold fs-5" href="#">
                    <i class="fas fa-school me-2"></i>EduMap Kabupaten Tanah Datar
                </a>
                <p class="text-white-50 mt-3 small">Memudakan akses informasi lokasi pendidikan di Kabupaten Tanah Datar.</p>
                <div class="mt-3">
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white-50"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <h5 class="text-white">Link Cepat</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= base_url('/') ?>" class="text-white-50 text-decoration-none small">Beranda</a></li>
                    <li><a href="<?= base_url('/peta') ?>" class="text-white-50 text-decoration-none small">Peta Sekolah</a></li>
                    <li><a href="<?= base_url('/about') ?>" class="text-white-50 text-decoration-none small">Tentang</a></li>
                    <li><a href="<?= base_url('/kontak') ?>" class="text-white-50 text-decoration-none small">Kontak</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h5 class="text-white">Jam Pelayanan</h5>
                <ul class="list-unstyled text-white-50 small">
                    <li><i class="fas fa-clock me-2"></i>Senin - Kamis: 08:00 - 16:00</li>
                    <li><i class="fas fa-clock me-2"></i>Jumat: 08:00 - 15:30</li>
                    <li><i class="fas fa-clock me-2"></i>Sabtu - Minggu: Tutup</li>
                </ul>
            </div>
        </div>
        <hr class="bg-secondary mt-4">
        <div class="text-center small text-white-50">
            &copy; <?= date('Y') ?> Dinas Pendidikan Kabupaten Tanah Datar. Built with <i class="fas fa-heart text-danger"></i> for Education
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // Initialize AOS
    AOS.init({ 
        duration: 800, 
        once: true,
        easing: 'ease-out-cubic'
    });

    // ============================================
    // DATA DARI CONTROLLER
    // ============================================
    var sekolahData = <?= json_encode($sekolah ?? []) ?>;
    var geojsonLayers = <?= json_encode($geojson_layers ?? []) ?>;
    var baseUrl = '<?= rtrim(base_url(), '/') ?>';
    
    // ============================================
    // INISIALISASI PETA
    // ============================================
    var homeMap = L.map('homeGeojsonMap', {
        center: [-0.5732, 100.8123],
        zoom: 11,
        zoomControl: true,
        fadeAnimation: true,
        zoomAnimation: true
    });

    // Base Layer
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        subdomains: 'abcd',
        maxZoom: 19,
        minZoom: 8
    }).addTo(homeMap);

    // ============================================
    // BUAT MARKER SEKOLAH - FIXED IMAGE HANDLING
    // ============================================
    function getJenjangLabel(sekolah) {
        return sekolah.jenjang || sekolah.tingkat || '-';
    }

    function getMarkerColor(jenjang) {
        if (jenjang === 'TK') return '#10b981';
        if (jenjang === 'SD') return '#dc2626';
        if (jenjang === 'SMP') return '#2563eb';
        return '#f59e0b';
    }

    function getMarkerIcon(jenjang) {
        var color = getMarkerColor(jenjang);
        return L.divIcon({
            html: '<div style="background:' + color + ';width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:0 2px 12px rgba(0,0,0,0.25);transition:all 0.3s;"><i class="fas fa-school" style="color:white;font-size:16px;"></i></div>',
            iconSize: [36, 36],
            className: 'home-marker',
            popupAnchor: [0, -18]
        });
    }

    function getImageUrl(sekolah) {
        // Cek apakah ada foto
        if (sekolah.foto && sekolah.foto !== '') {
            // Cek apakah foto sudah berupa URL lengkap
            if (sekolah.foto.startsWith('http://') || sekolah.foto.startsWith('https://')) {
                return sekolah.foto;
            }
            // Cek apakah foto dimulai dengan 'uploads/'
            if (sekolah.foto.startsWith('uploads/')) {
                return baseUrl + '/' + sekolah.foto;
            }
            // Default: foto di folder uploads/sekolah
            return baseUrl + '/uploads/sekolah/' + sekolah.foto;
        }
        return null;
    }

    function buildPopupContent(sekolah) {
        var jenjang = getJenjangLabel(sekolah);
        var alamat = sekolah.alamat || 'Alamat belum tersedia';
        var status = sekolah.status || 'Belum ada status';
        var akreditasi = sekolah.akreditasi || 'Belum ada akreditasi';
        var visi = sekolah.visi || 'Belum ada visi';
        var misi = sekolah.misi || 'Belum ada misi';
        var kontak = sekolah.kontak_admin || 'Belum ada kontak';
        var lat = sekolah.latitude || '-';
        var lng = sekolah.longitude || '-';
        var color = getMarkerColor(jenjang);
        
        // Handle image dengan lebih baik
        var imageUrl = getImageUrl(sekolah);
        var imageHtml = '';
        
        if (imageUrl) {
            imageHtml = '<img class="school-popup__image" src="' + imageUrl + '" alt="' + (sekolah.nama_sekolah || 'Sekolah') + '" onerror="this.style.display=\'none\';this.parentElement.querySelector(\'.school-popup__image-placeholder\').style.display=\'flex\';">';
        }
        
        var detailUrl = sekolah.id ? baseUrl + '/sekolah/' + sekolah.id : '#';
        var mapsUrl = 'https://www.google.com/maps?q=' + lat + ',' + lng;

        return '<div class="school-popup">' +
            '<div class="school-popup__image-wrapper">' +
                imageHtml +
                '<div class="school-popup__image-placeholder" style="' + (imageUrl ? 'display:none;' : 'display:flex;') + '">' +
                    '<i class="fas fa-school"></i>' +
                    '<span>' + (sekolah.nama_sekolah || 'Sekolah') + '</span>' +
                '</div>' +
            '</div>' +
            '<div class="school-popup__header" style="background: linear-gradient(135deg, ' + color + ', #2563eb);">' +
                '<h6 class="school-popup__title"><i class="fas fa-school"></i>' + (sekolah.nama_sekolah || '-') + '</h6>' +
                '<div class="school-popup__badges"><span class="school-popup__badge">' + jenjang + '</span><span class="school-popup__badge">Akreditasi ' + akreditasi + '</span></div>' +
            '</div>' +
            '<div class="school-popup__body">' +
                '<div class="school-popup__section"><div class="school-popup__label"><i class="fas fa-map-marker-alt"></i>Alamat</div><div class="school-popup__text">' + alamat + '</div></div>' +
                '<div class="school-popup__section"><div class="school-popup__label"><i class="fas fa-bullseye"></i>Visi</div><div class="school-popup__text">' + visi + '</div></div>' +
                '<div class="school-popup__section"><div class="school-popup__label"><i class="fas fa-book-open"></i>Misi</div><div class="school-popup__text">' + misi + '</div></div>' +
                '<div class="school-popup__section"><div class="school-popup__label"><i class="fas fa-phone-alt"></i>Kontak Admin</div><div class="school-popup__text">' + kontak + '</div></div>' +
                '<div class="school-popup__section"><div class="school-popup__label"><i class="fas fa-info-circle"></i>Status</div><div class="school-popup__text">' + status + '</div></div>' +
                '<div class="school-popup__actions">' +
                    '<a class="school-popup__btn" href="' + detailUrl + '" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt"></i>Detail Sekolah</a>' +
                    '<a class="school-popup__btn secondary" href="' + mapsUrl + '" target="_blank" rel="noopener noreferrer"><i class="fas fa-map-location-dot"></i>Google Maps</a>' +
                '</div>' +
            '</div>' +
        '</div>';
    }

    function createSchoolMarkers(data) {
        if (!data || data.length === 0) {
            console.log('Tidak ada data sekolah');
            return;
        }

        var validMarkers = 0;
        data.forEach(function(sekolah) {
            if (!sekolah.latitude || !sekolah.longitude) return;
            
            var lat = parseFloat(sekolah.latitude);
            var lng = parseFloat(sekolah.longitude);
            
            if (isNaN(lat) || isNaN(lng)) return;
            
            var jenjang = getJenjangLabel(sekolah);
            var icon = getMarkerIcon(jenjang);
            
            var marker = L.marker([lat, lng], { 
                icon: icon,
                riseOnHover: true
            }).addTo(homeMap);
            
            marker.bindPopup(buildPopupContent(sekolah), { 
                maxWidth: 320,
                className: 'custom-popup'
            });
            
            validMarkers++;
        });
        
        console.log('✅ Berhasil menampilkan ' + validMarkers + ' marker sekolah');
    }

    // ============================================
    // LOAD GEOJSON LAYERS
    // ============================================
    function loadGeojsonLayers() {
        if (!geojsonLayers || geojsonLayers.length === 0) {
            var info = L.control({ position: 'topright' });
            info.onAdd = function () {
                var div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                div.style.background = 'white';
                div.style.padding = '10px 15px';
                div.style.borderRadius = '10px';
                div.style.boxShadow = '0 2px 12px rgba(0,0,0,0.08)';
                div.style.fontSize = '0.85rem';
                div.innerHTML = '<i class="fas fa-info-circle text-primary me-1"></i> Tidak ada overlay GeoJSON aktif.';
                return div;
            };
            info.addTo(homeMap);
            return;
        }

        var boundsList = [];
        var loadedLayers = 0;

        geojsonLayers.forEach(function (layerConfig) {
            var url = baseUrl + '/' + layerConfig.file_path;
            
            fetch(url)
                .then(function (response) {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(function (geojsonData) {
                    var geojsonLayer = L.geoJSON(geojsonData, {
                        style: {
                            color: layerConfig.stroke_color || '#1e293b',
                            weight: Number(layerConfig.stroke_width) || 2,
                            fillColor: layerConfig.warna || '#2563eb',
                            fillOpacity: Number(layerConfig.fill_opacity) || 0.4,
                        },
                        onEachFeature: function (feature, layer) {
                            if (feature.properties && feature.properties.name) {
                                layer.bindTooltip(feature.properties.name, {
                                    permanent: false,
                                    direction: 'center',
                                    className: 'geojson-tooltip'
                                });
                            }
                        }
                    }).addTo(homeMap);

                    if (geojsonLayer.getBounds && geojsonLayer.getBounds().isValid()) {
                        boundsList.push(geojsonLayer.getBounds());
                    }
                    
                    loadedLayers++;
                    
                    if (loadedLayers === geojsonLayers.length && boundsList.length > 0) {
                        var merged = boundsList[0];
                        for (var i = 1; i < boundsList.length; i++) {
                            merged = merged.extend(boundsList[i]);
                        }
                        homeMap.fitBounds(merged.pad(0.1));
                    }
                })
                .catch(function (error) {
                    console.warn('❌ Gagal memuat layer GeoJSON:', layerConfig.file_path, error);
                });
        });
    }

    // ============================================
    // EKSEKUSI
    // ============================================
    document.getElementById('mapLoading').style.display = 'block';

    loadGeojsonLayers();

    setTimeout(function() {
        createSchoolMarkers(sekolahData);
        document.getElementById('mapLoading').style.display = 'none';
    }, 800);

    window.addEventListener('resize', function() {
        setTimeout(function() {
            homeMap.invalidateSize();
        }, 200);
    });

    console.log('✅ EduMap Lintau Buo siap digunakan!');
    console.log('📊 Jumlah data sekolah:', sekolahData.length);
</script>

</body>
</html>