<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Location;

class AssetController extends Controller
{
    private Asset $assetModel;
    private AssetCategory $categoryModel;
    private Location $locationModel;

    public function __construct()
    {
        parent::__construct();
        $this->assetModel = new Asset();
        $this->categoryModel = new AssetCategory();
        $this->locationModel = new Location();
    }

    public function index()
    {
        $this->requireAuth();
        
        $page = $_GET['page'] ?? 1;
        $query = $_GET['query'] ?? '';
        $perPage = 10;

        if ($query) {
            $assets = $this->assetModel->search($query, $page, $perPage);
        } else {
            $assets = $this->assetModel->getAllWithDetails($page, $perPage);
        }

        $stats = $this->assetModel->getAssetStats();

        return $this->view('assets/index', [
            'assets' => $assets,
            'stats' => $stats,
            'currentPage' => $page,
            'query' => $query
        ]);
    }

    public function show($id)
    {
        $this->requireAuth();
        
        $asset = $this->assetModel->getWithDetails($id);
        if (!$asset) {
            return $this->redirect('/assets');
        }

        $hierarchy = $this->assetModel->getAssetHierarchy($id);

        return $this->view('assets/show', [
            'asset' => $asset,
            'hierarchy' => $hierarchy
        ]);
    }

    public function create()
    {
        $this->requireAuth();
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'location_id' => $_POST['location_id'],
                'type' => $_POST['type'],
                'model' => $_POST['model'],
                'serial_number' => $_POST['serial_number'],
                'purchase_date' => $_POST['purchase_date'],
                'purchase_price' => $_POST['purchase_price'],
                'warranty_expiry' => $_POST['warranty_expiry'],
                'status' => $_POST['status'],
                'description' => $_POST['description']
            ];

            // Validate input
            $errors = $this->validateAsset($data);
            if (!empty($errors)) {
                $locations = $this->locationModel->getActiveLocations();
                return $this->view('assets/create', [
                    'locations' => $locations,
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->assetModel->create($data)) {
                $this->flash('success', 'Asset created successfully.');
                $this->redirect('/assets');
            } else {
                $this->flash('error', 'Failed to create asset.');
            }
        }

        $locations = $this->locationModel->getActiveLocations();
        return $this->view('assets/create', [
            'locations' => $locations
        ]);
    }

    public function edit($id)
    {
        $this->requireAuth();
        $this->requireRole('admin');

        $asset = $this->assetModel->getWithDetails($id);
        if (!$asset) {
            return $this->redirect('/assets');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'location_id' => $_POST['location_id'],
                'type' => $_POST['type'],
                'model' => $_POST['model'],
                'serial_number' => $_POST['serial_number'],
                'purchase_date' => $_POST['purchase_date'],
                'purchase_price' => $_POST['purchase_price'],
                'warranty_expiry' => $_POST['warranty_expiry'],
                'status' => $_POST['status'],
                'description' => $_POST['description']
            ];

            // Validate input
            $errors = $this->validateAsset($data);
            if (!empty($errors)) {
                $locations = $this->locationModel->getActiveLocations();
                return $this->view('assets/edit', [
                    'asset' => $asset,
                    'locations' => $locations,
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->assetModel->update($id, $data)) {
                $this->flash('success', 'Asset updated successfully.');
                $this->redirect("/assets/$id");
            } else {
                $this->flash('error', 'Failed to update asset.');
            }
        }

        $locations = $this->locationModel->getActiveLocations();
        return $this->view('assets/edit', [
            'asset' => $asset,
            'locations' => $locations
        ]);
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireRole('admin');

        $asset = $this->assetModel->getWithDetails($id);

        if (!$asset) {
            $this->redirect('/assets');
        }

        if ($this->assetModel->delete($id)) {
            $this->flash('success', 'Asset deleted successfully.');
        } else {
            $this->flash('error', 'Failed to delete asset.');
        }

        $this->redirect('/assets');
    }

    public function updateStatus($id)
    {
        $this->requireAuth();
        $this->requireRole('admin');

        if ($this->request->isPost()) {
            $status = $this->request->get('status');
            
            if (in_array($status, ['active', 'inactive', 'maintenance', 'retired'])) {
                if ($this->assetModel->updateStatus($id, $status)) {
                    return $this->json(['success' => true]);
                }
            }
        }

        return $this->json(['success' => false], 400);
    }

    public function search()
    {
        $this->requireAuth();
        
        $query = $_GET['query'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 10;

        $assets = $this->assetModel->search($query, $page, $perPage);

        return $this->json([
            'assets' => $assets,
            'currentPage' => $page
        ]);
    }

    public function getAssetCounts()
    {
        $this->requireAuth();
        
        $counts = [
            'byStatus' => $this->assetModel->getAssetCountByStatus(),
            'byCategory' => $this->assetModel->getAssetCountByCategory(),
            'byLocation' => $this->assetModel->getAssetCountByLocation()
        ];

        return $this->json($counts);
    }

    public function getAssetsByLocation()
    {
        $this->requireAuth();

        $locationId = $_GET['location_id'] ?? null;

        if (!$locationId) {
            return $this->json(['error' => 'Location ID is required'], 400);
        }

        $assets = $this->assetModel->getAssetsByLocation($locationId);

        return $this->json(['assets' => $assets]);
    }

    public function getAssetsByType()
    {
        $this->requireAuth();

        $type = $_GET['type'] ?? null;

        if (!$type) {
            return $this->json(['error' => 'Asset type is required'], 400);
        }

        $assets = $this->assetModel->getAssetsByType($type);

        return $this->json(['assets' => $assets]);
    }

} 