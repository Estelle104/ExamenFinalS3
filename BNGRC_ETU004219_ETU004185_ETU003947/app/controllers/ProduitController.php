<?php
    namespace app\controllers;
    use app\models\Produit;
    use Flight;

    class ProduitController {
        public function list() {
            $produitModel = new Produit();
            $produits = $produitModel->getAllProduits();
            Flight::render('produit/list.php', ['produits' => $produits]);
        }

        public function getAllProduits(){
            $produits = new Produit();
            return $produits->getAllProduits();
        }
    }