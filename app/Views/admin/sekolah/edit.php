<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f0f8ff; }
        .sidebar { background: linear-gradient(135deg, #2563eb, #1d4ed8); min-height: 305vh; color: white; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 12px 20px; border-radius: 10px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.2); color: white; }
        .form-container { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .form-label { font-weight: 500; color: #1e293b; }
        .form-label .required-star { color: #dc3545; font-weight: 700; }
        .form-control, .form-select { border-radius: 10px; border: 1px solid #e2e8f0; padding: 10px 15px; font-family: 'Poppins', sans-serif; }
        .form-control:focus, .form-select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); outline: none; }
        .btn-save { background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none; padding: 10px 30px; border-radius: 10px; font-weight: 600; color: white; transition: all 0.3s; }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(37,99,235,0.3); color: white; }
        .btn-back { background: #64748b; border: none; padding: 10px 30px; border-radius: 10px; color: white; transition: all 0.3s; }
        .btn-back:hover { background: #475569; color: white; }
        .navbar { background: white !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 10px 0; }
        .navbar-brand { font-size: 1.3rem; font-weight: 800; color: #2563eb !important; }
        .dropdown-menu { border-radius: 12px; box-shadow: var(--shadow); border: none; margin-top: 10px; }
        .dropdown-item:hover { background: #eff6ff; color: #2563eb; }
        
        .required-note { font-size: 12px; color: #64748b; margin-bottom: 15px; }
        .required-note .text-danger { font-weight: 700; }
        
        .map-wrapper {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: relative;
            margin-top: 15px;
        }
        
        .map-wrapper .map-header {
            background: #f8fafc;
            padding: 12px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .map-wrapper .map-header h6 {
            margin: 0;
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }
        
        .map-wrapper .map-header .badge-map {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
        }
        
        #map {
            height: 350px;
            width: 100%;
            background: #f0f2f5;
        }
        
        .mode-panel {
            position: absolute;
            top: 60px;
            right: 15px;
            z-index: 999;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
            padding: 10px;
            width: 160px;
        }
        
        .mode-panel button {
            width: 100%;
            text-align: left;
            margin-bottom: 6px;
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.25s ease;
            font-family: 'Poppins', sans-serif;
        }
        
        .mode-panel button:last-child { margin-bottom: 0; }
        .mode-panel button.active { background: #2563eb; color: white; }
        .mode-panel button:not(.active) { background: #f8fafc; color: #1e293b; }
        .mode-panel button:not(.active):hover { background: #eff6ff; }
        
        .coordinate-panel {
            background: #f8fafc;
            border-radius: 12px;
            padding: 15px 20px;
            border: 1px solid #e2e8f0;
            margin-top: 15px;
        }
        
        .coordinate-panel .coord-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .coordinate-panel .coord-value input {
            border: none;
            background: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            font-family: 'Courier New', monospace;
            width: 100%;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        
        .coordinate-panel .coord-value input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
            outline: none;
        }
        
        .btn-map-action {
            border-radius: 10px;
            padding: 6px 16px;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-map-action:hover { transform: translateY(-2px); }
        
        @media (max-width: 768px) {
            #map { height: 250px; }
            .mode-panel { top: 50px; right: 10px; width: 130px; padding: 8px; }
            .mode-panel button { font-size: 10px; padding: 8px 10px; }
        }
    </style>
</head>
<body>

<!-- Navbar untuk Admin (hanya profile) -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <div style="margin-left: auto;">
            <ul class="navbar-nav ms-auto">
                <!-- Dropdown Admin -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> <?= session()->get('nama_lengkap') ?>
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
                    <li class="nav-item"><a href="<?= base_url('/admin/dashboard') ?>" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a href="<?= base_url('/admin/sekolah') ?>" class="nav-link active"><i class="fas fa-school me-2"></i>Data Sekolah</a></li>
                    <?php if (session()->get('role') === 'admin_super') : ?>
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
            <div class="form-container">
                <h4 class="mb-4"><i class="fas fa-edit me-2 text-primary"></i><?= $title ?></h4>
                
                <div class="required-note">
                    <span class="text-danger">*</span> Kolom wajib diisi
                </div>
                <hr>
                
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                
                <form action="<?= base_url('/admin/sekolah/update/' . $sekolah['id']) ?>" method="post" id="formSekolah" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <!-- NPSN -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NPSN <span class="required-star">*</span></label>
                            <input type="text" name="npsn" class="form-control" value="<?= old('npsn', $sekolah['npsn']) ?>" required>
                        </div>
                        
                        <!-- Nama Sekolah -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Sekolah <span class="required-star">*</span></label>
                            <input type="text" name="nama_sekolah" class="form-control" value="<?= old('nama_sekolah', $sekolah['nama_sekolah']) ?>" required>
                        </div>
                        
                        <!-- Jenjang -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jenjang <span class="required-star">*</span></label>
                            <select name="jenjang" class="form-select" required>
                                <option value="">Pilih Jenjang</option>
                                 <option value="TK" <?= $sekolah['jenjang'] == 'TK' ? 'selected' : '' ?>>TK</option>
                                <option value="SD" <?= $sekolah['jenjang'] == 'SD' ? 'selected' : '' ?>>SD</option>
                                <option value="SMP" <?= $sekolah['jenjang'] == 'SMP' ? 'selected' : '' ?>>SMP</option>
                                
                            </select>
                        </div>
                        
                        <!-- Status -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status <span class="required-star">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="">Pilih Status</option>
                                <option value="Negeri" <?= $sekolah['status'] == 'Negeri' ? 'selected' : '' ?>>Negeri</option>
                                <option value="Swasta" <?= $sekolah['status'] == 'Swasta' ? 'selected' : '' ?>>Swasta</option>
                            </select>
                        </div>
                        
                        <!-- Akreditasi -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Akreditasi</label>
                            <select name="akreditasi" class="form-select">
                                <option value="">Pilih Akreditasi</option>
                                <option value="A" <?= $sekolah['akreditasi'] == 'A' ? 'selected' : '' ?>>A</option>
                                <option value="B" <?= $sekolah['akreditasi'] == 'B' ? 'selected' : '' ?>>B</option>
                                <option value="C" <?= $sekolah['akreditasi'] == 'C' ? 'selected' : '' ?>>C</option>
                                <option value="Unggul" <?= $sekolah['akreditasi'] == 'Unggul' ? 'selected' : '' ?>>Unggul</option>
                            </select>
                        </div>
                        
                        <!-- Kelurahan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelurahan/Desa <span class="required-star">*</span></label>
                            <input type="text" name="kelurahan" class="form-control" value="<?= old('kelurahan', $sekolah['kelurahan']) ?>" required>
                        </div>
                        
                        <!-- Tahun Berdiri -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Berdiri</label>
                            <input type="number" name="tahun_berdiri" class="form-control" value="<?= old('tahun_berdiri', $sekolah['tahun_berdiri']) ?>" min="1900" max="<?= date('Y') ?>">
                        </div>
                        
                        <!-- Alamat -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat <span class="required-star">*</span></label>
                            <textarea name="alamat" class="form-control" rows="2" required><?= old('alamat', $sekolah['alamat']) ?></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Foto Sekolah</label>
                            <?php if (!empty($sekolah['foto'])): ?>
                                <div class="mb-2">
                                    <img src="<?= base_url('uploads/sekolah/' . $sekolah['foto']) ?>" alt="Foto sekolah" style="max-height: 140px; object-fit: cover; border-radius: 10px; border: 1px solid #e2e8f0;">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto. Format JPG, PNG, atau WEBP. Maksimal 2MB.</small>
                            <input type="hidden" name="foto_lama" value="<?= esc($sekolah['foto'] ?? '') ?>">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Visi</label>
                            <textarea name="visi" class="form-control" rows="3" placeholder="Masukkan visi sekolah"><?= old('visi', $sekolah['visi'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Misi</label>
                            <textarea name="misi" class="form-control" rows="3" placeholder="Masukkan misi sekolah"><?= old('misi', $sekolah['misi'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Kontak Admin</label>
                            <input type="text" name="kontak_admin" class="form-control" value="<?= old('kontak_admin', $sekolah['kontak_admin'] ?? '') ?>" placeholder="Nomor telepon / email admin sekolah">
                        </div>
                    </div>
                    
                    <!-- ============================================ -->
                    <!-- MAP PREMIUM -->
                    <!-- ============================================ -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="map-wrapper">
                                <div class="map-header">
                                    <h6>
                                        <i class="fas fa-map-marked-alt me-2 text-primary"></i>
                                        Pilih Lokasi Sekolah <span class="required-star">*</span>
                                    </h6>
                                    <span class="badge-map">
                                        <i class="fas fa-crosshairs me-1"></i> Klik Peta
                                    </span>
                                </div>
                                
                                <div id="map"></div>
                                
                                <div class="mode-panel">
                                    <button id="btnStandard" class="active" onclick="switchMode('standard', this)">
                                        <i class="fas fa-map me-2"></i> Mode Standar
                                    </button>
                                    <button id="btnSatellite" onclick="switchMode('satellite', this)">
                                        <i class="fas fa-satellite-dish me-2"></i> Mode Satelit
                                    </button>
                                </div>
                                
                                <div class="coordinate-panel">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="coord-label">Latitude <span class="required-star">*</span></div>
                                            <div class="coord-value">
                                                <input type="text" name="latitude" id="latitude" class="form-control" value="<?= old('latitude', $sekolah['latitude'] ?? '-0.57320000') ?>" placeholder="Latitude" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="coord-label">Longitude <span class="required-star">*</span></div>
                                            <div class="coord-value">
                                                <input type="text" name="longitude" id="longitude" class="form-control" value="<?= old('longitude', $sekolah['longitude'] ?? '100.81230000') ?>" placeholder="Longitude" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="coord-label">Aksi</div>
                                            <div class="d-flex gap-2 mt-1">
                                                <button type="button" id="updateMapBtn" class="btn btn-primary btn-map-action">
                                                    <i class="fas fa-sync-alt me-1"></i> Perbarui
                                                </button>
                                                <button type="button" id="resetLocationBtn" class="btn btn-outline-secondary btn-map-action">
                                                    <i class="fas fa-undo me-1"></i> Reset
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Klik pada peta, geser marker, atau isi manual koordinat di atas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info py-3">
                                <h6 class="mb-2">Area GeoJSON Aktif</h6>
                                <?php if (!empty($geojson_layers)): ?>
                                    <ul class="mb-0 ps-3">
                                        <?php foreach ($geojson_layers as $layer): ?>
                                            <li><?= esc($layer['nama']) ?> <small class="text-muted">(<?= esc($layer['file_path']) ?>)</small></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="mb-0 text-muted">Tidak ada layer GeoJSON aktif. Aktifkan GeoJSON dari menu GeoJSON Overlay.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save me-2"></i>Update
                        </button>
                        <a href="<?= base_url('/admin/sekolah') ?>" class="btn-back">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Koordinat dari database atau default
    var defaultLat = parseFloat(document.getElementById('latitude').value) || -0.5732;
    var defaultLng = parseFloat(document.getElementById('longitude').value) || 100.8123;
    
    // ============================================
    // INISIALISASI PETA
    // ============================================
    var map = L.map('map', {
        center: [defaultLat, defaultLng],
        zoom: 11,
        zoomControl: true
    });
    
    // Layer 1: STANDAR (CARTO Light)
    var standardLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap & CARTO',
        subdomains: 'abcd',
        maxZoom: 11
    }).addTo(map);

    var activeGeojsonConfigs = <?= json_encode($geojson_layers ?? []) ?>;
    var activeGeojsonObjects = [];
    var activeGeojsonLoaded = activeGeojsonConfigs.length === 0;
    var lastValidLatLng = L.latLng(defaultLat, defaultLng);
    
    // Layer 2: SATELIT (Esri ArcGIS - Lebih stabil tanpa API Key)
    var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri',
        maxZoom: 19
    });

    // ============================================
    // SIMPAN REFERENSI LAYER
    // ============================================
    var currentLayer = 'standard';
    
    // ============================================
    // FUNGSI SWITCH MODE (SEDERHANA & PASTI BERFUNGSI)
    // ============================================
    function switchMode(mode, button) {
        // Update active button
        document.querySelectorAll('.mode-panel button').forEach(function(btn) {
            btn.classList.remove('active');
        });
        button.classList.add('active');
        
        // Hapus SEMUA layer
        map.eachLayer(function(layer) {
            // Hanya hapus tile layer, jangan hapus marker
            if (layer instanceof L.TileLayer) {
                map.removeLayer(layer);
            }
        });
        
        // Tambahkan layer yang dipilih
        if (mode === 'standard') {
            map.addLayer(standardLayer);
            currentLayer = 'standard';
            console.log('✅ Mode Standar aktif');
        } else {
            map.addLayer(satelliteLayer);
            currentLayer = 'satellite';
            console.log('✅ Mode Satelit aktif');
        }
    }
    
    // ============================================
    // MARKER
    // ============================================
    var marker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map);
    
    function pointInsideRing(point, ring) {
        var x = point.lng;
        var y = point.lat;
        var inside = false;
        for (var i = 0, j = ring.length - 1; i < ring.length; j = i++) {
            var xi = ring[i][0];
            var yi = ring[i][1];
            var xj = ring[j][0];
            var yj = ring[j][1];
            var intersect = ((yi > y) !== (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
        }
        return inside;
    }

    function pointInsidePolygon(point, polygon) {
        if (!Array.isArray(polygon) || polygon.length === 0) {
            return false;
        }
        if (!pointInsideRing(point, polygon[0])) {
            return false;
        }
        for (var i = 1; i < polygon.length; i++) {
            if (pointInsideRing(point, polygon[i])) {
                return false;
            }
        }
        return true;
    }

    function pointInsideGeojson(point, geojson) {
        if (!geojson || !geojson.type) {
            return false;
        }

        if (geojson.type === 'FeatureCollection') {
            return (geojson.features || []).some(function(feature) {
                return pointInsideGeojson(point, feature);
            });
        }

        if (geojson.type === 'Feature') {
            return pointInsideGeojson(point, geojson.geometry);
        }

        if (geojson.type === 'Polygon') {
            return pointInsidePolygon(point, geojson.coordinates);
        }

        if (geojson.type === 'MultiPolygon') {
            return (geojson.coordinates || []).some(function(polygon) {
                return pointInsidePolygon(point, polygon);
            });
        }

        return false;
    }

    function isLocationInsideActiveGeojson(lat, lng) {
        if (!activeGeojsonObjects || activeGeojsonObjects.length === 0) {
            return true;
        }
        var point = {
            lat: parseFloat(lat),
            lng: parseFloat(lng)
        };
        return activeGeojsonObjects.some(function(geojson) {
            return pointInsideGeojson(point, geojson);
        });
    }

    function setMarkerPosition(lat, lng, showMessage, updateInputs) {
        if (updateInputs === undefined) updateInputs = true;
        if (isLocationInsideActiveGeojson(lat, lng)) {
            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], 15);
            if (updateInputs) {
                updateCoordinates(lat, lng);
            }
            lastValidLatLng = L.latLng(lat, lng);
            return true;
        }

        if (showMessage !== false) {
            alert('Lokasi sekolah harus berada di dalam area GeoJSON aktif.');
        }

        if (updateInputs) {
            marker.setLatLng(lastValidLatLng);
            updateCoordinates(lastValidLatLng.lat, lastValidLatLng.lng);
        }
        return false;
    }

    function loadActiveGeojson() {
        if (!activeGeojsonConfigs || activeGeojsonConfigs.length === 0) {
            activeGeojsonLoaded = true;
            return;
        }

        var baseUrl = '<?= rtrim(base_url(), '/') ?>';
        var remaining = activeGeojsonConfigs.length;

        activeGeojsonConfigs.forEach(function(layerConfig) {
            if (!layerConfig.file_path) {
                remaining--;
                if (remaining <= 0) {
                    activeGeojsonLoaded = true;
                }
                return;
            }
            var url = baseUrl.replace(/\/+$/, '') + '/' + layerConfig.file_path.replace(/^\/+/, '');
            fetch(url)
                .then(function(res) {
                    if (!res.ok) {
                        throw new Error('HTTP error ' + res.status);
                    }
                    return res.json();
                })
                .then(function(data) {
                    activeGeojsonObjects.push(data);
                    L.geoJSON(data, {
                        style: {
                            color: layerConfig.stroke_color || '#1e293b',
                            weight: Number(layerConfig.stroke_width) || 2,
                            fillColor: layerConfig.warna || '#2563eb',
                            fillOpacity: Number(layerConfig.fill_opacity) || 0.4,
                        }
                    }).addTo(map);
                })
                .catch(function(err) {
                    console.warn('Gagal memuat GeoJSON aktif:', layerConfig.file_path, err);
                })
                .finally(function() {
                    remaining--;
                    if (remaining <= 0) {
                        activeGeojsonLoaded = true;
                    }
                });
        });
    }

    loadActiveGeojson();

    // ============================================
    // FUNGSI UPDATE KOORDINAT
    // ============================================
    function updateCoordinates(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
    }
    
    function updateMarkerFromInput() {
        var lat = parseFloat(document.getElementById('latitude').value);
        var lng = parseFloat(document.getElementById('longitude').value);
        
        if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            if (setMarkerPosition(lat, lng)) {
                alert('Koordinat berhasil diperbarui!');
            }
        } else {
            alert('Masukkan latitude (-90 s/d 90) dan longitude (-180 s/d 180) yang valid!');
        }
    }

    // ============================================
    // EVENT
    // ============================================
    updateCoordinates(defaultLat, defaultLng);
    
    // Klik pada peta
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        setMarkerPosition(lat, lng);
    });
    
    // Marker digeser
    marker.on('dragend', function(e) {
        var position = marker.getLatLng();
        setMarkerPosition(position.lat, position.lng, false);
    });
    
    // Input manual
    document.getElementById('latitude').addEventListener('input', function() {
        var lat = parseFloat(this.value);
        var lng = parseFloat(document.getElementById('longitude').value);
        if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            setMarkerPosition(lat, lng, false, false);
        }
    });
    
    document.getElementById('longitude').addEventListener('input', function() {
        var lat = parseFloat(document.getElementById('latitude').value);
        var lng = parseFloat(this.value);
        if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            setMarkerPosition(lat, lng, false, false);
        }
    });
    
    // Tombol Perbarui
    document.getElementById('updateMapBtn').addEventListener('click', function() {
        updateMarkerFromInput();
    });
    
    // Tombol Reset
    document.getElementById('resetLocationBtn').addEventListener('click', function() {
        marker.setLatLng([defaultLat, defaultLng]);
        updateCoordinates(defaultLat, defaultLng);
        map.setView([defaultLat, defaultLng], 13);
    });
    
    // Validasi form
    document.getElementById('formSekolah').addEventListener('submit', function(e) {
        var lat = document.getElementById('latitude').value;
        var lng = document.getElementById('longitude').value;
        
        if (!lat || !lng || lat === '' || lng === '') {
            e.preventDefault();
            alert('Silakan isi Latitude dan Longitude terlebih dahulu!');
            return;
        }

        if (!activeGeojsonLoaded && activeGeojsonConfigs.length > 0) {
            e.preventDefault();
            alert('Harap tunggu hingga layer GeoJSON aktif selesai dimuat.');
            return;
        }

        if (!isLocationInsideActiveGeojson(lat, lng)) {
            e.preventDefault();
            alert('Lokasi sekolah harus berada di dalam area GeoJSON aktif.');
        }
    });
</script>

</body>
</html>