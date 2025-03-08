<?php $this->layout('layouts/app', ['title' => htmlspecialchars($vendor['company_name'])]) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0"><?= htmlspecialchars($vendor['company_name']) ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('vendors') ?>">Vendor Directory</a></li>
                    <li class="breadcrumb-item active">Vendor Details</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (hasPermission('edit_vendor')): ?>
                <a href="<?= url("vendors/{$vendor['id']}/edit") ?>" class="btn btn-primary">
                    <i class="bx bx-edit"></i> Edit Vendor
                </a>
            <?php endif; ?>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i> Actions
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/create") ?>">
                            <i class="bx bx-file-plus me-2"></i> New Contract
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/invoices/create") ?>">
                            <i class="bx bx-receipt me-2"></i> New Invoice
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/evaluations/create") ?>">
                            <i class="bx bx-star me-2"></i> New Evaluation
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/portal/users") ?>">
                            <i class="bx bx-user-circle me-2"></i> Portal Users
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/documents") ?>">
                            <i class="bx bx-folder me-2"></i> Documents
                        </a>
                    </li>
                    <?php if (hasPermission('delete_vendor')): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger delete-vendor" href="#" data-id="<?= $vendor['id'] ?>">
                                <i class="bx bx-trash me-2"></i> Delete Vendor
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Overview Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <?php if ($vendor['logo_path']): ?>
                            <img src="<?= asset($vendor['logo_path']) ?>" alt="Logo" class="rounded me-3" style="width: 64px; height: 64px; object-fit: contain;">
                        <?php else: ?>
                            <div class="rounded me-3 d-flex align-items-center justify-content-center bg-light" style="width: 64px; height: 64px;">
                                <span class="fs-4 text-secondary"><?= strtoupper(substr($vendor['company_name'], 0, 2)) ?></span>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h5 class="mb-1"><?= htmlspecialchars($vendor['company_name']) ?></h5>
                            <p class="mb-0 text-muted">
                                <span class="badge bg-<?= getStatusClass($vendor['status']) ?> me-2">
                                    <?= ucfirst($vendor['status']) ?>
                                </span>
                                <?= htmlspecialchars($vendor['business_type']) ?>
                            </p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Company Information</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Registration No.</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($vendor['registration_number'] ?? 'N/A') ?></dd>

                                <dt class="col-sm-4">Tax ID</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($vendor['tax_id'] ?? 'N/A') ?></dd>

                                <dt class="col-sm-4">Website</dt>
                                <dd class="col-sm-8">
                                    <?php if ($vendor['website']): ?>
                                        <a href="<?= htmlspecialchars($vendor['website']) ?>" target="_blank">
                                            <?= htmlspecialchars($vendor['website']) ?>
                                        </a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </dd>

                                <dt class="col-sm-4">Categories</dt>
                                <dd class="col-sm-8">
                                    <?php foreach ($vendor['categories'] as $category): ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($category['name']) ?></span>
                                    <?php endforeach; ?>
                                </dd>
                            </dl>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Contact Information</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Primary Contact</dt>
                                <dd class="col-sm-8">
                                    <?= htmlspecialchars($vendor['primary_contact_name']) ?><br>
                                    <small class="text-muted">
                                        <i class="bx bx-envelope"></i> <?= htmlspecialchars($vendor['primary_contact_email']) ?><br>
                                        <i class="bx bx-phone"></i> <?= htmlspecialchars($vendor['primary_contact_phone']) ?>
                                    </small>
                                </dd>

                                <?php if ($vendor['secondary_contact_name']): ?>
                                    <dt class="col-sm-4">Secondary Contact</dt>
                                    <dd class="col-sm-8">
                                        <?= htmlspecialchars($vendor['secondary_contact_name']) ?><br>
                                        <small class="text-muted">
                                            <i class="bx bx-envelope"></i> <?= htmlspecialchars($vendor['secondary_contact_email']) ?><br>
                                            <i class="bx bx-phone"></i> <?= htmlspecialchars($vendor['secondary_contact_phone']) ?>
                                        </small>
                                    </dd>
                                <?php endif; ?>
                            </dl>
                        </div>

                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Address Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Billing Address</h6>
                                            <p class="card-text"><?= nl2br(htmlspecialchars($vendor['billing_address'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Shipping Address</h6>
                                            <p class="card-text"><?= nl2br(htmlspecialchars($vendor['shipping_address'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Services</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($vendor['services'])): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Rate Type</th>
                                        <th>Rate</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vendor['services'] as $service): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($service['service_name']) ?></td>
                                            <td><span class="badge bg-secondary"><?= ucfirst($service['rate_type']) ?></span></td>
                                            <td><?= number_format($service['rate_amount'], 2) ?> <?= htmlspecialchars($service['currency']) ?></td>
                                            <td><?= htmlspecialchars($service['description'] ?? '') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No services listed</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Active Contracts -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Active Contracts</h5>
                    <a href="<?= url("vendors/{$vendor['id']}/contracts") ?>" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($activeContracts)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Contract #</th>
                                        <th>Title</th>
                                        <th>Value</th>
                                        <th>Period</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($activeContracts as $contract): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>">
                                                    <?= htmlspecialchars($contract['contract_number']) ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($contract['title']) ?></td>
                                            <td><?= number_format($contract['contract_value'], 2) ?></td>
                                            <td>
                                                <?= date('M d, Y', strtotime($contract['start_date'])) ?> -
                                                <?= date('M d, Y', strtotime($contract['end_date'])) ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No active contracts</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Performance -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Performance History</h5>
                    <a href="<?= url("vendors/{$vendor['id']}/performance") ?>" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($performanceHistory)): ?>
                        <div class="mb-4">
                            <canvas id="performanceChart"></canvas>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Period</th>
                                        <th>Score</th>
                                        <th>Status</th>
                                        <th>Reviewed By</th>
                                        <th>Review Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($performanceHistory as $evaluation): ?>
                                        <tr>
                                            <td>
                                                <?= date('M Y', strtotime($evaluation['evaluation_period_start'])) ?> -
                                                <?= date('M Y', strtotime($evaluation['evaluation_period_end'])) ?>
                                            </td>
                                            <td>
                                                <div class="text-warning">
                                                    <?php
                                                    $score = round($evaluation['overall_score']);
                                                    for ($i = 1; $i <= 5; $i++):
                                                    ?>
                                                        <i class="bx bx<?= $i <= $score ? 's' : 'r' ?>-star"></i>
                                                    <?php endfor; ?>
                                                    <small class="text-muted">(<?= number_format($evaluation['overall_score'], 1) ?>)</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= getEvaluationStatusClass($evaluation['status']) ?>">
                                                    <?= ucfirst($evaluation['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($evaluation['reviewer_name']) ?></td>
                                            <td><?= date('M d, Y', strtotime($evaluation['review_date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No performance history available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="row g-4 mb-4">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg bg-primary-subtle rounded">
                                    <i class="bx bx-file fs-3 text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Active Contracts</h6>
                                    <h4 class="mb-0"><?= number_format($stats['active_contracts']) ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg bg-warning-subtle rounded">
                                    <i class="bx bx-receipt fs-3 text-warning"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Pending Invoices</h6>
                                    <h4 class="mb-0"><?= number_format($stats['pending_invoices']) ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Payment Terms</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($vendor['payment_terms'] ?? 'N/A') ?></dd>

                        <dt class="col-sm-5">Payment Method</dt>
                        <dd class="col-sm-7"><?= ucfirst(str_replace('_', ' ', $vendor['payment_method'] ?? 'N/A')) ?></dd>

                        <dt class="col-sm-5">Bank Name</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($vendor['bank_name'] ?? 'N/A') ?></dd>

                        <dt class="col-sm-5">Account Number</dt>
                        <dd class="col-sm-7">
                            <?php if ($vendor['bank_account_number']): ?>
                                ••••<?= substr($vendor['bank_account_number'], -4) ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Recent Documents -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Documents</h5>
                    <a href="<?= url("vendors/{$vendor['id']}/documents") ?>" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentDocuments)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentDocuments as $document): ?>
                                <a href="<?= asset($document['file_path']) ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                                    <i class="bx bx-file me-2 fs-4"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0"><?= htmlspecialchars($document['document_name']) ?></h6>
                                        <small class="text-muted">
                                            <?= ucfirst($document['document_type']) ?> •
                                            <?= date('M d, Y', strtotime($document['uploaded_at'])) ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-<?= getDocumentStatusClass($document['status']) ?>">
                                        <?= ucfirst($document['status']) ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No documents available</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Incidents -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Incidents</h5>
                    <a href="<?= url("vendors/{$vendor['id']}/incidents") ?>" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentIncidents)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentIncidents as $incident): ?>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-<?= getIncidentSeverityClass($incident['severity']) ?> me-2">
                                            <?= ucfirst($incident['severity']) ?>
                                        </span>
                                        <small class="text-muted">
                                            <?= date('M d, Y', strtotime($incident['reported_date'])) ?>
                                        </small>
                                        <span class="badge bg-<?= getIncidentStatusClass($incident['status']) ?> ms-auto">
                                            <?= ucfirst($incident['status']) ?>
                                        </span>
                                    </div>
                                    <p class="mb-0"><?= htmlspecialchars($incident['description']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No incidents reported</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notes -->
            <?php if ($vendor['notes']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?= nl2br(htmlspecialchars($vendor['notes'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle vendor deletion
    const deleteBtn = document.querySelector('.delete-vendor');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this vendor? This action cannot be undone.')) {
                const vendorId = this.dataset.id;
                fetch(`<?= url('vendors') ?>/${vendorId}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          window.location.href = '<?= url('vendors') ?>';
                      } else {
                          alert(data.message || 'Failed to delete vendor');
                      }
                  });
            }
        });
    }

    // Initialize performance chart if data exists
    const performanceChart = document.getElementById('performanceChart');
    if (performanceChart && window.Chart) {
        new Chart(performanceChart, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_map(function($e) {
                    return date('M Y', strtotime($e['evaluation_period_end']));
                }, $performanceHistory)) ?>,
                datasets: [{
                    label: 'Performance Score',
                    data: <?= json_encode(array_map(function($e) {
                        return $e['overall_score'];
                    }, $performanceHistory)) ?>,
                    borderColor: '#0d6efd',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5
                    }
                }
            }
        });
    }
});

function getStatusClass(status) {
    return {
        'active': 'success',
        'inactive': 'secondary',
        'pending': 'warning',
        'blacklisted': 'danger'
    }[status] || 'secondary';
}

function getEvaluationStatusClass(status) {
    return {
        'draft': 'secondary',
        'submitted': 'info',
        'approved': 'success',
        'rejected': 'danger'
    }[status] || 'secondary';
}

function getDocumentStatusClass(status) {
    return {
        'valid': 'success',
        'expired': 'danger',
        'pending': 'warning'
    }[status] || 'secondary';
}

function getIncidentSeverityClass(severity) {
    return {
        'low': 'info',
        'medium': 'warning',
        'high': 'danger',
        'critical': 'dark'
    }[severity] || 'secondary';
}

function getIncidentStatusClass(status) {
    return {
        'open': 'danger',
        'in_progress': 'warning',
        'resolved': 'success',
        'closed': 'secondary'
    }[status] || 'secondary';
}
</script>

<style>
.avatar {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-lg {
    width: 56px;
    height: 56px;
    font-size: 1.5rem;
}

.card {
    margin-bottom: 1.5rem;
}

.list-group-item {
    padding: 1rem;
}

.badge {
    padding: 0.5em 0.75em;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

@media (max-width: 992px) {
    .col-lg-8,
    .col-lg-4 {
        width: 100%;
    }
}
</style> 