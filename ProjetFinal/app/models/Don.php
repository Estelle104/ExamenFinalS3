<?php
namespace app\models;

use PDO;
use PDOException;

class Don extends Db
{
    private $table = 'dons';

    /**
     * Ajoute un don.
     */
    public function addDon(
        string $description,
        int $idProduit,
        ?int $idVille,
        int $quantite,
        ?string $dateDon,
        string $donneur,
        ?int $idRegion = null
    ): int {
        $dateDon = $dateDon ?: date('Y-m-d');
        $sql = "INSERT INTO {$this->table} (description, id_produit, id_ville, id_region, quantite, date_don, donneur)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->execute($sql, [$description, $idProduit, $idVille, $idRegion, $quantite, $dateDon, $donneur]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Récupère tous les dons avec le nom du produit et de la ville.
     */
    public function getAllDons(): array
    {
        $sql = "SELECT d.*, p.nom AS produit_nom, v.nom AS ville_nom
                FROM {$this->table} d
                LEFT JOIN produits p ON p.id = d.id_produit
                LEFT JOIN villes v ON v.id = d.id_ville
                ORDER BY d.date_don ASC, d.id DESC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Simule l'attribution des dons aux besoins compatibles.
     */
    /**
     * Prévisualise la simulation sans modifier la BDD
     */
    public function previewDispatch(): array
    {
        $summary = [
            'dispatch_crees' => 0,
            'dons_traite' => 0,
            'details' => []
        ];

        // Récalculer les quantités restantes basées sur les achats effectués
        $achats = $this->execute("SELECT * FROM achats ORDER BY date_achat ASC, id ASC")
            ->fetchAll(PDO::FETCH_ASSOC);

        $besoinsRestants = [];
        $besoinsData = $this->execute("SELECT id, quantite FROM besoins")->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($besoinsData as $besoin) {
            $besoinsRestants[$besoin['id']] = $besoin['quantite'];
        }

        // Appliquer les achats pour calculer les restants
        foreach ($achats as $achat) {
            $besoins = $this->execute(
                "SELECT id, quantite FROM besoins WHERE id_produit = ? AND COALESCE(quantite_restante, quantite) > 0",
                [(int) $achat['id_produit']]
            )->fetchAll(PDO::FETCH_ASSOC);

            $remaining = $achat['quantite'];
            foreach ($besoins as $besoin) {
                if ($remaining <= 0) break;
                $restant = $besoinsRestants[$besoin['id']] ?? $besoin['quantite'];
                if ($restant <= 0) continue;
                
                $utilise = min($remaining, $restant);
                $besoinsRestants[$besoin['id']] = $restant - $utilise;
                $remaining -= $utilise;
            }
        }

        // Simuler l'allocation des dons
        $dons = $this->execute(
            "SELECT * FROM {$this->table} ORDER BY date_don ASC, id ASC"
        )->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dons as $don) {
            $remainingDon = $don['quantite'] - $this->getQuantiteAttribueePourDon((int) $don['id']);
            if ($remainingDon <= 0) {
                continue;
            }

            $summary['dons_traite']++;

            $besoins = $this->getBesoinsCompatibles(
                (int) $don['id_produit'],
                $don['id_ville'] ? (int) $don['id_ville'] : null,
                $don['id_region'] ? (int) $don['id_region'] : null
            );

            foreach ($besoins as $besoin) {
                if ($remainingDon <= 0) {
                    break;
                }

                $remainingBesoin = $besoinsRestants[$besoin['id']] ?? 0;
                if ($remainingBesoin <= 0) {
                    continue;
                }

                $quantiteAttribuee = min($remainingDon, $remainingBesoin);
                if ($quantiteAttribuee <= 0) {
                    continue;
                }

                $summary['details'][] = [
                    'id_don' => $don['id'],
                    'id_besoin' => $besoin['id'],
                    'quantite' => $quantiteAttribuee
                ];

                $summary['dispatch_crees']++;
                $remainingDon -= $quantiteAttribuee;
                $besoinsRestants[$besoin['id']] -= $quantiteAttribuee;
            }
        }

        return $summary;
    }

    /**
     * Valide la simulation et applique réellement les changements
     */
    public function validerDispatch(array $details): array
    {
        $summary = ['creations' => 0, 'erreurs' => 0];

        $this->db->beginTransaction();
        try {
            foreach ($details as $detail) {
                try {
                    $this->execute(
                        'INSERT INTO dispatch (id_don, id_besoin, quantite_attribuee) VALUES (?, ?, ?)',
                        [(int) $detail['id_don'], (int) $detail['id_besoin'], (int) $detail['quantite']]
                    );
                    
                    // Mettre à jour le besoin
                    $besoin = $this->execute("SELECT * FROM besoins WHERE id = ?", [(int) $detail['id_besoin']])->fetch(PDO::FETCH_ASSOC);
                    if ($besoin) {
                        $newRestant = max(0, $besoin['quantite_restante'] - (int) $detail['quantite']);
                        $this->updateEtatBesoin((int) $besoin['id'], (int) $besoin['quantite'], $newRestant);
                    }
                    
                    $summary['creations']++;
                } catch (PDOException $e) {
                    $summary['erreurs']++;
                }
            }
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            $summary['erreurs']++;
        }

        return $summary;
    }

    /**
     * Récupère les dons restants groupés par catégorie (produit)
     */
    public function getDonsRestantsParCategorie(): array
    {
        $sql = "SELECT 
                    p.id AS id_produit,
                    p.nom AS produit_nom,
                    SUM(d.quantite) AS quantite_totale,
                    COALESCE((
                        SELECT SUM(disp.quantite_attribuee) 
                        FROM dispatch disp 
                        WHERE disp.id_don IN (SELECT id FROM dons WHERE id_produit = p.id)
                    ), 0) AS quantite_attribuee
                FROM dons d
                INNER JOIN produits p ON p.id = d.id_produit
                GROUP BY p.id, p.nom
                HAVING (SUM(d.quantite) - COALESCE((
                    SELECT SUM(disp.quantite_attribuee) 
                    FROM dispatch disp 
                    WHERE disp.id_don IN (SELECT id FROM dons WHERE id_produit = p.id)
                ), 0)) > 0
                ORDER BY p.nom ASC";
        
        $result = $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculer quantite_restante
        foreach ($result as &$row) {
            $row['quantite_restante'] = $row['quantite_totale'] - $row['quantite_attribuee'];
        }
        
        return $result;
    }

    /**
     * Récupère les IDs de tous les dons pour un produit donné
     */
    public function getIdsDonsPourProduit(int $idProduit): array
    {
        $sql = "SELECT id FROM dons WHERE id_produit = ?";
        $stmt = $this->execute($sql, [$idProduit]);
        $ids = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ids[] = $row['id'];
        }
        return $ids;
    }

