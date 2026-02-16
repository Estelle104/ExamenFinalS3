<?php
namespace app\models;

class Dispatch extends Db {
    protected $table = 'dispatch';

    public function getDispatchesForDon($idDon) {
        $sql = "SELECT d.*, b.description AS description_besoin, b.id_produit, b.id_ville, b.id_region, b.quantite AS quantite_besoin, b.date_besoin
                FROM {$this->table} d
                JOIN besoin b ON d.id_besoin = b.id
                WHERE d.id_don = ?";
        return $this->execute($sql, [$idDon])->fetchAll(PDO::FETCH_ASSOC);
    }

    
}