<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Work Orders</h1>
        </div>
        <div class="col text-end">
            <?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
                <a href="<?= $this->url('work-orders/create') ?>" class="btn btn-primary">
                    <i class='bx bx-plus'></i> New Work Order
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Work Orders</h6>
                    <h2 class="mb-0"><?= $stats['total_work_orders'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Pending</h6>
                    <h2 class="mb-0"><?= $stats['pending_work_orders'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">In Progress</h6>
                    <h2 class="mb-0"><?= $stats['in_progress_work_orders'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Completed</h6>
                    <h2 class="mb-0"><?= $stats['completed_work_orders'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $this->url('work-orders') ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="query" class="form-control" placeholder="Search work orders..." 
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

    <!-- Work Orders Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Asset/Location</th>
                            <th>Assigned To</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($workOrders)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No work orders found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($workOrders as $workOrder): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url("work-orders/{$workOrder['id']}") ?>">
                                            <?= htmlspecialchars($workOrder['title']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($workOrder['asset_name']): ?>
                                            <a href="<?= $this->url("assets/{$workOrder['asset_id']}") ?>">
                                                <?= htmlspecialchars($workOrder['asset_name']) ?>
                                            </a>
                                        <?php elseif ($workOrder['location_name']): ?>
                                            <a href="<?= $this->url("locations/{$workOrder['location_id']}") ?>">
                                                <?= htmlspecialchars($workOrder['location_name']) ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($workOrder['assigned_to_first_name']): ?>
                                            <?= htmlspecialchars($workOrder['assigned_to_first_name'] . ' ' . $workOrder['assigned_to_last_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $workOrder['priority'] === 'urgent' ? 'danger' : 
                                            ($workOrder['priority'] === 'high' ? 'warning' : 
                                            ($workOrder['priority'] === 'medium' ? 'info' : 'success')) ?>">
                                            <?= ucfirst($workOrder['priority']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $workOrder['status'] === 'completed' ? 'success' : 
                                            ($workOrder['status'] === 'in_progress' ? 'primary' : 
                                            ($workOrder['status'] === 'cancelled' ? 'danger' : 'secondary')) ?>">
                                            <?= ucfirst(str_replace('_', ' ', $workOrder['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($workOrder['due_date']): ?>
                                            <?= date('M d, Y', strtotime($workOrder['due_date'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">No due date</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= $this->url("work-orders/{$workOrder['id']}") ?>" 
                                                class="btn btn-sm btn-info" title="View Details">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
                                                <a href="<?= $this->url("work-orders/{$workOrder['id']}/edit") ?>" 
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <?php if ($this->user['role'] === 'admin'): ?>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal<?= $workOrder['id'] ?>"
                                                        title="Delete">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <?php if ($this->user['role'] === 'admin'): ?>
                                    <div class="modal fade" id="deleteModal<?= $workOrder['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Work Order</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this work order? This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST" action="<?= $this->url("work-orders/{$workOrder['id']}/delete") ?>" class="d-inline">
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
            <?php if (!empty($workOrders)): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url('work-orders', ['page' => $currentPage - 1]) ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="page-item active">
                            <span class="page-link"><?= $currentPage ?></span>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="<?= $this->url('work-orders', ['page' => $currentPage + 1]) ?>">
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