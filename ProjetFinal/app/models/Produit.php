<?php
namespace app\models;

use PDO;

class Produit extends Db
{
    private $table = 'produits';

    public function addProduit(string $nom, int $idCategorie, float $prixUnitaire): int
    {
        $sql = "INSERT INTO {$this->table} (nom, id_categorie, prix_unitaire) VALUES (?, ?, ?)";
        $this->execute($sql, [$nom, $idCategorie, $prixUnitaire]);
        return (int) $this->db->lastInsertId();
    }

    public function getAllProduits(): array
    {
        $sql = "SELECT p.*, c.libelle AS categorie_libelle, c.description AS categorie_description
                FROM {$this->table} p
                LEFT JOIN categorie_produits c ON c.id = p.id_categorie
                ORDER BY p.nom ASC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
