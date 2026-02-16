<?php
namespace app\models;

use PDO;

class Produit extends Db
{
    private $table = 'produits';

    /**
     * Ajoute un produit rattaché à une catégorie.
     * @return int Identifiant du produit créé.
     */
    public function addProduit(string $nom, int $idCategorie, float $prixUnitaire): int
    {
        $sql = "INSERT INTO {$this->table} (nom, id_categorie, prix_unitaire) VALUES (?, ?, ?)";
        $this->execute($sql, [$nom, $idCategorie, $prixUnitaire]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Récupère tous les produits avec les infos de catégorie.
     * @return array Liste des produits (avec categorie_libelle, categorie_description).
     */
    public function getAllProduits(): array
    {
        $sql = "SELECT p.*, c.libelle AS categorie_libelle, c.description AS categorie_description
                FROM {$this->table} p
                LEFT JOIN categorie_produits c ON c.id = p.id_categorie
                ORDER BY p.nom ASC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
