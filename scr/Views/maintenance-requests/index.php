<?php $this->layout('layouts/app', ['title' => 'Maintenance Requests']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Maintenance Requests</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Maintenance Requests</li>
                </ol>
            </nav>
        </div>
        <?php if ($this->user->hasPermission('create_maintenance_request')): ?>
            <a href="<?= url('maintenance-requests/create') ?>" class="btn btn-primary">
                <i class="bx bx-plus"></i> New Request
            </a>
        <?php endif; ?>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Requests</h6>
                    <h2 class="card-title mb-0"><?= $statistics['total'] ?></h2>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="bx bx-check-circle"></i> <?= $statistics['completed'] ?> Completed
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Pending Approval</h6>
                    <h2 class="card-title mb-0"><?= $statistics['pending'] ?></h2>
                    <div class="mt-2">
                        <small class="text-warning">
                            <i class="bx bx-time"></i> Awaiting Review
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">In Progress</h6>
                    <h2 class="card-title mb-0"><?= $statistics['in_progress'] ?></h2>
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="bx bx-loader"></i> Being Processed
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Urgent Requests</h6>
                    <h2 class="card-title mb-0 text-danger"><?= $statistics['urgent'] ?></h2>
                    <div class="mt-2">
                        <small class="text-danger">
                            <i class="bx bx-error"></i> High Priority
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
                               placeholder="Search requests..." value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="status">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= isset($_GET['status']) && $_GET['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="in_progress" <?= isset($_GET['status']) && $_GET['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="completed" <?= isset($_GET['status']) && $_GET['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="rejected" <?= isset($_GET['status']) && $_GET['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="priority">
                        <option value="">All Priorities</option>
                        <option value="low" <?= isset($_GET['priority']) && $_GET['priority'] === 'low' ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= isset($_GET['priority']) && $_GET['priority'] === 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= isset($_GET['priority']) && $_GET['priority'] === 'high' ? 'selected' : '' ?>>High</option>
                        <option value="urgent" <?= isset($_GET['priority']) && $_GET['priority'] === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" 
                                    <?= isset($_GET['category']) && $_GET['category'] == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
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

    <!-- Requests Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Asset</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td>#<?= $request['id'] ?></td>
                                <td>
                                    <a href="<?= url("maintenance-requests/{$request['id']}") ?>">
                                        <?= htmlspecialchars($request['title']) ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="d-flex align-items-center">
                                        <i class="bx bx-cube me-2"></i>
                                        <?= htmlspecialchars($request['asset_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $priorityClass = [
                                        'low' => 'success',
                                        'medium' => 'info',
                                        'high' => 'warning',
                                        'urgent' => 'danger'
                                    ][$request['priority']];
                                    ?>
                                    <span class="badge bg-<?= $priorityClass ?>">
                                        <?= ucfirst($request['priority']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'pending' => 'secondary',
                                        'approved' => 'info',
                                        'in_progress' => 'primary',
                                        'completed' => 'success',
                                        'rejected' => 'danger'
                                    ][$request['status']];
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= ucfirst(str_replace('_', ' ', $request['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="d-flex align-items-center">
                                        <i class="bx bx-user me-2"></i>
                                        <?= htmlspecialchars($request['requested_by_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('M j, Y', strtotime($request['created_at'])) ?>
                                    <small class="d-block text-muted">
                                        <?= date('g:i A', strtotime($request['created_at'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <?php if ($this->user->hasPermission('view_maintenance_requests')): ?>
                                            <a href="<?= url("maintenance-requests/{$request['id']}") ?>" 
                                               class="btn btn-sm btn-outline-secondary"
                                               title="View Details">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($this->user->hasPermission('edit_maintenance_request')): ?>
                                            <a href="<?= url("maintenance-requests/{$request['id']}/edit") ?>" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit Request">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($request['status'] === 'pending' && $this->user->hasPermission('approve_maintenance_request')): ?>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success approve-request"
                                                    data-id="<?= $request['id'] ?>"
                                                    title="Approve Request">
                                                <i class="bx bx-check"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($requests)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bx bx-info-circle fs-4 mb-2"></i>
                                    <p class="mb-0">No maintenance requests found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Maintenance requests pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('maintenance-requests', ['page' => $currentPage - 1]) ?>">
                                Previous
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('maintenance-requests', ['page' => $i]) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('maintenance-requests', ['page' => $currentPage + 1]) ?>">
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
    const prioritySelect = document.getElementById('priority');
    const categorySelect = document.getElementById('category');
    const clearFiltersBtn = document.getElementById('clearFilters');

    // Apply filters
    function applyFilters() {
        const params = new URLSearchParams(window.location.search);
        
        if (searchInput.value) params.set('query', searchInput.value);
        else params.delete('query');
        
        if (statusSelect.value) params.set('status', statusSelect.value);
        else params.delete('status');
        
        if (prioritySelect.value) params.set('priority', prioritySelect.value);
        else params.delete('priority');
        
        if (categorySelect.value) params.set('category', categorySelect.value);
        else params.delete('category');
        
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
    prioritySelect.addEventListener('change', applyFilters);
    categorySelect.addEventListener('change', applyFilters);

    clearFiltersBtn.addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });

    // Handle request approval
    document.querySelectorAll('.approve-request').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to approve this request?')) {
                fetch(`<?= url('maintenance-requests') ?>/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': '<?= csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Failed to approve request');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while approving the request');
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