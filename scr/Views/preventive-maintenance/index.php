<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Preventive Maintenance</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('preventive-maintenance/create') ?>" class="btn btn-primary">
                <i class='bx bx-plus'></i> Create Task
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Upcoming Tasks</h6>
                    <h2 class="mb-0"><?= count($upcomingTasks) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Overdue Tasks</h6>
                    <h2 class="mb-0 text-danger"><?= count($overdueTasks) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Tasks</h6>
                    <h2 class="mb-0"><?= count($tasks) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Completed Today</h6>
                    <h2 class="mb-0">0</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $this->url('preventive-maintenance') ?>" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Frequency</label>
                    <select name="frequency" class="form-select">
                        <option value="">All Frequencies</option>
                        <option value="daily" <?= $filters['frequency'] === 'daily' ? 'selected' : '' ?>>Daily</option>
                        <option value="weekly" <?= $filters['frequency'] === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                        <option value="monthly" <?= $filters['frequency'] === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                        <option value="quarterly" <?= $filters['frequency'] === 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                        <option value="yearly" <?= $filters['frequency'] === 'yearly' ? 'selected' : '' ?>>Yearly</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Asset</label>
                    <select name="asset_id" class="form-select">
                        <option value="">All Assets</option>
                        <?php foreach ($assets as $asset): ?>
                            <option value="<?= $asset['id'] ?>" <?= $filters['asset_id'] == $asset['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($asset['name']) ?> (<?= htmlspecialchars($asset['asset_tag']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class='bx bx-filter'></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Asset</th>
                            <th>Frequency</th>
                            <th>Assigned To</th>
                            <th>Last Performed</th>
                            <th>Next Due</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tasks)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No preventive maintenance tasks found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url("preventive-maintenance/{$task['id']}") ?>">
                                            <?= htmlspecialchars($task['title']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($task['asset_id']): ?>
                                            <a href="<?= $this->url("assets/{$task['asset_id']}") ?>">
                                                <?= htmlspecialchars($task['asset_name']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">No Asset</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= ucfirst($task['frequency']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($task['assigned_to']): ?>
                                            <?= htmlspecialchars($task['assigned_to_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($task['last_performed']): ?>
                                            <?= date('M d, Y', strtotime($task['last_performed'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Never</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $nextDue = strtotime($task['next_due']);
                                        $isOverdue = $nextDue < time();
                                        ?>
                                        <span class="<?= $isOverdue ? 'text-danger' : '' ?>">
                                            <?= date('M d, Y', $nextDue) ?>
                                            <?php if ($isOverdue): ?>
                                                <i class='bx bx-time-five'></i>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= $this->url("preventive-maintenance/{$task['id']}") ?>" 
                                                class="btn btn-sm btn-info" title="View Details">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <?php if ($this->user['role'] === 'admin'): ?>
                                                <a href="<?= $this->url("preventive-maintenance/{$task['id']}/edit") ?>" 
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger delete-task" 
                                                    data-id="<?= $task['id'] ?>" title="Delete">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (!empty($tasks)): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url("preventive-maintenance?page=" . ($currentPage - 1)) ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= ceil(count($tasks) / 10); $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $this->url("preventive-maintenance?page={$i}") ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < ceil(count($tasks) / 10)): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url("preventive-maintenance?page=" . ($currentPage + 1)) ?>">
                                    Next
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this preventive maintenance task? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="" class="d-inline">
                    <?= $this->csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmation
    document.querySelectorAll('.delete-task').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const modal = document.getElementById('deleteModal');
            const form = modal.querySelector('form');
            form.action = '<?= $this->url('preventive-maintenance/') ?>' + id + '/delete';
            new bootstrap.Modal(modal).show();
        });
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script> 