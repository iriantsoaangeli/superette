<?php

namespace App\Models;

use CodeIgniter\Model;

class CaisseModel extends Model
{
    protected $table            = 'caisse';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nom_caisse', 'statut'];
    protected $useTimestamps = false;

    public function findAllOuvertes()
    {
        return $this->where('statut', 'ouverte')->findAll();
    }
}