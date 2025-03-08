<?php

namespace App\Controllers;

use App\Models\Warranty;
use App\Models\Asset;
use App\Models\Supplier;
use App\Models\WarrantyClaim;

class WarrantyController extends Controller
{
    private $warranty;
    private $asset;
    private $supplier;
    private $warrantyClaim;

    public function __construct()
    {
        parent::__construct();
        $this->warranty = new Warranty();
        $this->asset = new Asset();
        $this->supplier = new Supplier();
        $this->warrantyClaim = new WarrantyClaim();
    }

    public function index()
    {
        $this->authorize('view_warranties');
        
        $warranties = $this->warranty->getAllWithDetails();
        $statistics = $this->warranty->getStatistics();
        
        return $this->view('warranties/index', [
            'warranties' => $warranties,
            'statistics' => $statistics
        ]);
    }

    public function dashboard()
    {
        $this->authorize('view_warranties');
        
        $statistics = $this->warranty->getStatistics();
        $expiringWarranties = $this->warranty->getExpiringWarranties();
        $recentClaims = $this->warrantyClaim->getRecentClaims();
        $warrantyByType = $this->warranty->getWarrantiesByType();
        
        return $this->view('warranties/dashboard', [
            'statistics' => $statistics,
            'expiringWarranties' => $expiringWarranties,
            'recentClaims' => $recentClaims,
            'warrantyByType' => $warrantyByType
        ]);
    }

    public function create()
    {
        $this->authorize('create_warranty');
        
        $assets = $this->asset->getAll();
        $suppliers = $this->supplier->getAll();
        $warrantyTypes = $this->warranty->getWarrantyTypes();
        
        return $this->view('warranties/create', [
            'assets' => $assets,
            'suppliers' => $suppliers,
            'warrantyTypes' => $warrantyTypes
        ]);
    }

    public function store()
    {
        $this->authorize('create_warranty');
        
        $data = $this->validate($_POST, [
            'asset_id' => 'required|exists:assets,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'warranty_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'terms_conditions' => 'required',
            'coverage_details' => 'required',
            'cost' => 'numeric|min:0',
            'contract_number' => 'required|unique:warranties,contract_number'
        ]);

        if ($this->warranty->create($data)) {
            $this->flash('success', 'Warranty created successfully.');
            return $this->redirect('warranties');
        }

        $this->flash('error', 'Failed to create warranty.');
        return $this->redirect('warranties/create');
    }

    public function show($id)
    {
        $this->authorize('view_warranties');
        
        $warranty = $this->warranty->getById($id);
        if (!$warranty) {
            $this->flash('error', 'Warranty not found.');
            return $this->redirect('warranties');
        }

        $claims = $this->warrantyClaim->getByWarrantyId($id);
        
        return $this->view('warranties/show', [
            'warranty' => $warranty,
            'claims' => $claims
        ]);
    }

    public function edit($id)
    {
        $this->authorize('edit_warranty');
        
        $warranty = $this->warranty->getById($id);
        if (!$warranty) {
            $this->flash('error', 'Warranty not found.');
            return $this->redirect('warranties');
        }

        $assets = $this->asset->getAll();
        $suppliers = $this->supplier->getAll();
        $warrantyTypes = $this->warranty->getWarrantyTypes();
        
        return $this->view('warranties/edit', [
            'warranty' => $warranty,
            'assets' => $assets,
            'suppliers' => $suppliers,
            'warrantyTypes' => $warrantyTypes
        ]);
    }

    public function update($id)
    {
        $this->authorize('edit_warranty');
        
        $data = $this->validate($_POST, [
            'asset_id' => 'required|exists:assets,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'warranty_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'terms_conditions' => 'required',
            'coverage_details' => 'required',
            'cost' => 'numeric|min:0',
            'contract_number' => "required|unique:warranties,contract_number,{$id}"
        ]);

        if ($this->warranty->update($id, $data)) {
            $this->flash('success', 'Warranty updated successfully.');
            return $this->redirect("warranties/{$id}");
        }

        $this->flash('error', 'Failed to update warranty.');
        return $this->redirect("warranties/{$id}/edit");
    }

