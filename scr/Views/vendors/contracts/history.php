<?php $this->layout('layouts/app', ['title' => 'Contract History - ' . htmlspecialchars($contract['title'])]) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Contract History</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('vendors') ?>">Vendor Directory</a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}") ?>"><?= htmlspecialchars($vendor['company_name']) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}/contracts") ?>">Contracts</a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>"><?= htmlspecialchars($contract['title']) ?></a></li>
                    <li class="breadcrumb-item active">History</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>" class="btn btn-outline-primary">
                <i class="bx bx-arrow-back"></i> Back to Contract
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bx bx-export"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/history/export/excel") ?>">
                            <i class="bx bxs-file-export me-2"></i> Export to Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/history/export/pdf") ?>">
                            <i class="bx bxs-file-pdf me-2"></i> Export to PDF
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- History Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Change History</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($history)): ?>
                        <div class="timeline-extended">
                            <?php 
                            $current_date = null;
                            foreach ($history as $entry):
                                $entry_date = date('Y-m-d', strtotime($entry['created_at']));
                                if ($entry_date !== $current_date):
                                    $current_date = $entry_date;
                            ?>
                                <div class="timeline-date">
                                    <?= date('F d, Y', strtotime($entry['created_at'])) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="timeline-item">
                                <div class="timeline-time">
                                    <?= date('H:i', strtotime($entry['created_at'])) ?>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="fw-semibold"><?= htmlspecialchars($entry['user_name']) ?></span>
                                            <span class="badge bg-<?= getChangeTypeClass($entry['change_type']) ?> ms-2">
                                                <?= ucfirst($entry['change_type']) ?>
                                            </span>
                                        </div>
                                        <?php if ($entry['version']): ?>
                                            <small class="text-muted">Version <?= $entry['version'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <p class="mb-2"><?= htmlspecialchars($entry['description']) ?></p>
                                    <?php if (!empty($entry['changes'])): ?>
                                        <div class="changes-table">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Field</th>
                                                        <th>Old Value</th>
                                                        <th>New Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($entry['changes'] as $change): ?>
                                                        <tr>
                                                            <td><?= ucwords(str_replace('_', ' ', $change['field'])) ?></td>
                                                            <td class="text-danger">
                                                                <?= formatHistoryValue($change['field'], $change['old_value']) ?>
                                                            </td>
                                                            <td class="text-success">
                                                                <?= formatHistoryValue($change['field'], $change['new_value']) ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($entry['attachments'])): ?>
                                        <div class="mt-2">
                                            <?php foreach ($entry['attachments'] as $attachment): ?>
                                                <a href="<?= asset($attachment['file_path']) ?>" class="btn btn-outline-secondary btn-sm me-2" target="_blank">
                                                    <i class="bx bx-file"></i> <?= htmlspecialchars($attachment['filename']) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No history records found</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contract Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contract Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Contract #</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($contract['contract_number']) ?></dd>

                        <dt class="col-sm-5">Title</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($contract['title']) ?></dd>

                        <dt class="col-sm-5">Status</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-<?= getContractStatusClass($contract['status']) ?>">
                                <?= ucfirst($contract['status']) ?>
                            </span>
                        </dd>

                        <dt class="col-sm-5">Created On</dt>
                        <dd class="col-sm-7"><?= date('M d, Y', strtotime($contract['created_at'])) ?></dd>

                        <dt class="col-sm-5">Last Modified</dt>
                        <dd class="col-sm-7"><?= date('M d, Y', strtotime($contract['updated_at'])) ?></dd>

                        <dt class="col-sm-5">Period</dt>
                        <dd class="col-sm-7">
                            <?= date('M d, Y', strtotime($contract['start_date'])) ?> -
                            <?= date('M d, Y', strtotime($contract['end_date'])) ?>
                        </dd>

                        <dt class="col-sm-5">Value</dt>
                        <dd class="col-sm-7"><?= $currency ?><?= number_format($contract['contract_value'], 2) ?></dd>
                    </dl>
                </div>
            </div>

            <!-- History Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">History Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h3 class="mb-1"><?= number_format($stats['total_changes']) ?></h3>
                                <small class="text-muted">Total Changes</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h3 class="mb-1"><?= number_format($stats['total_versions']) ?></h3>
                                <small class="text-muted">Versions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h3 class="mb-1"><?= number_format($stats['unique_users']) ?></h3>
                                <small class="text-muted">Contributors</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h3 class="mb-1"><?= number_format($stats['total_attachments']) ?></h3>
                                <small class="text-muted">Attachments</small>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($stats['change_types'])): ?>
                        <hr>
                        <h6 class="mb-3">Change Types</h6>
                        <?php foreach ($stats['change_types'] as $type => $count): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-<?= getChangeTypeClass($type) ?>">
                                    <?= ucfirst($type) ?>
                                </span>
                                <span class="text-muted"><?= number_format($count) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Filter -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filter History</h5>
                </div>
                <div class="card-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label for="date_range" class="form-label">Date Range</label>
                            <select class="form-select" id="date_range" name="date_range">
                                <option value="">All Time</option>
                                <option value="today" <?= ($filters['date_range'] ?? '') === 'today' ? 'selected' : '' ?>>Today</option>
                                <option value="week" <?= ($filters['date_range'] ?? '') === 'week' ? 'selected' : '' ?>>This Week</option>
                                <option value="month" <?= ($filters['date_range'] ?? '') === 'month' ? 'selected' : '' ?>>This Month</option>
                                <option value="custom" <?= ($filters['date_range'] ?? '') === 'custom' ? 'selected' : '' ?>>Custom Range</option>
                            </select>
                        </div>
                        <div class="date-range-inputs" style="display: none;">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $filters['start_date'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $filters['end_date'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="change_type" class="form-label">Change Type</label>
                            <select class="form-select" id="change_type" name="change_type">
                                <option value="">All Types</option>
                                <option value="update" <?= ($filters['change_type'] ?? '') === 'update' ? 'selected' : '' ?>>Update</option>
                                <option value="create" <?= ($filters['change_type'] ?? '') === 'create' ? 'selected' : '' ?>>Create</option>
                                <option value="delete" <?= ($filters['change_type'] ?? '') === 'delete' ? 'selected' : '' ?>>Delete</option>
                                <option value="renew" <?= ($filters['change_type'] ?? '') === 'renew' ? 'selected' : '' ?>>Renew</option>
                                <option value="terminate" <?= ($filters['change_type'] ?? '') === 'terminate' ? 'selected' : '' ?>>Terminate</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="user" class="form-label">User</label>
                            <select class="form-select" id="user" name="user">
                                <option value="">All Users</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>" <?= ($filters['user'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($user['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateRange = document.getElementById('date_range');
    const dateRangeInputs = document.querySelector('.date-range-inputs');
    const filterForm = document.getElementById('filterForm');

    // Handle date range display
    dateRange.addEventListener('change', function() {
        dateRangeInputs.style.display = this.value === 'custom' ? 'block' : 'none';
    });

    // Initialize date range display
    if (dateRange.value === 'custom') {
        dateRangeInputs.style.display = 'block';
    }

    // Handle form submission
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const params = new URLSearchParams();
        
        for (const [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }

        window.location.href = `<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/history") ?>?${params.toString()}`;
    });
});

