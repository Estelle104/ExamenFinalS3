<?php

namespace app\controllers;

use app\models\ConfFraisAchat;
use Flight;

class ConfFraisAchatController
{
    public function edit()
    {
        $config = new ConfFraisAchat();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taux = isset($_POST['taux_pourcentage']) ? (float) $_POST['taux_pourcentage'] : 0.0;
            if ($taux < 0) {
                $taux = 0.0;
            }

            $config->setTauxActuel($taux);
            $_SESSION['success'] = 'Frais d\'achat mis à jour.';
            header('Location: ' . Flight::get('flight.base_url') . '/achat/frais');
            exit();
        }

        $tauxActuel = $config->getTauxActuel();
        Flight::render('modele.php', [
            'contentPage' => 'achat/frais',
            'currentPage' => 'achat',
            'pageTitle' => 'Frais d\'achat - BNGRC',
            'tauxActuel' => $tauxActuel
        ]);
    }
}
