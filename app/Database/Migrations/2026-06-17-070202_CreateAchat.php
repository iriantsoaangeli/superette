<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAchat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'produit_id' => [
                'type' => 'INTEGER',
            ],
            'caisse_id' => [
                'type' => 'INTEGER',
            ],
            'quantite_achetee' => [
                'type'    => 'INTEGER',
                'default' => 1,
            ],
            'date_achat' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('achat');
    }

    public function down()
    {
        $this->forge->dropTable('achat');
    }
}
