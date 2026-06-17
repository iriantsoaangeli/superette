<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;


class CreateCaisse extends Migration
{

    public function up()
    {

        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom_caisse' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'statut' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'ouverte', // ouverte, fermée
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('caisse');

        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
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
            'montant' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'date' => [
                'type' => 'DATETIME',
            ],
            'type' => [
                'enum' => ['entree', 'sortie'],
            ],
            'achat_id' => [
                'type' => 'INTEGER',
                'foreignKey' => [
                    'table' => 'achat',
                    'field' => 'id',
                    'onDelete' => 'CASCADE',
                    'onUpdate' => 'CASCADE',
                ],
                'null' => true,
            ],
        ]);
        $this->forge->createTable('mouvement_caisse');
    }

    public function down()
    {
        $this->forge->dropTable('caisse');
    }
}