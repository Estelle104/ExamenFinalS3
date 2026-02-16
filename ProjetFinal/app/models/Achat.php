<?php
namespace app\models;

use PDO;
use PDOException;

class Achat extends Db
{
    private $table = 'achats';

    
    public function addAchat(
        int $idProduit,
        ?int $idVille,
        int $quantite,
        float $montantTotal,
        ?string $dateAchat = null
    ): int {
        $dateAchat = $dateAchat ?: date('Y-m-d');
        $sql = "INSERT INTO {$this->table} (id_produit, id_ville, quantite, montant_total, date_achat)
                VALUES (?, ?, ?, ?, ?)";
        $this->execute($sql, [$idProduit, $idVille, $quantite, $montantTotal, $dateAchat]);
        return (int) $this->db->lastInsertId();
    }

    
    public function getAllAchats(): array
    {
        $sql = "SELECT a.*, p.nom AS produit_nom, v.nom AS ville_nom
                FROM {$this->table} a
                LEFT JOIN produits p ON p.id = a.id_produit
                LEFT JOIN villes v ON v.id = a.id_ville
                ORDER BY a.date_achat ASC, a.id ASC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getAchatById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $row = $this->execute($sql, [$id])->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    
    public function updateAchat(
        int $id,
        int $idProduit,
        ?int $idVille,
        int $quantite,
        float $montantTotal,
        ?string $dateAchat = null
    ): int {
        $dateAchat = $dateAchat ?: date('Y-m-d');
        $sql = "UPDATE {$this->table}
                SET id_produit = ?, id_ville = ?, quantite = ?, montant_total = ?, date_achat = ?
                WHERE id = ?";
        $this->execute($sql, [$idProduit, $idVille, $quantite, $montantTotal, $dateAchat, $id]);
        return 1;
    }

    
    public function deleteAchat(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->execute($sql, [$id]);
        return 1;
    }

    public function getTotalArgentDisponible(?int $idVille = null): float
    {
        // Récupérer l'id du produit "Argent" (prioritaire)
        $rowArgent = $this->execute("SELECT id FROM produits WHERE LOWER(nom) = 'argent' LIMIT 1")
            ->fetch(PDO::FETCH_ASSOC);
        $idArgent = $rowArgent ? (int) $rowArgent['id'] : null;

        $paramsDon = [];
        if ($idArgent !== null) {
            $whereDon = "WHERE d.id_produit = ?";
            $paramsDon[] = $idArgent;
        } else {
            $whereDon = "WHERE p.nom LIKE ?";
            $paramsDon[] = '%Argent%';
        }

        if ($idVille !== null) {
            $rowRegion = $this->execute("SELECT id_region FROM villes WHERE id = ?", [$idVille])
                ->fetch(PDO::FETCH_ASSOC);
            $idRegion = $rowRegion ? (int) $rowRegion['id_region'] : null;

            if ($idRegion !== null) {
                $whereDon .= " AND (d.id_ville = ? OR d.id_region = ?)";
                $paramsDon[] = $idVille;
                $paramsDon[] = $idRegion;
            } else {
                $whereDon .= " AND d.id_ville = ?";
                $paramsDon[] = $idVille;
            }
        }

        $sqlDon = "SELECT COALESCE(SUM(d.quantite), 0) AS total_dons
               FROM dons d
               LEFT JOIN produits p ON p.id = d.id_produit
               {$whereDon}";

        $rowDon = $this->execute($sqlDon, $paramsDon)->fetch(PDO::FETCH_ASSOC);
        $totalDons = (float) ($rowDon['total_dons'] ?? 0);

        $paramsAch = [];
        $whereAch = '';
        if ($idVille !== null) {
            $whereAch = 'WHERE id_ville = ?';
            $paramsAch[] = $idVille;
        }

        $sqlAch = "SELECT COALESCE(SUM(montant_total), 0) AS total_achats
                   FROM {$this->table}
                   {$whereAch}";

        $rowAch = $this->execute($sqlAch, $paramsAch)->fetch(PDO::FETCH_ASSOC);
        $totalAchats = (float) ($rowAch['total_achats'] ?? 0);

        return max(0.0, $totalDons - $totalAchats);
    }

    
    public function createAchatEtAppliquerBesoins(
        int $idProduit,
        ?int $idVille,
        int $quantite,
        float $montantTotal,
        ?string $dateAchat = null
    ): int {
        $this->db->beginTransaction();
        try {
            $achatId = $this->addAchat(
                $idProduit,
                $idVille,
                $quantite,
                $montantTotal,
                $dateAchat
            );

            $this->applyAchatToBesoins($idProduit, $idVille, $quantite);

            $this->db->commit();
            return $achatId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    
    public function applyAchatToBesoins(int $idProduit, ?int $idVille, int $quantite): void
    {
        $besoinModel = new Besoin();
        $besoins = $besoinModel->getBesoinsPourAchat($idProduit, $idVille);

        $remaining = $quantite;
        foreach ($besoins as $besoin) {
            if ($remaining <= 0) {
                break;
            }

            $restantBesoin = $besoin['quantite_restante'] ?? $besoin['quantite'];
            $restantBesoin = (int) $restantBesoin;

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

    
    public function applyAchatsSurBesoins(): void
    {
        $achats = $this->getAllAchats();
        foreach ($achats as $achat) {
            $this->applyAchatToBesoins(
                (int) $achat['id_produit'],
                $achat['id_ville'] ? (int) $achat['id_ville'] : null,
                (int) $achat['quantite']
            );
        }
    }

    private function updateEtatBesoin(int $idBesoin, int $quantiteTotale, int $restant): int
    {
        $etat = 'En attente';
        if ($restant <= 0) {
            $etat = 'Satisfait';
        } elseif ($restant < $quantiteTotale) {
            $etat = 'Partiel';
        }

        $sql = "UPDATE besoins SET etat = ?, quantite_restante = ? WHERE id = ?";
        $this->execute($sql, [$etat, max(0, $restant), $idBesoin]);
        return 1;
    }
}

