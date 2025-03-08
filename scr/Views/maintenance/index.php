<?php $this->layout('layouts/app', ['title' => 'Maintenance Management']) ?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Total Schedules</h6>
                <h2 class="card-title mb-0"><?= $statistics['total'] ?></h2>
                <div class="mt-2">
                    <small class="text-success">
                        <i class="bx bx-calendar"></i> Active Schedules: <?= $statistics['active'] ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Completed</h6>
                <h2 class="card-title mb-0"><?= $statistics['completed'] ?></h2>
                <div class="mt-2">
                    <small class="text-success">
                        <i class="bx bx-check-circle"></i> 
                        <?= $statistics['total'] > 0 ? 
                            round(($statistics['completed'] / $statistics['total']) * 100) : 0 ?>% Complete
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
                <h6 class="card-subtitle mb-2 text-muted">Upcoming (7 Days)</h6>
                <h2 class="card-title mb-0"><?= count($upcoming) ?></h2>
                <div class="mt-2">
                    <small class="text-info">
                        <i class="bx bx-calendar-check"></i> Scheduled Tasks
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="btn-group">
            <?php if ($this->user->hasPermission('create_maintenance')): ?>
                <a href="<?= url('maintenance/create') ?>" class="btn btn-primary">
                    <i class="bx bx-plus"></i> New Schedule
                </a>
            <?php endif; ?>
            <?php if ($this->user->hasPermission('export_maintenance')): ?>
                <a href="<?= url('maintenance/export') ?>" class="btn btn-outline-secondary">
                    <i class="bx bx-export"></i> Export
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Overdue Tasks Alert -->
<?php if ($statistics['overdue'] > 0): ?>
    <div class="alert alert-danger mb-4" role="alert">
        <h4 class="alert-heading"><i class="bx bx-error"></i> Overdue Maintenance Tasks</h4>
        <p>There are <?= $statistics['overdue'] ?> overdue maintenance tasks that require immediate attention.</p>
        <hr>
        <p class="mb-0">Please review and complete these tasks as soon as possible to ensure proper equipment maintenance.</p>
    </div>
<?php endif; ?>

