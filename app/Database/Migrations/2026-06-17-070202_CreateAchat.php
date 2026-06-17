<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAchat extends Migration
{

    public function up()
    {
        // 3. Table : achat (liaison entre produit et caisse)
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'produit_id' => [
                'type' => 'INTEGER',
                'foreignKey' => [
                    'table' => 'produit',
                    'field' => 'id',
                    'onDelete' => 'CASCADE',
                    'onUpdate' => 'CASCADE',
                ],
            ],
            'caisse_id' => [
                'type' => 'INTEGER',
                'foreignKey' => [
                    'table' => 'caisse',
                    'field' => 'id',
                    'onDelete' => 'CASCADE',
                    'onUpdate' => 'CASCADE',
                ],
            ],
            'quantite' => [
                'type' => 'INTEGER',
            ],
            'date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'mvm_id' => [
                'type' => 'INTEGER',
                'foreignKey' => [
                    'table' => 'mouvement_stock',
                    'field' => 'id',
                    'onDelete' => 'CASCADE',
                    'onUpdate' => 'CASCADE',
                ],
            ],
        ]);
        $this->forge->addKey('id', true);
        // Note pour SQLite : Les clés étrangères nécessitent d'être activées dans la configuration
        $this->forge->createTable('achat');
    }
    public function down()
    {
        $this->forge->dropTable('achat');
    }
}