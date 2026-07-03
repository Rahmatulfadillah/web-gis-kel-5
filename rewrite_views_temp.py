from pathlib import Path

views = {
    'app/Views/home.php': """<?php $title = 'Pemetaan Sekolah - EduMap Tanah Datar'; ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class=\"hero-section py-5\">
    <div class=\"container\">
        <div class=\"row align-items-center gy-5\">
            <div class=\"col-lg-6\" data-aos=\"fade-right\">
                <span class=\"badge bg-primary mb-4 px-3 py-2\">Kabupaten Tanah Datar</span>
                <h1 class=\"section-title mb-4\">Pemetaan Sekolah <span class=\"text-primary\">TK, SD, SMP</span></h1>
                <p class=\"section-description text-muted mb-4\">Temukan lokasi sekolah dengan mudah, lihat akreditasi, dan arahkan kunjungan Anda melalui peta interaktif.</p>
                <div class=\"d-flex flex-wrap gap-3\">
                    <a href=\"<?= base_url('/peta') ?>\" class=\"btn btn-primary btn-lg rounded-pill\"><i class=\"fas fa-map me-2\"></i>Lihat Peta</a>
                    <a href=\"<?= base_url('/about') ?>\" class=\"btn btn-outline-custom btn-lg rounded-pill\"><i class=\"fas fa-info-circle me-2\"></i>Tentang EduMap</a>
                </div>
            </div>
            <div class=\"col-lg-6 text-center\" data-aos=\"fade-left\">
                <div class=\"hero-card p-4 rounded-4 shadow-sm\">
                    <img src=\"https://cdn-icons-png.flaticon.com/512/3062/3062631.png\" alt=\"Ilustrasi sekolah\" class=\"img-fluid\">
                </div>
            </div>
        </div>
    </div>
</section>

<section class=\"container my-5\">
    <div class=\"text-center mb-5\">
        <h2 class=\"section-title\">Statistik Sekolah</h2>
        <p class=\"section-description\">Data sekolah TK, SD, dan SMP yang terdaftar di sistem.</p>
    </div>
    <div class=\"row g-4\">
        <div class=\"col-md-3\">
            <div class=\"stat-card text-center p-4\">
                <div class=\"stat-number\"><?= $total_sekolah ?? 0 ?></div>
                <p class=\"fw-bold mb-1\">Total Sekolah</p>
                <p class=\"text-muted small\">TK, SD, SMP</p>
            </div>
        </div>
        <div class=\"col-md-3\">
            <div class=\"stat-card text-center p-4\">
                <div class=\"stat-number text-success\"><?= $total_tk ?? 0 ?></div>
                <p class=\"fw-bold mb-1\">Taman Kanak-Kanak</p>
                <p class=\"text-muted small\">Jumlah TK</p>
            </div>
        </div>
        <div class=\"col-md-3\">
            <div class=\"stat-card text-center p-4\">
                <div class=\"stat-number text-danger\"><?= $total_sd ?? 0 ?></div>
                <p class=\"fw-bold mb-1\">Sekolah Dasar</p>
                <p class=\"text-muted small\">Jumlah SD</p>
            </div>
        </div>
        <div class=\"col-md-3\">
            <div class=\"stat-card text-center p-4\">
                <div class=\"stat-number text-primary\"><?= $total_smp ?? 0 ?></div>
                <p class=\"fw-bold mb-1\">Sekolah Menengah Pertama</p>
                <p class=\"text-muted small\">Jumlah SMP</p>
            </div>
        </div>
    </div>
</section>

<section class=\"bg-light py-5\">
    <div class=\"container\">
        <div class=\"text-center mb-5\">
            <h2 class=\"section-title\">Fitur Utama</h2>
            <p class=\"section-description\">Akses informasi sekolah secara cepat lewat peta dan detail lengkap.</p>
        </div>
        <div class=\"row g-4\">
            <div class=\"col-md-4\">
                <div class=\"feature-card p-4 h-100 text-center\">
                    <div class=\"feature-icon mx-auto mb-4\"><i class=\"fas fa-map-marked-alt\"></i></div>
                    <h5>Peta Interaktif</h5>
                    <p class=\"text-muted\">Jelajahi posisi sekolah dengan tampilan marker yang jelas.</p>
                </div>
            </div>
            <div class=\"col-md-4\">
                <div class=\"feature-card p-4 h-100 text-center\">
                    <div class=\"feature-icon mx-auto mb-4\"><i class=\"fas fa-filter\"></i></div>
                    <h5>Filter Jenjang</h5>
                    <p class=\"text-muted\">Saring sekolah berdasarkan TK, SD, dan SMP.</p>
                </div>
            </div>
            <div class=\"col-md-4\">
                <div class=\"feature-card p-4 h-100 text-center\">
                    <div class=\"feature-icon mx-auto mb-4\"><i class=\"fas fa-info-circle\"></i></div>
                    <h5>Detail Sekolah</h5>
                    <p class=\"text-muted\">Lihat informasi lengkap dan kontak sekolah.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class=\"container my-5\">
    <div class=\"text-center mb-5\">
        <h2 class=\"section-title\">Peta Sebaran Sekolah</h2>
        <p class=\"section-description\">Visualisasi sekolah di seluruh Kabupaten Tanah Datar.</p>
    </div>
    <div class=\"map-card p-3\">
        <div id=\"homeGeojsonMap\"></div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('additionalScripts') ?>
<script src=\"https://unpkg.com/leaflet@1.9.4/dist/leaflet.js\"></script>
<script>
    const sekolahData = <?= json_encode($sekolah ?? []) ?>;
    const geojsonLayers = <?= json_encode($geojson_layers ?? []) ?>;

    const homeMap = L.map('homeGeojsonMap').setView([-0.5732, 100.8123], 11);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap',
        subdomains: 'abcd',
        maxZoom: 19,
    }).addTo(homeMap);

    const defaultColors = { TK: '#10b981', SD: '#dc2626', SMP: '#2563eb' };
    const markerColors = (level) => defaultColors[level] || '#f59e0b';

    const buildPopup = (item) => {
        const jenjang = item.jenjang || item.tingkat || '-';
        const alamat = item.alamat || 'Alamat belum tersedia';
        const akreditasi = item.akreditasi || 'Belum ada akreditasi';
        const status = item.status || 'Belum ada status';
        const detailUrl = item.id ? '<?= base_url('sekolah') ?>/' + item.id : '#';
        const color = markerColors(jenjang);

        return `
            <div class=\"school-popup\">
                <div class=\"school-popup__header\" style=\"background: linear-gradient(135deg, ${color}, #2563eb);\">
                    <h6 class=\"school-popup__title\"><i class=\"fas fa-school me-2\"></i>${item.nama_sekolah || '-'}</h6>
                    <div class=\"school-popup__badges\"><span class=\"school-popup__badge\">${jenjang}</span><span class=\"school-popup__badge\">Akreditasi ${akreditasi}</span></div>
                </div>
                <div class=\"school-popup__body\">
                    <div class=\"school-popup__section\"><div class=\"school-popup__label\"><i class=\"fas fa-map-marker-alt\"></i>Alamat</div><div class=\"school-popup__text\">${alamat}</div></div>
                    <div class=\"school-popup__section\"><div class=\"school-popup__label\"><i class=\"fas fa-info-circle\"></i>Status</div><div class=\"school-popup__text\">${status}</div></div>
                    <div class=\"school-popup__actions\"><a class=\"school-popup__btn\" href=\"${detailUrl}\">Detail Sekolah</a></div>
                </div>
            </div>
        `;
    };

    sekolahData.forEach((item) => {
        const lat = parseFloat(item.latitude);
        const lng = parseFloat(item.longitude);
        if (Number.isNaN(lat) || Number.isNaN(lng)) return;

        const marker = L.marker([lat, lng], {
            icon: L.divIcon({
                html: `<div style=\"background:${markerColors(item.jenjang)};width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.2)\"><i class=\"fas fa-school\" style=\"color:white;font-size:16px\"></i></div>`,
                iconSize: [40, 40],
                className: '',
            }),
        }).addTo(homeMap);
        marker.bindPopup(buildPopup(item), { maxWidth: 340 });
    });

    if (geojsonLayers.length) {
        geojsonLayers.forEach((layerConfig) => {
            fetch('<?= rtrim(base_url(), '/') ?>/' + layerConfig.file_path)
                .then((response) => response.json())
                .then((geojsonData) => {
                    L.geoJSON(geojsonData, {
                        style: {
                            color: layerConfig.stroke_color,
                            weight: Number(layerConfig.stroke_width) || 2,
                            fillColor: layerConfig.warna,
                            fillOpacity: Number(layerConfig.fill_opacity) || 0.35,
                        },
                    }).addTo(homeMap);
                });
        });
    }
</script>
<?= $this->endSection() ?>
""",

    'app/Views/about.php': """<?php $title = 'Tentang EduMap Lintau Buo'; ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class=\"page-header text-white\">
    <div class=\"container text-center\">
        <h1 class=\"mb-3\"><i class=\"fas fa-info-circle me-2\"></i>Tentang EduMap Lintau Buo</h1>
        <p class=\"lead mb-0\">Platform peta sekolah TK, SD, dan SMP untuk masyarakat dan pemerintah Kabupaten Tanah Datar.</p>
    </div>
</div>

<div class=\"container my-5\">
    <div class=\"row gy-4\">
        <div class=\"col-lg-8\">
            <div class=\"info-card p-5\">
                <h2 class=\"mb-4\">Misi Kami</h2>
                <p class=\"text-muted\">Memberikan akses transparan ke data sekolah dengan peta interaktif yang mudah digunakan.</p>
                <p class=\"text-muted\">Kami membantu pelajar, orang tua, dan perencana pendidikan untuk menemukan sekolah terbaik dengan informasi lokasi, akreditasi, dan fasilitas.</p>
            </div>
        </div>
        <div class=\"col-lg-4\">
            <div class=\"info-card p-5 text-center\">
                <div class=\"badge-soft py-2 px-4 mb-3\">Visi EduMap</div>
                <h5 class=\"mb-3\">Meningkatkan Akses Pendidikan</h5>
                <ul class=\"list-unstyled text-muted\">
                    <li class=\"mb-3\"><i class=\"fas fa-check-circle text-primary me-2\"></i>Data sekolah mudah dicari.</li>
                    <li class=\"mb-3\"><i class=\"fas fa-check-circle text-primary me-2\"></i>Informasi lokasi lebih transparan.</li>
                    <li><i class=\"fas fa-check-circle text-primary me-2\"></i>Memperkuat perencanaan pendidikan berbasis lokasi.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class=\"row gy-4 mt-4\">
        <div class=\"col-md-4\">
            <div class=\"value-card p-4 h-100 text-center\">
                <div class=\"feature-icon mx-auto mb-3\"><i class=\"fas fa-search-location\"></i></div>
                <h5>Cari Sekolah</h5>
                <p class=\"text-muted\">Temukan sekolah berdasar lokasi dan jenjang dengan cepat.</p>
            </div>
        </div>
        <div class=\"col-md-4\">
            <div class=\"value-card p-4 h-100 text-center\">
                <div class=\"feature-icon mx-auto mb-3\"><i class=\"fas fa-chart-line\"></i></div>
                <h5>Visual Data</h5>
                <p class=\"text-muted\">Tampilkan sebaran sekolah secara visual untuk analisis.</p>
            </div>
        </div>
        <div class=\"col-md-4\">
            <div class=\"value-card p-4 h-100 text-center\">
                <div class=\"feature-icon mx-auto mb-3\"><i class=\"fas fa-building\"></i></div>
                <h5>Evaluasi Sekolah</h5>
                <p class=\"text-muted\">Lihat detail akreditasi, fasilitas, dan data penting lain.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
""",

    'app/Views/kontak.php': """<?php $title = 'Kontak EduMap Tanah Datar'; ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class=\"page-header text-white\">
    <div class=\"container text-center\">
        <h1 class=\"mb-3\"><i class=\"fas fa-envelope me-2\"></i>Hubungi Kami</h1>
        <p class=\"lead mb-0\">Tim EduMap siap membantu Anda. Silakan ajukan pertanyaan atau laporan melalui kontak resmi.</p>
    </div>
</div>

<div class=\"container my-5\">
    <div class=\"row gy-4\">
        <div class=\"col-lg-4\">
            <div class=\"contact-card\">
                <div class=\"contact-icon mb-3\"><i class=\"fas fa-map-marker-alt\"></i></div>
                <h5 class=\"mb-3\">Alamat</h5>
                <p class=\"text-muted\">Dinas Pendidikan Kabupaten Tanah Datar</p>
                <p class=\"text-muted\">Sumatera Barat, Indonesia</p>
            </div>
        </div>
        <div class=\"col-lg-4\">
            <div class=\"contact-card\">
                <div class=\"contact-icon mb-3\"><i class=\"fas fa-phone-alt\"></i></div>
                <h5 class=\"mb-3\">Telepon</h5>
                <p class=\"text-muted\">+62 831 6189 7613</p>
                <p class=\"text-muted\">WhatsApp tersedia</p>
            </div>
        </div>
        <div class=\"col-lg-4\">
            <div class=\"contact-card\">
                <div class=\"contact-icon mb-3\"><i class=\"fas fa-envelope\"></i></div>
                <h5 class=\"mb-3\">Email</h5>
                <p class=\"text-muted\">info@lintau-buo.go.id</p>
                <p class=\"text-muted\">pendidikan@lintau-buo.go.id</p>
            </div>
        </div>
    </div>

    <div class=\"row gy-4 mt-4\">
        <div class=\"col-lg-6\">
            <div class=\"info-card p-4\">
                <h4 class=\"mb-4\">Kirim Pesan</h4>
                <form>
                    <div class=\"mb-3\">
                        <label class=\"form-label\">Nama Lengkap</label>
                        <input type=\"text\" class=\"form-control\" placeholder=\"Nama Anda\" required>
                    </div>
                    <div class=\"mb-3\">
                        <label class=\"form-label\">Email</label>
                        <input type=\"email\" class=\"form-control\" placeholder=\"Email Anda\" required>
                    </div>
                    <div class=\"mb-3\">
                        <label class=\"form-label\">Subjek</label>
                        <input type=\"text\" class=\"form-control\" placeholder=\"Subjek pesan\">
                    </div>
                    <div class=\"mb-3\">
                        <label class=\"form-label\">Pesan</label>
                        <textarea class=\"form-control\" rows=\"5\" placeholder=\"Tuliskan pesan Anda...\" required></textarea>
                    </div>
                    <button type=\"submit\" class=\"btn btn-primary btn-send w-100\"><i class=\"fas fa-paper-plane me-2\"></i>Kirim Pesan</button>
                </form>
            </div>
        </div>
        <div class=\"col-lg-6\">
            <div class=\"map-card p-0 rounded-4 overflow-hidden\">
                <iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d255109.27392030363!2d100.6004749!3d-0.5732!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4c8e5b5e5b5e5%3A0x5e5e5e5e5e5e5e5e!2sLintau%20Buo%2C%20Tanah%20Datar%2C%20West%20Sumatra!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid\" style=\"border:0; width:100%; height:100%; min-height:420px;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
""",

    'app/Views/peta/index.php': """<?php $title = 'Peta Sekolah - EduMap Tanah Datar'; ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class=\"page-header text-white\">
    <div class=\"container text-center\">
        <h1 class=\"mb-3\"><i class=\"fas fa-map-marked-alt me-2\"></i>Peta Sekolah</h1>
        <p class=\"lead mb-0\">Temukan sekolah TK, SD, dan SMP di Kabupaten Tanah Datar dengan peta interaktif yang responsif.</p>
    </div>
</div>

<div class=\"container my-5\">
    <div class=\"row gy-4\">
        <div class=\"col-lg-8\">
            <div class=\"map-card p-0 overflow-hidden\">
                <div class=\"card-header d-flex justify-content-between align-items-center bg-white border-0\">
                    <div>
                        <h5 class=\"mb-1\">Peta Sekolah</h5>
                        <p class=\"text-muted mb-0\">Klik marker untuk melihat detail setiap sekolah.</p>
                    </div>
                    <a href=\"<?= base_url('/peta/full_map') ?>\" class=\"btn btn-primary btn-fullscreen\"><i class=\"fas fa-expand me-2\"></i>Full Map</a>
                </div>
                <div id=\"map\"></div>
            </div>
        </div>
        <div class=\"col-lg-4\">
            <div class=\"info-panel p-4 mb-4\">
                <h5 class=\"info-title\">Filter Sekolah</h5>
                <div class=\"d-flex flex-wrap gap-2 mb-4\">
                    <button class=\"btn btn-filter active\" id=\"filter-all\" onclick=\"filterMarker('all', this)\">Semua</button>
                    <button class=\"btn btn-filter\" id=\"filter-tk\" onclick=\"filterMarker('TK', this)\">TK</button>
                    <button class=\"btn btn-filter\" id=\"filter-sd\" onclick=\"filterMarker('SD', this)\">SD</button>
                    <button class=\"btn btn-filter\" id=\"filter-smp\" onclick=\"filterMarker('SMP', this)\">SMP</button>
                </div>
                <h5 class=\"info-title\">Layer Aktif</h5>
                <?php if (!empty($geojson_layers)): ?>
                    <ul class=\"list-unstyled mb-0\">
                        <?php foreach ($geojson_layers as $layer): ?>
                            <li class=\"mb-2\"><i class=\"fas fa-layer-group text-primary me-2\"></i><?= esc($layer['nama']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class=\"text-muted\">Tidak ada layer GeoJSON aktif.</p>
                <?php endif; ?>
            </div>
            <div class=\"info-panel p-4\">
                <h5 class=\"info-title\">Sekolah Unggulan</h5>
                <?php if (!empty($sekolah)): ?>
                    <?php foreach (array_slice($sekolah, 0, 6) as $item): ?>
                        <div class=\"school-item\">
                            <div class=\"school-name\"><?= esc($item['nama_sekolah']) ?></div>
                            <div class=\"school-address\"><i class=\"fas fa-map-marker-alt me-1\"></i><?= esc($item['alamat']) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class=\"text-muted\">Data sekolah tidak tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('additionalScripts') ?>
<script src=\"https://unpkg.com/leaflet@1.9.4/dist/leaflet.js\"></script>
<script>
    const sekolahData = <?= json_encode($sekolah ?? []) ?>;
    const geojsonLayers = <?= json_encode($geojson_layers ?? []) ?>;
    let map;
    const markers = [];
    const colors = { TK: '#10b981', SD: '#dc2626', SMP: '#2563eb' };

    function getMarkerColor(level) {
        return colors[level] || '#f59e0b';
    }

    function buildPopup(item) {
        const jenjang = item.jenjang || item.tingkat || '-';
        const alamat = item.alamat || 'Alamat belum tersedia';
        const status = item.status || 'Belum ada status';
        const detailUrl = item.id ? '<?= base_url('sekolah') ?>/' + item.id : '#';

        return `
            <div class=\"school-popup\">
                <div class=\"school-popup__header\" style=\"background: linear-gradient(135deg, ${getMarkerColor(jenjang)}, #2563eb);\">
                    <h6 class=\"school-popup__title\"><i class=\"fas fa-school me-2\"></i>${item.nama_sekolah || '-'}</h6>
                </div>
                <div class=\"school-popup__body\">
                    <div class=\"school-popup__section\"><div class=\"school-popup__label\"><i class=\"fas fa-map-marker-alt\"></i>Alamat</div><div class=\"school-popup__text\">${alamat}</div></div>
                    <div class=\"school-popup__section\"><div class=\"school-popup__label\"><i class=\"fas fa-info-circle\"></i>Status</div><div class=\"school-popup__text\">${status}</div></div>
                    <div class=\"school-popup__actions\"><a class=\"school-popup__btn\" href=\"${detailUrl}\">Detail Sekolah</a></div>
                </div>
            </div>
        `;
    }

    function createMarker(item) {
        const lat = parseFloat(item.latitude);
        const lng = parseFloat(item.longitude);
        if (Number.isNaN(lat) || Number.isNaN(lng)) return;

        const marker = L.marker([lat, lng], {
            icon: L.divIcon({
                html: `<div style=\"background:${getMarkerColor(item.jenjang)};width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.2)\"><i class=\"fas fa-school\" style=\"color:white;font-size:16px\"></i></div>`,
                iconSize: [40, 40],
                className: '',
            }),
        }).addTo(map);

        marker.bindPopup(buildPopup(item), { maxWidth: 320 });
        markers.push({ marker, jenis: item.jenjang || item.tingkat || 'Lainnya' });
    }

    function loadGeojsonLayers() {
        geojsonLayers.forEach((layerConfig) => {
            fetch('<?= rtrim(base_url(), '/') ?>/' + layerConfig.file_path)
                .then((response) => response.json())
                .then((geojsonData) => {
                    L.geoJSON(geojsonData, {
                        style: {
                            color: layerConfig.stroke_color,
                            weight: Number(layerConfig.stroke_width) || 2,
                            fillColor: layerConfig.warna,
                            fillOpacity: Number(layerConfig.fill_opacity) || 0.35,
                        },
                    }).addTo(map);
                });
        });
    }

    function filterMarker(type, button) {
        document.querySelectorAll('.btn-filter').forEach((btn) => btn.classList.remove('active'));
        button.classList.add('active');

        markers.forEach(({ marker, jenis }) => {
            if (type === 'all' || jenis === type) {
                marker.addTo(map);
            } else {
                map.removeLayer(marker);
            }
        });
    }

    function initMap() {
        map = L.map('map').setView([-0.5732, 100.8123], 11);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap',
            subdomains: 'abcd',
            maxZoom: 19,
        }).addTo(map);

        sekolahData.forEach(createMarker);
        loadGeojsonLayers();
    }

    initMap();
</script>
<?= $this->endSection() ?>
""",

    'app/Views/peta/full_map.php': """<?php $title = 'Peta Full Map - EduMap Tanah Datar'; ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class=\"page-header text-white\">
    <div class=\"container text-center\">
        <h1 class=\"mb-3\"><i class=\"fas fa-globe me-2\"></i>Peta Full Map</h1>
        <p class=\"lead mb-0\">Mode tampilan penuh untuk memeriksa lokasi sekolah dengan kontrol standar dan satelit.</p>
    </div>
</div>

<div class=\"container-fluid px-0\">
    <div class=\"position-relative\" style=\"margin-top:-30px;\">
        <div class=\"container d-flex justify-content-center py-3\">
            <div class=\"map-card p-3 d-flex flex-wrap gap-2 justify-content-center shadow-sm\">
                <button id=\"btnStandard\" class=\"btn btn-primary btn-filter active\" onclick=\"switchMode('standard', this)\"><i class=\"fas fa-map me-2\"></i>Standar</button>
                <button id=\"btnSatellite\" class=\"btn btn-outline-custom btn-filter\" onclick=\"switchMode('satellite', this)\"><i class=\"fas fa-satellite-dish me-2\"></i>Satelit</button>
                <a class=\"btn btn-outline-custom btn-filter\" href=\"<?= base_url('/peta') ?>\"><i class=\"fas fa-arrow-left me-2\"></i>Kembali</a>
            </div>
        </div>
        <div id=\"map\" style=\"height: calc(100vh - 100px);\"></div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('additionalScripts') ?>
<script src=\"https://unpkg.com/leaflet@1.9.4/dist/leaflet.js\"></script>
<script>
    const sekolahData = <?= json_encode($sekolah ?? []) ?>;
    const geojsonLayers = <?= json_encode($geojson_layers ?? []) ?>;
    let map;
    let standardLayer;
    let satelliteLayer;

    function getMarkerColor(level) {
        return level === 'TK' ? '#10b981' : level === 'SD' ? '#dc2626' : '#2563eb';
    }

    function buildPopup(item) {
        const jenjang = item.jenjang || item.tingkat || '-';
        const alamat = item.alamat || 'Alamat belum tersedia';
        const status = item.status || 'Belum ada status';
        const detailUrl = item.id ? '<?= base_url('sekolah') ?>/' + item.id : '#';

        return `
            <div class=\"school-popup\">
                <div class=\"school-popup__header\" style=\"background: linear-gradient(135deg, ${getMarkerColor(jenjang)}, #2563eb);\">
                    <h6 class=\"school-popup__title\"><i class=\"fas fa-school me-2\"></i>${item.nama_sekolah || '-'}</h6>
                </div>
                <div class=\"school-popup__body\">
                    <div class=\"school-popup__section\"><div class=\"school-popup__label\"><i class=\"fas fa-map-marker-alt\"></i>Alamat</div><div class=\"school-popup__text\">${alamat}</div></div>
                    <div class=\"school-popup__section\"><div class=\"school-popup__label\"><i class=\"fas fa-info-circle\"></i>Status</div><div class=\"school-popup__text\">${status}</div></div>
                    <div class=\"school-popup__actions\"><a class=\"school-popup__btn\" href=\"${detailUrl}\">Detail Sekolah</a></div>
                </div>
            </div>
        `;
    }

    function createMarker(item) {
        if (!item.latitude || !item.longitude) return;
        const marker = L.marker([parseFloat(item.latitude), parseFloat(item.longitude)], {
            icon: L.divIcon({
                html: `<div style=\"background:${getMarkerColor(item.jenjang)};width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.2)\"><i class=\"fas fa-school\" style=\"color:white;font-size:16px\"></i></div>`,
                iconSize: [40, 40],
                className: '',
            }),
        }).addTo(map);
        marker.bindPopup(buildPopup(item), { maxWidth: 320 });
    }

    function loadGeojsonLayers() {
        geojsonLayers.forEach((layerConfig) => {
            fetch('<?= rtrim(base_url(), '/') ?>/' + layerConfig.file_path)
                .then((response) => response.json())
                .then((geojsonData) => {
                    L.geoJSON(geojsonData, {
                        style: {
                            color: layerConfig.stroke_color,
                            weight: Number(layerConfig.stroke_width) || 2,
                            fillColor: layerConfig.warna,
                            fillOpacity: Number(layerConfig.fill_opacity) || 0.35,
                        },
                    }).addTo(map);
                });
        });
    }

    function switchMode(mode, button) {
        document.querySelectorAll('.btn-filter').forEach((btn) => btn.classList.toggle('active', btn === button));
        if (mode === 'satellite') {
            if (map.hasLayer(standardLayer)) map.removeLayer(standardLayer);
            satelliteLayer.addTo(map);
        } else {
            if (map.hasLayer(satelliteLayer)) map.removeLayer(satelliteLayer);
            standardLayer.addTo(map);
        }
    }

    function initMap() {
        map = L.map('map').setView([-0.5732, 100.8123], 12);
        standardLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap',
            subdomains: 'abcd',
            maxZoom: 19,
        });
        satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri',
            maxZoom: 19,
        });
        standardLayer.addTo(map);
        (sekolahData || []).forEach(createMarker);
        loadGeojsonLayers();
    }

    initMap();
</script>
<?= $this->endSection() ?>
""",

    'app/Views/sekolah/detail.php': """<?php $title = isset($sekolah['nama_sekolah']) ? esc($sekolah['nama_sekolah']) . ' - Detail Sekolah' : 'Detail Sekolah'; ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class=\"container py-5\">
    <div class=\"row gy-4\">
        <div class=\"col-lg-8\">
            <div class=\"hero-card overflow-hidden rounded-4 shadow-sm\">
                <?php if (!empty($sekolah['foto'])): ?>
                    <img src=\"<?= base_url('uploads/sekolah/' . $sekolah['foto']) ?>\" alt=\"Foto sekolah\" class=\"img-fluid\">
                <?php else: ?>
                    <img src=\"<?= base_url('uploads/default-school.png') ?>\" alt=\"Foto sekolah\" class=\"img-fluid\">
                <?php endif; ?>
            </div>
        </div>
        <div class=\"col-lg-4\">
            <div class=\"info-card p-4 h-100\">
                <h3 class=\"fw-bold mb-3\"><?= esc($sekolah['nama_sekolah'] ?? '-') ?></h3>
                <div class=\"d-flex flex-wrap gap-2 mb-3\">
                    <span class=\"badge badge-soft rounded-pill px-3 py-2\"><i class=\"fas fa-layer-group me-1\"></i><?= esc($sekolah['jenjang'] ?? '-') ?></span>
                    <span class=\"badge badge-soft rounded-pill px-3 py-2\"><i class=\"fas fa-medal me-1\"></i>Akreditasi <?= esc($sekolah['akreditasi'] ?? '-') ?></span>
                </div>
                <p class=\"text-muted mb-3\"><i class=\"fas fa-map-marker-alt me-2\"></i><?= esc($sekolah['alamat'] ?? '-') ?></p>
                <ul class=\"list-unstyled text-muted mb-0\">
                    <li class=\"mb-3\"><strong>NPSN:</strong> <?= esc($sekolah['npsn'] ?? '-') ?></li>
                    <li class=\"mb-3\"><strong>Kelurahan:</strong> <?= esc($sekolah['kelurahan'] ?? '-') ?></li>
                    <li class=\"mb-3\"><strong>Tahun Berdiri:</strong> <?= esc($sekolah['tahun_berdiri'] ?? '-') ?></li>
                    <li class=\"mb-3\"><strong>Kontak Admin:</strong> <?= esc($sekolah['kontak_admin'] ?? '-') ?></li>
                    <li><strong>Koordinat:</strong> <?= esc($sekolah['latitude'] ?? '-') ?>, <?= esc($sekolah['longitude'] ?? '-') ?></li>
                </ul>
            </div>
        </div>
        <div class=\"col-12\">
            <div class=\"row g-4\">
                <div class=\"col-md-6\">
                    <div class=\"info-card p-4 h-100\">
                        <h5 class=\"fw-bold mb-3\">Visi Sekolah</h5>
                        <p class=\"text-muted mb-0\"><?= nl2br(esc($sekolah['visi'] ?? 'Belum ada visi.')) ?></p>
                    </div>
                </div>
                <div class=\"col-md-6\">
                    <div class=\"info-card p-4 h-100\">
                        <h5 class=\"fw-bold mb-3\">Misi Sekolah</h5>
                        <p class=\"text-muted mb-0\"><?= nl2br(esc($sekolah['misi'] ?? 'Belum ada misi.')) ?></p>
                    </div>
                </div>
            </div>
            <div class=\"mt-4\">
                <a href=\"<?= base_url('/peta') ?>\" class=\"btn btn-outline-custom rounded-pill\"><i class=\"fas fa-arrow-left me-2\"></i>Kembali ke Peta</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
""",
}

for path, content in views.items():
    filename = Path(path)
    filename.write_text(content, encoding='utf-8')
    print(f'Updated {filename}')
