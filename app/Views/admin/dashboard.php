<?php
// Dashboard View - Supports admin_super and admin_sekolah roles
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php if(!($is_super_admin ?? true)): ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <?php endif; ?>
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f0f8ff; }
        .sidebar { background: linear-gradient(135deg, #2563eb, #1d4ed8); min-height: calc(100vh - 70px); color: white; position: sticky; top: 70px; height: calc(100vh - 70px); overflow-y: auto; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 12px 20px; border-radius: 10px; transition: all 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.2); color: white; }
        .navbar-top { background: white; border-radius: 15px; padding: 15px 20px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .stat-card { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .stat-icon { width: 50px; height: 50px; background: rgba(37,99,235,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .stat-icon i { font-size: 24px; color: #2563eb; }
        .navbar { background: white !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 10px 20px; }

        /* ========= SCHOOL DASHBOARD ========= */
        .school-info-card { background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(37,99,235,0.08); overflow: hidden; border: 1px solid rgba(37,99,235,0.08); margin-bottom: 24px; }
        .card-header-custom { background: linear-gradient(135deg, #2563eb, #7c3aed); padding: 16px 22px; color: white; }
        .card-header-custom h5 { font-weight: 700; margin: 0; font-size: 1rem; }
        .card-body-custom { padding: 18px 22px; }
        .info-item { display: flex; align-items: flex-start; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .info-item:last-child { border-bottom: none; }
        .info-icon { width: 34px; height: 34px; border-radius: 9px; background: #eff6ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .info-icon i { color: #2563eb; font-size: 14px; }
        .info-label { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { font-size: 14px; font-weight: 600; color: #1e293b; margin-top: 2px; }
        .badge-jenjang { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
        .badge-jenjang.tk { background: linear-gradient(135deg, #10b981, #34d399); color: white; }
        .badge-jenjang.sd { background: linear-gradient(135deg, #dc2626, #ef4444); color: white; }
        .badge-jenjang.smp { background: linear-gradient(135deg, #2563eb, #60a5fa); color: white; }
        .map-card { background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(37,99,235,0.08); overflow: hidden; border: 1px solid rgba(37,99,235,0.08); margin-bottom: 24px; }
        .map-card-header { background: white; padding: 16px 22px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .map-card-header h5 { font-weight: 700; color: #1e293b; margin: 0; font-size: 1rem; }
        #schoolMap { height: 380px; width: 100%; }
        .school-foto-card { background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(37,99,235,0.08); overflow: hidden; border: 1px solid rgba(37,99,235,0.08); margin-bottom: 24px; }
        .school-foto-card img { width: 100%; height: 200px; object-fit: cover; }
        .foto-placeholder { width: 100%; height: 200px; background: linear-gradient(135deg, #e0f2fe, #eff6ff); display: flex; align-items: center; justify-content: center; flex-direction: column; color: #94a3b8; }
        .quick-btn { display: flex; align-items: center; gap: 10px; padding: 11px 18px; border-radius: 12px; font-weight: 600; font-size: 0.88rem; transition: all 0.3s; text-decoration: none; color: white; margin-bottom: 10px; }
        .quick-btn:hover { transform: translateX(4px); color: white; }
        .quick-btn.edit-btn { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
        .quick-btn.maps-btn { background: linear-gradient(135deg, #10b981, #059669); }
        .quick-btn.profil-btn { background: linear-gradient(135deg, #7c3aed, #6d28d9); }
        .leaflet-popup-content-wrapper { border-radius: 14px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important; }
        .leaflet-popup-content { font-family: 'Poppins', sans-serif !important; font-size: 13px !important; margin: 12px 16px !important; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <div style="margin-left: auto;">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <?php if(session()->get('foto') && file_exists(FCPATH . 'uploads/foto_profil/' . session()->get('foto'))): ?>
                            <img src="<?= base_url('uploads/foto_profil/' . session()->get('foto')) ?>"
                                 style="width:30px;height:30px;border-radius:50%;object-fit:cover;border:2px solid #2563eb;margin-right:6px;" alt="foto">
                        <?php else: ?>
                            <i class="fas fa-user-circle me-1"></i>
                        <?php endif; ?>
                        <?= session()->get('nama_lengkap') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url('/admin/profil') ?>"><i class="fas fa-user-circle me-2"></i>Profil Saya</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/admin/edit_profil') ?>"><i class="fas fa-edit me-2"></i>Edit Profil</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/admin/ganti_password') ?>"><i class="fas fa-key me-2"></i>Ganti Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= base_url('/auth/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid" style="margin-top: 70px;">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
            <div class="sidebar p-3">
                <h4 class="text-center mb-4"><i class="fas fa-school me-2"></i>EduMap</h4>
                <hr class="bg-light">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="<?= base_url('/admin/dashboard') ?>" class="nav-link active"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a href="<?= base_url('/admin/sekolah') ?>" class="nav-link"><i class="fas fa-school me-2"></i>Data Sekolah</a></li>
                    <?php if(session()->get('role') === 'admin_super'): ?>
                    <li class="nav-item"><a href="<?= base_url('/admin/geojson') ?>" class="nav-link"><i class="fas fa-map me-2"></i>GeoJSON Overlay</a></li>
                    <li class="nav-item"><a href="<?= base_url('/admin/users') ?>" class="nav-link"><i class="fas fa-users-cog me-2"></i>Manajemen Admin</a></li>
                    <li class="nav-item"><a href="<?= base_url('/admin/activity-logs') ?>" class="nav-link"><i class="fas fa-history me-2"></i>Log Aktivitas</a></li>
                    <?php endif; ?>
                    <hr class="bg-light">
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <?php
                $currentRole = session()->get('role');
                $roleLabel   = $currentRole === 'admin_super' ? 'Super Admin' : 'Admin Sekolah';
                $roleBadge   = $currentRole === 'admin_super' ? 'danger' : 'primary';
            ?>

            <div class="navbar-top d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard Admin</h4>
                <div>
                    <?php if(session()->get('role') === 'admin_super'): ?>
                    <a href="<?= base_url('/admin/sekolah/tambah') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus me-2"></i>Tambah Sekolah</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Welcome Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1">Halo, <?= esc(session()->get('nama_lengkap')) ?></h5>
                        <p class="text-muted mb-0">Anda masuk sebagai <span class="badge bg-<?= $roleBadge ?>"><?= esc($roleLabel) ?></span></p>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Username</div>
                        <div class="fw-semibold"><?= esc(session()->get('username')) ?></div>
                    </div>
                </div>
            </div>

            <?php if(session()->getFlashdata('success')): ?><div class="alert alert-success"><?= session()->getFlashdata('success') ?></div><?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?><div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div><?php endif; ?>

            <?php if($is_super_admin ?? true): ?>
            <!-- ===== SUPER ADMIN: Statistik TK / SD / SMP ===== -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="stat-card"><div class="d-flex justify-content-between">
                        <div><h6 class="text-muted mb-2">Total Sekolah</h6><h3 class="mb-0"><?= $total_sekolah ?? 0 ?></h3></div>
                        <div class="stat-icon"><i class="fas fa-school"></i></div>
                    </div></div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="stat-card"><div class="d-flex justify-content-between">
                        <div><h6 class="text-muted mb-2">TK</h6><h3 class="mb-0"><?= $total_tk ?? 0 ?></h3></div>
                        <div class="stat-icon"><i class="fas fa-seedling text-success"></i></div>
                    </div></div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="stat-card"><div class="d-flex justify-content-between">
                        <div><h6 class="text-muted mb-2">SD</h6><h3 class="mb-0"><?= $total_sd ?? 0 ?></h3></div>
                        <div class="stat-icon"><i class="fas fa-building text-danger"></i></div>
                    </div></div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="stat-card"><div class="d-flex justify-content-between">
                        <div><h6 class="text-muted mb-2">SMP</h6><h3 class="mb-0"><?= $total_smp ?? 0 ?></h3></div>
                        <div class="stat-icon"><i class="fas fa-building text-primary"></i></div>
                    </div></div>
                </div>
                

            </div>

            <?php else: ?>
            <!-- ===== ADMIN SEKOLAH: Info Sekolah + Peta ===== -->
            <?php $s = $sekolah_detail ?? null; ?>
            <?php if($s): ?>
            <div class="row">
                <!-- Kolom Kiri: Foto + Info + Aksi -->
                <div class="col-lg-5">

                    <!-- Foto Sekolah -->
                    <div class="school-foto-card">
                        <?php if(!empty($s['foto']) && file_exists(FCPATH . 'uploads/sekolah/' . $s['foto'])): ?>
                            <img src="<?= base_url('uploads/sekolah/' . $s['foto']) ?>" alt="<?= esc($s['nama_sekolah']) ?>">
                        <?php else: ?>
                            <div class="foto-placeholder">
                                <i class="fas fa-school fa-3x mb-2"></i>
                                <span class="small">Belum ada foto</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Informasi Sekolah -->
                    <div class="school-info-card">
                        <?php $jenjang = strtolower($s['jenjang'] ?? 'sd'); ?>
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-info-circle me-2"></i>Informasi Sekolah</h5>
                            <span class="badge-jenjang <?= $jenjang ?>"><?= esc(strtoupper($jenjang)) ?></span>
                        </div>
                        <div class="card-body-custom">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-school"></i></div>
                                <div><div class="info-label">Nama Sekolah</div><div class="info-value"><?= esc($s['nama_sekolah'] ?? '-') ?></div></div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-id-card"></i></div>
                                <div><div class="info-label">NPSN</div><div class="info-value"><?= esc($s['npsn'] ?? '-') ?></div></div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-flag"></i></div>
                                <div><div class="info-label">Status</div><div class="info-value"><?= esc($s['status'] ?? '-') ?></div></div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-star"></i></div>
                                <div><div class="info-label">Akreditasi</div><div class="info-value"><?= esc($s['akreditasi'] ?? '-') ?></div></div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div><div class="info-label">Kelurahan</div><div class="info-value"><?= esc($s['kelurahan'] ?? '-') ?></div></div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-home"></i></div>
                                <div><div class="info-label">Alamat</div><div class="info-value"><?= esc($s['alamat'] ?? '-') ?></div></div>
                            </div>
                            <?php if(!empty($s['tahun_berdiri'])): ?>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                                <div><div class="info-label">Tahun Berdiri</div><div class="info-value"><?= esc($s['tahun_berdiri']) ?></div></div>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($s['kontak_admin'])): ?>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                                <div><div class="info-label">Kontak Admin</div><div class="info-value"><?= esc($s['kontak_admin']) ?></div></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Aksi Cepat -->
                    

                </div>

                <!-- Kolom Kanan: Peta + Visi + Misi -->
                <div class="col-lg-7">

                    <!-- Peta Interaktif -->
                    <div class="map-card">
                        <div class="map-card-header">
                            <h5><i class="fas fa-map-marked-alt me-2 text-primary"></i>Lokasi Sekolah</h5>
                           
                        </div>
                        <div id="schoolMap"></div>
                    </div>

                    <!-- Visi -->
                    <?php if(!empty($s['visi'])): ?>
                    <div class="school-info-card">
                        <div class="card-header-custom"><h5><i class="fas fa-bullseye me-2"></i>Visi</h5></div>
                        <div class="card-body-custom">
                            <p class="mb-0" style="font-size:0.9rem;line-height:1.7;color:#334155;"><?= esc($s['visi']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Misi -->
                    <?php if(!empty($s['misi'])): ?>
                    <div class="school-info-card">
                        <div class="card-header-custom"><h5><i class="fas fa-book-open me-2"></i>Misi</h5></div>
                        <div class="card-body-custom">
                            <p class="mb-0" style="font-size:0.9rem;line-height:1.7;color:#334155;"><?= esc($s['misi']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

            <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Data sekolah belum ditemukan. Hubungi Super Admin untuk menghubungkan akun ini dengan data sekolah.
            </div>
            <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if(!($is_super_admin ?? true) && isset($sekolah_detail) && $sekolah_detail): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function() {
    var s = <?= json_encode($sekolah_detail) ?>;
    var geojsonLayers = <?= json_encode($geojson_layers ?? []) ?>;
    var lat = parseFloat(s.latitude) || -0.5732;
    var lng = parseFloat(s.longitude) || 100.8123;

    var map = L.map('schoolMap', { zoomControl: true }).setView([lat, lng], 15);

    // Tile: CARTO Voyager (lebih berwarna)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; CARTO',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    // Marker warna sesuai jenjang
    var colors = { TK: '#10b981', SD: '#dc2626', SMP: '#2563eb' };
    var color = colors[s.jenjang] || '#f59e0b';

    var icon = L.divIcon({
        html: '<div style="background:' + color + ';width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:4px solid white;box-shadow:0 4px 16px rgba(0,0,0,0.25);">' +
              '<i class="fas fa-school" style="color:white;font-size:18px;"></i></div>',
        iconSize: [46, 46],
        className: ''
    });

    L.marker([lat, lng], { icon: icon }).addTo(map)
        .bindPopup(
            '<b><i class="fas fa-school me-1"></i>' + (s.nama_sekolah || '-') + '</b><br>' +
            '<small class="text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i>' + (s.alamat || '-') + '</small>',
            { maxWidth: 280 }
        ).openPopup();

    // GeoJSON overlay
    if (geojsonLayers && geojsonLayers.length > 0) {
        var baseUrl = '<?= rtrim(base_url(), '/') ?>';
        geojsonLayers.forEach(function(layer) {
            fetch(baseUrl + '/' + layer.file_path)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    L.geoJSON(data, {
                        style: {
                            color: layer.stroke_color || '#1e293b',
                            weight: Number(layer.stroke_width) || 2,
                            fillColor: layer.warna || '#2563eb',
                            fillOpacity: Number(layer.fill_opacity) || 0.15
                        }
                    }).addTo(map);
                })
                .catch(function() {});
        });
    }
})();
</script>
<?php endif; ?>

</body>
</html>
