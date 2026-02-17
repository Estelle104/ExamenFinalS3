<?php
namespace app\models;

use PDO;

class ConfFraisAchat extends Db
{
    private $table = 'conf_frais_achat';

    /**
     * Récupère le taux de frais actuel (en %).
     */
    public function getTauxActuel(): float
    {
        $sql = "SELECT taux_pourcentage FROM {$this->table} ORDER BY id DESC LIMIT 1";
        $row = $this->execute($sql)->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $this->setTauxActuel(0.0);
            return 0.0;
        }

        return (float) $row['taux_pourcentage'];
    }

    /**
     * Enregistre un nouveau taux de frais (en %).
     */
    public function setTauxActuel(float $taux): int
    {
        $sql = "INSERT INTO {$this->table} (taux_pourcentage, date_config)
                VALUES (?, ?)";
        $this->execute($sql, [$taux, date('Y-m-d')]);
        return (int) $this->db->lastInsertId();
    }
}
