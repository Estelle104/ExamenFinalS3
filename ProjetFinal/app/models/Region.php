<?php
namespace app\models;

use PDO;

class Region extends Db
{
    private $table = 'regions';

    /**
     * Ajoute une région dans la base.
     * @return int Identifiant de la région créée.
     */
    public function addRegion(string $nom): int
    {
        $sql = "INSERT INTO {$this->table} (nom) VALUES (?)";
        $this->execute($sql, [$nom]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Récupère toutes les régions triées par nom.
     * @return array Liste des régions.
     */
    public function getAllRegions(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY nom ASC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
