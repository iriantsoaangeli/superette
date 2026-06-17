<?php

namespace App\Controllers;

use App\Models\CaisseModel;
use App\Models\MouvementCaisseModel;
class CaisseController extends BaseController
{
    protected $caisseModel;
    protected $mouvementCaisseModel;

    public function __construct()
    {
        $this->caisseModel = new CaisseModel();
        $this->mouvementCaisseModel = new MouvementCaisseModel();
    }

    public function index()
    {
        // caisse ouverte
        $data['caisses'] = $this->caisseModel->findAllOuvertes();
        return view('accueil', $data);
    }

    public function selectionner()
    {
        $caisseId = $this->request->getPost('caisse_id');
        $caisse = $this->caisseModel->find($caisseId);
        if ($caisse) {
            $this->session->set('caisse_id', $caisse['id']);
            $this->session->set('caisse_nom', $caisse['nom_caisse']);
            return redirect()->to('/achats');
        }
        return redirect()->to('/')->with('error', 'Caisse invalide ou introuvable.');
    }

    public function deconnexion()
    {
        $this->session->remove(['caisse_id', 'caisse_nom']);
        return redirect()->to('/');
    }
}