    /**
     * Stratégie 1: Dispatch par date (besoins les plus anciens d'abord)
     */
    public function strategieParDate(int $idProduit): array
    {
        $details = [];
        
        // Récupérer les dons de ce produit avec leurs quantités restantes
        $dons = $this->execute(
            "SELECT d.*, 
                    d.quantite - COALESCE((SELECT SUM(quantite_attribuee) FROM dispatch WHERE id_don = d.id), 0) AS restant
             FROM dons d 
             WHERE d.id_produit = ? 
             HAVING restant > 0
             ORDER BY d.date_don ASC, d.id ASC",
            [$idProduit]
        )->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les besoins de ce produit avec leurs quantités restantes
        $besoins = $this->execute(
            "SELECT * FROM besoins 
             WHERE id_produit = ? AND quantite_restante > 0
             ORDER BY date_besoin ASC, id ASC",
            [$idProduit]
        )->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dons as $don) {
            $remainingDon = (int) $don['restant'];
            if ($remainingDon <= 0) continue;

            foreach ($besoins as &$besoin) {
                if ($remainingDon <= 0) break;
                
                $remainingBesoin = (int) $besoin['quantite_restante'];
                if ($remainingBesoin <= 0) continue;

                $quantite = min($remainingDon, $remainingBesoin);
                
                $details[] = [
                    'id_don' => $don['id'],
                    'id_besoin' => $besoin['id'],
                    'quantite' => $quantite,
                    'ville_nom' => $this->getVilleNom($besoin['id_ville'])
                ];

                $remainingDon -= $quantite;
                $besoin['quantite_restante'] -= $quantite;
            }
        }