    public function delete($id)
    {
        $this->authorize('delete_warranty');
        
        if ($this->warranty->delete($id)) {
            $this->flash('success', 'Warranty deleted successfully.');
        } else {
            $this->flash('error', 'Failed to delete warranty.');
        }

        return $this->redirect('warranties');
    }

    public function search()
    {
        $this->authorize('view_warranties');
        
        $query = $_GET['query'] ?? '';
        $filters = [
            'status' => $_GET['status'] ?? null,
            'type' => $_GET['type'] ?? null,
            'supplier' => $_GET['supplier'] ?? null,
            'expiry_from' => $_GET['expiry_from'] ?? null,
            'expiry_to' => $_GET['expiry_to'] ?? null
        ];

        $results = $this->warranty->search($query, $filters);
        
        if (isset($_GET['ajax'])) {
            return $this->json($results);
        }

        return $this->view('warranties/search', [
            'results' => $results,
            'query' => $query,
            'filters' => $filters
        ]);
    }

    public function getExpiringWarranties()
    {
        $this->authorize('view_warranties');
        
        $days = $_GET['days'] ?? 30;
        $warranties = $this->warranty->getExpiringWarranties($days);
        
        if (isset($_GET['ajax'])) {
            return $this->json($warranties);
        }

        return $this->view('warranties/expiring', [
            'warranties' => $warranties,
            'days' => $days
        ]);
    }

    public function getExpiredWarranties()
    {
        $this->authorize('view_warranties');
        
        $warranties = $this->warranty->getExpiredWarranties();
        
        if (isset($_GET['ajax'])) {
            return $this->json($warranties);
        }

        return $this->view('warranties/expired', [
            'warranties' => $warranties
        ]);
    }

    public function createClaim($id)
    {
        $this->authorize('create_warranty_claim');
        
        $data = $this->validate($_POST, [
            'claim_date' => 'required|date',
            'description' => 'required',
            'issue_type' => 'required',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $data['warranty_id'] = $id;
        $data['status'] = 'pending';
        $data['created_by'] = $this->auth->id;

        if ($claimId = $this->warrantyClaim->create($data)) {
            $this->flash('success', 'Warranty claim created successfully.');
            return $this->redirect("warranties/{$id}/claims/{$claimId}");
        }

        $this->flash('error', 'Failed to create warranty claim.');
        return $this->redirect("warranties/{$id}");
    }

    public function getClaims($id)
    {
        $this->authorize('view_warranty_claims');
        
        $warranty = $this->warranty->getById($id);
        if (!$warranty) {
            $this->flash('error', 'Warranty not found.');
            return $this->redirect('warranties');
        }

        $claims = $this->warrantyClaim->getByWarrantyId($id);
        
        if (isset($_GET['ajax'])) {
            return $this->json($claims);
        }

        return $this->view('warranties/claims', [
            'warranty' => $warranty,
            'claims' => $claims
        ]);
    }

    public function renewWarranty($id)
    {
        $this->authorize('renew_warranty');
        
        $data = $this->validate($_POST, [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'cost' => 'numeric|min:0',
            'terms_conditions' => 'required',
            'coverage_details' => 'required'
        ]);

        if ($this->warranty->renew($id, $data)) {
            $this->flash('success', 'Warranty renewed successfully.');
        } else {
            $this->flash('error', 'Failed to renew warranty.');
        }

        return $this->redirect("warranties/{$id}");
    }

    public function getAssetWarranties($assetId)
    {
        $this->authorize('view_warranties');
        
        $warranties = $this->warranty->getByAssetId($assetId);
        
        if (isset($_GET['ajax'])) {
            return $this->json($warranties);
        }

        $asset = $this->asset->getById($assetId);
        
        return $this->view('warranties/asset', [
            'warranties' => $warranties,
            'asset' => $asset
        ]);
    }
} 