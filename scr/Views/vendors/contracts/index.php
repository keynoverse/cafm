<?php $this->layout('layouts/app', ['title' => 'Vendor Contracts - ' . htmlspecialchars($vendor['company_name'])]) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Vendor Contracts</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('vendors') ?>">Vendor Directory</a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}") ?>"><?= htmlspecialchars($vendor['company_name']) ?></a></li>
                    <li class="breadcrumb-item active">Contracts</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (hasPermission('create_contract')): ?>
                <a href="<?= url("vendors/{$vendor['id']}/contracts/create") ?>" class="btn btn-primary">
                    <i class="bx bx-plus"></i> New Contract
                </a>
            <?php endif; ?>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bx bx-export"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/export/excel") ?>">
                            <i class="bx bxs-file-export me-2"></i> Export to Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/export/pdf") ?>">
                            <i class="bx bxs-file-pdf me-2"></i> Export to PDF
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-primary-subtle rounded">
                            <i class="bx bx-file fs-3 text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">Total Contracts</h6>
                            <h4 class="mb-0"><?= number_format($stats['total_contracts']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-success-subtle rounded">
                            <i class="bx bx-check-circle fs-3 text-success"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">Active</h6>
                            <h4 class="mb-0"><?= number_format($stats['active_contracts']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-warning-subtle rounded">
                            <i class="bx bx-time fs-3 text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">Expiring Soon</h6>
                            <h4 class="mb-0"><?= number_format($stats['expiring_contracts']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-danger-subtle rounded">
                            <i class="bx bx-x-circle fs-3 text-danger"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">Expired</h6>
                            <h4 class="mb-0"><?= number_format($stats['expired_contracts']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search contracts..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="expired" <?= ($filters['status'] ?? '') === 'expired' ? 'selected' : '' ?>>Expired</option>
                        <option value="terminated" <?= ($filters['status'] ?? '') === 'terminated' ? 'selected' : '' ?>>Terminated</option>
                        <option value="renewed" <?= ($filters['status'] ?? '') === 'renewed' ? 'selected' : '' ?>>Renewed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_range" class="form-label">Date Range</label>
                    <select class="form-select" id="date_range" name="date_range">
                        <option value="">All Time</option>
                        <option value="active" <?= ($filters['date_range'] ?? '') === 'active' ? 'selected' : '' ?>>Currently Active</option>
                        <option value="expiring" <?= ($filters['date_range'] ?? '') === 'expiring' ? 'selected' : '' ?>>Expiring Soon</option>
                        <option value="expired" <?= ($filters['date_range'] ?? '') === 'expired' ? 'selected' : '' ?>>Expired</option>
                        <option value="custom" <?= ($filters['date_range'] ?? '') === 'custom' ? 'selected' : '' ?>>Custom Range</option>
                    </select>
                </div>
                <div class="col-md-4 date-range-inputs" style="display: none;">
                    <div class="row">
                        <div class="col-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($filters['start_date'] ?? '') ?>">
                        </div>
                        <div class="col-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($filters['end_date'] ?? '') ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-filter-alt"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Contracts Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Contract #</th>
                            <th>Title</th>
                            <th>Value</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th>Renewal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($contracts)): ?>
                            <?php foreach ($contracts as $contract): ?>
                                <tr>
                                    <td>
                                        <a href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>" class="text-body fw-semibold">
                                            <?= htmlspecialchars($contract['contract_number']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($contract['title']) ?>
                                        <?php if ($contract['file_path']): ?>
                                            <a href="<?= asset($contract['file_path']) ?>" class="ms-2 text-muted" target="_blank">
                                                <i class="bx bx-file"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= number_format($contract['contract_value'], 2) ?></td>
                                    <td>
                                        <?= date('M d, Y', strtotime($contract['start_date'])) ?> -
                                        <?= date('M d, Y', strtotime($contract['end_date'])) ?>
                                        <?php
                                        $days_remaining = (strtotime($contract['end_date']) - time()) / (60 * 60 * 24);
                                        if ($days_remaining > 0 && $days_remaining <= 30):
                                        ?>
                                            <span class="badge bg-warning ms-1">Expires in <?= ceil($days_remaining) ?> days</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= getContractStatusClass($contract['status']) ?>">
                                            <?= ucfirst($contract['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($contract['renewal_reminder_date']): ?>
                                            <small class="text-muted">
                                                Reminder: <?= date('M d, Y', strtotime($contract['renewal_reminder_date'])) ?>
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted">No reminder set</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>">
                                                        <i class="bx bx-show me-2"></i> View Details
                                                    </a>
                                                </li>
                                                <?php if (hasPermission('edit_contract')): ?>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/edit") ?>">
                                                            <i class="bx bx-edit me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($contract['status'] === 'active'): ?>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/renew") ?>">
                                                            <i class="bx bx-refresh me-2"></i> Renew
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-warning terminate-contract" href="#" data-id="<?= $contract['id'] ?>">
                                                            <i class="bx bx-x-circle me-2"></i> Terminate
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if (hasPermission('delete_contract')): ?>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger delete-contract" href="#" data-id="<?= $contract['id'] ?>">
                                                            <i class="bx bx-trash me-2"></i> Delete
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bx bx-file fs-1 text-muted"></i>
                                    <p class="mb-0">No contracts found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($pagination['total_pages'] > 1): ?>
                <div class="d-flex justify-content-end mt-4">
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item <?= $pagination['current_page'] === 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= url("vendors/{$vendor['id']}/contracts", ['page' => $pagination['current_page'] - 1] + $filters) ?>">
                                    Previous
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= url("vendors/{$vendor['id']}/contracts", ['page' => $i] + $filters) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $pagination['current_page'] === $pagination['total_pages'] ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= url("vendors/{$vendor['id']}/contracts", ['page' => $pagination['current_page'] + 1] + $filters) ?>">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Terminate Contract Modal -->
<div class="modal fade" id="terminateContractModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terminate Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="terminateForm">
                    <input type="hidden" name="contract_id" id="terminate_contract_id">
                    <div class="mb-3">
                        <label for="termination_reason" class="form-label">Reason for Termination</label>
                        <textarea class="form-control" id="termination_reason" name="termination_reason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="termination_date" class="form-label">Termination Date</label>
                        <input type="date" class="form-control" id="termination_date" name="termination_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmTermination">Terminate</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const dateRange = document.getElementById('date_range');
    const dateRangeInputs = document.querySelector('.date-range-inputs');
    const terminateModal = new bootstrap.Modal(document.getElementById('terminateContractModal'));

    // Handle date range filter
    dateRange.addEventListener('change', function() {
        dateRangeInputs.style.display = this.value === 'custom' ? 'block' : 'none';
    });

    // Initialize date range display
    if (dateRange.value === 'custom') {
        dateRangeInputs.style.display = 'block';
    }

    // Handle contract termination
    document.querySelectorAll('.terminate-contract').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('terminate_contract_id').value = this.dataset.id;
            terminateModal.show();
        });
    });

    // Handle termination confirmation
    document.getElementById('confirmTermination').addEventListener('click', function() {
        const form = document.getElementById('terminateForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const contractId = document.getElementById('terminate_contract_id').value;
        const formData = new FormData(form);

        fetch(`<?= url("vendors/{$vendor['id']}/contracts") ?>/${contractId}/terminate`, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  window.location.reload();
              } else {
                  alert(data.message || 'Failed to terminate contract');
              }
          });
    });

    // Handle contract deletion
    document.querySelectorAll('.delete-contract').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this contract? This action cannot be undone.')) {
                const contractId = this.dataset.id;
                fetch(`<?= url("vendors/{$vendor['id']}/contracts") ?>/${contractId}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          window.location.reload();
                      } else {
                          alert(data.message || 'Failed to delete contract');
                      }
                  });
            }
        });
    });
});

function getContractStatusClass(status) {
    return {
        'draft': 'secondary',
        'active': 'success',
        'expired': 'danger',
        'terminated': 'warning',
        'renewed': 'info'
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

.badge {
    padding: 0.5em 0.75em;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

.dropdown-menu {
    padding: 0.5rem 0;
}

.dropdown-item {
    padding: 0.5rem 1rem;
}

@media (max-width: 768px) {
    .date-range-inputs .row {
        flex-direction: column;
    }
    
    .date-range-inputs .col-6 {
        width: 100%;
        margin-bottom: 1rem;
    }
}
</style> 