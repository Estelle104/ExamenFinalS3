<?php
namespace app\models;

use PDO;

class Region extends Db
{
    private $table = 'regions';

    public function addRegion(string $nom): int
    {
        $sql = "INSERT INTO {$this->table} (nom) VALUES (?)";
        $this->execute($sql, [$nom]);
        return (int) $this->db->lastInsertId();
    }

    public function getAllRegions(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY nom ASC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
