<?php
namespace app\models;

use PDO;
use PDOException;

class Besoin extends Db
{
    private $table = 'besoins';

    public static function addBesoin(
        string $description,
        int $idProduit,
        int $idVille,
        ?int $idRegion,
        int $quantite,
        ?string $dateBesoin = null
    ): int {
        $dateBesoin = $dateBesoin ?: date('Y-m-d');

        $sqlWithEtat = "INSERT INTO {$this->table} (description, id_produit, id_ville, id_region, quantite, date_besoin, etat)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        try {
            $this->execute($sqlWithEtat, [
                $description,
                $idProduit,
                $idVille,
                $idRegion,
                $quantite,
                $dateBesoin,
                'En attente'
            ]);
        } catch (PDOException $e) {
            $sql = "INSERT INTO {$this->table} (description, id_produit, id_ville, id_region, quantite, date_besoin)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $this->execute($sql, [
                $description,
                $idProduit,
                $idVille,
                $idRegion,
                $quantite,
                $dateBesoin
            ]);
        }

        return (int) $this->db->lastInsertId();
    }

    public static function calculMontantBesoin(int $idBesoin): ?float
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

    public static function getAllBesoins(): array
    {
        $sql = "SELECT b.*, p.nom AS produit_nom, v.nom AS ville_nom, r.nom AS region_nom
                FROM {$this->table} b
                LEFT JOIN produits p ON p.id = b.id_produit
                LEFT JOIN villes v ON v.id = b.id_ville
                LEFT JOIN regions r ON r.id = b.id_region
                ORDER BY b.date_besoin DESC, b.id DESC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
}
