<?php $this->layout('layouts/app', ['title' => $request['title']]) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('maintenance-requests') ?>">Maintenance Requests</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($request['title']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group">
                <?php if ($this->user->hasPermission('edit_maintenance_request')): ?>
                    <a href="<?= url("maintenance-requests/{$request['id']}/edit") ?>" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit Request
                    </a>
                <?php endif; ?>

                <?php if ($request['status'] === 'pending' && $this->user->hasPermission('approve_maintenance_request')): ?>
                    <button type="button" class="btn btn-success approve-request" data-id="<?= $request['id'] ?>">
                        <i class="bx bx-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger reject-request" data-id="<?= $request['id'] ?>">
                        <i class="bx bx-x"></i> Reject
                    </button>
                <?php endif; ?>

                <?php if ($request['status'] === 'approved' && $this->user->hasPermission('convert_maintenance_request')): ?>
                    <button type="button" class="btn btn-info convert-to-work-order" data-id="<?= $request['id'] ?>">
                        <i class="bx bx-git-branch"></i> Convert to Work Order
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Request Details</h5>
                    <div>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Equipment:</div>
                        <div class="col-md-9">
                            <span class="d-flex align-items-center">
                                <i class="bx bx-cube me-2"></i>
                                <?= htmlspecialchars($request['asset_name']) ?>
                                <small class="text-muted ms-2">(<?= $request['asset_tag'] ?>)</small>
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Description:</div>
                        <div class="col-md-9">
                            <?= nl2br(htmlspecialchars($request['description'])) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Priority:</div>
                        <div class="col-md-9">
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
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Category:</div>
                        <div class="col-md-9">
                            <?= htmlspecialchars($request['category_name']) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Requested By:</div>
                        <div class="col-md-9">
                            <span class="d-flex align-items-center">
                                <i class="bx bx-user me-2"></i>
                                <?= htmlspecialchars($request['requested_by_name']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Requested Date:</div>
                        <div class="col-md-9">
                            <?= date('M j, Y g:i A', strtotime($request['created_at'])) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Requested Completion:</div>
                        <div class="col-md-9">
                            <?= date('M j, Y', strtotime($request['requested_completion_date'])) ?>
                            <?php
                            $completionDate = new DateTime($request['requested_completion_date']);
                            $today = new DateTime();
                            $interval = $today->diff($completionDate);
                            $isPast = $completionDate < $today;
                            ?>
                            <small class="d-block <?= $isPast ? 'text-danger' : 'text-success' ?>">
                                <?= $isPast ? "{$interval->days} days overdue" : "in {$interval->days} days" ?>
                            </small>
                        </div>
                    </div>

                    <?php if ($request['status'] === 'rejected'): ?>
                        <div class="row mb-3">
                            <div class="col-md-3 text-muted">Rejection Reason:</div>
                            <div class="col-md-9">
                                <div class="alert alert-danger mb-0">
                                    <?= nl2br(htmlspecialchars($request['rejection_reason'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($request['notes']): ?>
                        <div class="row mb-3">
                            <div class="col-md-3 text-muted">Additional Notes:</div>
                            <div class="col-md-9">
                                <?= nl2br(htmlspecialchars($request['notes'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($request['attachments'])): ?>
                        <div class="row mb-3">
                            <div class="col-md-3 text-muted">Attachments:</div>
                            <div class="col-md-9">
                                <div class="list-group">
                                    <?php foreach ($request['attachments'] as $attachment): ?>
                                        <a href="<?= url("maintenance-requests/attachments/{$attachment['id']}") ?>" 
                                           class="list-group-item list-group-item-action d-flex align-items-center"
                                           target="_blank">
                                            <i class="bx bx-file me-2"></i>
                                            <?= htmlspecialchars($attachment['filename']) ?>
                                            <small class="text-muted ms-auto">
                                                <?= formatFileSize($attachment['size']) ?>
                                            </small>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Request History -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Request History</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($history as $record): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">
                                        <?= date('M j, Y g:i A', strtotime($record['created_at'])) ?>
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
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- SLA Information -->
            <?php if ($request['sla']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">SLA Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Response Time:</small>
                            <?= $request['sla']['response_time'] ?> hours
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Resolution Time:</small>
                            <?= $request['sla']['resolution_time'] ?> hours
                        </div>
                        <?php if ($request['sla_status']): ?>
                            <div class="alert alert-<?= $request['sla_status'] === 'breached' ? 'danger' : 'success' ?> mb-0">
                                <i class="bx bx-<?= $request['sla_status'] === 'breached' ? 'error' : 'check-circle' ?>"></i>
                                SLA <?= $request['sla_status'] === 'breached' ? 'Breached' : 'Within Limits' ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Related Work Order -->
            <?php if ($request['work_order']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Related Work Order</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">
                                    <a href="<?= url("work-orders/{$request['work_order']['id']}") ?>">
                                        #<?= $request['work_order']['id'] ?>
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <?= ucfirst($request['work_order']['status']) ?>
                                </small>
                            </div>
                            <span class="badge bg-<?= $request['work_order']['status'] === 'completed' ? 'success' : 'primary' ?>">
                                <?= ucfirst($request['work_order']['status']) ?>
                            </span>
                        </div>
                        <a href="<?= url("work-orders/{$request['work_order']['id']}") ?>" class="btn btn-outline-primary btn-sm w-100">
                            View Work Order
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php if ($this->user->hasPermission('view_asset_history')): ?>
                        <a href="<?= url("assets/{$request['asset_id']}/history") ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-history me-2"></i> View Equipment History
                        </a>
                    <?php endif; ?>
                    <?php if ($this->user->hasPermission('create_work_order')): ?>
                        <a href="<?= url('work-orders/create', ['asset_id' => $request['asset_id']]) ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-plus-circle me-2"></i> Create Work Order
                        </a>
                    <?php endif; ?>
                    <?php if ($this->user->hasPermission('view_documentation')): ?>
                        <a href="<?= url("assets/{$request['asset_id']}/documents") ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-file me-2"></i> View Documentation
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Request Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Maintenance Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label required">Rejection Reason</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="3" required></textarea>
                        <div class="form-text">Please provide a reason for rejecting this request</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmReject">Reject Request</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle request approval
    const approveButton = document.querySelector('.approve-request');
    if (approveButton) {
        approveButton.addEventListener('click', function() {
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
    }

    // Handle request rejection
    const rejectButton = document.querySelector('.reject-request');
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
    const confirmRejectButton = document.getElementById('confirmReject');
    const rejectForm = document.getElementById('rejectForm');

    if (rejectButton) {
        rejectButton.addEventListener('click', function() {
            rejectModal.show();
        });

        confirmRejectButton.addEventListener('click', function() {
            const id = rejectButton.dataset.id;
            const reason = document.getElementById('rejection_reason').value.trim();

            if (!reason) {
                alert('Please provide a reason for rejection');
                return;
            }

            fetch(`<?= url('maintenance-requests') ?>/${id}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?= csrf_token() ?>'
                },
                body: JSON.stringify({ rejection_reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Failed to reject request');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while rejecting the request');
            });
        });
    }

    // Handle conversion to work order
    const convertButton = document.querySelector('.convert-to-work-order');
    if (convertButton) {
        convertButton.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to convert this request to a work order?')) {
                fetch(`<?= url('maintenance-requests') ?>/${id}/convert`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': '<?= csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert('Failed to convert request to work order');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while converting the request');
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

.list-group-item {
    transition: background-color 0.3s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.required:after {
    content: " *";
    color: red;
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