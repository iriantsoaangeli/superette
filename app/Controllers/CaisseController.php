<?php

namespace App\Controllers;

use \Config\Services;
use App\Models\CaisseModel;
use App\Models\MouvementCaisseModel;
class CaisseController extends BaseController
{
    protected $session;
    protected $caisseModel;
    protected $mouvementCaisseModel;

    public function __construct()
    {
        $this->session = Services::session();
        $this->caisseModel = new CaisseModel();
        $this->mouvementCaisseModel = new MouvementCaisseModel();
    }

    // 1. Écran d'accueil
    public function index()
    {
        // caisse ouverte
        $data['caisses'] = $this->caisseModel->where('statut', 'ouverte')->findAll();
        return view('accueil', $data);
    }

    // 2. Traitement du formulaire et mise en session
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
}