function getChangeTypeClass(type) {
    return {
        'update': 'info',
        'create': 'success',
        'delete': 'danger',
        'renew': 'primary',
        'terminate': 'warning'
    }[type] || 'secondary';
}
</script>

<style>
.timeline-extended {
    position: relative;
    padding-left: 3rem;
}

.timeline-date {
    position: relative;
    margin: 2rem 0 1rem;
    padding-left: 1rem;
    font-weight: 600;
    color: #6c757d;
}

.timeline-date:before {
    content: '';
    position: absolute;
    left: -3rem;
    top: 0.5rem;
    width: 1rem;
    height: 2px;
    background-color: #dee2e6;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -3rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    background-color: #dee2e6;
    border: 2px solid #fff;
}

.timeline-item:after {
    content: '';
    position: absolute;
    left: -2.6875rem;
    top: 1rem;
    bottom: 0;
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item:last-child:after {
    display: none;
}

.timeline-time {
    font-size: 0.875em;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.timeline-content {
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
}

.changes-table {
    margin-top: 1rem;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
    overflow: hidden;
}

.changes-table .table {
    margin-bottom: 0;
    font-size: 0.875em;
}

.changes-table th {
    background-color: #f1f3f5;
    font-weight: 600;
}

.badge {
    padding: 0.5em 0.75em;
}

@media (max-width: 992px) {
    .col-lg-8,
    .col-lg-4 {
        width: 100%;
    }
}
</style> 