<?php
namespace app\models;

use PDO;

class Besoin extends Db
{
    private $table = 'besoins';

    /**
     * Ajoute un besoin.
     * @return int Identifiant du besoin créé.
     */
    public function addBesoin(
        string $description,
        int $idProduit,
        ?int $idVille,
        ?int $idRegion,
        int $quantite,
        ?string $dateBesoin = null
    ): int {
        $dateBesoin = $dateBesoin ?: date('Y-m-d');

        $sql = "INSERT INTO {$this->table} (description, id_produit, id_ville, id_region, quantite, quantite_restante, date_besoin)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->execute($sql, [
            $description,
            $idProduit,
            $idVille,
            $idRegion,
            $quantite,
            $quantite,
            $dateBesoin
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Calcule le montant estimé d'un besoin (quantité * prix unitaire).
     * @return float|null Montant calculé ou null si le besoin n'existe pas.
     */
    public function calculMontantBesoin(int $idBesoin): ?float
    {
        $sql = "SELECT b.quantite, p.prix_unitaire, (b.quantite * p.prix_unitaire) AS montant
                FROM {$this->table} b
                INNER JOIN produits p ON p.id = b.id_produit
                WHERE b.id = ?";
        $row = $this->execute($sql, [$idBesoin])->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return (float) $row['montant'];
    }

    /**
     * Récupère tous les besoins triés par date.
     * @return array Liste des besoins.
     */
    public function getAllBesoins(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY date_besoin ASC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Liste des produits qui ont des besoins restants (optionnellement filtrés par ville).
     * @return array Liste des produits avec quantite_restante_totale.
     */
    public function getProduitsAvecBesoins(?int $idVille = null): array
    {
        $sql = "SELECT p.id, p.nom,
                       COALESCE(SUM(COALESCE(b.quantite_restante, b.quantite)), 0) AS quantite_restante_totale
                FROM {$this->table} b
                INNER JOIN produits p ON p.id = b.id_produit
                WHERE COALESCE(b.quantite_restante, b.quantite) > 0 ";
        $params = [];

        if ($idVille !== null) {
            $sql .= "AND b.id_ville = ? ";
            $params[] = $idVille;
        }

        $sql .= "GROUP BY p.id, p.nom ORDER BY p.nom ASC";
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Total restant pour un produit (optionnellement filtré par ville).
     */
    public function getTotalRestantByProduit(?int $idProduit, ?int $idVille = null): int
    {
        $sql = "SELECT COALESCE(SUM(COALESCE(quantite_restante, quantite)), 0) AS total
                FROM {$this->table}
                WHERE id_produit = ? ";
        $params = [$idProduit];

        if ($idVille !== null) {
            $sql .= "AND id_ville = ? ";
            $params[] = $idVille;
        }

        $row = $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Récupère les besoins par produit (optionnellement filtré par ville), du plus ancien au plus récent.
     */
    public function getBesoinsPourAchat(int $idProduit, ?int $idVille = null): array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE id_produit = ? AND COALESCE(quantite_restante, quantite) > 0 ";
        $params = [$idProduit];

        if ($idVille !== null) {
            $sql .= "AND id_ville = ? ";
            $params[] = $idVille;
        }

        $sql .= "ORDER BY COALESCE(date_besoin, '0000-00-00') ASC, id ASC";
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}
