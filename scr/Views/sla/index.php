<?php $this->layout('layouts/app', ['title' => 'SLA Management']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Service Level Agreements</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">SLA Management</li>
                </ol>
            </nav>
        </div>
        <?php if ($this->user->hasPermission('create_sla')): ?>
            <a href="<?= url('sla/create') ?>" class="btn btn-primary">
                <i class="bx bx-plus"></i> New SLA
            </a>
        <?php endif; ?>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total SLAs</h6>
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
                    <h6 class="card-subtitle mb-2 text-muted">Compliance Rate</h6>
                    <h2 class="card-title mb-0"><?= number_format($statistics['compliance_rate'], 1) ?>%</h2>
                    <div class="mt-2">
                        <small class="text-<?= $statistics['compliance_rate'] >= 90 ? 'success' : 'warning' ?>">
                            <i class="bx bx-trending-up"></i> Last 30 Days
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Current Violations</h6>
                    <h2 class="card-title mb-0"><?= $statistics['violations'] ?></h2>
                    <div class="mt-2">
                        <small class="text-<?= $statistics['violations'] > 0 ? 'danger' : 'success' ?>">
                            <i class="bx bx-error-circle"></i> Active Issues
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Average Response Time</h6>
                    <h2 class="card-title mb-0"><?= number_format($statistics['avg_response_time'], 1) ?>h</h2>
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="bx bx-time"></i> Last 30 Days
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
                               placeholder="Search SLAs..." value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="priority">
                        <option value="">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                        Clear Filters
                    </button>
                </div>
                <?php if ($this->user->hasPermission('generate_sla_reports')): ?>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-primary w-100" id="exportReport">
                            <i class="bx bx-export"></i> Export Report
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- SLAs Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Priority</th>
                            <th>Category</th>
                            <th>Response Time</th>
                            <th>Resolution Time</th>
                            <th>Compliance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($slas as $sla): ?>
                            <tr>
                                <td>
                                    <a href="<?= url("sla/{$sla['id']}") ?>">
                                        <?= htmlspecialchars($sla['name']) ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-<?= getPriorityClass($sla['priority']) ?>">
                                        <?= ucfirst($sla['priority']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($sla['category_name']) ?></td>
                                <td><?= $sla['response_time'] ?> hours</td>
                                <td><?= $sla['resolution_time'] ?> hours</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-<?= getComplianceClass($sla['compliance_rate']) ?>" 
                                             role="progressbar" 
                                             style="width: <?= $sla['compliance_rate'] ?>%"
                                             aria-valuenow="<?= $sla['compliance_rate'] ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?= number_format($sla['compliance_rate'], 1) ?>%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $sla['active'] ? 'success' : 'secondary' ?>">
                                        <?= $sla['active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= url("sla/{$sla['id']}") ?>" 
                                           class="btn btn-sm btn-outline-secondary"
                                           title="View Details">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <?php if ($this->user->hasPermission('edit_sla')): ?>
                                            <a href="<?= url("sla/{$sla['id']}/edit") ?>" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit SLA">
                                                <i class="bx bx-edit"></i>
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
                <nav aria-label="SLA pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('sla', ['page' => $currentPage - 1]) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('sla', ['page' => $i]) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('sla', ['page' => $currentPage + 1]) ?>">Next</a>
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
    const prioritySelect = document.getElementById('priority');
    const categorySelect = document.getElementById('category');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const exportReportBtn = document.getElementById('exportReport');

    function applyFilters() {
        const params = new URLSearchParams(window.location.search);
        
        if (searchInput.value) params.set('query', searchInput.value);
        else params.delete('query');
        
        if (prioritySelect.value) params.set('priority', prioritySelect.value);
        else params.delete('priority');
        
        if (categorySelect.value) params.set('category', categorySelect.value);
        else params.delete('category');
        
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyFilters();
        }
    });

    prioritySelect.addEventListener('change', applyFilters);
    categorySelect.addEventListener('change', applyFilters);

    clearFiltersBtn.addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });

    if (exportReportBtn) {
        exportReportBtn.addEventListener('click', function() {
            window.location.href = `${window.location.pathname}/export?${new URLSearchParams(window.location.search)}`;
        });
    }
});

function getPriorityClass(priority) {
    return {
        'low': 'success',
        'medium': 'info',
        'high': 'warning',
        'urgent': 'danger'
    }[priority] || 'secondary';
}

function getComplianceClass(rate) {
    if (rate >= 95) return 'success';
    if (rate >= 90) return 'info';
    if (rate >= 80) return 'warning';
    return 'danger';
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

.progress {
    background-color: #e9ecef;
    border-radius: 0.25rem;
}

.progress-bar {
    transition: width 0.6s ease;
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