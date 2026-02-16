<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/config/config.php';

use app\models\Region;
use app\models\Ville;
use app\models\Categorie;
use app\models\Produit;
use app\models\Besoin;
use app\models\Don;

function printSection(string $title): void {
    echo "\n===== {$title} =====\n";
}

// printSection('Regions');
// $region = new Region();
// print_r($region->getAllRegions());

// printSection('Villes');
// $ville = new Ville();
// print_r($ville->getAllVilles());

// printSection('Add Categorie');
// $categorie = new Categorie();
// $catId = $categorie->addCategorie('Test categorie', 'Categorie de test');
// var_dump($catId);

// printSection('Produits');
// $produit = new Produit();
// print_r($produit->getAllProduits());

// printSection('Add Besoin');
// $besoin = new Besoin();
// $besoinId = $besoin->addBesoin('Besoin test', 1, 1, null, 10);
// var_dump($besoinId);

// printSection('Add Don');
$don = new Don();
// $donId = $don->addDon('Don test', 1, 1, 5, null, 'Test Donneur');
// var_dump($donId);

printSection('All Dons');
print_r($don->getAllDons());

printSection('Simuler Dispatch');
print_r($don->simulerDispatch());

printSection('Statistiques par ville');
print_r($don->statistiquesParVille());

printSection('Totals besoins/dons');
print_r($don->getTotals());
