<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSchoolPopupFields extends Migration
{
    public function up()
    {
        $fields = [];

        if (!$this->db->fieldExists('foto', 'sekolah')) {
            $fields['foto'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ];
        }

        if (!$this->db->fieldExists('visi', 'sekolah')) {
            $fields['visi'] = [
                'type' => 'TEXT',
                'null' => true,
            ];
        }

        if (!$this->db->fieldExists('misi', 'sekolah')) {
            $fields['misi'] = [
                'type' => 'TEXT',
                'null' => true,
            ];
        }

        if (!$this->db->fieldExists('kontak_admin', 'sekolah')) {
            $fields['kontak_admin'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('sekolah', $fields);
        }
    }

    public function down()
    {
        $columns = [];

        if ($this->db->fieldExists('foto', 'sekolah')) {
            $columns[] = 'foto';
        }
        if ($this->db->fieldExists('visi', 'sekolah')) {
            $columns[] = 'visi';
        }
        if ($this->db->fieldExists('misi', 'sekolah')) {
            $columns[] = 'misi';
        }
        if ($this->db->fieldExists('kontak_admin', 'sekolah')) {
            $columns[] = 'kontak_admin';
        }

        if (!empty($columns)) {
            $this->forge->dropColumn('sekolah', $columns);
        }
    }
}
