<?php

namespace App\Controllers;

use App\Models\Achat;
use App\Models\Produit;

class AchatController extends BaseController
{
    protected $produitModel;

    public function __construct()
    {
        $this->produitModel = new Produit();
    }
    
    public function saisirAchat(){
        $data = $this->produitModel->getAllProduits();
        return view('achat', ['produits' => $data]);
    }
}