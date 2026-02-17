<?php
namespace app\controllers;

use app\models\Don;

use Flight;

class DonController {
    
    public function list() {
        $donModel = new Don();
        $dons = $donModel->getAllDons();
        Flight::render('modele.php', [
            'contentPage' => 'don/list',
            'currentPage' => 'don',
            'pageTitle' => 'Liste des dons - Takalo-Takalo',
            'dons' => $dons
        ]);
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

            // Ville et région sont optionnels (don national si les deux sont null)
            if ($description && $idProduit && $quantite && $donneur) {
                $donModel = new Don();
                $donModel->addDon($description, (int)$idProduit, $idVille, (int)$quantite, $dateDon, $donneur, $idRegion);

                $_SESSION['success'] = 'Don créé avec succès';
                header('Location: ' . Flight::get('flight.base_url') . '/dons');
                exit();
            } else {
                $_SESSION['error'] = 'Description, produit, quantité et donneur sont requis';
            }
        }

        Flight::render('modele.php', [
            'contentPage' => 'don/add',
            'currentPage' => 'don',
            'pageTitle' => 'Ajouter un don - Takalo-Takalo'
        ]);
    }

    public function edit($id) {
        $donModel = new Don();
        $don = $donModel->getDonById((int)$id);

        if (!$don) {
            $_SESSION['error'] = 'Don introuvable';
            header('Location: ' . Flight::get('flight.base_url') . '/dons');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $description = $_POST['description'] ?? '';
            $idProduit = $_POST['id_produit'] ?? 0;
            $idVille = !empty($_POST['id_ville']) ? (int)$_POST['id_ville'] : null;
            $idRegion = !empty($_POST['id_region']) ? (int)$_POST['id_region'] : null;
            $quantite = $_POST['quantite'] ?? 0;
            $dateDon = $_POST['date_don'] ?? null;
            $donneur = $_POST['donneur'] ?? '';

            // Ville et région sont optionnels (don national si les deux sont null)
            if ($description && $idProduit && $quantite && $donneur) {
                $donModel->updateDon((int)$id, $description, (int)$idProduit, $idVille, (int)$quantite, $dateDon, $donneur, $idRegion);

                $_SESSION['success'] = 'Don modifié avec succès';
                header('Location: ' . Flight::get('flight.base_url') . '/dons');
                exit();
            } else {
                $_SESSION['error'] = 'Description, produit, quantité et donneur sont requis';
            }
        }

        Flight::render('modele.php', [
            'contentPage' => 'don/edit',
            'currentPage' => 'don',
            'pageTitle' => 'Modifier un don - Takalo-Takalo',
            'don' => $don
        ]);
    }

    public function delete($id) {
        $donModel = new Don();
        $don = $donModel->getDonById((int)$id);

        if (!$don) {
            $_SESSION['error'] = 'Don introuvable';
        } else {
            $donModel->deleteDon((int)$id);
            $_SESSION['success'] = 'Don supprimé avec succès';
        }

        header('Location: ' . Flight::get('flight.base_url') . '/dons');
        exit();
    }
}