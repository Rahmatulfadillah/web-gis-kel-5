<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username', 'email', 'password', 'nama_lengkap',
        'role', 'school_id', 'foto', 'last_login', 'is_active', 'is_logged_in'
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

        if (password_verify($password, $user['password'])) {
            return $user;
        }

        // Fallback untuk backward compatibility: jika di DB masih plaintext
        if ($user['password'] === $password) {
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

    // Cek apakah ada superadmin lain yang sedang aktif login
    public function isSuperAdminLoggedIn($excludeUserId = null)
    {
        $query = $this->where('role', 'admin_super')
            ->where('is_logged_in', 1);

        if ($excludeUserId !== null) {
            $query->where('id !=', $excludeUserId);
        }

        $result = $query->countAllResults();
        return $result > 0;
    }

    // Set status is_logged_in untuk user tertentu
    public function setSuperAdminLoginStatus($userId, $status)
    {
        return $this->update($userId, ['is_logged_in' => $status]);
    }

    // Reset semua is_logged_in superadmin (keamanan ekstra)
    public function resetAllSuperAdminSessions()
    {
        return $this->where('role', 'admin_super')
            ->set(['is_logged_in' => 0])
            ->update();
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

