<?php $this->layout('layouts/app', ['title' => 'Energy Consumption']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Energy Consumption</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item active">Consumption</li>
                </ol>
            </nav>
        </div>
        <?php if ($this->user->hasPermission('create_energy_consumption')): ?>
            <a href="<?= url('energy/consumption/create') ?>" class="btn btn-primary">
                <i class="bx bx-plus"></i> Record Consumption
            </a>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" class="form-control" id="search" 
                               placeholder="Search meters..." value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="type">
                        <option value="">All Types</option>
                        <option value="electricity">Electricity</option>
                        <option value="gas">Gas</option>
                        <option value="water">Water</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="asset">
                        <option value="">All Assets</option>
                        <?php foreach ($assets as $asset): ?>
                            <option value="<?= $asset['id'] ?>"><?= htmlspecialchars($asset['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">Date Range</span>
                        <input type="date" class="form-control" id="startDate" name="start_date">
                        <input type="date" class="form-control" id="endDate" name="end_date">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                        Clear Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Consumption Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Electricity</h6>
                    <h2 class="card-title mb-0"><?= number_format($summary['electricity'], 2) ?> kWh</h2>
                    <div class="mt-2">
                        <small class="text-<?= $summary['electricity_trend'] < 0 ? 'success' : 'warning' ?>">
                            <i class="bx bx-trending-<?= $summary['electricity_trend'] < 0 ? 'down' : 'up' ?>"></i>
                            <?= abs($summary['electricity_trend']) ?>% vs previous period
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Gas</h6>
                    <h2 class="card-title mb-0"><?= number_format($summary['gas'], 2) ?> m³</h2>
                    <div class="mt-2">
                        <small class="text-<?= $summary['gas_trend'] < 0 ? 'success' : 'warning' ?>">
                            <i class="bx bx-trending-<?= $summary['gas_trend'] < 0 ? 'down' : 'up' ?>"></i>
                            <?= abs($summary['gas_trend']) ?>% vs previous period
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Water</h6>
                    <h2 class="card-title mb-0"><?= number_format($summary['water'], 2) ?> m³</h2>
                    <div class="mt-2">
                        <small class="text-<?= $summary['water_trend'] < 0 ? 'success' : 'warning' ?>">
                            <i class="bx bx-trending-<?= $summary['water_trend'] < 0 ? 'down' : 'up' ?>"></i>
                            <?= abs($summary['water_trend']) ?>% vs previous period
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Peak Hour Usage</h6>
                    <h2 class="card-title mb-0"><?= number_format($summary['peak_percentage'], 1) ?>%</h2>
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="bx bx-time"></i> Of Total Consumption
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Consumption Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Asset/Location</th>
                            <th>Meter ID</th>
                            <th>Type</th>
                            <th>Reading</th>
                            <th>Date</th>
                            <th>Peak Hour</th>
                            <th>Recorded By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($readings as $reading): ?>
                            <tr>
                                <td>
                                    <a href="<?= url("assets/{$reading['asset_id']}") ?>">
                                        <?= htmlspecialchars($reading['asset_name']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($reading['meter_id']) ?></td>
                                <td>
                                    <span class="badge bg-<?= getTypeClass($reading['reading_type']) ?>">
                                        <?= ucfirst($reading['reading_type']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= number_format($reading['reading_value'], 2) ?>
                                    <?= htmlspecialchars($reading['reading_unit']) ?>
                                </td>
                                <td>
                                    <?= date('M j, Y g:i A', strtotime($reading['reading_date'])) ?>
                                </td>
                                <td>
                                    <?php if ($reading['peak_hour']): ?>
                                        <span class="badge bg-warning">Peak</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Off-Peak</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($reading['recorded_by']) ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= url("energy/consumption/{$reading['id']}") ?>" 
                                           class="btn btn-sm btn-outline-secondary"
                                           title="View Details">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <?php if ($this->user->hasPermission('edit_energy_consumption')): ?>
                                            <a href="<?= url("energy/consumption/{$reading['id']}/edit") ?>" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit Reading">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($readings)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bx bx-info-circle fs-4 mb-2"></i>
                                    <p class="mb-0">No consumption readings found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Consumption pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('energy/consumption', ['page' => $currentPage - 1]) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('energy/consumption', ['page' => $i]) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('energy/consumption', ['page' => $currentPage + 1]) ?>">Next</a>
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
    const typeSelect = document.getElementById('type');
    const assetSelect = document.getElementById('asset');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const clearFiltersBtn = document.getElementById('clearFilters');

    function applyFilters() {
        const params = new URLSearchParams(window.location.search);
        
        if (searchInput.value) params.set('query', searchInput.value);
        else params.delete('query');
        
        if (typeSelect.value) params.set('type', typeSelect.value);
        else params.delete('type');
        
        if (assetSelect.value) params.set('asset', assetSelect.value);
        else params.delete('asset');
        
        if (startDateInput.value) params.set('start_date', startDateInput.value);
        else params.delete('start_date');
        
        if (endDateInput.value) params.set('end_date', endDateInput.value);
        else params.delete('end_date');
        
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    // Event listeners for filter changes
    [searchInput, typeSelect, assetSelect, startDateInput, endDateInput].forEach(element => {
        if (element.tagName === 'INPUT' && element.type === 'text') {
            element.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyFilters();
                }
            });
        } else {
            element.addEventListener('change', applyFilters);
        }
    });

    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });

    // Set minimum date for end date based on start date
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
    });
});

function getTypeClass(type) {
    return {
        'electricity': 'primary',
        'gas': 'danger',
        'water': 'info',
        'other': 'secondary'
    }[type] || 'secondary';
}
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

    .input-group {
        margin-bottom: 1rem;
    }
}
</style> 