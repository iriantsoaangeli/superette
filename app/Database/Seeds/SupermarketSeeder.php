<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SupermarketSeeder extends Seeder
{
    public function run()
    {
        // Insertion des 5 produits
        $produits = [
            [
                'designation'    => 'Paquet de Café 250g',
                'prix'           => 4.50,
                'quantite_stock' => 50,
                'created_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'designation'    => 'Bouteille de Lait 1L',
                'prix'           => 1.20,
                'quantite_stock' => 120,
                'created_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'designation'    => 'Sac de Riz 1kg',
                'prix'           => 2.80,
                'quantite_stock' => 80,
                'created_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'designation'    => 'Tablette de Chocolat Noir',
                'prix'           => 1.95,
                'quantite_stock' => 65,
                'created_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'designation'    => 'Paquet de Pâtes 500g',
                'prix'           => 0.99,
                'quantite_stock' => 200,
                'created_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('produit')->insertBatch($produits);

        // Insertion des 2 caisses
        $caisses = [
            [
                'nom_caisse' => 'Caisse Centrale N°1',
                'statut'     => 'ouverte',
            ],
            [
                'nom_caisse' => 'Caisse Rapide N°2',
                'statut'     => 'ouverte',
            ],
        ];

        $this->db->table('caisse')->insertBatch($caisses);
    }
}