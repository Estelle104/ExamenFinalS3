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
        int $idVille,
        int $quantite,
        ?string $dateDon,
        string $donneur
    ): int {
        $dateDon = $dateDon ?: date('Y-m-d');
        $sql = "INSERT INTO {$this->table} (description, id_produit, id_ville, quantite, date_don, donneur)
                VALUES (?, ?, ?, ?, ?, ?)";
        $this->execute($sql, [$description, $idProduit, $idVille, $quantite, $dateDon, $donneur]);
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
            $dons = $this->execute(
                "SELECT * FROM {$this->table} ORDER BY date_don ASC, id ASC"
            )->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dons as $don) {
                $remainingDon = $don['quantite'] - $this->getQuantiteAttribueePourDon((int) $don['id']);
                if ($remainingDon <= 0) {
                    continue;
                }

                $summary['dons_traite']++;

                $besoins = $this->getBesoinsCompatibles((int) $don['id_produit']);
                foreach ($besoins as $besoin) {
                    if ($remainingDon <= 0) {
                        break;
                    }

                    $remainingBesoin = $besoin['quantite'] - $this->getQuantiteAttribueePourBesoin((int) $besoin['id']);
                    if ($remainingBesoin <= 0) {
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

    /**
     * Calcule le total global des dons attribués (dispatch).
     * @return int Total attribué.
     */
    public function getTotalAttribue(): int
    {
        $sql = "SELECT COALESCE(SUM(quantite_attribuee), 0) AS total_attribue
                FROM dispatch";
        $row = $this->execute($sql)->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['total_attribue'] ?? 0);
    }

    /**
     * Récupère les besoins compatibles pour un produit donné.
     * @return array Liste des besoins compatibles.
     */
    private function getBesoinsCompatibles(int $idProduit): array
    {
        $sql = "SELECT * FROM besoins WHERE id_produit = ? ORDER BY date_besoin ASC, id ASC";
        return $this->execute($sql, [$idProduit])->fetchAll(PDO::FETCH_ASSOC);
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

}
