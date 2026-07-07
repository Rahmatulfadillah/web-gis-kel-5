<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;
use App\Models\GeojsonConfigModel;

class Geojson extends BaseController
{
    private function checkAccess()
    {
        $role = session()->get('role');
        if (!session()->get('isLoggedIn') || !in_array($role, ['admin_super', 'admin_sekolah', 'admin'], true)) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login sebagai admin!');
        }

        return null;
    }

    private function checkSuperAdmin()
    {
        $role = session()->get('role');
        if (!session()->get('isLoggedIn') || $role !== 'admin_super') {
            return redirect()->to('/auth/login')->with('error', 'Akses hanya untuk Super Admin!');
        }

        return null;
    }

    public function index()
    {
        $redirect = $this->checkAccess();
        if ($redirect) {
            return $redirect;
        }
        
        $model = new GeojsonConfigModel();
        $data['geojson'] = $model->orderBy('id', 'DESC')->findAll();
        $data['title'] = 'GeoJSON Overlay';
        
        return view('admin/geojson/index', $data);
    }
    
    public function tambah()
    {
        $redirect = $this->checkAccess();
        if ($redirect) {
            return $redirect;
        }
        
        $data['title'] = 'Tambah GeoJSON';
        return view('admin/geojson/tambah', $data);
    }
    
    public function simpan()
    {
        $redirect = $this->checkAccess();
        if ($redirect) {
            return $redirect;
        }
        
        $model = new GeojsonConfigModel();
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'file_path' => $this->request->getPost('file_path'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'warna' => $this->request->getPost('warna'),
            'fill_opacity' => $this->request->getPost('fill_opacity'),
            'stroke_color' => $this->request->getPost('stroke_color'),
            'stroke_width' => $this->request->getPost('stroke_width'),
        ];
        
        if ($model->save($data)) {
            $logModel = new ActivityLogModel();
            $logModel->insert([
                'user_id' => session()->get('user_id'),
                'admin_name' => session()->get('nama_lengkap'),
                'action' => 'create',
                'entity_type' => 'geojson',
                'entity_id' => $model->insertID(),
                'description' => 'Menambahkan konfigurasi GeoJSON ' . ($this->request->getPost('nama') ?? ''),
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('/admin/geojson')->with('success', 'GeoJSON berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan GeoJSON!')->withInput();
        }
    }
    
    public function edit($id)
    {
        $redirect = $this->checkAccess();
        if ($redirect) {
            return $redirect;
        }
        
        $model = new GeojsonConfigModel();
        $data['geojson'] = $model->find($id);
        
        if (!$data['geojson']) {
            return redirect()->to('/admin/geojson')->with('error', 'Data tidak ditemukan!');
        }
        
        $data['title'] = 'Edit GeoJSON';
        return view('admin/geojson/edit', $data);
    }
    
    public function update($id)
    {
        $redirect = $this->checkAccess();
        if ($redirect) {
            return $redirect;
        }
        
        $model = new GeojsonConfigModel();
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'file_path' => $this->request->getPost('file_path'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'warna' => $this->request->getPost('warna'),
            'fill_opacity' => $this->request->getPost('fill_opacity'),
            'stroke_color' => $this->request->getPost('stroke_color'),
            'stroke_width' => $this->request->getPost('stroke_width'),
        ];
        
        if ($model->update($id, $data)) {
            $logModel = new ActivityLogModel();
            $logModel->insert([
                'user_id' => session()->get('user_id'),
                'admin_name' => session()->get('nama_lengkap'),
                'action' => 'update',
                'entity_type' => 'geojson',
                'entity_id' => $id,
                'description' => 'Mengubah konfigurasi GeoJSON ' . ($this->request->getPost('nama') ?? ''),
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('/admin/geojson')->with('success', 'GeoJSON berhasil diupdate!');
        } else {
            return redirect()->back()->with('error', 'Gagal mengupdate GeoJSON!');
        }
    }

    public function scan()
    {
        $redirect = $this->checkSuperAdmin();
        if ($redirect) {
            return $redirect;
        }

        $geojsonDir = ROOTPATH . 'public/geojson';
        $model = new GeojsonConfigModel();
        $inserted = 0;
        $scannedFiles = [];

        if (is_dir($geojsonDir)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($geojsonDir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $fileInfo) {
                if ($fileInfo->isFile() && strtolower($fileInfo->getExtension()) === 'geojson') {
                    $relativePath = str_replace('\\', '/', ltrim(str_replace($geojsonDir, '', $fileInfo->getPathname()), DIRECTORY_SEPARATOR));
                    $relativePath = ltrim($relativePath, '/');
                    $filePath = 'geojson/' . $relativePath;
                    $fullPath = ROOTPATH . 'public/' . $filePath;
                    $exists = $model->where('file_path', $filePath)->first();

                    $featureCount = 0;
                    $geometryTypes = [];

                    if (is_file($fullPath)) {
                        $content = @file_get_contents($fullPath);
                        if ($content !== false) {
                            $json = json_decode($content, true);
                            if (is_array($json)) {
                                if (($json['type'] ?? null) === 'FeatureCollection') {
                                    $features = $json['features'] ?? [];
                                    $featureCount = count($features);
                                    foreach ($features as $feature) {
                                        $geometryType = $feature['geometry']['type'] ?? 'Unknown';
                                        if (!in_array($geometryType, $geometryTypes, true)) {
                                            $geometryTypes[] = $geometryType;
                                        }
                                    }
                                } elseif (($json['type'] ?? null) === 'Feature') {
                                    $featureCount = 1;
                                    $geometryType = $json['geometry']['type'] ?? 'Unknown';
                                    $geometryTypes[] = $geometryType;
                                } elseif (($json['type'] ?? null) !== null) {
                                    $geometryTypes[] = $json['type'];
                                }
                            }
                        }
                    }

                    $scannedFiles[] = [
                        'filename' => $relativePath,
                        'file_path' => $filePath,
                        'status' => $exists ? 'exists' : 'new',
                        'feature_count' => $featureCount,
                        'geometry_types' => $geometryTypes,
                    ];

                    if (!$exists) {
                        $model->insert([
                            'nama' => pathinfo($relativePath, PATHINFO_FILENAME),
                            'file_path' => $filePath,
                            'is_active' => 0,
                            'warna' => '#2563eb',
                            'fill_opacity' => 0.5,
                            'stroke_color' => '#1e293b',
                            'stroke_width' => 2,
                        ]);
                        $inserted++;
                    }
                }
            }
        }

        if ($inserted > 0) {
            $logModel = new ActivityLogModel();
            $logModel->insert([
                'user_id' => session()->get('user_id'),
                'admin_name' => session()->get('nama_lengkap'),
                'action' => 'scan',
                'entity_type' => 'geojson',
                'entity_id' => null,
                'description' => 'Memindai folder GeoJSON dan menambahkan ' . $inserted . ' file baru',
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $data['geojson'] = $model->orderBy('id', 'DESC')->findAll();
        $data['scannedFiles'] = $scannedFiles;
        $data['scanMessage'] = 'Scan selesai. ' . $inserted . ' file GeoJSON baru ditambahkan.';
        $data['title'] = 'GeoJSON Overlay';

        if (count($scannedFiles) === 0) {
            $data['scanMessage'] = 'Scan selesai. Tidak ada file GeoJSON di folder public/geojson.';
        }

        return view('admin/geojson/index', $data);
    }

    public function toggle($id)
    {
        $redirect = $this->checkAccess();
        if ($redirect) {
            return $redirect;
        }

        $model = new GeojsonConfigModel();
        $item = $model->find($id);
        if (!$item) {
            return redirect()->to('/admin/geojson')->with('error', 'Data tidak ditemukan!');
        }

        $isActive = $item['is_active'] ? 0 : 1;
        $model->update($id, ['is_active' => $isActive]);

        $logModel = new ActivityLogModel();
        $logModel->insert([
            'user_id' => session()->get('user_id'),
            'admin_name' => session()->get('nama_lengkap'),
            'action' => 'update',
            'entity_type' => 'geojson',
            'entity_id' => $id,
            'description' => ($isActive ? 'Mengaktifkan' : 'Menonaktifkan') . ' GeoJSON ' . ($item['nama'] ?? $item['file_path']),
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/geojson')->with('success', 'GeoJSON berhasil ' . ($isActive ? 'diaktifkan' : 'dinonaktifkan') . '!');
    }
    
    public function hapus($id)
    {
        $redirect = $this->checkAccess();
        if ($redirect) {
            return $redirect;
        }
        
        $model = new GeojsonConfigModel();
        
        if ($model->delete($id)) {
            $logModel = new ActivityLogModel();
            $logModel->insert([
                'user_id' => session()->get('user_id'),
                'admin_name' => session()->get('nama_lengkap'),
                'action' => 'delete',
                'entity_type' => 'geojson',
                'entity_id' => $id,
                'description' => 'Menghapus konfigurasi GeoJSON',
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('/admin/geojson')->with('success', 'GeoJSON berhasil dihapus!');
        } else {
            return redirect()->to('/admin/geojson')->with('error', 'Gagal menghapus GeoJSON!');
        }
    }
}