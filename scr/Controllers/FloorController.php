<?php

namespace App\Controllers;

use App\Models\Floor;
use App\Models\Building;

class FloorController extends Controller
{
    protected $floor;
    protected $building;

    public function __construct()
    {
        parent::__construct();
        $this->floor = new Floor();
        $this->building = new Building();
    }

    public function index()
    {
        $this->requireAuth();

        $page = $_GET['page'] ?? 1;
        $query = $_GET['query'] ?? '';
        $perPage = 10;

        if ($query) {
            $floors = $this->floor->search($query, $page, $perPage);
        } else {
            $floors = $this->floor->getAllWithDetails($page, $perPage);
        }

        $stats = $this->floor->getFloorStats();

        return $this->view('floors/index', [
            'floors' => $floors,
            'stats' => $stats,
            'currentPage' => $page,
            'query' => $query
        ]);
    }

    public function show($id)
    {
        $this->requireAuth();

        $floor = $this->floor->getWithDetails($id);

        if (!$floor) {
            $this->redirect('floors');
        }

        $hierarchy = $this->floor->getFloorHierarchy($id);

        return $this->view('floors/show', [
            'floor' => $floor,
            'hierarchy' => $hierarchy
        ]);
    }

    public function create()
    {
        $this->requireAuth();
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'building_id' => $_POST['building_id'],
                'description' => $_POST['description'],
                'status' => $_POST['status']
            ];

            // Validate input
            $errors = $this->validateFloor($data);
            if (!empty($errors)) {
                $buildings = $this->building->getActiveBuildings();
                return $this->view('floors/create', [
                    'buildings' => $buildings,
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->floor->create($data)) {
                $this->setFlash('success', 'Floor created successfully.');
                $this->redirect('floors');
            } else {
                $this->setFlash('error', 'Failed to create floor.');
            }
        }

        $buildings = $this->building->getActiveBuildings();
        return $this->view('floors/create', [
            'buildings' => $buildings
        ]);
    }

    public function edit($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        $floor = $this->floor->getWithDetails($id);

        if (!$floor) {
            $this->redirect('floors');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'building_id' => $_POST['building_id'],
                'description' => $_POST['description'],
                'status' => $_POST['status']
            ];

            // Validate input
            $errors = $this->validateFloor($data);
            if (!empty($errors)) {
                $buildings = $this->building->getActiveBuildings();
                return $this->view('floors/edit', [
                    'floor' => $floor,
                    'buildings' => $buildings,
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->floor->update($id, $data)) {
                $this->setFlash('success', 'Floor updated successfully.');
                $this->redirect("floors/{$id}");
            } else {
                $this->setFlash('error', 'Failed to update floor.');
            }
        }

        $buildings = $this->building->getActiveBuildings();
        return $this->view('floors/edit', [
            'floor' => $floor,
            'buildings' => $buildings
        ]);
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        $floor = $this->floor->getWithDetails($id);

        if (!$floor) {
            $this->redirect('floors');
        }

        if ($this->floor->delete($id)) {
            $this->setFlash('success', 'Floor deleted successfully.');
        } else {
            $this->setFlash('error', 'Failed to delete floor.');
        }

        $this->redirect('floors');
    }

    public function search()
    {
        $this->requireAuth();

        $query = $_GET['query'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 10;

        $floors = $this->floor->search($query, $page, $perPage);

        return $this->json([
            'floors' => $floors,
            'currentPage' => $page
        ]);
    }

    public function getFloorsByBuilding()
    {
        $this->requireAuth();

        $buildingId = $_GET['building_id'] ?? null;

        if (!$buildingId) {
            return $this->json(['error' => 'Building ID is required'], 400);
        }

        $floors = $this->floor->getFloorsByBuilding($buildingId);

        return $this->json(['floors' => $floors]);
    }

    protected function validateFloor($data)
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Please enter a name for the floor.';
        }

        if (empty($data['building_id'])) {
            $errors['building_id'] = 'Please select a building.';
        }

        return $errors;
    }
} 