<?php
namespace app\models;

use PDO;
use PDOException;

class User {
    private $pdo;
    private $table = 'users';

    public function __construct()
    {
        $this->pdo = $this->getConnection();
    }

    /**
     * Établit la connexion à la base de données
     */
    private function getConnection()
    {
        try {
            $config = require __DIR__ . '/../config/config.php';
            $dbConfig = $config['database'];

            $pdo = new PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8mb4",
                $dbConfig['user'],
                $dbConfig['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            return $pdo;
        } catch (PDOException $e) {
            die('Erreur de connexion: ' . $e->getMessage());
        }
    }

    public function chequeLoginAdmin($loginUser, $mdp) {
        $sql = "SELECT * FROM {$this->table} WHERE login = ? AND mdp = ? AND id_type_user = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$loginUser, $mdp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function chequeLoginClient($loginUser, $mdp) {
        $sql = "SELECT * FROM {$this->table} WHERE login = ? AND mdp = ? AND id_type_user = 2";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$loginUser, $mdp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByLogin($login) {
        $sql = "SELECT * FROM {$this->table} WHERE login = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$login]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inscriptionClient($nom, $login, $mdp) {
        $sql = "INSERT INTO {$this->table} (nom, login, mdp, id_type_user) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nom, $login, $mdp, 2]);
        return $this->pdo->lastInsertId();
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}