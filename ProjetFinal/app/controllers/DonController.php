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
            $idVille = $_POST['id_ville'] ?? 0;
            $quantite = $_POST['quantite'] ?? 0;
            $dateDon = $_POST['date_don'] ?? null;
            $donneur = $_POST['donneur'] ?? '';

            if ($description && $idProduit && $idVille && $quantite && $donneur) {
                $donModel = new Don();
                $donModel->addDon($description, (int)$idProduit, (int)$idVille, (int)$quantite, $dateDon, $donneur);

                $_SESSION['success'] = 'Don created successfully';
                header('Location: ' . Flight::get('flight.base_url') . '/dons');
                exit();
            } else {
                $_SESSION['error'] = 'All required fields must be filled';
            }
        }

        Flight::render('don/add.php');
    }
}