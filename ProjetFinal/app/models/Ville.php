<?php
namespace app\models;

use PDO;

class Ville extends Db
{
    private $table = 'villes';

    /**
     * Ajoute une ville rattachée à une région.
     * @return int Identifiant de la ville créée.
     */
    public function addVille(string $nom, int $idRegion): int
    {
        $sql = "INSERT INTO {$this->table} (nom, id_region) VALUES (?, ?)";
        $this->execute($sql, [$nom, $idRegion]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Récupère toutes les villes avec le nom de la région.
     * @return array Liste des villes (avec champ region_nom).
     */
    public function getAllVilles(): array
    {
        $sql = "SELECT v.*, r.nom AS region_nom
                FROM {$this->table} v
                LEFT JOIN regions r ON r.id = v.id_region
                ORDER BY v.nom ASC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
