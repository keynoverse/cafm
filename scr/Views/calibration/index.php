<?php $this->layout('layouts/app', ['title' => 'Equipment Calibration']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Equipment Calibration</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Equipment Calibration</li>
                </ol>
            </nav>
        </div>
        <?php if ($this->user->hasPermission('create_calibration')): ?>
            <a href="<?= url('calibration/create') ?>" class="btn btn-primary">
                <i class="bx bx-plus"></i> New Calibration
            </a>
        <?php endif; ?>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Equipment</h6>
                    <h2 class="card-title mb-0"><?= $statistics['total'] ?></h2>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="bx bx-check-circle"></i> <?= $statistics['calibrated'] ?> Calibrated
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Due This Week</h6>
                    <h2 class="card-title mb-0"><?= $statistics['due_this_week'] ?></h2>
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="bx bx-calendar"></i> Upcoming Calibrations
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Overdue</h6>
                    <h2 class="card-title mb-0 text-danger"><?= $statistics['overdue'] ?></h2>
                    <div class="mt-2">
                        <small class="text-danger">
                            <i class="bx bx-time"></i> Requires Attention
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Success Rate</h6>
                    <h2 class="card-title mb-0">
                        <?= $statistics['total'] > 0 ? 
                            round(($statistics['passed'] / $statistics['total']) * 100) : 0 ?>%
                    </h2>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="bx bx-trending-up"></i> Pass Rate
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
                               placeholder="Search equipment..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="status">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="in_progress" <?= isset($_GET['status']) && $_GET['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="completed" <?= isset($_GET['status']) && $_GET['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="failed" <?= isset($_GET['status']) && $_GET['status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="timeframe">
                        <option value="">All Time</option>
                        <option value="this_week" <?= isset($_GET['timeframe']) && $_GET['timeframe'] === 'this_week' ? 'selected' : '' ?>>This Week</option>
                        <option value="this_month" <?= isset($_GET['timeframe']) && $_GET['timeframe'] === 'this_month' ? 'selected' : '' ?>>This Month</option>
                        <option value="overdue" <?= isset($_GET['timeframe']) && $_GET['timeframe'] === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                        Clear Filters
                    </button>
                </div>
                <div class="col-md-2">
                    <?php if ($this->user->hasPermission('export_calibration')): ?>
                        <a href="<?= url('calibration/export') ?>" class="btn btn-outline-primary w-100">
                            <i class="bx bx-export"></i> Export
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Calibration Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Equipment</th>
                            <th>Type</th>
                            <th>Last Calibration</th>
                            <th>Next Due</th>
                            <th>Standard</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($calibrations as $cal): ?>
                            <tr>
                                <td>
                                    <a href="<?= url("calibration/{$cal['id']}") ?>" class="d-flex align-items-center">
                                        <i class="bx bx-cube me-2"></i>
                                        <div>
                                            <?= htmlspecialchars($cal['asset_name']) ?>
                                            <small class="d-block text-muted"><?= $cal['asset_tag'] ?></small>
                                        </div>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($cal['calibration_type']) ?></td>
                                <td>
                                    <?php if ($cal['last_calibration_date']): ?>
                                        <?= date('M j, Y', strtotime($cal['last_calibration_date'])) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not calibrated</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $dueDate = new DateTime($cal['next_calibration_date']);
                                    $today = new DateTime();
                                    $interval = $today->diff($dueDate);
                                    $isPast = $dueDate < $today;
                                    ?>
                                    <span class="<?= $isPast ? 'text-danger' : 'text-success' ?>">
                                        <?= date('M j, Y', strtotime($cal['next_calibration_date'])) ?>
                                        <small class="d-block">
                                            <?= $isPast ? 
                                                "{$interval->days} days overdue" : 
                                                "in {$interval->days} days" ?>
                                        </small>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($cal['calibration_standard']) ?></td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'pending' => 'secondary',
                                        'in_progress' => 'info',
                                        'completed' => 'success',
                                        'failed' => 'danger'
                                    ][$cal['status']];
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= ucfirst(str_replace('_', ' ', $cal['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <?php if ($this->user->hasPermission('view_calibration')): ?>
                                            <a href="<?= url("calibration/{$cal['id']}") ?>" 
                                               class="btn btn-sm btn-outline-secondary"
                                               title="View Details">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($this->user->hasPermission('edit_calibration')): ?>
                                            <a href="<?= url("calibration/{$cal['id']}/edit") ?>" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($this->user->hasPermission('perform_calibration') && $cal['status'] === 'pending'): ?>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success start-calibration"
                                                    data-id="<?= $cal['id'] ?>"
                                                    title="Start Calibration">
                                                <i class="bx bx-play"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($calibrations)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bx bx-info-circle fs-4 mb-2"></i>
                                    <p class="mb-0">No calibration records found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Calibration pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('calibration', ['page' => $currentPage - 1]) ?>">
                                Previous
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('calibration', ['page' => $i]) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('calibration', ['page' => $currentPage + 1]) ?>">
                                Next
                            </a>
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
    const timeframeSelect = document.getElementById('timeframe');
    const clearFiltersBtn = document.getElementById('clearFilters');

    // Apply filters
    function applyFilters() {
        const params = new URLSearchParams(window.location.search);
        
        if (searchInput.value) params.set('search', searchInput.value);
        else params.delete('search');
        
        if (statusSelect.value) params.set('status', statusSelect.value);
        else params.delete('status');
        
        if (timeframeSelect.value) params.set('timeframe', timeframeSelect.value);
        else params.delete('timeframe');
        
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    // Event listeners
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyFilters();
        }
    });

    statusSelect.addEventListener('change', applyFilters);
    timeframeSelect.addEventListener('change', applyFilters);

    clearFiltersBtn.addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });

    // Handle calibration start
    document.querySelectorAll('.start-calibration').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to start this calibration?')) {
                fetch(`<?= url('calibration') ?>/${id}/start`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': '<?= csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = `<?= url('calibration') ?>/${id}/perform`;
                    } else {
                        alert('Failed to start calibration');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while starting the calibration');
                });
            }
        });
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