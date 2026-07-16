<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        $existing = $userModel->where('username', 'superadmin')->first();
        if ($existing) {
            return;
        }

        $userModel->insert([
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => password_hash('superadmin123', PASSWORD_DEFAULT),
            'nama_lengkap' => 'Super Admin',
            'role' => 'admin_super',
            'school_id' => null,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
