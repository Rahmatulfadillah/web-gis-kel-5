<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username', 'email', 'password', 'nama_lengkap', 
        'role', 'school_id', 'foto', 'last_login', 'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Login untuk semua role
    public function login($username, $password)
    {
        $user = $this->where('username', $username)
                     ->where('is_active', 1)
                     ->first();
        
        if (!$user) {
            return false;
        }
        
        // Cek dengan password_verify
        if (password_verify($password, $user['password'])) {
            return $user;
        }
        
        // Fallback untuk backward compatibility: jika di DB masih plaintext
        if ($user['password'] === $password) {
            // Otomatis hash dan update password di DB agar selanjutnya ter-hash
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $this->update($user['id'], ['password' => $newHash]);
            $user['password'] = $newHash;
            return $user;
        }
        
        return false;
    }
    
    // Cek apakah user adalah super admin
    public function isSuperAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === 'admin_super';
    }
    
    // Cek apakah user adalah admin sekolah
    public function isAdminSekolah($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === 'admin_sekolah';
    }
    
    // Get sekolah untuk admin sekolah
    public function getSekolahByAdmin($userId)
    {
        $user = $this->find($userId);
        if ($user && $user['role'] === 'admin_sekolah') {
            return $user['school_id'];
        }
        return null;
    }
}