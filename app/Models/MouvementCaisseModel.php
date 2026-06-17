<?php

namespace App\Models;

use CodeIgniter\Model;

class MouvementCaisseModel extends Model
{
    protected $table            = 'mouvement_caisse';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // Champs autorisés pour les transactions financières de la caisse
    protected $allowedFields    = ['caisse_id', 'montant', 'date', 'type', 'achat_id'];

    protected $useTimestamps = false;

    public function getMouvementsAvecCaisse()
    {
        return $this->select('mouvement_caisse.*, caisse.nom_caisse')
                    ->join('caisse', 'caisse.id = mouvement_caisse.caisse_id')
                    ->findAll();
    }
}