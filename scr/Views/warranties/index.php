<?php $this->layout('layouts/app', ['title' => 'Warranty Management']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Warranty Management</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Warranties</li>
                </ol>
            </nav>
        </div>
        <?php if ($this->user->hasPermission('create_warranty')): ?>
            <a href="<?= url('warranties/create') ?>" class="btn btn-primary">
                <i class="bx bx-plus"></i> New Warranty
            </a>
        <?php endif; ?>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Warranties</h6>
                    <h2 class="card-title mb-0"><?= $statistics['total'] ?></h2>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="bx bx-check-circle"></i> <?= $statistics['active'] ?> Active
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Expiring Soon</h6>
                    <h2 class="card-title mb-0"><?= $statistics['expiring_soon'] ?></h2>
                    <div class="mt-2">
                        <small class="text-warning">
                            <i class="bx bx-time"></i> Within 30 Days
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Active Claims</h6>
                    <h2 class="card-title mb-0"><?= $statistics['active_claims'] ?></h2>
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="bx bx-file"></i> In Progress
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Expired</h6>
                    <h2 class="card-title mb-0 text-danger"><?= $statistics['expired'] ?></h2>
                    <div class="mt-2">
                        <small class="text-danger">
                            <i class="bx bx-x-circle"></i> Need Renewal
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" class="form-control" id="search" 
                               placeholder="Search warranties..." value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="status">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>
                        <option value="expiring">Expiring Soon</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="type">
                        <option value="">All Types</option>
                        <?php foreach ($warrantyTypes as $type): ?>
                            <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="supplier">
                        <option value="">All Suppliers</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= $supplier['id'] ?>"><?= htmlspecialchars($supplier['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                        Clear Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Warranties Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Asset</th>
                            <th>Type</th>
                            <th>Supplier</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($warranties as $warranty): ?>
                            <tr>
                                <td>
                                    <a href="<?= url("assets/{$warranty['asset_id']}") ?>">
                                        <?= htmlspecialchars($warranty['asset_name']) ?>
                                    </a>
                                    <small class="d-block text-muted"><?= $warranty['asset_tag'] ?></small>
                                </td>
                                <td><?= htmlspecialchars($warranty['warranty_type']) ?></td>
                                <td>
                                    <a href="<?= url("suppliers/{$warranty['supplier_id']}") ?>">
                                        <?= htmlspecialchars($warranty['supplier_name']) ?>
                                    </a>
                                </td>
                                <td><?= date('M j, Y', strtotime($warranty['start_date'])) ?></td>
                                <td>
                                    <?= date('M j, Y', strtotime($warranty['end_date'])) ?>
                                    <?php
                                    $daysLeft = ceil((strtotime($warranty['end_date']) - time()) / (60 * 60 * 24));
                                    if ($daysLeft > 0 && $daysLeft <= 30): ?>
                                        <small class="d-block text-warning">
                                            <?= $daysLeft ?> days left
                                        </small>
                                    <?php elseif ($daysLeft <= 0): ?>
                                        <small class="d-block text-danger">
                                            Expired
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = 'secondary';
                                    if ($daysLeft > 0) {
                                        $statusClass = $daysLeft <= 30 ? 'warning' : 'success';
                                    } else {
                                        $statusClass = 'danger';
                                    }
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= $daysLeft > 0 ? ($daysLeft <= 30 ? 'Expiring Soon' : 'Active') : 'Expired' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= url("warranties/{$warranty['id']}") ?>" 
                                           class="btn btn-sm btn-outline-secondary"
                                           title="View Details">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <?php if ($this->user->hasPermission('edit_warranty')): ?>
                                            <a href="<?= url("warranties/{$warranty['id']}/edit") ?>" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit Warranty">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($daysLeft <= 30 && $this->user->hasPermission('renew_warranty')): ?>
                                            <a href="<?= url("warranties/{$warranty['id']}/renew") ?>" 
                                               class="btn btn-sm btn-outline-success"
                                               title="Renew Warranty">
                                                <i class="bx bx-refresh"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($warranties)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bx bx-info-circle fs-4 mb-2"></i>
                                    <p class="mb-0">No warranties found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Warranty pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('warranties', ['page' => $currentPage - 1]) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('warranties', ['page' => $i]) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('warranties', ['page' => $currentPage + 1]) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const typeSelect = document.getElementById('type');
    const supplierSelect = document.getElementById('supplier');
    const clearFiltersBtn = document.getElementById('clearFilters');

    function applyFilters() {
        const params = new URLSearchParams(window.location.search);
        
        if (searchInput.value) params.set('query', searchInput.value);
        else params.delete('query');
        
        if (statusSelect.value) params.set('status', statusSelect.value);
        else params.delete('status');
        
        if (typeSelect.value) params.set('type', typeSelect.value);
        else params.delete('type');
        
        if (supplierSelect.value) params.set('supplier', supplierSelect.value);
        else params.delete('supplier');
        
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyFilters();
        }
    });

    statusSelect.addEventListener('change', applyFilters);
    typeSelect.addEventListener('change', applyFilters);
    supplierSelect.addEventListener('change', applyFilters);

    clearFiltersBtn.addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });
});
</script>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.table td {
    vertical-align: middle;
}

.btn-group {
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

tr:hover .btn-group {
    opacity: 1;
}

@media (max-width: 768px) {
    .btn-group {
        opacity: 1;
    }
    
    .card:hover {
        transform: none;
    }
}
</style> 