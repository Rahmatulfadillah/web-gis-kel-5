<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSekolahTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'npsn' => [
                'type' => 'VARCHAR',
                'constraint' => 8,
            ],
            'nama_sekolah' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'jenjang' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'akreditasi' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'kelurahan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'tahun_berdiri' => [
                'type' => 'INT',
                'constraint' => 4,
                'null' => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
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
            'latitude' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'longitude' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('npsn', false, true);
        $this->forge->createTable('sekolah', true);
    }

    public function down()
    {
        $this->forge->dropTable('sekolah');
    }
}