<?php
namespace app\controllers;

use app\models\Ville;
use app\models\Besoin;
use app\models\Don;
use app\models\Produit;
use app\models\Categorie;

use Flight;

class DashboardController {
    
    public function index() {
        $villeModel = new Ville();
        $besoinModel = new Besoin();
        $donModel = new Don();
        $produitModel = new Produit();
        $categorieModel = new Categorie();
        
        $villes = $villeModel->getAllVilles();
        $allBesoins = $besoinModel->getAllBesoins();
        $allDons = $donModel->getAllDons();
        $allProduits = $produitModel->getAllProduits();
        $allCategories = $categorieModel->getAllCategories();
        
        // Index des produits par ID
        $produitsById = [];
        foreach ($allProduits as $produit) {
            $produitsById[$produit['id']] = $produit;
        }
        
        // Index des catégories par ID
        $categoriesById = [];
        foreach ($allCategories as $categorie) {
            $categoriesById[$categorie['id']] = $categorie['libelle'];
        }
        
        $dashboard = [];
        
        foreach ($villes as $ville) {
            $besoinsVille = array_filter($allBesoins, fn($b) => $b['id_ville'] == $ville['id']);
            
            $quantiteRestante = 0;
            foreach ($besoinsVille as $besoin) {
                $quantiteRestante += ($besoin['quantite_restante'] ?? $besoin['quantite']);
            }

            // Grouper par catégorie
            $parCategorie = [];
            foreach ($besoinsVille as $besoin) {
                $idProduit = $besoin['id_produit'] ?? null;
                if ($idProduit && isset($produitsById[$idProduit])) {
                    $produit = $produitsById[$idProduit];
                    $idCategorie = $produit['id_categorie'] ?? 0;
                    $nomCategorie = $categoriesById[$idCategorie] ?? 'Autre';
                    
                    if (!isset($parCategorie[$nomCategorie])) {
                        $parCategorie[$nomCategorie] = [
                            'nom' => $nomCategorie,
                            'produits' => [],
                            'nbBesoins' => 0,
                            'quantiteNecessaire' => 0,
                            'quantiteAllouee' => 0,
                            'quantiteRestante' => 0
                        ];
                    }
                    
                    // Ajouter le produit à la liste
                    $parCategorie[$nomCategorie]['produits'][$produit['nom']] = true;
                    $parCategorie[$nomCategorie]['nbBesoins']++;
                    $parCategorie[$nomCategorie]['quantiteNecessaire'] += $besoin['quantite'];
                    $restantBesoin = $besoin['quantite_restante'] ?? $besoin['quantite'];
                    $parCategorie[$nomCategorie]['quantiteRestante'] += $restantBesoin;
                    $parCategorie[$nomCategorie]['quantiteAllouee'] += ($besoin['quantite'] - $restantBesoin);
                }
            }
            
            // Convertir les produits en liste
            foreach ($parCategorie as &$cat) {
                $cat['produits'] = array_keys($cat['produits']);
            }
            unset($cat);
            
            // Liste des produits pour l'affichage principal (maintenant par catégorie)
            $categoriesVille = array_keys($parCategorie);
            
            $totalBesoinsQuantite = 0;
            foreach ($besoinsVille as $besoin) {
                $totalBesoinsQuantite += $besoin['quantite'];
            }
            
            $quantiteAllouee = $totalBesoinsQuantite - $quantiteRestante;
            
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
                'produits' => $categoriesVille,
                'parCategorie' => array_values($parCategorie),
                'etat' => $etat,
                'pourcentage' => $pourcentage,
                'oldestBesoinDate' => $oldestBesoinDate
            ];
        }

