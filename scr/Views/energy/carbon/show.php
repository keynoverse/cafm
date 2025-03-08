<?php $this->layout('layouts/app', ['title' => 'Carbon Emission Details']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Carbon Emission Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('energy/carbon') ?>">Carbon Footprint</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if ($this->user->hasPermission('edit_carbon_footprint')): ?>
                <a href="<?= url("energy/carbon/{$emission['id']}/edit") ?>" class="btn btn-primary">
                    <i class="bx bx-edit"></i> Edit Record
                </a>
            <?php endif; ?>
            <?php if ($this->user->hasPermission('delete_carbon_footprint')): ?>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bx bx-trash"></i> Delete Record
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Emission Details Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Emission Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Source Type</label>
                            <p class="mb-0">
                                <span class="badge bg-<?= getSourceClass($emission['source_type']) ?>">
                                    <?= ucfirst($emission['source_type']) ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Related Asset</label>
                            <p class="mb-0">
                                <?php if ($emission['asset_id']): ?>
                                    <a href="<?= url("assets/{$emission['asset_id']}") ?>">
                                        <?= htmlspecialchars($emission['asset_name']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Not specified</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Amount</label>
                            <p class="mb-0">
                                <?= number_format($emission['emission_amount'], 2) ?>
                                <?= htmlspecialchars($emission['emission_unit']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">CO2 Equivalent</label>
                            <p class="mb-0">
                                <?= number_format($emission['co2_equivalent'], 2) ?> tCO2e
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Recording Period</label>
                            <p class="mb-0">
                                <?= date('M j, Y', strtotime($emission['recording_period_start'])) ?> -
                                <?= date('M j, Y', strtotime($emission['recording_period_end'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Calculation Method</label>
                            <p class="mb-0"><?= ucfirst(str_replace('_', ' ', $emission['calculation_method'])) ?></p>
                        </div>
                        <?php if ($emission['calculation_details']): ?>
                            <div class="col-12">
                                <label class="fw-bold">Calculation Details</label>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($emission['calculation_details'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            <?php if ($emission['notes']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notes</h5>
                    </div>
                    <div class="card-body">
                        <?= nl2br(htmlspecialchars($emission['notes'])) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Attachments Card -->
            <?php if (!empty($attachments)): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Attachments</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($attachments as $attachment): ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-2 border rounded">
                                        <i class="bx bx-file me-2 fs-4"></i>
                                        <div class="flex-grow-1">
                                            <div class="text-truncate">
                                                <?= htmlspecialchars($attachment['filename']) ?>
                                            </div>
                                            <small class="text-muted">
                                                <?= formatFileSize($attachment['size']) ?>
                                            </small>
                                        </div>
                                        <a href="<?= url("energy/carbon/attachments/{$attachment['id']}") ?>" 
                                           class="btn btn-sm btn-outline-primary" download>
                                            <i class="bx bx-download"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Record Info Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Record Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted d-block">Recorded By</small>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-user me-2"></i>
                                <?= htmlspecialchars($emission['recorded_by']) ?>
                            </div>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">Created At</small>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-calendar me-2"></i>
                                <?= date('M j, Y g:i A', strtotime($emission['created_at'])) ?>
                            </div>
                        </li>
                        <?php if ($emission['updated_at'] !== $emission['created_at']): ?>
                            <li class="mb-3">
                                <small class="text-muted d-block">Last Updated</small>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-time me-2"></i>
                                    <?= date('M j, Y g:i A', strtotime($emission['updated_at'])) ?>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Impact Analysis Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Impact Analysis</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted d-block mb-1">Contribution to Total</label>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: <?= $impact['contribution_percentage'] ?>%;"
                                 aria-valuenow="<?= $impact['contribution_percentage'] ?>" 
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <small class="d-block mt-1">
                            <?= number_format($impact['contribution_percentage'], 1) ?>% of monthly emissions
                        </small>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block mb-2">Equivalent To</label>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="bx bx-car me-2"></i>
                                <?= number_format($impact['car_equivalent']) ?> km driven by car
                            </li>
                            <li class="mb-2">
                                <i class="bx bx-tree me-2"></i>
                                <?= number_format($impact['tree_equivalent']) ?> trees needed for a year
                            </li>
                            <li>
                                <i class="bx bx-home me-2"></i>
                                <?= number_format($impact['home_equivalent'], 1) ?> homes' energy for one day
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<?php if ($this->user->hasPermission('delete_carbon_footprint')): ?>
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Emission Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this emission record? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="<?= url("energy/carbon/{$emission['id']}") ?>" method="POST" class="d-inline">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger">Delete Record</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
function getSourceClass(source) {
    return {
        'electricity': 'primary',
        'gas': 'danger',
        'water': 'info',
        'transportation': 'warning',
        'waste': 'secondary',
        'other': 'dark'
    }[source] || 'secondary';
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
}

.progress {
    background-color: #e9ecef;
    border-radius: 0.25rem;
}

.progress-bar {
    background-color: #0d6efd;
    transition: width 0.6s ease;
}

.list-unstyled i {
    font-size: 1.25rem;
}

@media (max-width: 768px) {
    .col-md-4 {
        margin-top: 1.5rem;
    }
}
</style> 