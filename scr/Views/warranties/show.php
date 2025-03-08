<?php $this->layout('layouts/app', ['title' => "{$warranty['asset_name']} Warranty"]) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('warranties') ?>">Warranty Management</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($warranty['asset_name']) ?> Warranty</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group">
                <?php if ($this->user->hasPermission('edit_warranty')): ?>
                    <a href="<?= url("warranties/{$warranty['id']}/edit") ?>" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit Warranty
                    </a>
                <?php endif; ?>
                <?php if ($daysLeft <= 30 && $this->user->hasPermission('renew_warranty')): ?>
                    <a href="<?= url("warranties/{$warranty['id']}/renew") ?>" class="btn btn-success">
                        <i class="bx bx-refresh"></i> Renew Warranty
                    </a>
                <?php endif; ?>
                <?php if ($this->user->hasPermission('create_warranty_claim')): ?>
                    <a href="<?= url("warranties/{$warranty['id']}/claims/create") ?>" class="btn btn-info">
                        <i class="bx bx-file-plus"></i> Create Claim
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
                    <h5 class="card-title mb-0">Warranty Details</h5>
                    <?php
                    $statusClass = $daysLeft > 0 ? ($daysLeft <= 30 ? 'warning' : 'success') : 'danger';
                    $statusText = $daysLeft > 0 ? ($daysLeft <= 30 ? 'Expiring Soon' : 'Active') : 'Expired';
                    ?>
                    <span class="badge bg-<?= $statusClass ?>">
                        <?= $statusText ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Equipment:</div>
                        <div class="col-md-9">
                            <a href="<?= url("assets/{$warranty['asset_id']}") ?>">
                                <?= htmlspecialchars($warranty['asset_name']) ?>
                            </a>
                            <small class="d-block text-muted"><?= $warranty['asset_tag'] ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Supplier:</div>
                        <div class="col-md-9">
                            <a href="<?= url("suppliers/{$warranty['supplier_id']}") ?>">
                                <?= htmlspecialchars($warranty['supplier_name']) ?>
                            </a>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Type:</div>
                        <div class="col-md-9">
                            <?= htmlspecialchars($warranty['warranty_type']) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Contract Number:</div>
                        <div class="col-md-9">
                            <?= htmlspecialchars($warranty['contract_number']) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Start Date:</div>
                        <div class="col-md-9">
                            <?= date('M j, Y', strtotime($warranty['start_date'])) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">End Date:</div>
                        <div class="col-md-9">
                            <?= date('M j, Y', strtotime($warranty['end_date'])) ?>
                            <?php if ($daysLeft > 0): ?>
                                <small class="d-block text-<?= $daysLeft <= 30 ? 'warning' : 'success' ?>">
                                    <?= $daysLeft ?> days remaining
                                </small>
                            <?php else: ?>
                                <small class="d-block text-danger">
                                    Expired <?= abs($daysLeft) ?> days ago
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($warranty['cost']): ?>
                        <div class="row mb-3">
                            <div class="col-md-3 text-muted">Cost:</div>
                            <div class="col-md-9">
                                $<?= number_format($warranty['cost'], 2) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Coverage Details:</div>
                        <div class="col-md-9">
                            <?= nl2br(htmlspecialchars($warranty['coverage_details'])) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Terms & Conditions:</div>
                        <div class="col-md-9">
                            <?= nl2br(htmlspecialchars($warranty['terms_conditions'])) ?>
                        </div>
                    </div>

                    <?php if ($warranty['notes']): ?>
                        <div class="row mb-3">
                            <div class="col-md-3 text-muted">Additional Notes:</div>
                            <div class="col-md-9">
                                <?= nl2br(htmlspecialchars($warranty['notes'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($warranty['attachments'])): ?>
                        <div class="row mb-3">
                            <div class="col-md-3 text-muted">Attachments:</div>
                            <div class="col-md-9">
                                <div class="list-group">
                                    <?php foreach ($warranty['attachments'] as $attachment): ?>
                                        <a href="<?= url("warranties/attachments/{$attachment['id']}") ?>" 
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

            <!-- Warranty Claims -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Warranty Claims</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Claim Date</th>
                                    <th>Issue Type</th>
                                    <th>Status</th>
                                    <th>Resolution</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($claims as $claim): ?>
                                    <tr>
                                        <td>
                                            <?= date('M j, Y', strtotime($claim['claim_date'])) ?>
                                        </td>
                                        <td><?= htmlspecialchars($claim['issue_type']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= getClaimStatusClass($claim['status']) ?>">
                                                <?= ucfirst($claim['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($claim['resolution_date']): ?>
                                                <?= date('M j, Y', strtotime($claim['resolution_date'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= url("warranties/{$warranty['id']}/claims/{$claim['id']}") ?>" 
                                                   class="btn btn-sm btn-outline-secondary"
                                                   title="View Claim">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($claims)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="bx bx-info-circle fs-4 mb-2"></i>
                                            <p class="mb-0">No warranty claims found</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Total Claims:</small>
                        <h3 class="mb-0"><?= $stats['total_claims'] ?></h3>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Active Claims:</small>
                        <h3 class="mb-0"><?= $stats['active_claims'] ?></h3>
                    </div>
                    <div>
                        <small class="text-muted d-block">Successful Claims:</small>
                        <h3 class="text-success mb-0"><?= $stats['successful_claims'] ?></h3>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php if ($this->user->hasPermission('view_asset_history')): ?>
                        <a href="<?= url("assets/{$warranty['asset_id']}/history") ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-history me-2"></i> View Equipment History
                        </a>
                    <?php endif; ?>
                    <?php if ($this->user->hasPermission('view_maintenance_records')): ?>
                        <a href="<?= url("assets/{$warranty['asset_id']}/maintenance") ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-wrench me-2"></i> View Maintenance Records
                        </a>
                    <?php endif; ?>
                    <?php if ($this->user->hasPermission('view_documentation')): ?>
                        <a href="<?= url("assets/{$warranty['asset_id']}/documents") ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-file me-2"></i> View Documentation
                        </a>
                    <?php endif; ?>
                    <?php if ($this->user->hasPermission('contact_supplier')): ?>
                        <a href="<?= url("suppliers/{$warranty['supplier_id']}/contact") ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-envelope me-2"></i> Contact Supplier
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function getClaimStatusClass(status) {
    return {
        'pending': 'secondary',
        'in_progress': 'info',
        'approved': 'success',
        'rejected': 'danger',
        'resolved': 'primary'
    }[status] || 'secondary';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1.5rem;
}

.list-group-item {
    transition: background-color 0.3s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
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