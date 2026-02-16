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
        
        // Récupérer les allocations depuis dispatch
        $dispatches = $this->getDispatches();
        
        // Préparer les données pour le tableau
        $dashboard = [];
        
        foreach ($villes as $ville) {
            // Filtrer les besoins de cette ville
            $besoinsVille = array_filter($allBesoins, fn($b) => $b['id_ville'] == $ville['id']);
            
            // Calculer les allocations reçues pour cette ville par dispatch
            $quantiteAllouee = 0;
            foreach ($dispatches as $dispatch) {
                // Trouver le besoin correspondant
                foreach ($besoinsVille as $besoin) {
                    if ($besoin['id'] == $dispatch['id_besoin']) {
                        $quantiteAllouee += $dispatch['quantite_attribuee'];
                        break;
                    }
                }
            }
            
            // Calculer le total des besoins par ville
            $totalBesoinsQuantite = 0;
            foreach ($besoinsVille as $besoin) {
                $totalBesoinsQuantite += $besoin['quantite'];
            }
            
            // Déterminer l'état basé sur les allocations
            if (count($besoinsVille) == 0) {
                $etat = 'N/A';
                $pourcentage = 0;
            } elseif ($quantiteAllouee >= $totalBesoinsQuantite) {
                $etat = '✅ Satisfait';
                $pourcentage = 100;
            } elseif ($quantiteAllouee > 0) {
                $etat = '⏳ Partiel';
                $pourcentage = round(($quantiteAllouee / $totalBesoinsQuantite) * 100, 2);
            } else {
                $etat = '❌ En attente';
                $pourcentage = 0;
            }
            
            $dashboard[] = [
                'ville' => $ville,
                'besoins' => $besoinsVille,
                'totalBesoins' => count($besoinsVille),
                'totalBesoinsQuantite' => $totalBesoinsQuantite,
                'totalAllouee' => $quantiteAllouee,
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

    private function getDispatches() {
        // Récupérer tous les dispatches
        try {
            $pdo = new \PDO(
                "mysql:host=localhost;dbname=bngrc_final_s3;charset=utf8mb4",
                'root',
                '',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
            $stmt = $pdo->query("SELECT * FROM dispatch");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function simulate() {
        $donModel = new Don();
        $result = $donModel->simulerDispatch();
        
        $_SESSION['success'] = "Simulation complétée: {$result['dispatch_crees']} allocations créées, {$result['dons_traite']} dons traités";
        
        header('Location: ' . Flight::get('flight.base_url') . '/dashboard');
        exit();
    }
}
