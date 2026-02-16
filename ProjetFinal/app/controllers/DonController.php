<?php
namespace app\controllers;

use app\models\Don;

use Flight;

class DonController {
    
    public function list() {
        $donModel = new Don();
        $dons = $donModel->getAllDons();
        Flight::render('don/list.php', ['dons' => $dons]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $description = $_POST['description'] ?? '';
            $idProduit = $_POST['id_produit'] ?? 0;
            $idVille = !empty($_POST['id_ville']) ? (int)$_POST['id_ville'] : null;
            $idRegion = !empty($_POST['id_region']) ? (int)$_POST['id_region'] : null;
            $quantite = $_POST['quantite'] ?? 0;
            $dateDon = $_POST['date_don'] ?? null;
            $donneur = $_POST['donneur'] ?? '';

            // Au moins une localisation (ville ou région) est requise
            if ($description && $idProduit && $quantite && $donneur && ($idVille || $idRegion)) {
                $donModel = new Don();
                $donModel->addDon($description, (int)$idProduit, $idVille, (int)$quantite, $dateDon, $donneur, $idRegion);

                $_SESSION['success'] = 'Don created successfully';
                header('Location: ' . Flight::get('flight.base_url') . '/dons');
                exit();
            } else {
                $_SESSION['error'] = 'Description, produit, quantité, donneur et au moins une localisation (ville ou région) sont requis';
            }
        }

        Flight::render('don/add.php');
    }
}