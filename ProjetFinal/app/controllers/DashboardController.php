<?php
namespace app\controllers;

use app\models\Ville;
use app\models\Besoin;
use app\models\Don;

use Flight;

class DashboardController {
    
    public function index() {
        $villeModel = new Ville();
        $besoinModel = new Besoin();
        $donModel = new Don();
        
        // Récupérer toutes les villes
        $villes = $villeModel->getAllVilles();
        $allBesoins = $besoinModel->getAllBesoins();
        $allDons = $donModel->getAllDons();
        
        // Préparer les données pour le tableau
        $dashboard = [];
        
        foreach ($villes as $ville) {
            // Filtrer les besoins de cette ville
            $besoinsVille = array_filter($allBesoins, fn($b) => $b['id_ville'] == $ville['id']);
            
            // Filtrer les dons de cette ville
            $donsVille = array_filter($allDons, fn($d) => $d['id_ville'] == $ville['id']);
            
            // Calculer le total des dons par ville
            $totalDonsQuantite = 0;
            foreach ($donsVille as $don) {
                $totalDonsQuantite += $don['quantite'];
            }
            
            // Calculer le total des besoins par ville
            $totalBesoinsQuantite = 0;
            foreach ($besoinsVille as $besoin) {
                $totalBesoinsQuantite += $besoin['quantite'];
            }
            
            // Déterminer l'état
            if (count($besoinsVille) == 0) {
                $etat = 'N/A';
                $pourcentage = 0;
            } elseif ($totalDonsQuantite >= $totalBesoinsQuantite) {
                $etat = '✅ Satisfait';
                $pourcentage = 100;
            } elseif ($totalDonsQuantite > 0) {
                $etat = '⏳ Partiel';
                $pourcentage = round(($totalDonsQuantite / $totalBesoinsQuantite) * 100, 2);
            } else {
                $etat = '❌ En attente';
                $pourcentage = 0;
            }
            
            $dashboard[] = [
                'ville' => $ville,
                'besoins' => $besoinsVille,
                'dons' => $donsVille,
                'totalBesoins' => count($besoinsVille),
                'totalDons' => count($donsVille),
                'totalBesoinsQuantite' => $totalBesoinsQuantite,
                'totalDonsQuantite' => $totalDonsQuantite,
                'etat' => $etat,
                'pourcentage' => $pourcentage
            ];
        }

        Flight::render('dashboard/index.php', [
            'dashboard' => $dashboard,
            'totalVilles' => count($villes),
            'totalBesoins' => count($allBesoins),
            'totalDons' => count($allDons)
        ]);
    }
}
