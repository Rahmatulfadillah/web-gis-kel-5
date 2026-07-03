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
                <h1 class=\"section-title mb-4\">Pemetaan Sekolah <br><span class=\"text-primary\">TK, SD &amp; SMP</span></h1>
                <p class=\"section-description text-muted mb-4\">Temukan sekolah terdekat, lihat distribusi pendidikan, dan dapatkan informasi lengkap tentang fasilitas sekolah di Kabupaten Tanah Datar.</p>
                <div class=\"d-flex flex-wrap gap-3\">
                    <a href=\"<?= base_url('/peta') ?>\" class=\"btn btn-primary btn-lg rounded-pill\"><i class=\"fas fa-map me-2\"></i>Lihat Peta</a>
                    <a href=\"<?= base_url('/about') ?>\" class=\"btn btn-outline-custom btn-lg rounded-pill\"><i class=\"fas fa-info-circle me-2\"></i>Pelajari</a>
                </div>
            </div>
            <div class=\"col-lg-6 text-center\" data-aos=\"fade-left\">
                <div class=\"hero-card p-4 rounded-4 shadow-sm\">
                    <img src=\"https://cdn-icons-png.flaticon.com/512/3062/3062631.png\" alt=\"Ilustrasi pendidikan\" class=\"img-fluid\">
                </div>
            </div>
        </div>
    </div>
</section>

<section class=\"container my-5\">
    <div class=\"text-center mb-5\">
        <h2 class=\"section-title\">Statistik Sekolah</h2>
        <p class=\"section-description\">Data terkini sekolah TK, SD, dan SMP yang terdaftar di sistem.</p>
    </div>
    <div class=\"row g-4\">
        <div class=\"col-md-3\">
            <div class=\"stat-card text-center p-4\">
                <div class=\"stat-number\"><?= $total_sekolah ?? 0 ?></div>
                <p class=\"fw-bold mb-1\">Total Sekolah</p>
                <p class=\"text-muted small\">TK, SD &amp; SMP</p>
            </div>
        </div>
        <div class=\"col-md-3\">
            <div class=\"stat-card text-center p-4\">
                <div class=\"stat-number text-success\"><?= $total_tk ?? 0 ?></div>
                <p class=\"fw-bold mb-1\">Taman Kanak-Kanak</p>
                <p class=\"text-muted small\">Jumlah TK yang terdata</p>
            </div>
        </div>
        <div class=\"col-md-3\">
            <div class=\"stat-card text-center p-4\">
                <div class=\"stat-number text-danger\"><?= $total_sd ?? 0 ?></div>
                <p class=\"fw-bold mb-1\">Sekolah Dasar</p>
                <p class=\"text-muted small\">Jumlah SD yang terdata</p>
            </div>
        </div>
        <div class=\"col-md-3\">
            <div class=\"stat-card text-center p-4\">
                <div class=\"stat-number text-primary\"><?= $total_smp ?? 0 ?></div>
                <p class=\"fw-bold mb-1\">Sekolah Menengah Pertama</p>
                <p class=\"text-muted small\">Jumlah SMP yang terdata</p>
            </div>
        </div>
    </div>
</section>

<section class=\"bg-light py-5\">
    <div class=\"container\">
        <div class=\"text-center mb-5\">
            <h2 class=\"section-title\">Fitur Unggulan</h2>
            <p class=\"section-description\">Fitur yang mendukung pemeriksaan data sekolah dan navigasi.</p>
        </div>
        <div class=\"row g-4\">
            <div class=\"col-md-4\">
                <div class=\"feature-card p-4 h-100 text-center\">
                    <div class=\"feature-icon mx-auto mb-4\"><i class=\"fas fa-map-marked-alt\"></i></div>
                    <h5>Peta Interaktif</h5>
                    <p class=\"text-muted\">Menyajikan lokasi sekolah secara visual dan mudah dijelajahi.</p>
                </div>
            </div>
            <div class=\"col-md-4\">
                <div class=\"feature-card p-4 h-100 text-center\">
                    <div class=\"feature-icon mx-auto mb-4\"><i class=\"fas fa-filter\"></i></div>
                    <h5>Filter Sekolah</h5>
                    <p class=\"text-muted\">Saring sekolah berdasarkan jenjang dan status secara cepat.</p>
                </div>
            </div>
            <div class=\"col-md-4\">
                <div class=\"feature-card p-4 h-100 text-center\">
                    <div class=\"feature-icon mx-auto mb-4\"><i class=\"fas fa-info-circle\"></i></div>
                    <h5>Informasi Lengkap</h5>
                    <p class=\"text-muted\">Lengkapi detail sekolah dengan informasi penting dan kontak.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class=\"container my-5\">
    <div class=\"text-center mb-5\">
        <h2 class=\"section-title\">Peta Sebaran Sekolah</h2>
        <p class=\"section-description\">Visualisasi lokasi sekolah di seluruh Kabupaten Tanah Datar.</p>
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
    function getMarkerColor(jenjang) {
        return defaultColors[jenjang] || '#f59e0b';
    }

    function buildPopup(item) {
        const jenjang = item.jenjang || item.tingkat || '-';
        const alamat = item.alamat || 'Alamat belum tersedia';
        const akreditasi = item.akreditasi || 'Belum ada akreditasi';
        const status = item.status || 'Belum ada status';
        const detailUrl = item.id ? '<?= base_url('sekolah') ?>/' + item.id : '#';
        const color = getMarkerColor(jenjang);

        return `
            <div class=\"school-popup\">
                <div class=\"school-popup__header\" style=\"background: linear-gradient(135deg, ${color}, #2563eb);\">
                    <h6 class=\"school-popup__title\"><i class=\"fas fa-school\"></i>${item.nama_sekolah || '-'}</h6>
                    <div class=\"school-popup__badges\"><span class=\"school-popup__badge\">${jenjang}</span><span class=\"school-popup__badge\">Akreditasi ${akreditasi}</span></div>
                </div>
                <div class=\"school-popup__body\">
                    <div class=\"school-popup__section\"><div class=\"school-popup__label\"><i class=\"fas fa-map-marker-alt\"></i>Alamat</div><div class=\"school-popup__text\">${alamat}</div></div>
                    <div class=\"school-popup__section\"><div class=\"school-popup__label\"><i class=\"fas fa-info-circle\"></i>Status</div><div class=\"school-popup__text\">${status}</div></div>
                    <div class=\"school-popup__actions\"><a class=\"school-popup__btn\" href=\"${detailUrl}\" target=\"_blank\">Detail</a></div>
                </div>
            </div>
        `;
    }

    sekolahData.forEach((item) => {
        const lat = parseFloat(item.latitude);
        const lng = parseFloat(item.longitude);
        if (Number.isNaN(lat) || Number.isNaN(lng)) return;

        const marker = L.marker([lat, lng], {
            icon: L.divIcon({
                html: `<div style=\"background:${getMarkerColor(item.jenjang)};width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.2)\"><i class=\"fas fa-school\" style=\"color:white;font-size:16px\"></i></div>`,
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
"""
}

for path_str, content in views.items():
    path = Path(path_str)
    path.write_text(content, encoding='utf-8')
    print(f'Wrote {path}')
