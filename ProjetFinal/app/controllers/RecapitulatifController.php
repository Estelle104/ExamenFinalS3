<?php
namespace app\controllers;

use app\models\Besoin;
use app\models\Produit;
use Flight;

class RecapitulatifController
{
    // Page principale
    public function index() {
        Flight::render('modele.php', [
            'contentPage' => 'recapitulatif/index',
            'currentPage' => 'recapitulatif',
            'pageTitle' => 'Récapitulatif - BNGRC'
        ]);
    }

    // AJAX : retourne le récapitulatif des besoins (total, satisfait, restant)
    public function ajax() {
        $besoin = new Besoin();
        $produitModel = new Produit();
        $produits = $produitModel->getAllProduits();
        $recap = [];
        foreach ($produits as $prod) {
            $id = $prod['id'];
            $nom = $prod['nom'];
            $prix = $prod['prix_unitaire'];
            $besoins = $besoin->getBesoinsPourAchat($id, null);
            $total = 0; $satisfait = 0; $restant = 0;
            foreach ($besoins as $b) {
                $q = $b['quantite'];
                $qr = isset($b['quantite_restante']) ? $b['quantite_restante'] : $q;
                $total += $q * $prix;
                $restant += $qr * $prix;
                $satisfait += ($q - $qr) * $prix;
            }
            $recap[] = [
                'produit' => $nom,
                'total' => $total,
                'satisfait' => $satisfait,
                'restant' => $restant
            ];
        }
        Flight::json(['success' => true, 'recap' => $recap]);
    }
}