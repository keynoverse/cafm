<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Assets</h1>
        </div>
        <div class="col text-end">
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url('assets/create') ?>" class="btn btn-primary">
                    <i class='bx bx-plus'></i> New Asset
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Assets</h6>
                    <h2 class="mb-0"><?= $stats['total_assets'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Active Assets</h6>
                    <h2 class="mb-0"><?= $stats['active_assets'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Maintenance</h6>
                    <h2 class="mb-0"><?= $stats['maintenance_assets'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Inactive Assets</h6>
                    <h2 class="mb-0"><?= $stats['inactive_assets'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $this->url('assets') ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="query" class="form-control" placeholder="Search assets..." 
                        value="<?= $_GET['query'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class='bx bx-search'></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Assets Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Model</th>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($assets)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No assets found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($assets as $asset): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url("assets/{$asset['id']}") ?>">
                                            <?= htmlspecialchars($asset['name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= $this->url("locations/{$asset['location_id']}") ?>">
                                            <?= htmlspecialchars($asset['location_name']) ?>
                                        </a>
                                    </td>
                                    <td><?= ucfirst(str_replace('_', ' ', $asset['type'])) ?></td>
                                    <td><?= htmlspecialchars($asset['model']) ?></td>
                                    <td><?= htmlspecialchars($asset['serial_number']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $asset['status'] === 'active' ? 'success' : 
                                            ($asset['status'] === 'maintenance' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($asset['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= $this->url("assets/{$asset['id']}") ?>" 
                                                class="btn btn-sm btn-info" title="View Details">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <?php if ($this->user['role'] === 'admin'): ?>
                                                <a href="<?= $this->url("assets/{$asset['id']}/edit") ?>" 
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal<?= $asset['id'] ?>"
                                                    title="Delete">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <?php if ($this->user['role'] === 'admin'): ?>
                                    <div class="modal fade" id="deleteModal<?= $asset['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Asset</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this asset? This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST" action="<?= $this->url("assets/{$asset['id']}/delete") ?>" class="d-inline">
                                                        <?= $this->csrf_field() ?>
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (!empty($assets)): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url('assets', ['page' => $currentPage - 1]) ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="page-item active">
                            <span class="page-link"><?= $currentPage ?></span>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="<?= $this->url('assets', ['page' => $currentPage + 1]) ?>">
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
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        new bootstrap.Tooltip(element);
    });
});
</script>

<?php
function getStatusColor($status) {
    switch ($status) {
        case 'active':
            return 'success';
        case 'inactive':
            return 'secondary';
        case 'maintenance':
            return 'warning';
        case 'retired':
            return 'danger';
        default:
            return 'primary';
    }
}
?> 