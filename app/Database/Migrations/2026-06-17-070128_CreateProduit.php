<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * La table produit et la table mouvement_stock 
 */
class CreateProduit extends Migration
{
    public function up()
    {
        //  Table : produit
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'designation' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'prix' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'quantite_stock' => [
                'type' => 'INTEGER',
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('produit');


        // Historique des mouvements de stock des produits
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'produit_id' => [
                'type' => 'INTEGER',
            ],
            'quantite' => [
                'type' => 'INTEGER',
            ],
            'date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'type' => [
                'enum' => ['entree', 'sortie'],
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('mouvement_stock');

    }

    public function down()
    {
        $this->forge->dropTable('produit');
        $this->forge->dropTable('mouvement_stock');
    }
}