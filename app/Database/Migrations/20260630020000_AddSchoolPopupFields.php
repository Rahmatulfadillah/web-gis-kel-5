<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSchoolPopupFields extends Migration
{
    public function up()
    {
        $fields = [
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'visi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'misi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kontak_admin' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];

        $this->forge->addColumn('sekolah', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('sekolah', ['foto', 'visi', 'misi', 'kontak_admin']);
    }
}
