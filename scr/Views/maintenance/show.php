<?php $this->layout('layouts/app', ['title' => htmlspecialchars($schedule['title'])]) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('maintenance') ?>">Maintenance</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($schedule['title']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group">
                <?php if ($this->user->hasPermission('edit_maintenance')): ?>
                    <a href="<?= url("maintenance/{$schedule['id']}/edit") ?>" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit Schedule
                    </a>
                <?php endif; ?>
                <?php if ($this->user->hasPermission('complete_maintenance') && $schedule['status'] !== 'completed'): ?>
                    <button type="button" class="btn btn-success complete-schedule" data-schedule-id="<?= $schedule['id'] ?>">
                        <i class="bx bx-check"></i> Mark as Completed
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Schedule Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Schedule Details</h5>
                    <span class="badge bg-<?= $schedule['status'] === 'active' ? 'success' : 
                        ($schedule['status'] === 'completed' ? 'info' : 
                        ($schedule['status'] === 'overdue' ? 'danger' : 'secondary')) ?>">
                        <?= ucfirst($schedule['status']) ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Asset:</div>
                        <div class="col-md-9">
                            <span class="d-flex align-items-center">
                                <i class="bx bx-cube me-1"></i>
                                <?= htmlspecialchars($schedule['asset_name']) ?>
                                <small class="text-muted ms-1">(<?= $schedule['asset_tag'] ?>)</small>
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Description:</div>
                        <div class="col-md-9">
                            <?= nl2br(htmlspecialchars($schedule['description'] ?? 'No description provided')) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Frequency:</div>
                        <div class="col-md-9">
                            <?= ucfirst(str_replace('_', ' ', $schedule['frequency'])) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Next Due Date:</div>
                        <div class="col-md-9">
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
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Assigned To:</div>
                        <div class="col-md-9">
                            <?php if ($schedule['assigned_to']): ?>
                                <span class="d-flex align-items-center">
                                    <i class="bx bx-user me-1"></i>
                                    <?= htmlspecialchars($schedule['assigned_first_name'] . ' ' . $schedule['assigned_last_name']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">Unassigned</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Created By:</div>
                        <div class="col-md-9">
                            <span class="d-flex align-items-center">
                                <i class="bx bx-user me-1"></i>
                                <?= htmlspecialchars($schedule['created_first_name'] . ' ' . $schedule['created_last_name']) ?>
                                <small class="text-muted ms-2">
                                    on <?= date('M j, Y', strtotime($schedule['created_at'])) ?>
                                </small>
                            </span>
                        </div>
                    </div>

                    <?php if ($schedule['last_completed_date']): ?>
                        <div class="row mb-3">
                            <div class="col-md-3 text-muted">Last Completed:</div>
                            <div class="col-md-9">
                                <?= date('M j, Y', strtotime($schedule['last_completed_date'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Maintenance History -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Maintenance History</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($history)): ?>
                        <div class="timeline">
                            <?php foreach ($history as $record): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0">
                                            <?= date('M j, Y', strtotime($record['performed_date'])) ?>
                                        </h6>
                                        <p class="mb-2">
                                            <?= htmlspecialchars($record['description']) ?>
                                        </p>
                                        <small class="text-muted">
                                            Performed by: <?= htmlspecialchars($record['performed_by_name']) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No maintenance history available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- Next Occurrence -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Next Occurrence</h6>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bx bx-calendar text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0"><?= $schedule['next_due_date'] ?></h5>
                            <small class="text-muted">
                                <?= ucfirst(str_replace('_', ' ', $schedule['frequency'])) ?> maintenance
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Asset Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Asset Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bx bx-cube text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0"><?= htmlspecialchars($schedule['asset_name']) ?></h6>
                            <small class="text-muted"><?= $schedule['asset_tag'] ?></small>
                        </div>
                    </div>
                    <a href="<?= url("assets/{$schedule['asset_id']}") ?>" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bx bx-link"></i> View Asset Details
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php if ($this->user->hasPermission('create_work_order')): ?>
                        <a href="<?= url('work-orders/create', ['asset_id' => $schedule['asset_id']]) ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-plus-circle me-2"></i> Create Work Order
                        </a>
                    <?php endif; ?>
                    <?php if ($this->user->hasPermission('view_asset_history')): ?>
                        <a href="<?= url("assets/{$schedule['asset_id']}/history") ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-history me-2"></i> View Asset History
                        </a>
                    <?php endif; ?>
                    <?php if ($this->user->hasPermission('view_documentation')): ?>
                        <a href="<?= url("assets/{$schedule['asset_id']}/documents") ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-file me-2"></i> View Documentation
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle schedule completion
    const completeButton = document.querySelector('.complete-schedule');
    if (completeButton) {
        completeButton.addEventListener('click', function() {
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
    }
});
</script>

<style>
.timeline {
    position: relative;
    padding: 1rem 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-left: 2.5rem;
    padding-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: 0;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #0d6efd;
}

.timeline-content {
    padding: 0.5rem 0;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .btn-group .btn {
        width: 100%;
        margin-bottom: 0.5rem;
        border-radius: 0.25rem !important;
    }
}
</style> 