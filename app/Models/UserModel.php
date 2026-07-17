<<<<<<< HEAD
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
        
        if ($user['password'] == $password) {
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
    
    // Cek apakah ada superadmin yang sedang aktif login
    public function isSuperAdminLoggedIn()
    {
        $result = $this->where('role', 'admin_super')
                       ->where('is_logged_in', 1)
                       ->countAllResults();
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
}
=======
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
>>>>>>> 5e6855ea7d7bafe26f614d6a02f88fa7ae41aab5
