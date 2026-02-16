<?php
namespace app\controllers;

use app\models\Region;

class RegionController {
    
    public function list() {
        $regions = Region::getAllRegions();
        Flight::render('region/list.php', ['regions' => $regions]);
    }

    public function listRegion() {
        return Region::getAllRegions();
    }

    public function show($id) {
        $region = Region::findById($id);
        if (!$region) {
            Flight::halt(404, 'Region not found');
        }
        Flight::render('region/show.php', ['region' => $region]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if ($name) {
                $region = new Region();
                $region->name = $name;
                $region->description = $description;
                $region->save();

                $_SESSION['success'] = 'Region created successfully';
                header('Location: ' . Flight::get('flight.base_url') . '/regions');
                exit();
            } else {
                $_SESSION['error'] = 'Name is required';
            }
        }

        Flight::render('region/add.php');
    }

    public function edit($id) {
        $region = Region::findById($id);
        if (!$region) {
            Flight::halt(404, 'Region not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $region->name = $_POST['name'] ?? $region->name;
            $region->description = $_POST['description'] ?? $region->description;
            $region->update();

            $_SESSION['success'] = 'Region updated successfully';
            header('Location: ' . Flight::get('flight.base_url') . '/regions');
            exit();
        }

        Flight::render('region/edit.php', ['region' => $region]);
    }

    public function delete($id) {
        $region = Region::findById($id);
        if (!$region) {
            Flight::halt(404, 'Region not found');
        }

        $region->delete();
        $_SESSION['success'] = 'Region deleted successfully';
        header('Location: ' . Flight::get('flight.base_url') . '/regions');
        exit();
    }
}