<!-- Maintenance Schedule Tabs -->
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#all-schedules">
                    All Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#upcoming">
                    Upcoming <span class="badge bg-info"><?= count($upcoming) ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#overdue">
                    Overdue <span class="badge bg-danger"><?= count($overdue) ?></span>
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <!-- All Schedules Tab -->
            <div class="tab-pane fade show active" id="all-schedules">
                <!-- Search and Filters -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" class="form-control" id="scheduleSearch" placeholder="Search schedules...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="frequencyFilter">
                            <option value="">All Frequencies</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="bi_weekly">Bi-Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="semi_annual">Semi-Annual</option>
                            <option value="annual">Annual</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                            Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Schedules Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Asset</th>
                                <th>Frequency</th>
                                <th>Next Due</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="schedulesTableBody">
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td>
                                        <a href="<?= url("maintenance/{$schedule['id']}") ?>">
                                            <?= htmlspecialchars($schedule['title']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="d-flex align-items-center">
                                            <i class="bx bx-cube me-1"></i>
                                            <?= htmlspecialchars($schedule['asset_name']) ?>
                                            <small class="text-muted ms-1">(<?= $schedule['asset_tag'] ?>)</small>
                                        </span>
                                    </td>
                                    <td><?= ucfirst(str_replace('_', ' ', $schedule['frequency'])) ?></td>
                                    <td>
                                        <?php
                                        $dueDate = new DateTime($schedule['next_due_date']);
                                        $today = new DateTime();
                                        $interval = $today->diff($dueDate);
                                        $isPast = $dueDate < $today;
                                        ?>
                                        <span class="<?= $isPast ? 'text-danger' : 'text-success' ?>">
                                            <?= $schedule['next_due_date'] ?>
                                            <small class="d-block text-muted">
                                                <?= $isPast ? 
                                                    "{$interval->days} days overdue" : 
                                                    "in {$interval->days} days" ?>
                                            </small>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($schedule['assigned_to']): ?>
                                            <span class="d-flex align-items-center">
                                                <i class="bx bx-user me-1"></i>
                                                <?= htmlspecialchars($schedule['assigned_first_name'] . ' ' . $schedule['assigned_last_name']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'active' => 'success',
                                            'completed' => 'info',
                                            'overdue' => 'danger',
                                            'inactive' => 'secondary'
                                        ][$schedule['status']];
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>">
                                            <?= ucfirst($schedule['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($this->user->hasPermission('edit_maintenance')): ?>
                                                <a href="<?= url("maintenance/{$schedule['id']}/edit") ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->user->hasPermission('complete_maintenance')): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success complete-schedule"
                                                        data-schedule-id="<?= $schedule['id'] ?>"
                                                        <?= $schedule['status'] === 'completed' ? 'disabled' : '' ?>>
                                                    <i class="bx bx-check"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Upcoming Tab -->
            <div class="tab-pane fade" id="upcoming">
                <div class="list-group">
                    <?php foreach ($upcoming as $schedule): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?= htmlspecialchars($schedule['title']) ?></h5>
                                <small class="text-success">
                                    Due: <?= $schedule['next_due_date'] ?>
                                </small>
                            </div>
                            <p class="mb-1">
                                Asset: <?= htmlspecialchars($schedule['asset_name']) ?> 
                                (<?= $schedule['asset_tag'] ?>)
                            </p>
                            <small class="text-muted">
                                Assigned to: <?= $schedule['assigned_to'] ? 
                                    htmlspecialchars($schedule['assigned_first_name'] . ' ' . $schedule['assigned_last_name']) : 
                                    'Unassigned' ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Overdue Tab -->
            <div class="tab-pane fade" id="overdue">
                <div class="list-group">
                    <?php foreach ($overdue as $schedule): ?>
                        <div class="list-group-item list-group-item-danger">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?= htmlspecialchars($schedule['title']) ?></h5>
                                <small class="text-danger">
                                    Due: <?= $schedule['next_due_date'] ?>
                                </small>
                            </div>
                            <p class="mb-1">
                                Asset: <?= htmlspecialchars($schedule['asset_name']) ?> 
                                (<?= $schedule['asset_tag'] ?>)
                            </p>
                            <small>
                                Assigned to: <?= $schedule['assigned_to'] ? 
                                    htmlspecialchars($schedule['assigned_first_name'] . ' ' . $schedule['assigned_last_name']) : 
                                    'Unassigned' ?>
                            </small>
                            <?php if ($this->user->hasPermission('complete_maintenance')): ?>
                                <button type="button" 
                                        class="btn btn-sm btn-danger float-end complete-schedule"
                                        data-schedule-id="<?= $schedule['id'] ?>">
                                    Complete Now
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search and filters
    const scheduleSearch = document.getElementById('scheduleSearch');
    const frequencyFilter = document.getElementById('frequencyFilter');
    const statusFilter = document.getElementById('statusFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
    function filterSchedules() {
        const searchTerm = scheduleSearch.value.toLowerCase();
        const frequency = frequencyFilter.value;
        const status = statusFilter.value;
        
        const rows = document.querySelectorAll('#schedulesTableBody tr');
        
        rows.forEach(row => {
            const title = row.querySelector('td:first-child').textContent.toLowerCase();
            const rowFrequency = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const rowStatus = row.querySelector('.badge').textContent.toLowerCase();
            
            const matchesSearch = title.includes(searchTerm);
            const matchesFrequency = !frequency || rowFrequency.includes(frequency.toLowerCase());
            const matchesStatus = !status || rowStatus === status.toLowerCase();
            
            row.style.display = matchesSearch && matchesFrequency && matchesStatus ? '' : 'none';
        });
    }
    
    scheduleSearch.addEventListener('input', filterSchedules);
    frequencyFilter.addEventListener('change', filterSchedules);
    statusFilter.addEventListener('change', filterSchedules);
    
    clearFiltersBtn.addEventListener('click', function() {
        scheduleSearch.value = '';
        frequencyFilter.value = '';
        statusFilter.value = '';
        filterSchedules();
    });
    
    // Handle schedule completion
    document.querySelectorAll('.complete-schedule').forEach(button => {
        button.addEventListener('click', function() {
            const scheduleId = this.dataset.scheduleId;
            
            if (confirm('Are you sure you want to mark this maintenance schedule as completed?')) {
                fetch(`<?= url('maintenance') ?>/${scheduleId}/complete`, {
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
                        alert('Failed to complete maintenance schedule');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while completing the maintenance schedule');
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

.list-group-item {
    transition: all 0.3s ease;
}

.list-group-item:hover {
    transform: translateX(5px);
}

.badge {
    transition: all 0.3s ease;
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