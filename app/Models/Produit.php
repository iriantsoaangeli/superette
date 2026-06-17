<?php

namespace App\Models;

use CodeIgniter\Model;

class Produit extends Model
{
    protected $table = 'produit';
    protected $primaryKey = 'id';
    protected $allowedFields = ['designation', 'prix', 'quantite_stock', 'created_at'];

    public function getAllProduits()
    {
        return $this->findAll();
    }
}