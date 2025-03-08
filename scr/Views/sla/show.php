<?php $this->layout('layouts/app', ['title' => $sla['name']]) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('sla') ?>">SLA Management</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($sla['name']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group">
                <?php if ($this->user->hasPermission('edit_sla')): ?>
                    <a href="<?= url("sla/{$sla['id']}/edit") ?>" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit SLA
                    </a>
                <?php endif; ?>
                <?php if ($this->user->hasPermission('generate_sla_reports')): ?>
                    <button type="button" class="btn btn-outline-primary" id="exportReport">
                        <i class="bx bx-export"></i> Export Report
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
                    <h5 class="card-title mb-0">SLA Details</h5>
                    <span class="badge bg-<?= $sla['active'] ? 'success' : 'secondary' ?>">
                        <?= $sla['active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Priority:</div>
                        <div class="col-md-9">
                            <span class="badge bg-<?= getPriorityClass($sla['priority']) ?>">
                                <?= ucfirst($sla['priority']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Category:</div>
                        <div class="col-md-9">
                            <?= htmlspecialchars($sla['category_name']) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Description:</div>
                        <div class="col-md-9">
                            <?= nl2br(htmlspecialchars($sla['description'])) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Response Time:</div>
                        <div class="col-md-9">
                            <?= $sla['response_time'] ?> hours
                            <small class="text-muted d-block">Maximum time to respond to a request</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Resolution Time:</div>
                        <div class="col-md-9">
                            <?= $sla['resolution_time'] ?> hours
                            <small class="text-muted d-block">Maximum time to resolve a request</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Escalation Time:</div>
                        <div class="col-md-9">
                            <?= $sla['escalation_time'] ?> hours
                            <small class="text-muted d-block">Time after which the request is escalated</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Business Hours Only:</div>
                        <div class="col-md-9">
                            <i class="bx bx-<?= $sla['business_hours_only'] ? 'check text-success' : 'x text-danger' ?>"></i>
                            <?= $sla['business_hours_only'] ? 'Yes' : 'No' ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted">Created:</div>
                        <div class="col-md-9">
                            <?= date('M j, Y g:i A', strtotime($sla['created_at'])) ?>
                            <small class="text-muted d-block">By: <?= htmlspecialchars($sla['created_by_name']) ?></small>
                        </div>
                    </div>

                    <?php if ($sla['updated_at']): ?>
                        <div class="row mb-3">
                            <div class="col-md-3 text-muted">Last Updated:</div>
                            <div class="col-md-9">
                                <?= date('M j, Y g:i A', strtotime($sla['updated_at'])) ?>
                                <small class="text-muted d-block">By: <?= htmlspecialchars($sla['updated_by_name']) ?></small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Performance Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h6 class="text-muted mb-2">Compliance Rate</h6>
                                <div class="display-4 mb-2">
                                    <?= number_format($performance['compliance_rate'], 1) ?>%
                                </div>
                                <small class="text-<?= $performance['compliance_rate'] >= 90 ? 'success' : 'warning' ?>">
                                    <i class="bx bx-trending-up"></i> Last 30 Days
                                </small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h6 class="text-muted mb-2">Average Response Time</h6>
                                <div class="display-4 mb-2">
                                    <?= number_format($performance['avg_response_time'], 1) ?>h
                                </div>
                                <small class="text-info">
                                    <i class="bx bx-time"></i> Last 30 Days
                                </small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h6 class="text-muted mb-2">Average Resolution Time</h6>
                                <div class="display-4 mb-2">
                                    <?= number_format($performance['avg_resolution_time'], 1) ?>h
                                </div>
                                <small class="text-info">
                                    <i class="bx bx-time"></i> Last 30 Days
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Chart -->
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Violations -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Violations</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Request</th>
                                    <th>Type</th>
                                    <th>Exceeded By</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($violations as $violation): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= url("maintenance-requests/{$violation['request_id']}") ?>">
                                                <?= htmlspecialchars($violation['request_title']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">
                                                <?= ucfirst($violation['violation_type']) ?>
                                            </span>
                                        </td>
                                        <td><?= formatDuration($violation['exceeded_by']) ?></td>
                                        <td>
                                            <?= date('M j, Y g:i A', strtotime($violation['created_at'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($violations)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="bx bx-check-circle text-success fs-4 mb-2"></i>
                                            <p class="mb-0">No recent violations</p>
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
            <!-- Current Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Current Status</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Active Requests:</small>
                        <h3 class="mb-0"><?= $status['active_requests'] ?></h3>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Pending Response:</small>
                        <h3 class="mb-0"><?= $status['pending_response'] ?></h3>
                    </div>
                    <div>
                        <small class="text-muted d-block">At Risk:</small>
                        <h3 class="text-<?= $status['at_risk'] > 0 ? 'danger' : 'success' ?> mb-0">
                            <?= $status['at_risk'] ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php if ($this->user->hasPermission('view_maintenance_requests')): ?>
                        <a href="<?= url('maintenance-requests', ['sla_id' => $sla['id']]) ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-list-ul me-2"></i> View Associated Requests
                        </a>
                    <?php endif; ?>
                    <?php if ($this->user->hasPermission('view_sla_violations')): ?>
                        <a href="<?= url("sla/{$sla['id']}/violations") ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-error-circle me-2"></i> View All Violations
                        </a>
                    <?php endif; ?>
                    <?php if ($this->user->hasPermission('generate_sla_reports')): ?>
                        <a href="<?= url("sla/{$sla['id']}/reports") ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="bx bx-chart me-2"></i> View Detailed Reports
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance Chart
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($performance['daily'], 'date')) ?>,
            datasets: [
                {
                    label: 'Compliance Rate',
                    data: <?= json_encode(array_column($performance['daily'], 'compliance_rate')) ?>,
                    borderColor: '#0d6efd',
                    tension: 0.4,
                    fill: false
                },
                {
                    label: 'Response Time (hours)',
                    data: <?= json_encode(array_column($performance['daily'], 'avg_response_time')) ?>,
                    borderColor: '#20c997',
                    tension: 0.4,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Export Report Handler
    const exportReportBtn = document.getElementById('exportReport');
    if (exportReportBtn) {
        exportReportBtn.addEventListener('click', function() {
            window.location.href = `<?= url("sla/{$sla['id']}/export") ?>`;
        });
    }
});

function getPriorityClass(priority) {
    return {
        'low': 'success',
        'medium': 'info',
        'high': 'warning',
        'urgent': 'danger'
    }[priority] || 'secondary';
}

function formatDuration(hours) {
    if (hours < 1) {
        return Math.round(hours * 60) + ' minutes';
    }
    if (hours < 24) {
        return Math.round(hours) + ' hours';
    }
    return Math.round(hours / 24) + ' days';
}
</script>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1.5rem;
}

.display-4 {
    font-size: 2.5rem;
    font-weight: 300;
    line-height: 1.2;
}

.chart-container {
    position: relative;
    margin: auto;
}

.list-group-item {
    transition: background-color 0.3s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
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

    .display-4 {
        font-size: 2rem;
    }
}
</style> 