        usort($dashboard, function ($a, $b) {
            $aDate = $a['oldestBesoinDate'] ?? null;
            $bDate = $b['oldestBesoinDate'] ?? null;

            if ($aDate === null && $bDate === null) return 0;
            if ($aDate === null) return 1;
            if ($bDate === null) return -1;
            if ($aDate === $bDate) return 0;
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

    /**
     * Page de détails des besoins et dons par produit
     */
    public function details() {
        $donModel = new Don();
        $besoinModel = new Besoin();
        $produitModel = new Produit();
        $villeModel = new Ville();
        
        $produits = $produitModel->getAllProduits();
        $villes = $villeModel->getAllVilles();
        
        $detailsParProduit = [];
        
        foreach ($produits as $produit) {
            $idProduit = $produit['id'];
            
            // Total des dons pour ce produit
            $dons = $donModel->getAllDons();
            $totalDons = 0;
            $donsProduit = [];
            foreach ($dons as $don) {
                if ($don['id_produit'] == $idProduit) {
                    $totalDons += $don['quantite'];
                    $donsProduit[] = $don;
                }
            }
            
            // Total des besoins pour ce produit
            $besoins = $besoinModel->getAllBesoins();
            $totalBesoins = 0;
            $totalRestant = 0;
            $besoinsProduit = [];
            foreach ($besoins as $besoin) {
                if ($besoin['id_produit'] == $idProduit) {
                    $totalBesoins += $besoin['quantite'];
                    $totalRestant += ($besoin['quantite_restante'] ?? $besoin['quantite']);
                    $besoinsProduit[] = $besoin;
                }
            }
            
            $totalSatisfait = $totalBesoins - $totalRestant;
            $pourcentage = $totalBesoins > 0 ? round(($totalSatisfait / $totalBesoins) * 100, 2) : 0;
            
            // Besoins par ville
            $besoinsParVille = [];
            foreach ($villes as $ville) {
                $qteVille = 0;
                $restantVille = 0;
                foreach ($besoinsProduit as $b) {
                    if ($b['id_ville'] == $ville['id']) {
                        $qteVille += $b['quantite'];
                        $restantVille += ($b['quantite_restante'] ?? $b['quantite']);
                    }
                }
                if ($qteVille > 0) {
                    $besoinsParVille[] = [
                        'ville' => $ville['nom'],
                        'quantite' => $qteVille,
                        'restant' => $restantVille,
                        'satisfait' => $qteVille - $restantVille
                    ];
                }
            }
            
            $detailsParProduit[] = [
                'produit' => $produit,
                'total_dons' => $totalDons,
                'total_besoins' => $totalBesoins,
                'total_satisfait' => $totalSatisfait,
                'total_restant' => $totalRestant,
                'pourcentage' => $pourcentage,
                'nb_dons' => count($donsProduit),
                'nb_besoins' => count($besoinsProduit),
                'besoins_par_ville' => $besoinsParVille
            ];
        }
        
        Flight::render('modele.php', [
            'contentPage' => 'dashboard/details',
            'currentPage' => 'dashboard',
            'pageTitle' => 'Détails par Produit - BNGRC',
            'detailsParProduit' => $detailsParProduit
        ]);
    }

    /**
     * Page de simulation avec choix de stratégie par catégorie
     */
    public function simulate() {
        $donModel = new Don();
        
        // Récupérer les dons restants par catégorie
        $categories = $donModel->getDonsRestantsParCategorie();
        
        // Initialiser la session de simulation
        $_SESSION['simulation_preview'] = [
            'categories' => $categories,
            'details' => [],
            'dispatch_crees' => 0
        ];
        $_SESSION['strategies_confirmees'] = [];
        
        header('Location: ' . Flight::get('flight.base_url') . '/simulate-preview');
        exit();
    }

    /**
     * Affiche l'aperçu de simulation avec sélection de stratégies
     */
    public function previewSimulation() {
        $donModel = new Don();
        
        // Toujours récupérer les catégories fraîches
        $categories = $donModel->getDonsRestantsParCategorie();
        $strategiesConfirmees = $_SESSION['strategies_confirmees'] ?? [];
        $preview = $_SESSION['simulation_preview'] ?? ['details' => [], 'dispatch_crees' => 0];

        Flight::render('modele.php', [
            'contentPage' => 'dashboard/simulation-preview',
            'currentPage' => 'dashboard',
            'pageTitle' => 'Aperçu Simulation - BNGRC',
            'categories' => $categories,
            'strategiesConfirmees' => $strategiesConfirmees,
            'preview' => $preview
        ]);
    }

    /**
     * AJAX: Prévisualise une stratégie pour un produit
     */
    public function previewStrategie() {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        $idProduit = (int) ($data['id_produit'] ?? 0);
        $strategie = $data['strategie'] ?? '';

        if (!$idProduit || !$strategie) {
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
            return;
        }

        $donModel = new Don();
        $details = [];

        switch ($strategie) {
            case 'date':
                $details = $donModel->strategieParDate($idProduit);
                break;
            case 'moins_besoins':
                $details = $donModel->strategieMoinsBesoins($idProduit);
                break;
            case 'proportionnel':
                $details = $donModel->strategieProportionnel($idProduit);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Stratégie inconnue']);
                return;
        }

        // Grouper par ville pour l'affichage
        $parVille = [];
        foreach ($details as $d) {
            $ville = $d['ville_nom'] ?? 'Inconnue';
            if (!isset($parVille[$ville])) {
                $parVille[$ville] = ['quantite' => 0, 'count' => 0];
            }
            $parVille[$ville]['quantite'] += $d['quantite'];
            $parVille[$ville]['count']++;
        }

        $preview = [];
        foreach ($parVille as $ville => $data) {
            $preview[] = [
                'ville' => $ville,
                'quantite' => $data['quantite'],
                'allocations' => $data['count']
            ];
        }

        echo json_encode([
            'success' => true,
            'preview' => $preview,
            'total_allocations' => count($details),
            'total_quantite' => array_sum(array_column($details, 'quantite'))
        ]);
    }

    /**
     * AJAX: Confirme une stratégie pour un produit
     */
    public function confirmerStrategie() {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        $idProduit = (int) ($data['id_produit'] ?? 0);
        $strategie = $data['strategie'] ?? '';

        if (!$idProduit || !$strategie) {
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
            return;
        }

        $donModel = new Don();
        $details = [];

        switch ($strategie) {
            case 'date':
                $details = $donModel->strategieParDate($idProduit);
                break;
            case 'moins_besoins':
                $details = $donModel->strategieMoinsBesoins($idProduit);
                break;
            case 'proportionnel':
                $details = $donModel->strategieProportionnel($idProduit);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Stratégie inconnue']);
                return;
        }

        if (empty($details)) {
            echo json_encode(['success' => false, 'message' => 'Aucune allocation possible']);
            return;
        }

        // Stocker en session
        $preview = $_SESSION['simulation_preview'] ?? ['details' => [], 'dispatch_crees' => 0];
        
        // Supprimer les anciennes allocations pour ce produit
        $idsDons = $donModel->getIdsDonsPourProduit($idProduit);
        $preview['details'] = array_filter($preview['details'], function($d) use ($idsDons) {
            return !in_array($d['id_don'], $idsDons);
        });
        
        // Ajouter les nouvelles
        $preview['details'] = array_merge(array_values($preview['details']), $details);
        $preview['dispatch_crees'] = count($preview['details']);
        
        $_SESSION['simulation_preview'] = $preview;
        
        // Marquer comme confirmé
        if (!isset($_SESSION['strategies_confirmees'])) {
            $_SESSION['strategies_confirmees'] = [];
        }
        $_SESSION['strategies_confirmees'][$idProduit] = $strategie;

        echo json_encode([
            'success' => true,
            'message' => 'Stratégie confirmée',
            'total_allocations' => count($details)
        ]);
    }

    /**
     * Valide toutes les stratégies confirmées et crée les dispatch en base
     */
    public function validerSimulation() {
        $preview = $_SESSION['simulation_preview'] ?? null;
        $strategiesConfirmees = $_SESSION['strategies_confirmees'] ?? [];
        
        if (!$preview || empty($preview['details'])) {
            $_SESSION['error'] = 'Aucune allocation à valider. Sélectionnez des stratégies d\'abord.';
            header('Location: ' . Flight::get('flight.base_url') . '/simulate-preview');
            exit();
        }

        if (empty($strategiesConfirmees)) {
            $_SESSION['error'] = 'Aucune stratégie confirmée. Cliquez sur "Confirmer" pour chaque catégorie.';
            header('Location: ' . Flight::get('flight.base_url') . '/simulate-preview');
            exit();
        }

        $donModel = new Don();
        $result = $donModel->validerDispatch($preview['details']);
        
        // Nettoyer la session
        unset($_SESSION['simulation_preview']);
        unset($_SESSION['strategies_confirmees']);
        
        $_SESSION['success'] = "✅ Dispatch validé: {$result['creations']} allocations créées.";
        
        header('Location: ' . Flight::get('flight.base_url') . '/dashboard');
        exit();
    }

    /**
     * Annule la simulation en cours
     */
    public function annulerSimulation() {
        unset($_SESSION['simulation_preview']);
        unset($_SESSION['strategies_confirmees']);
        $_SESSION['info'] = 'Simulation annulée.';
        
        header('Location: ' . Flight::get('flight.base_url') . '/dashboard');
        exit();
    }
}
