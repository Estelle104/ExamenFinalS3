<?php
namespace app\controllers;
use app\models\Achat;
use Flight;

class AchatController {

    // 🔹 Ajouter un achat
    public function addAchat() {
        $achat = new Achat();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id_produit = $_POST['id_produit'];
            $id_ville   = $_POST['id_ville'];
            $quantite   = $_POST['quantite'];
            $prix_unitaire = $_POST['prix_unitaire'];
            $frais = $_POST['frais']; // ex: 10 pour 10%

            // 🔸 Calcul montant
            $montant = $quantite * $prix_unitaire;
            $montant_total = $montant * (1 + ($frais / 100));

            // 🔸 Vérifier argent disponible
            $argentDisponible = $achat::getTotalArgentDisponible();

            if ($argentDisponible < $montant_total) {
                echo "Erreur : Fonds insuffisants.";
                return;
            }

            // 🔸 Enregistrer $achat
            $achat::create($id_produit, $id_ville, $quantite, $montant_total);

            echo "Achat effectué avec succès.";
        }
    }

    // 🔹 Lister tous les achats
    public function listAchats() {
        $achat = new Achat();
        $achats = $achat::getAll();
        require '../views/achats/liste.php';
    }

}
