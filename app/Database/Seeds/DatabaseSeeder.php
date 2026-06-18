<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder maître — lance tous les seeders dans l'ordre.
 * Usage : php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('SupermarketSeeder');
    }
}
