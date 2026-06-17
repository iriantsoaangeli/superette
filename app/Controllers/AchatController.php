<?php

namespace App\Controllers;

use App\Models\AchatModel;
use App\Models\ProduitModel;
use App\Models\MouvementCaisseModel;
use \Config\Database;

class AchatController extends BaseController
{
    protected $produitModel;
    protected $mouvementCaisseModel;
    protected $achatModel;

    public function __construct()
    {
        $this->produitModel = new ProduitModel();
        $this->mouvementCaisseModel = new MouvementCaisseModel();
        $this->achatModel = new AchatModel();
    }
    
    public function achats()
    {
        if (!$this->session->has('caisse_id')) {
            return redirect()->to('/accueil')->with('error', 'Selectionne une caisse');
        }
        $data = [
            'caisse_id'   => $this->session->get('caisse_id'),
            'caisse_nom'  => $this->session->get('caisse_nom'),
            'produits'    => $this->produitModel->findAll()
        ];
        return view('achat', $data);
    }

    public function saisirAchat(){
        $data = $this->produitModel->getAllProduits();
        return view('achat', ['produits' => $data]);
    }

    public function enregistrerAchat()
    {
        if (!$this->session->has('caisse_id')) {
            return redirect()->to('/accueil')->with('error', 'Session expirée.');
        }

        $produitModel = new ProduitModel();
        $achatModel   = new AchatModel();

        $produitId = $this->request->getPost('produit_id');
        $quantite  = (int) $this->request->getPost('quantite');
        $caisseId  = $this->session->get('caisse_id');

        $produit = $produitModel->find($produitId);
        if (!$produit || $produit['quantite_stock'] < $quantite) {
            return redirect()->to('/achats')->with('error', 'Stock insuffisant ou produit introuvable.');
        }

        $montantTotal = $produit['prix'] * $quantite;

        $db = Database::connect();
        $db->transStart();

        $idAchat = $achatModel->insert([
            'produit_id'       => $produitId,
            'caisse_id'        => $caisseId,
            'quantite_achetee' => $quantite,
            'date_achat'       => date('Y-m-d H:i:s')
        ]);

        $this->mouvementCaisseModel->insert([
            'caisse_id' => $caisseId,
            'montant'   => $montantTotal,
            'date'      => date('Y-m-d H:i:s'),
            'type'      => 'entree',
            'achat_id'  => $idAchat
        ]);

        $produitModel->update($produitId, [
            'quantite_stock' => $produit['quantite_stock'] - $quantite
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/achats')->with('error', 'Une erreur est survenue lors de la transaction.');
        }

        return redirect()->to('/achats')->with('success', 'Achat enregistré avec succès ! Encaissé : ' . $montantTotal . '€');
    }
    
}
