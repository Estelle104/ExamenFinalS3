<?php
namespace app\models;

class Categorie extends Db
{
    private $table = 'categorie_produits';

    public function addCategorie(string $libelle, string $description): int
    {
        $sql = "INSERT INTO {$this->table} (libelle, description) VALUES (?, ?)";
        $this->execute($sql, [$libelle, $description]);
        return (int) $this->db->lastInsertId();
    }

    public function getAllCategories(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY libelle ASC";
        return $this->execute($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}
