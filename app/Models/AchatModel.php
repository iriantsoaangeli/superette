<?php

namespace App\Models;

use CodeIgniter\Model;

class AchatModel extends Model
{
    protected $table            = 'achat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['produit_id', 'caisse_id', 'quantite', 'date', 'mvm_id'];
    protected $useTimestamps    = false;

    public function getAchatsAvecDetails()
    {
        return $this->select('achat.*, produit.designation, produit.prix, caisse.nom_caisse')
                    ->join('produit', 'produit.id = achat.produit_id')
                    ->join('caisse', 'caisse.id = achat.caisse_id')
                    ->findAll();
    }
}
