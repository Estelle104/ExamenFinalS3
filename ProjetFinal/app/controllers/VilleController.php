<?php
namespace app\controllers;

use app\models\Ville;

class VilleController {
    
    public function list($regionId) {
        $villes = Ville::getByRegionId($regionId);
        Flight::render('ville/list.php', ['villes' => $villes, 'regionId' => $regionId]);
    }

    public function show($id) {
        $ville = Ville::findById($id);
        if (!$ville) {
            Flight::halt(404, 'Ville not found');
        }
        Flight::render('ville/show.php', ['ville' => $ville]);
    }

    public function add($regionId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if ($name) {
                $ville = new Ville();
                $ville->name = $name;
                $ville->description = $description;
                $ville->region_id = $regionId;
                $ville->save();

                $_SESSION['success'] = 'Ville created successfully';
                header('Location: ' . Flight::get('flight.base_url') . '/regions/' . $regionId . '/villes');
                exit();
            } else {
                $_SESSION['error'] = 'Name is required';
            }
        }

        Flight::render('ville/add.php', ['regionId' => $regionId]);
    }

    public function edit($id) {
        $ville = Ville::findById($id);
        if (!$ville) {
            Flight::halt(404, 'Ville not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ville->name = $_POST['name'] ?? $ville->name;
            $ville->description = $_POST['description'] ?? $ville->description;
            $ville->update();

            $_SESSION['success'] = 'Ville updated successfully';
            header('Location: ' . Flight::get('flight.base_url') . '/regions/' . $ville->region_id . '/villes');
            exit();
        }

        Flight::render('ville/edit.php', ['ville' => $ville]);
    }

    public function delete($id) {
        $ville = Ville::findById($id);
        if (!$ville) {
            Flight::halt(404, 'Ville not found');
        }

        $regionId = $ville->region_id;
        $ville->delete();
        $_SESSION['success'] = 'Ville deleted successfully';
        header('Location: ' . Flight::get('flight.base_url') . '/regions/' . $regionId . '/villes');
        exit();
    }
}
