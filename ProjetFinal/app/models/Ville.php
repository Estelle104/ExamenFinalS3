<?php
namespace app\models;

use PDO;

class Ville extends Db
{
    private $table = 'villes';

    public static function addVille(string $nom, int $idRegion): int
    {
        $sql = "INSERT INTO {$this->table} (nom, id_region) VALUES (?, ?)";
        $this->execute($sql, [$nom, $idRegion]);
        return (int) $this->db->lastInsertId();
    }

    public static function getAllVilles(): array
    {
        $sql = "SELECT v.*, r.nom AS region_nom
                FROM {$this->table} v
                LEFT JOIN regions r ON r.id = v.id_region
                ORDER BY v.nom ASC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
