<?php

namespace App\Controllers;

use App\Models\SekolahModel;
use App\Models\GeojsonConfigModel;

class Sekolah extends BaseController
{
    public function index()
    {
        $sekolahModel = new SekolahModel();
        $geojsonModel = new GeojsonConfigModel();
        
        $data['sekolah'] = $sekolahModel->select('id, npsn, nama_sekolah, jenjang, status, akreditasi, alamat, foto, visi, misi, kontak_admin, latitude, longitude')->findAll();
        
        // Mengambil layer geojson yang aktif
        $data['geojson_layers'] = $geojsonModel->where('is_active', 1)->orderBy('nama')->findAll();
        
        return view('peta/index', $data);
    }

    public function fullMap()
    {
        $sekolahModel = new SekolahModel();
        $geojsonModel = new GeojsonConfigModel();
        
        $data['sekolah'] = $sekolahModel->select('id, npsn, nama_sekolah, jenjang, status, akreditasi, alamat, foto, visi, misi, kontak_admin, latitude, longitude')->findAll();
        
        // Mengambil layer geojson yang aktif
        $data['geojson_layers'] = $geojsonModel->where('is_active', 1)->orderBy('nama')->findAll();
        
        return view('peta/full_map', $data);
    }

    public function detail($id = null)
    {
        if ($id === null || !is_numeric($id)) {
            return redirect()->to('/peta')->with('error', 'Data sekolah tidak ditemukan.');
        }

        $sekolahModel = new SekolahModel();
        $sekolah = $sekolahModel->select('id, npsn, nama_sekolah, jenjang, status, akreditasi, alamat, foto, visi, misi, kontak_admin, latitude, longitude, kelurahan, tahun_berdiri')->find($id);

        if (!$sekolah) {
            return redirect()->to('/peta')->with('error', 'Data sekolah tidak ditemukan.');
        }

        $data['title'] = 'Detail Sekolah';
        $data['sekolah'] = $sekolah;

        return view('sekolah/detail', $data);
    }
}