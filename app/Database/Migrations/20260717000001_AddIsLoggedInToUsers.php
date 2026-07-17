<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsLoggedInToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'is_logged_in' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'is_active',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'is_logged_in');
    }
}
