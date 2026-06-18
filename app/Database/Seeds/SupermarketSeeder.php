<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SupermarketSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // ── 1. USERS ──────────────────────────────────────────────
        $users = [
            [
                'username'      => 'admin',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'role'          => 'admin',
            ],
            [
                'username'      => 'caissier',
                'password_hash' => password_hash('caisse123', PASSWORD_DEFAULT),
                'role'          => 'caissier',
            ],
            [
                'username'      => 'marie',
                'password_hash' => password_hash('marie123', PASSWORD_DEFAULT),
                'role'          => 'caissier',
            ],
        ];

        $this->db->table('users')->insertBatch($users);

        // ── 2. CAISSES ────────────────────────────────────────────
        $caisses = [
            ['nom_caisse' => 'Caisse Centrale N°1', 'statut' => 'ouverte'],
            ['nom_caisse' => 'Caisse Rapide N°2',   'statut' => 'ouverte'],
            ['nom_caisse' => 'Caisse N°3',           'statut' => 'ouverte'],
            ['nom_caisse' => 'Caisse Express N°4',   'statut' => 'fermee'],
        ];

        $this->db->table('caisse')->insertBatch($caisses);

        // ── 3. PRODUITS ───────────────────────────────────────────
        $produits = [
            ['designation' => 'Café Moulu 250g',         'prix' => 4.50,  'quantite_stock' => 50,  'created_at' => $now],
            ['designation' => 'Lait Entier 1L',           'prix' => 1.20,  'quantite_stock' => 120, 'created_at' => $now],
            ['designation' => 'Riz Blanc 1kg',            'prix' => 2.80,  'quantite_stock' => 80,  'created_at' => $now],
            ['designation' => 'Chocolat Noir 100g',       'prix' => 1.95,  'quantite_stock' => 65,  'created_at' => $now],
            ['designation' => 'Pâtes Spaghetti 500g',     'prix' => 0.99,  'quantite_stock' => 200, 'created_at' => $now],
            ['designation' => 'Huile Tournesol 1L',       'prix' => 3.20,  'quantite_stock' => 40,  'created_at' => $now],
            ['designation' => 'Sucre en Poudre 1kg',      'prix' => 1.50,  'quantite_stock' => 90,  'created_at' => $now],
            ['designation' => 'Farine de Blé 1kg',        'prix' => 1.10,  'quantite_stock' => 75,  'created_at' => $now],
            ['designation' => 'Beurre 250g',               'prix' => 2.45,  'quantite_stock' => 35,  'created_at' => $now],
            ['designation' => 'Œufs x6',                   'prix' => 1.80,  'quantite_stock' => 60,  'created_at' => $now],
            ['designation' => 'Eau Minérale 1.5L',         'prix' => 0.55,  'quantite_stock' => 150, 'created_at' => $now],
            ['designation' => 'Jus d\'Orange 1L',          'prix' => 2.10,  'quantite_stock' => 45,  'created_at' => $now],
            ['designation' => 'Yaourt Nature x4',          'prix' => 1.65,  'quantite_stock' => 55,  'created_at' => $now],
            ['designation' => 'Savon de Marseille 300g',   'prix' => 2.30,  'quantite_stock' => 30,  'created_at' => $now],
            ['designation' => 'Sel de Table 1kg',          'prix' => 0.75,  'quantite_stock' => 100, 'created_at' => $now],
            ['designation' => 'Thon en Boîte 140g',        'prix' => 1.85,  'quantite_stock' => 70,  'created_at' => $now],
            ['designation' => 'Tomates Pelées 400g',       'prix' => 0.90,  'quantite_stock' => 85,  'created_at' => $now],
            ['designation' => 'Maïs en Boîte 285g',        'prix' => 1.10,  'quantite_stock' => 60,  'created_at' => $now],
            ['designation' => 'Confiture Fraise 370g',     'prix' => 2.60,  'quantite_stock' => 40,  'created_at' => $now],
            ['designation' => 'Céréales Muesli 500g',      'prix' => 3.90,  'quantite_stock' => 25,  'created_at' => $now],
        ];

        $this->db->table('produit')->insertBatch($produits);
    }
}
