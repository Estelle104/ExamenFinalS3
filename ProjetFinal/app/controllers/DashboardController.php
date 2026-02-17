<?php
namespace app\controllers;

use app\models\Ville;
use app\models\Besoin;
use app\models\Don;
use app\models\Produit;

use Flight;

class DashboardController {
    
    public function index() {
        $villeModel = new Ville();
        $besoinModel = new Besoin();
        $donModel = new Don();
        $produitModel = new Produit();
        
        // Récupérer toutes les villes
        $villes = $villeModel->getAllVilles();
        $allBesoins = $besoinModel->getAllBesoins();
        $allDons = $donModel->getAllDons();
        $allProduits = $produitModel->getAllProduits();
        $produitsById = [];
        foreach ($allProduits as $produit) {
            $produitsById[$produit['id']] = $produit['nom'];
        }
        
        // Préparer les données pour le tableau
        $dashboard = [];
        
        foreach ($villes as $ville) {
            // Filtrer les besoins de cette ville
            $besoinsVille = array_filter($allBesoins, fn($b) => $b['id_ville'] == $ville['id']);
            
            // Calculer la quantité restante pour cette ville
            // quantite_restante = total des quantités restantes de tous les besoins
            $quantiteRestante = 0;
            foreach ($besoinsVille as $besoin) {
                // Utiliser quantite_restante si disponible, sinon quantite (pour compatibilité)
                $quantiteRestante += ($besoin['quantite_restante'] ?? $besoin['quantite']);
            }

            // Produits demandés par ville
            $produitsVille = [];
            foreach ($besoinsVille as $besoin) {
                $idProduit = $besoin['id_produit'] ?? null;
                if ($idProduit && isset($produitsById[$idProduit])) {
                    $produitsVille[$produitsById[$idProduit]] = true;
                }
            }
            $produitsVille = array_keys($produitsVille);
            
            // Calculer le total des besoins (quantité originale)
            $totalBesoinsQuantite = 0;
            foreach ($besoinsVille as $besoin) {
                $totalBesoinsQuantite += $besoin['quantite'];
            }
            
            // Quantité allouée = originale - restante
            $quantiteAllouee = $totalBesoinsQuantite - $quantiteRestante;
            
            // Déterminer l'état basé sur quantite_restante
            if (count($besoinsVille) == 0) {
                $etat = 'N/A';
                $pourcentage = 0;
            } elseif ($quantiteRestante <= 0) {
                $etat = '✅ Satisfait';
                $pourcentage = 100;
            } elseif ($quantiteAllouee > 0) {
                $etat = '⏳ Partiel';
                $pourcentage = round(($quantiteAllouee / $totalBesoinsQuantite) * 100, 2);
            } else {
                $etat = '❌ En attente';
                $pourcentage = 0;
            }

            // Date du besoin le plus ancien pour tri d'affichage
            $oldestBesoinDate = null;
            foreach ($besoinsVille as $besoin) {
                $dateBesoin = $besoin['date_besoin'] ?? null;
                if ($dateBesoin !== null && $dateBesoin !== '') {
                    if ($oldestBesoinDate === null || $dateBesoin < $oldestBesoinDate) {
                        $oldestBesoinDate = $dateBesoin;
                    }
                }
            }
            
            $dashboard[] = [
                'ville' => $ville,
                'besoins' => $besoinsVille,
                'totalBesoins' => count($besoinsVille),
                'totalBesoinsQuantite' => $totalBesoinsQuantite,
                'quantiteAllouee' => $quantiteAllouee,
                'quantiteRestante' => $quantiteRestante,
                'produits' => $produitsVille,
                'etat' => $etat,
                'pourcentage' => $pourcentage,
                'oldestBesoinDate' => $oldestBesoinDate
            ];
        }

        usort($dashboard, function ($a, $b) {
            $aDate = $a['oldestBesoinDate'] ?? null;
            $bDate = $b['oldestBesoinDate'] ?? null;

            if ($aDate === null && $bDate === null) {
                return 0;
            }
            if ($aDate === null) {
                return 1;
            }
            if ($bDate === null) {
                return -1;
            }

            if ($aDate === $bDate) {
                return 0;
            }

            return $aDate < $bDate ? -1 : 1;
        });

        Flight::render('modele.php', [
            'contentPage' => 'dashboard/index',
            'currentPage' => 'dashboard',
            'pageTitle' => 'Dashboard - BNGRC',
            'dashboard' => $dashboard,
            'totalVilles' => count($villes),
            'totalBesoins' => count($allBesoins),
            'totalDons' => count($allDons)
        ]);
    }

    // private function getDispatches() {
    //     // Récupérer tous les dispatches
    //     try {
    //         $pdo = new \PDO(
    //             "mysql:host=localhost;dbname=bngrc_final_s3;charset=utf8mb4",
    //             'root',
    //             '',
    //             [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
    //         );
    //         $stmt = $pdo->query("SELECT * FROM dispatch");
    //         return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    //     } catch (\Exception $e) {
    //         return [];
    //     }
    // }

    public function simulate() {
        $donModel = new Don();
        $preview = $donModel->previewDispatch();
        
        $_SESSION['simulation_preview'] = $preview;
        
        header('Location: ' . Flight::get('flight.base_url') . '/simulate-preview');
        exit();
    }

    public function previewSimulation() {
        $preview = $_SESSION['simulation_preview'] ?? null;
        
        if (!$preview) {
            $_SESSION['error'] = 'Aucune simulation en cours.';
            header('Location: ' . Flight::get('flight.base_url') . '/dashboard');
            exit();
        }

        Flight::render('modele.php', [
            'contentPage' => 'dashboard/simulation-preview',
            'currentPage' => 'dashboard',
            'pageTitle' => 'Aperçu Simulation - BNGRC',
            'preview' => $preview
        ]);
    }

    public function validerSimulation() {
        $preview = $_SESSION['simulation_preview'] ?? null;
        
        if (!$preview || !isset($preview['details'])) {
            $_SESSION['error'] = 'Simulation invalide.';
            header('Location: ' . Flight::get('flight.base_url') . '/dashboard');
            exit();
        }

        $donModel = new Don();
        $result = $donModel->validerDispatch($preview['details']);
        
        $_SESSION['success'] = "Dispatch validé: {$result['creations']} allocations créées.";
        unset($_SESSION['simulation_preview']);
        
        header('Location: ' . Flight::get('flight.base_url') . '/dashboard');
        exit();
    }

    public function annulerSimulation() {
        unset($_SESSION['simulation_preview']);
        $_SESSION['info'] = 'Simulation annulée.';
        
        header('Location: ' . Flight::get('flight.base_url') . '/dashboard');
        exit();
    }
}
