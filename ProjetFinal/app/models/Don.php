<?php
namespace app\models;

use PDO;
use PDOException;

class Don extends Db
{
    private $table = 'dons';

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

    public function getAllDons(): array
    {
        $sql = "SELECT d.*, p.nom AS produit_nom, v.nom AS ville_nom
                FROM {$this->table} d
                LEFT JOIN produits p ON p.id = d.id_produit
                LEFT JOIN villes v ON v.id = d.id_ville
                ORDER BY d.date_don ASC, d.id DESC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function simulerDispatch(): array
    {
        $summary = [
            'dispatch_crees' => 0,
            'dons_traite' => 0,
            'besoins_mis_a_jour' => 0,
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
                        $summary['besoins_mis_a_jour'] += $this->updateEtatBesoin((int) $besoin['id'], $besoin['quantite'], $remainingBesoin);
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

                    $summary['besoins_mis_a_jour'] += $this->updateEtatBesoin((int) $besoin['id'], (int) $besoin['quantite'], $remainingBesoin);
                }
            }

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }

        return $summary;
    }

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

    public function getTotals(): array
    {
        $sql = "SELECT
                    (SELECT COALESCE(SUM(quantite), 0) FROM besoins) AS total_besoins,
                    (SELECT COALESCE(SUM(quantite), 0) FROM {$this->table}) AS total_dons";

        $row = $this->execute($sql)->fetch(PDO::FETCH_ASSOC);
        return $row ?: ['total_besoins' => 0, 'total_dons' => 0];
    }

    private function getBesoinsCompatibles(int $idProduit): array
    {
        $sql = "SELECT * FROM besoins WHERE id_produit = ? ORDER BY date_besoin ASC, id ASC";
        return $this->execute($sql, [$idProduit])->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getQuantiteAttribueePourDon(int $idDon): int
    {
        $sql = "SELECT COALESCE(SUM(quantite_attribuee), 0) AS total
                FROM dispatch
                WHERE id_don = ?";
        $row = $this->execute($sql, [$idDon])->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['total'] ?? 0);
    }

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
            $sql = "UPDATE besoins SET etat = ? WHERE id = ?";
            $this->execute($sql, [$etat, $idBesoin]);
            return 1;
        } catch (PDOException $e) {
            return 0;
        }
    }
}
