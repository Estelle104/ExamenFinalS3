<?php
namespace app\models;

class Categorie extends Db
{
    private $table = 'categorie_produits';

    public static function addCategorie(string $libelle, string $description): int
    {
        $sql = "INSERT INTO {$this->table} (libelle, description) VALUES (?, ?)";
        $this->execute($sql, [$libelle, $description]);
        return (int) $this->db->lastInsertId();
    }
}
