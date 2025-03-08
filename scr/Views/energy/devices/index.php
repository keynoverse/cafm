<?php $this->layout('layouts/app', ['title' => 'Smart Building Devices']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Smart Building Devices</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item active">Smart Devices</li>
                </ol>
            </nav>
        </div>
        <?php if ($this->user->hasPermission('create_smart_device')): ?>
            <a href="<?= url('energy/devices/create') ?>" class="btn btn-primary">
                <i class="bx bx-plus"></i> Add Device
            </a>
        <?php endif; ?>
    </div>

    <!-- System Status -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Active Devices</h6>
                    <h2 class="card-title mb-0" data-stat="active_devices">
                        <?= $stats['active_devices'] ?>/<?= $stats['total_devices'] ?>
                    </h2>
                    <div class="mt-2">
                        <small class="text-<?= $stats['active_devices'] === $stats['total_devices'] ? 'success' : 'warning' ?>">
                            <i class="bx bx-signal-5"></i>
                            <?= number_format(($stats['active_devices'] / $stats['total_devices']) * 100, 1) ?>% Online
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Data Points Today</h6>
                    <h2 class="card-title mb-0" data-stat="data_points">
                        <?= number_format($stats['data_points_today']) ?>
                    </h2>
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="bx bx-data"></i>
                            <?= number_format($stats['data_points_rate']) ?> points/hour
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Alerts</h6>
                    <h2 class="card-title mb-0" data-stat="alerts">
                        <?= $stats['active_alerts'] ?>
                    </h2>
                    <div class="mt-2">
                        <small class="text-<?= $stats['active_alerts'] > 0 ? 'danger' : 'success' ?>">
                            <i class="bx bx-bell"></i>
                            <?= $stats['active_alerts'] > 0 ? 'Active Alerts' : 'No Active Alerts' ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">System Status</h6>
                    <h2 class="card-title mb-0">
                        <span class="badge bg-<?= getStatusClass($stats['system_status']) ?>" 
                              data-stat="system_status">
                            <?= ucfirst($stats['system_status']) ?>
                        </span>
                    </h2>
                    <div class="mt-2">
                        <small class="text-muted" data-stat="last_check">
                            <i class="bx bx-time"></i>
                            Last checked: <?= date('g:i A', strtotime($stats['last_check'])) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" class="form-control" id="search" 
                               placeholder="Search devices..." value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="device_type">
                        <option value="">All Types</option>
                        <option value="sensor">Sensor</option>
                        <option value="meter">Meter</option>
                        <option value="controller">Controller</option>
                        <option value="gateway">Gateway</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="status">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="error">Error</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                        Clear Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Devices Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Device Name</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Last Communication</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($devices as $device): ?>
                            <tr>
                                <td>
                                    <a href="<?= url("energy/devices/{$device['id']}") ?>">
                                        <?= htmlspecialchars($device['device_name']) ?>
                                    </a>
                                    <?php if ($device['firmware_update_available']): ?>
                                        <span class="badge bg-warning ms-2">Update Available</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= getTypeClass($device['device_type']) ?>">
                                        <?= ucfirst($device['device_type']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($device['location']) ?></td>
                                <td>
                                    <span class="badge bg-<?= getStatusClass($device['status']) ?>">
                                        <?= ucfirst($device['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('M j, Y g:i A', strtotime($device['last_communication'])) ?>
                                    <?php if (strtotime($device['last_communication']) < strtotime('-1 hour')): ?>
                                        <small class="text-danger d-block">No recent data</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= url("energy/devices/{$device['id']}") ?>" 
                                           class="btn btn-sm btn-outline-secondary"
                                           title="View Details">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <?php if ($this->user->hasPermission('edit_smart_device')): ?>
                                            <a href="<?= url("energy/devices/{$device['id']}/edit") ?>" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit Device">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($this->user->hasPermission('configure_smart_device')): ?>
                                            <a href="<?= url("energy/devices/{$device['id']}/configure") ?>" 
                                               class="btn btn-sm btn-outline-info"
                                               title="Configure Device">
                                                <i class="bx bx-cog"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Device pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('energy/devices', ['page' => $currentPage - 1]) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('energy/devices', ['page' => $i]) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('energy/devices', ['page' => $currentPage + 1]) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function getTypeClass(type) {
    return {
        'sensor': 'info',
        'meter': 'primary',
        'controller': 'success',
        'gateway': 'warning'
    }[type] || 'secondary';
}

function getStatusClass(status) {
    return {
        'active': 'success',
        'inactive': 'secondary',
        'maintenance': 'warning',
        'error': 'danger'
    }[status] || 'secondary';
}

document.addEventListener('DOMContentLoaded', function() {
    // Filter handlers
    const filterForm = document.getElementById('filterForm');
    const clearFiltersBtn = document.getElementById('clearFilters');

    filterForm.querySelectorAll('input, select').forEach(element => {
        element.addEventListener('change', applyFilters);
    });

    clearFiltersBtn.addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });

    function applyFilters() {
        filterForm.submit();
    }

    // Auto-refresh status
    setInterval(function() {
        fetch(`<?= url('energy/devices/status') ?>`)
            .then(response => response.json())
            .then(updateStats);
    }, 60000);
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