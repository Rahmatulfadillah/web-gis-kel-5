<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - EduMap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .hero-card { background: linear-gradient(135deg, #2563eb, #3b82f6); color: white; border-radius: 24px; overflow: hidden; }
        .hero-card img { width: 100%; height: 280px; object-fit: cover; opacity: 0.9; }
        .info-card { background: white; border-radius: 18px; box-shadow: 0 8px 24px rgba(15,23,42,0.06); }
        .badge-soft { background: #eff6ff; color: #2563eb; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="hero-card shadow">
                <?php if (!empty($sekolah['foto'])): ?>
                    <img src="<?= base_url('uploads/sekolah/' . $sekolah['foto']) ?>" alt="Foto sekolah">
                <?php else: ?>
                    <img src="<?= base_url('uploads/default-school.png') ?>" alt="Foto sekolah default">
                <?php endif; ?>
                <div class="p-4">
                    <h1 class="h3 fw-bold mb-2"><?= esc($sekolah['nama_sekolah'] ?? '-') ?></h1>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge badge-soft rounded-pill px-3 py-2"><i class="fas fa-layer-group me-1"></i><?= esc($sekolah['jenjang'] ?? '-') ?></span>
                        <span class="badge badge-soft rounded-pill px-3 py-2"><i class="fas fa-medal me-1"></i>Akreditasi <?= esc($sekolah['akreditasi'] ?? '-') ?></span>
                        <span class="badge badge-soft rounded-pill px-3 py-2"><i class="fas fa-info-circle me-1"></i><?= esc($sekolah['status'] ?? '-') ?></span>
                    </div>
                    <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i><?= esc($sekolah['alamat'] ?? '-') ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="info-card p-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-circle-info me-2 text-primary"></i>Informasi Sekolah</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3"><strong>NPSN</strong><br><?= esc($sekolah['npsn'] ?? '-') ?></li>
                    <li class="mb-3"><strong>Kelurahan</strong><br><?= esc($sekolah['kelurahan'] ?? '-') ?></li>
                    <li class="mb-3"><strong>Tahun Berdiri</strong><br><?= esc($sekolah['tahun_berdiri'] ?? '-') ?></li>
                    <li class="mb-3"><strong>Kontak Admin</strong><br><?= esc($sekolah['kontak_admin'] ?? '-') ?></li>
                    <li><strong>Koordinat</strong><br><?= esc($sekolah['latitude'] ?? '-') ?>, <?= esc($sekolah['longitude'] ?? '-') ?></li>
                </ul>
            </div>
        </div>

        <div class="col-12">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="info-card p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-bullseye me-2 text-primary"></i>Visi</h5>
                        <p class="mb-0 text-muted"><?= nl2br(esc($sekolah['visi'] ?? 'Belum ada visi.')) ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-book-open me-2 text-primary"></i>Misi</h5>
                        <p class="mb-0 text-muted"><?= nl2br(esc($sekolah['misi'] ?? 'Belum ada misi.')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="<?= base_url('/peta') ?>" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-2"></i>Kembali ke Peta</a>
    </div>
</div>
</body>
</html>
