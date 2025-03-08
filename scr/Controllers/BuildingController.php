<?php

namespace App\Controllers;

use App\Models\Building;

class BuildingController extends Controller
{
    protected $building;

    public function __construct()
    {
        parent::__construct();
        $this->building = new Building();
    }

    public function index()
    {
        $this->requireAuth();

        $page = $_GET['page'] ?? 1;
        $query = $_GET['query'] ?? '';
        $perPage = 10;

        if ($query) {
            $buildings = $this->building->search($query, $page, $perPage);
        } else {
            $buildings = $this->building->getAllWithDetails($page, $perPage);
        }

        $stats = $this->building->getBuildingStats();

        return $this->view('buildings/index', [
            'buildings' => $buildings,
            'stats' => $stats,
            'currentPage' => $page,
            'query' => $query
        ]);
    }

    public function show($id)
    {
        $this->requireAuth();

        $building = $this->building->getWithDetails($id);

        if (!$building) {
            $this->redirect('buildings');
        }

        $hierarchy = $this->building->getBuildingHierarchy($id);

        return $this->view('buildings/show', [
            'building' => $building,
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
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'state' => $_POST['state'],
                'country' => $_POST['country'],
                'postal_code' => $_POST['postal_code'],
                'description' => $_POST['description'],
                'status' => $_POST['status']
            ];

            // Validate input
            $errors = $this->validateBuilding($data);
            if (!empty($errors)) {
                return $this->view('buildings/create', [
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->building->create($data)) {
                $this->setFlash('success', 'Building created successfully.');
                $this->redirect('buildings');
            } else {
                $this->setFlash('error', 'Failed to create building.');
            }
        }

        return $this->view('buildings/create');
    }

    public function edit($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        $building = $this->building->getWithDetails($id);

        if (!$building) {
            $this->redirect('buildings');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'state' => $_POST['state'],
                'country' => $_POST['country'],
                'postal_code' => $_POST['postal_code'],
                'description' => $_POST['description'],
                'status' => $_POST['status']
            ];

            // Validate input
            $errors = $this->validateBuilding($data);
            if (!empty($errors)) {
                return $this->view('buildings/edit', [
                    'building' => $building,
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->building->update($id, $data)) {
                $this->setFlash('success', 'Building updated successfully.');
                $this->redirect("buildings/{$id}");
            } else {
                $this->setFlash('error', 'Failed to update building.');
            }
        }

        return $this->view('buildings/edit', [
            'building' => $building
        ]);
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        $building = $this->building->getWithDetails($id);

        if (!$building) {
            $this->redirect('buildings');
        }

        if ($this->building->delete($id)) {
            $this->setFlash('success', 'Building deleted successfully.');
        } else {
            $this->setFlash('error', 'Failed to delete building.');
        }

        $this->redirect('buildings');
    }

    public function search()
    {
        $this->requireAuth();

        $query = $_GET['query'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 10;

        $buildings = $this->building->search($query, $page, $perPage);

        return $this->json([
            'buildings' => $buildings,
            'currentPage' => $page
        ]);
    }

    protected function validateBuilding($data)
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Please enter a name for the building.';
        }

        if (empty($data['address'])) {
            $errors['address'] = 'Please enter the building address.';
        }

        if (empty($data['city'])) {
            $errors['city'] = 'Please enter the city.';
        }

        if (empty($data['state'])) {
            $errors['state'] = 'Please enter the state/province.';
        }

        if (empty($data['country'])) {
            $errors['country'] = 'Please enter the country.';
        }

        if (empty($data['postal_code'])) {
            $errors['postal_code'] = 'Please enter the postal code.';
        }

        return $errors;
    }
} 