        return $details;
    }

    /**
     * Stratégie 2: Dispatch aux villes avec le moins de besoins d'abord
     */
    public function strategieMoinsBesoins(int $idProduit): array
    {
        $details = [];
        
        // Récupérer les dons de ce produit avec leurs quantités restantes
        $dons = $this->execute(
            "SELECT d.*, 
                    d.quantite - COALESCE((SELECT SUM(quantite_attribuee) FROM dispatch WHERE id_don = d.id), 0) AS restant
             FROM dons d 
             WHERE d.id_produit = ? 
             HAVING restant > 0
             ORDER BY d.date_don ASC, d.id ASC",
            [$idProduit]
        )->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les besoins groupés par ville, ordonnés par quantité totale croissante
        $besoins = $this->execute(
            "SELECT b.*, v.nom AS ville_nom,
                    (SELECT SUM(quantite) FROM besoins WHERE id_ville = b.id_ville AND id_produit = ?) AS total_ville
             FROM besoins b
             LEFT JOIN villes v ON v.id = b.id_ville
             WHERE b.id_produit = ? AND b.quantite_restante > 0
             ORDER BY total_ville ASC, b.date_besoin ASC, b.id ASC",
            [$idProduit, $idProduit]
        )->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dons as $don) {
            $remainingDon = (int) $don['restant'];
            if ($remainingDon <= 0) continue;

            foreach ($besoins as &$besoin) {
                if ($remainingDon <= 0) break;
                
                $remainingBesoin = (int) $besoin['quantite_restante'];
                if ($remainingBesoin <= 0) continue;

                $quantite = min($remainingDon, $remainingBesoin);
                
                $details[] = [
                    'id_don' => $don['id'],
                    'id_besoin' => $besoin['id'],
                    'quantite' => $quantite,
                    'ville_nom' => $besoin['ville_nom'] ?? 'Inconnue'
                ];

                $remainingDon -= $quantite;
                $besoin['quantite_restante'] -= $quantite;
            }
        }

        return $details;
    }

    /**
     * Stratégie 3: Dispatch proportionnel (répartition équitable)
     */
    public function strategieProportionnel(int $idProduit): array
    {
        $details = [];
        
        // Calculer la quantité totale de dons disponibles pour ce produit
        $totalDonsDisponibles = (int) ($this->execute(
            "SELECT SUM(d.quantite - COALESCE((SELECT SUM(quantite_attribuee) FROM dispatch WHERE id_don = d.id), 0)) AS total
             FROM dons d 
             WHERE d.id_produit = ?",
            [$idProduit]
        )->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

        if ($totalDonsDisponibles <= 0) return $details;

        // Récupérer les besoins groupés par ville
        $besoinsParVille = $this->execute(
            "SELECT b.id_ville, v.nom AS ville_nom, SUM(b.quantite_restante) AS total_restant
             FROM besoins b
             LEFT JOIN villes v ON v.id = b.id_ville
             WHERE b.id_produit = ? AND b.quantite_restante > 0
             GROUP BY b.id_ville, v.nom
             ORDER BY v.nom ASC",
            [$idProduit]
        )->fetchAll(PDO::FETCH_ASSOC);

        $totalBesoins = array_sum(array_column($besoinsParVille, 'total_restant'));
        if ($totalBesoins <= 0) return $details;

        // ÉTAPE 1: Calculer les allocations proportionnelles avec parties décimales
        $allocations = [];
        $sommeArrondis = 0;
        
        foreach ($besoinsParVille as $index => $villeData) {
            $proportion = $villeData['total_restant'] / $totalBesoins;
            $allocationExacte = $totalDonsDisponibles * $proportion;
            $partieEntiere = (int) floor($allocationExacte);
            $partieDecimale = $allocationExacte - $partieEntiere;
            
            $allocations[$index] = [
                'id_ville' => $villeData['id_ville'],
                'ville_nom' => $villeData['ville_nom'],
                'total_restant' => $villeData['total_restant'],
                'allocation_exacte' => $allocationExacte,
                'allocation_arrondie' => $partieEntiere,
                'partie_decimale' => $partieDecimale
            ];
            
            $sommeArrondis += $partieEntiere;
        }
        
        // ÉTAPE 2: Calculer le reste à distribuer
        $reste = $totalDonsDisponibles - $sommeArrondis;
        
        // ÉTAPE 3: Distribuer le reste selon la partie décimale (plus grande d'abord)
        if ($reste > 0) {
            // Trier par partie décimale décroissante
            usort($allocations, function($a, $b) {
                return $b['partie_decimale'] <=> $a['partie_decimale'];
            });
            
            // Distribuer le reste en boucle
            $indexAlloc = 0;
            $nbAllocations = count($allocations);
            
            while ($reste > 0 && $nbAllocations > 0) {
                // Vérifier qu'on ne dépasse pas le besoin de cette ville
                $villeAlloc = &$allocations[$indexAlloc];
                $maxPossible = $villeAlloc['total_restant'] - $villeAlloc['allocation_arrondie'];
                
                if ($maxPossible > 0) {
                    $villeAlloc['allocation_arrondie']++;
                    $reste--;
                }
                
                // Passer à la ville suivante (boucle circulaire)
                $indexAlloc = ($indexAlloc + 1) % $nbAllocations;
                
                // Si on a fait un tour complet sans pouvoir distribuer, sortir
                if ($indexAlloc === 0) {
                    $peutEncoreDistribuer = false;
                    foreach ($allocations as $alloc) {
                        if ($alloc['total_restant'] > $alloc['allocation_arrondie']) {
                            $peutEncoreDistribuer = true;
                            break;
                        }
                    }
                    if (!$peutEncoreDistribuer) break;
                }
            }
        }

        // Récupérer les dons disponibles
        $dons = $this->execute(
            "SELECT d.*, 
                    d.quantite - COALESCE((SELECT SUM(quantite_attribuee) FROM dispatch WHERE id_don = d.id), 0) AS restant
             FROM dons d 
             WHERE d.id_produit = ? 
             HAVING restant > 0
             ORDER BY d.date_don ASC, d.id ASC",
            [$idProduit]
        )->fetchAll(PDO::FETCH_ASSOC);

        // ÉTAPE 4: Appliquer les allocations finales
        foreach ($allocations as $villeAlloc) {
            $quantiteAllouee = $villeAlloc['allocation_arrondie'];
            
            if ($quantiteAllouee <= 0) continue;

            // Récupérer les besoins de cette ville
            $besoinsVille = $this->execute(
                "SELECT * FROM besoins 
                 WHERE id_produit = ? AND id_ville = ? AND quantite_restante > 0
                 ORDER BY date_besoin ASC, id ASC",
                [$idProduit, $villeAlloc['id_ville']]
            )->fetchAll(PDO::FETCH_ASSOC);

            $remainingAlloue = $quantiteAllouee;

            foreach ($dons as &$don) {
                if ($remainingAlloue <= 0) break;
                
                $remainingDon = (int) $don['restant'];
                if ($remainingDon <= 0) continue;

                foreach ($besoinsVille as &$besoin) {
                    if ($remainingAlloue <= 0 || $remainingDon <= 0) break;
                    
                    $remainingBesoin = (int) $besoin['quantite_restante'];
                    if ($remainingBesoin <= 0) continue;

                    $quantite = min($remainingDon, $remainingBesoin, $remainingAlloue);
                    
                    $details[] = [
                        'id_don' => $don['id'],
                        'id_besoin' => $besoin['id'],
                        'quantite' => $quantite,
                        'ville_nom' => $villeAlloc['ville_nom'] ?? 'Inconnue'
                    ];

                    $remainingDon -= $quantite;
                    $don['restant'] -= $quantite;
                    $besoin['quantite_restante'] -= $quantite;
                    $remainingAlloue -= $quantite;
                }
            }
        }

        return $details;
    }

    /**
     * Récupère le nom d'une ville
     */
    private function getVilleNom(?int $idVille): string
    {
        if (!$idVille) return 'Non spécifié';
        $result = $this->execute("SELECT nom FROM villes WHERE id = ?", [$idVille])->fetch(PDO::FETCH_ASSOC);
        return $result['nom'] ?? 'Inconnue';
    }

    public function simulerDispatch(): array
    {
        $summary = [
            'dispatch_crees' => 0,
            'dons_traite' => 0,
        ];

        $this->db->beginTransaction();
        try {
            // Réinitialiser l'état des besoins et le dispatch pour une simulation propre
            $this->execute("UPDATE besoins SET quantite_restante = quantite, etat = 'En attente'");
            $this->execute("DELETE FROM dispatch");

            // Appliquer les achats (sur argent) avant l'allocation des dons
            $this->applyAchatsSurBesoinsSameConnection();

            $dons = $this->execute(
                "SELECT * FROM {$this->table} ORDER BY date_don ASC, id ASC"
            )->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dons as $don) {
                $remainingDon = $don['quantite'] - $this->getQuantiteAttribueePourDon((int) $don['id']);
                if ($remainingDon <= 0) {
                    continue;
                }

                $summary['dons_traite']++;

                // Passer la ville ET région du don pour filtrage correct
                $besoins = $this->getBesoinsCompatibles(
                    (int) $don['id_produit'],
                    $don['id_ville'] ? (int) $don['id_ville'] : null,
                    $don['id_region'] ? (int) $don['id_region'] : null
                );
                foreach ($besoins as $besoin) {
                    if ($remainingDon <= 0) {
                        break;
                    }

                    $remainingBesoin = $besoin['quantite'] - $this->getQuantiteAttribueePourBesoin((int) $besoin['id']);
                    if ($remainingBesoin <= 0) {
                        $this->updateEtatBesoin((int) $besoin['id'], (int) $besoin['quantite'], 0);
                        continue;
                    }

                    $quantiteAttribuee = min($remainingDon, $remainingBesoin);
                    if ($quantiteAttribuee <= 0) {
                        continue;
                    }

                    $this->execute(
                        'INSERT INTO dispatch (id_don, id_besoin, quantite_attribuee) VALUES (?, ?, ?)',
                        [(int) $don['id'], (int) $besoin['id'], (int) $quantiteAttribuee]
                    );

                    $summary['dispatch_crees']++;

                    $remainingDon -= $quantiteAttribuee;
                    $remainingBesoin -= $quantiteAttribuee;

                    $this->updateEtatBesoin(
                        (int) $besoin['id'],
                        (int) $besoin['quantite'],
                        (int) $remainingBesoin
                    );

                }
            }

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }

        return $summary;
    }

    /**
     * Calcule les totaux des besoins et dons par ville.
     * @return array Liste des villes avec total_besoins et total_dons.
     */
    public function statistiquesParVille(): array
    {
        $sql = "SELECT v.id, v.nom,
                       COALESCE(b.total_besoins, 0) AS total_besoins,
                       COALESCE(d.total_dons, 0) AS total_dons
                FROM villes v
                LEFT JOIN (
                    SELECT id_ville, SUM(quantite) AS total_besoins
                    FROM besoins
                    GROUP BY id_ville
                ) b ON b.id_ville = v.id
                LEFT JOIN (
                    SELECT id_ville, SUM(quantite) AS total_dons
                    FROM {$this->table}
                    GROUP BY id_ville
                ) d ON d.id_ville = v.id
                ORDER BY v.nom ASC";

        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule les dons attribués par ville à partir du dispatch.
     * @return array Liste des villes avec total_attribue.
     */
    public function statistiquesAttributionsParVille(): array
    {
        $sql = "SELECT v.id, v.nom,
                       COALESCE(a.total_attribue, 0) AS total_attribue
                FROM villes v
                LEFT JOIN (
                    SELECT b.id_ville, SUM(disp.quantite_attribuee) AS total_attribue
                    FROM dispatch disp
                    INNER JOIN besoins b ON b.id = disp.id_besoin
                    GROUP BY b.id_ville
                ) a ON a.id_ville = v.id
                ORDER BY v.nom ASC";

        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule les totaux globaux des besoins et des dons.
     * @return array Tableau avec total_besoins et total_dons.
     */
    public function getTotals(): array
    {
        $sql = "SELECT
                    (SELECT COALESCE(SUM(quantite), 0) FROM besoins) AS total_besoins,
                    (SELECT COALESCE(SUM(quantite), 0) FROM {$this->table}) AS total_dons";

        $row = $this->execute($sql)->fetch(PDO::FETCH_ASSOC);
        return $row ?: ['total_besoins' => 0, 'total_dons' => 0];
    }

    private function getBesoinsCompatibles(int $idProduit, ?int $idVille, ?int $idRegion): array
    {
        // Filtrer par produit ET par localisation (ville ou région)
        $sql = "SELECT * FROM besoins WHERE id_produit = ? ";
        $params = [$idProduit];
        
        // Si don a une ville → chercher besoins de la même ville
        if ($idVille !== null) {
            $sql .= "AND id_ville = ? ";
            $params[] = $idVille;
        }
        // Sinon si don a une région → chercher besoins de la même région (toutes villes)
        elseif ($idRegion !== null) {
            $sql .= "AND id_region = ? ";
            $params[] = $idRegion;
        }
        
        $sql .= "ORDER BY COALESCE(date_besoin, '0000-00-00') ASC, id ASC";
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule la quantité déjà attribuée pour un don.
     * @return int Quantité attribuée.
     */
    private function getQuantiteAttribueePourDon(int $idDon): int
    {
        $sql = "SELECT COALESCE(SUM(quantite_attribuee), 0) AS total
                FROM dispatch
                WHERE id_don = ?";
        $row = $this->execute($sql, [$idDon])->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Calcule la quantité déjà attribuée pour un besoin.
     * @return int Quantité attribuée.
     */
    private function getQuantiteAttribueePourBesoin(int $idBesoin): int
    {
        $sql = "SELECT COALESCE(SUM(quantite_attribuee), 0) AS total
                FROM dispatch
                WHERE id_besoin = ?";
        $row = $this->execute($sql, [$idBesoin])->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['total'] ?? 0);
    }

    private function updateEtatBesoin(int $idBesoin, int $quantiteTotale, int $restant): int
    {
        $etat = 'En attente';
        if ($restant <= 0) {
            $etat = 'Satisfait';
        } elseif ($restant < $quantiteTotale) {
            $etat = 'Partiel';
        }

        try {
            // Mettre à jour l'état ET la quantité_restante (préserve quantité originale)
            $sql = "UPDATE besoins SET etat = ?, quantite_restante = ? WHERE id = ?";
            $this->execute($sql, [$etat, max(0, $restant), $idBesoin]);
            return 1;
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Applique tous les achats sur les besoins en utilisant la même connexion (évite les locks).
     */
    private function applyAchatsSurBesoinsSameConnection(): void
    {
        $achats = $this->execute("SELECT * FROM achats ORDER BY date_achat ASC, id ASC")
            ->fetchAll(PDO::FETCH_ASSOC);

        foreach ($achats as $achat) {
            $this->applyAchatToBesoinsSameConnection(
                (int) $achat['id_produit'],
                $achat['id_ville'] ? (int) $achat['id_ville'] : null,
                (int) $achat['quantite']
            );
        }
    }

    
    private function applyAchatToBesoinsSameConnection(int $idProduit, ?int $idVille, int $quantite): void
    {
        $sql = "SELECT * FROM besoins WHERE id_produit = ? AND COALESCE(quantite_restante, quantite) > 0 ";
        $params = [$idProduit];
        if ($idVille !== null) {
            $sql .= "AND id_ville = ? ";
            $params[] = $idVille;
        }
        $sql .= "ORDER BY COALESCE(date_besoin, '0000-00-00') ASC, id ASC";

        $besoins = $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);

        $remaining = $quantite;
        foreach ($besoins as $besoin) {
            if ($remaining <= 0) {
                break;
            }

            $restantBesoin = (int) ($besoin['quantite_restante'] ?? $besoin['quantite']);
            if ($restantBesoin <= 0) {
                $this->updateEtatBesoin((int) $besoin['id'], (int) $besoin['quantite'], 0);
                continue;
            }

            $utilise = min($remaining, $restantBesoin);
            $restantBesoin -= $utilise;
            $remaining -= $utilise;

            $this->updateEtatBesoin((int) $besoin['id'], (int) $besoin['quantite'], $restantBesoin);
        }
    }
}
