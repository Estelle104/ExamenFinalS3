<?php
namespace app\controllers;

use app\models\Achat;
use app\models\Produit;
use app\models\ConfFraisAchat;
use app\models\Besoin;
use app\models\Ville;
use Flight;

class AchatController
{
    // Route AJAX pour feedback interactif du formulaire d'achat
    public function recapAchatAjax()
    {
        $id_produit = isset($_GET['id_produit']) ? (int)$_GET['id_produit'] : 0;
        $id_ville = isset($_GET['id_ville']) && $_GET['id_ville'] !== '' ? (int)$_GET['id_ville'] : null;
        $quantite = isset($_GET['quantite']) ? (int)$_GET['quantite'] : 0;

        $produit = new Produit();
        $achat = new Achat();
        $besoin = new Besoin();
        $confFrais = new ConfFraisAchat();

        $prix_unitaire = $produit->getPrixUnitaire($id_produit);
        $frais = $confFrais->getTauxActuel();
            $argentDisponible = $achat->getTotalArgentDisponible($id_ville);
        $besoinsRestants = $besoin->getTotalRestantByProduit($id_produit, $id_ville);

        if (!$prix_unitaire || $besoinsRestants <= 0) {
            Flight::json([
                'success' => false,
                'message' => 'Aucun besoin restant ou produit invalide.'
            ]);
            return;
        }

        $montant = $quantite * $prix_unitaire;
        $montant_frais = $montant * ($frais / 100);
        $montant_total = $montant + $montant_frais;

        Flight::json([
            'success' => true,
            'prix_unitaire' => $prix_unitaire,
            'frais' => round($montant_frais, 2),
            'montant' => round($montant, 2),
            'montant_total' => round($montant_total, 2),
            'argent_disponible' => round($argentDisponible, 2),
            'besoins_restants' => $besoinsRestants
        ]);
    }

    // AJAX : produits ayant des besoins restants par ville
    public function produitsParVilleAjax()
    {
        $id_ville = isset($_GET['id_ville']) && $_GET['id_ville'] !== '' ? (int)$_GET['id_ville'] : null;
        $besoin = new Besoin();
        $produits = $besoin->getProduitsAvecBesoins($id_ville);
        Flight::json([
            'success' => true,
            'produits' => $produits
        ]);
    }

    // Ajouter un achat
    public function addAchat()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $achat = new Achat();
            $produit = new Produit();
            $confFrais = new ConfFraisAchat();
            $besoin = new Besoin();

            $id_produit    = (int) $_POST['id_produit'];
            $id_ville      = !empty($_POST['id_ville']) ? (int) $_POST['id_ville'] : null;
            $quantite      = (int) $_POST['quantite'];
            $prix_unitaire = (float) $produit->getPrixUnitaire($id_produit);
            $frais         = (float) $confFrais->getTauxActuel();
            // $date_achat    = $_POST['date_achat'];
            $date_achat = !empty($_POST['date_achat']) 
            ? $_POST['date_achat'] 
            : date('Y-m-d');


            if ($prix_unitaire <= 0) {
                Flight::json([
                    'success' => false,
                    'message' => 'Produit invalide ou prix unitaire manquant.'
                ]);
                return;
            }

            $montant = $quantite * $prix_unitaire;
            $montant_total = $montant * (1 + ($frais / 100));

            $argentDisponible = $achat->getTotalArgentDisponible($id_ville);

            $totalRestant = $besoin->getTotalRestantByProduit($id_produit, $id_ville);
            if ($totalRestant <= 0) {
                Flight::json([
                    'success' => false,
                    'message' => 'Aucun besoin restant pour ce produit.'
                ]);
                return;
            }

            if ($quantite > $totalRestant) {
                Flight::json([
                    'success' => false,
                    'message' => 'Quantité demandée supérieure aux besoins restants.'
                ]);
                return;
            }

            if ($argentDisponible < $montant_total) {
                Flight::json([
                    'success' => false,
                    'message' => 'Fonds insuffisants.'
                ]);
                return;
            }

            $achat->createAchatEtAppliquerBesoins(
                $id_produit,
                $id_ville,
                $quantite,
                $montant_total,
                $date_achat
            );

            Flight::json([
                'success' => true,
                'message' => 'Achat effectué avec succès.'
            ]);
            return;
        }

        $confFrais = new ConfFraisAchat();
        $besoin = new Besoin();
        $villeModel = new Ville();
        $tauxActuel = $confFrais->getTauxActuel();
        $produits = $besoin->getProduitsAvecBesoins();
        $villes = $villeModel->getAllVilles();
        Flight::render('modele.php', [
            'contentPage' => 'achat/add',
            'currentPage' => 'achat',
            'pageTitle' => 'Achat - BNGRC',
            'tauxActuel' => $tauxActuel,
            'produits' => $produits,
            'villes' => $villes
        ]);
    }

    public function listAchats(){

        $achat = new Achat();
        $villeModel = new Ville();
        $produitModel = new Produit();
        
        // Récupérer tous les achats
        $achats = $achat->getAllAchats();
        
        // Récupérer les villes et produits pour les filtres
        $villes = $villeModel->getAllVilles();
        $produits = $produitModel->getAllProduits();

        Flight::render('modele.php', [
            'contentPage' => 'achat/list',
            'currentPage' => 'achat',
            'pageTitle' => 'Liste des achats - BNGRC',
            'achats' => $achats,
            'villes' => $villes,
            'produits' => $produits
        ]);
    }


}
