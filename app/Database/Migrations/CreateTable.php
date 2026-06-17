<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTables extends Migration
{
    public function up()
    {
        // 1. Table : produit
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'designation' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'prix' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'quantite_stock' => [
                'type'       => 'INTEGER',
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('produit');

        // 2. Table : caisse
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom_caisse' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'statut' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'ouverte', // ouverte, fermée
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('caisse');

        // 3. Table : achat (liaison entre produit et caisse)
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'produit_id' => [
                'type'       => 'INTEGER',
            ],
            'caisse_id' => [
                'type'       => 'INTEGER',
            ],
            'quantite_achetee' => [
                'type'       => 'INTEGER',
            ],
            'date_achat' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        // Note pour SQLite : Les clés étrangères nécessitent d'être activées dans la configuration
        $this->forge->createTable('achat');
    }

    public function down()
    {
        $this->forge->dropTable('achat');
        $this->forge->dropTable('caisse');
        $this->forge->dropTable('produit');
    }
}