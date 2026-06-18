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
        $this->produitModel        = new ProduitModel();
        $this->mouvementCaisseModel = new MouvementCaisseModel();
        $this->achatModel          = new AchatModel();
    }

    public function achats()
    {
        if (!$this->session->has('caisse_id')) {
            return redirect()->to('/accueil')->with('error', 'Selectionne une caisse');
        }
        $data = [
            'caisse_id'  => $this->session->get('caisse_id'),
            'caisse_nom' => $this->session->get('caisse_nom'),
            'produits'   => $this->produitModel->findAll()
        ];
        return view('achat', $data);
    }

    public function saisirAchat()
    {
        if (!$this->session->has('caisse_id')) {
            return redirect()->to('/accueil')->with('error', 'Selectionne une caisse');
        }
        $data = [
            'caisse_id'  => $this->session->get('caisse_id'),
            'caisse_nom' => $this->session->get('caisse_nom'),
            'produits'   => $this->produitModel->findAll()
        ];
        return view('achat', $data);
    }

    /**
     * AJAX — Vérifie la cohérence des lignes, puis insère l'achat.
     *
     * Reçoit en JSON :
     *   lignes : [{ produit_id, libelle, pu, quantite, total }, ...]
     *   totalGeneral : float   (somme côté JS des total de chaque ligne)
     *
     * Règle : pour chaque ligne  pu * quantite === total
     *         ET somme(totaux lignes) === totalGeneral
     *
     * Retourne JSON :
     *   { success: true }                    → clôture OK
     *   { success: false, message: "..." }   → erreur de vérification
     */
    public function cloturerAchat()
    {
        $this->response->setContentType('application/json');

        if (!$this->session->has('caisse_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expirée. Veuillez sélectionner une caisse.'
            ]);
        }

        $json   = $this->request->getJSON(true);
        $lignes = $json['lignes']       ?? [];
        $totalJS = (float)($json['totalGeneral'] ?? 0);
        $caisseId = $this->session->get('caisse_id');

        if (empty($lignes)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucune ligne à clôturer.'
            ]);
        }

        // ── VÉRIFICATION ──────────────────────────────────────────────
        $totalCalcule = 0;

        foreach ($lignes as $i => $ligne) {
            $pu       = (float)($ligne['pu']       ?? 0);
            $quantite = (int)  ($ligne['quantite'] ?? 0);
            $total    = (float)($ligne['total']    ?? 0);

            $attendu = round($pu * $quantite, 2);

            if (abs($attendu - round($total, 2)) > 0.01) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Erreur ligne #" . ($i + 1) . " « {$ligne['libelle']} » : "
                               . "pU({$pu}) × qté({$quantite}) = {$attendu} ≠ total reçu ({$total})."
                ]);
            }

            $totalCalcule += $attendu;
        }

        if (abs(round($totalCalcule, 2) - round($totalJS, 2)) > 0.01) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Total général incohérent : calculé " . round($totalCalcule, 2)
                           . " ≠ reçu " . round($totalJS, 2) . "."
            ]);
        }

        // ── INSERTION ─────────────────────────────────────────────────
        $db = Database::connect();
        $db->transStart();

        try {
            foreach ($lignes as $ligne) {
                $produitId = (int)$ligne['produit_id'];
                $quantite  = (int)$ligne['quantite'];
                $montant   = (float)$ligne['total'];

                $produit = $this->produitModel->find($produitId);

                if (!$produit || $produit['quantite_stock'] < $quantite) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Stock insuffisant ou produit introuvable : « {$ligne['libelle']} »."
                    ]);
                }

                $idAchat = $this->achatModel->insert([
                    'produit_id'       => $produitId,
                    'caisse_id'        => $caisseId,
                    'quantite_achetee' => $quantite,
                    'date_achat'       => date('Y-m-d H:i:s')
                ]);

                $this->mouvementCaisseModel->insert([
                    'caisse_id' => $caisseId,
                    'montant'   => $montant,
                    'date'      => date('Y-m-d H:i:s'),
                    'type'      => 'entree',
                    'achat_id'  => $idAchat
                ]);

                $this->produitModel->update($produitId, [
                    'quantite_stock' => $produit['quantite_stock'] - $quantite
                ]);
            }
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'La transaction a échoué. Veuillez réessayer.'
            ]);
        }

        return $this->response->setJSON(['success' => true]);
    }

    public function enregistrerAchat()
    {
        if (!$this->session->has('caisse_id')) {
            return redirect()->to('/accueil')->with('error', 'Session expirée.');
        }

        $produitId = $this->request->getPost('produit_id');
        $quantite  = (int)$this->request->getPost('quantite');
        $caisseId  = $this->session->get('caisse_id');

        $produit = $this->produitModel->find($produitId);
        if (!$produit || $produit['quantite_stock'] < $quantite) {
            return redirect()->to('/achats')->with('error', 'Stock insuffisant ou produit introuvable.');
        }

        $montantTotal = $produit['prix'] * $quantite;

        $db = Database::connect();
        $db->transStart();

        $idAchat = $this->achatModel->insert([
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

        $this->produitModel->update($produitId, [
            'quantite_stock' => $produit['quantite_stock'] - $quantite
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/achats')->with('error', 'Une erreur est survenue lors de la transaction.');
        }

        return redirect()->to('/achats')->with('success', 'Achat enregistré avec succès ! Encaissé : ' . $montantTotal . '€');
    }
}
