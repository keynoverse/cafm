<?php $this->layout('layouts/app', ['title' => 'Calibration Details']) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('calibration') ?>">Calibration</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($calibration['asset_name']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group">
                <?php if ($this->user->hasPermission('edit_calibration')): ?>
                    <a href="<?= url("calibration/{$calibration['id']}/edit") ?>" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit Record
                    </a>
                <?php endif; ?>
                <?php if ($this->user->hasPermission('perform_calibration') && $calibration['status'] === 'pending'): ?>
                    <button type="button" class="btn btn-success start-calibration" data-id="<?= $calibration['id'] ?>">
                        <i class="bx bx-play"></i> Start Calibration
                    </button>
                <?php endif; ?>
                <?php if ($this->user->hasPermission('export_calibration')): ?>
                    <a href="<?= url("calibration/{$calibration['id']}/export") ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-export"></i> Export Record
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Calibration Details</h5>
                    <span class="badge bg-<?= $calibration['status'] === 'pending' ? 'secondary' : 
                        ($calibration['status'] === 'in_progress' ? 'info' : 
                        ($calibration['status'] === 'completed' ? 'success' : 'danger')) ?>">
                        <?= ucfirst(str_replace('_', ' ', $calibration['status'])) ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Equipment:</div>
                        <div class="col-md-9">
                            <span class="d-flex align-items-center">
                                <i class="bx bx-cube me-1"></i>
                                <?= htmlspecialchars($calibration['asset_name']) ?>
                                <small class="text-muted ms-1">(<?= $calibration['asset_tag'] ?>)</small>
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Type:</div>
                        <div class="col-md-9">
                            <?= htmlspecialchars($calibration['calibration_type']) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Standard:</div>
                        <div class="col-md-9">
                            <?= htmlspecialchars($calibration['calibration_standard']) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Next Due Date:</div>
                        <div class="col-md-9">
                            <?php
                            $dueDate = new DateTime($calibration['next_calibration_date']);
                            $today = new DateTime();
                            $interval = $today->diff($dueDate);
                            $isPast = $dueDate < $today;
                            ?>
                            <span class="<?= $isPast ? 'text-danger' : 'text-success' ?>">
                                <?= date('M j, Y', strtotime($calibration['next_calibration_date'])) ?>
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
                            <?php if ($calibration['performed_by']): ?>
                                <span class="d-flex align-items-center">
                                    <i class="bx bx-user me-1"></i>
                                    <?= htmlspecialchars($calibration['performed_by_name']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">Unassigned</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($calibration['last_calibration_date']): ?>
                        <div class="row mb-3">
                            <div class="col-md-3 text-muted">Last Calibration:</div>
                            <div class="col-md-9">
                                <?= date('M j, Y', strtotime($calibration['last_calibration_date'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($calibration['calibration_result']): ?>
                        <div class="row mb-3">
                            <div class="col-md-3 text-muted">Results:</div>
                            <div class="col-md-9">
                                <?= nl2br(htmlspecialchars($calibration['calibration_result'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($calibration['notes']): ?>
                        <div class="row mb-3">
                            <div class="col-md-3 text-muted">Notes:</div>
                            <div class="col-md-9">
                                <?= nl2br(htmlspecialchars($calibration['notes'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Calibration History -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Calibration History</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($history)): ?>
                        <div class="timeline">
                            <?php foreach ($history as $record): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0">
                                            <?= date('M j, Y', strtotime($record['created_at'])) ?>
                                        </h6>
                                        <p class="mb-2">
                                            <?= htmlspecialchars($record['action']) ?>
                                            <?php if ($record['details']): ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($record['details']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </p>
                                        <small class="text-muted">
                                            By: <?= htmlspecialchars($record['user_name']) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No calibration history available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- Equipment Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Equipment Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bx bx-cube text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0"><?= htmlspecialchars($calibration['asset_name']) ?></h6>
                            <small class="text-muted"><?= $calibration['asset_tag'] ?></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Category:</small>
                        <?= htmlspecialchars($calibration['asset_category']) ?>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Location:</small>
                        <?= htmlspecialchars($calibration['asset_location']) ?>
                    </div>
                    <a href="<?= url("assets/{$calibration['asset_id']}") ?>" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bx bx-link"></i> View Equipment Details
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
                        <a href="<?= url('work-orders/create', ['asset_id' => $calibration['asset_id']]) ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-plus-circle me-2"></i> Create Work Order
                        </a>
                    <?php endif; ?>
                    <?php if ($this->user->hasPermission('view_asset_history')): ?>
                        <a href="<?= url("assets/{$calibration['asset_id']}/history") ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-history me-2"></i> View Equipment History
                        </a>
                    <?php endif; ?>
                    <?php if ($this->user->hasPermission('view_documentation')): ?>
                        <a href="<?= url("assets/{$calibration['asset_id']}/documents") ?>" 
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
    // Handle calibration start
    const startButton = document.querySelector('.start-calibration');
    if (startButton) {
        startButton.addEventListener('click', function() {
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