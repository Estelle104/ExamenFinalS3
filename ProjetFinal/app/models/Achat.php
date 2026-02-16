<?php
namespace app\models;

use PDO;

class Achat extends Db
{
    private $table = 'achats';

    /**
     * Ajoute un achat.
     * @return int Identifiant de l'achat créé.
     */
    public function addAchat(
        int $idProduit,
        int $idVille,
        int $quantite,
        float $montantTotal,
        ?string $dateAchat = null
    ): int {
        $dateAchat = $dateAchat ?: date('Y-m-d');
        $sql = "INSERT INTO {$this->table} (id_produit, id_ville, quantite, montant_total, date_achat)
                VALUES (?, ?, ?, ?, ?)";
        $this->execute($sql, [$idProduit, $idVille, $quantite, $montantTotal, $dateAchat]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Récupère tous les achats.
     * @return array Liste des achats.
     */
    public function getAllAchats(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY date_achat ASC, id ASC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un achat par id.
     * @return array|null Achat ou null si non trouvé.
     */
    public function getAchatById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $row = $this->execute($sql, [$id])->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Met à jour un achat.
     * @return int Nombre de lignes mises à jour.
     */
    public function updateAchat(
        int $id,
        int $idProduit,
        int $idVille,
        int $quantite,
        float $montantTotal,
        ?string $dateAchat = null
    ): int {
        $dateAchat = $dateAchat ?: date('Y-m-d');
        $sql = "UPDATE {$this->table}
                SET id_produit = ?, id_ville = ?, quantite = ?, montant_total = ?, date_achat = ?
                WHERE id = ?";
        $this->execute($sql, [$idProduit, $idVille, $quantite, $montantTotal, $dateAchat, $id]);
        return 1;
    }

    /**
     * Supprime un achat.
     * @return int Nombre de lignes supprimées.
     */
    public function deleteAchat(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->execute($sql, [$id]);
        return 1;
    }
}
