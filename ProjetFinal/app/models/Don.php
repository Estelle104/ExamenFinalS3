<?php
namespace app\models;

use PDO;
use PDOException;

class Don extends Db
{
    private $table = 'dons';

    /**
     * Ajoute un don.
     * @return int Identifiant du don créé.
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
     * @return array Liste des dons.
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
     * @return array Résumé (dispatch_crees, dons_traite).
     */
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

    /**
     * Applique un achat aux besoins (plus anciens d'abord) avec la même connexion.
     */
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
