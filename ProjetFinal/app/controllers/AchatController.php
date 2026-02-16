<?php

namespace app\controllers;

use app\models\Achat;
use app\models\Produit;
use Flight;

class AchatController
{

    // Ajouter un achat
    public function addAchat()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $achat = new Achat();

            $id_produit    = (int) $_POST['id_produit'];
            $id_ville      = (int) $_POST['id_ville'];
            $quantite      = (int) $_POST['quantite'];
            $prix_unitaire = (float) $_POST['prix_unitaire'];
            $frais         = (float) $_POST['frais'];
            // $date_achat    = $_POST['date_achat'];
            $date_achat = !empty($_POST['date_achat']) 
            ? $_POST['date_achat'] 
            : date('Y-m-d');


            $montant = $quantite * $prix_unitaire;
            $montant_total = $montant * (1 + ($frais / 100));

            $argentDisponible = Achat::getTotalArgentDisponible();

            if ($argentDisponible < $montant_total) {
                Flight::json([
                    'success' => false,
                    'message' => 'Fonds insuffisants.'
                ]);
                return;
            }

            $achat->addAchat(
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
        }
    }

    public function listAchats(){

        $achat = new Achat();
        $achats = $achat->getAllAchats();

        Flight::render('achats/liste', [
            'achats' => $achats
        ]);
    }


}
