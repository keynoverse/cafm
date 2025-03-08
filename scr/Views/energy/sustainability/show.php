<?php $this->layout('layouts/app', ['title' => htmlspecialchars($report['title'])]) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0"><?= htmlspecialchars($report['title']) ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('energy/sustainability') ?>">Sustainability Reports</a></li>
                    <li class="breadcrumb-item active">View Report</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if ($report['status'] === 'draft'): ?>
                <a href="<?= url("energy/sustainability/{$report['id']}/edit") ?>" class="btn btn-primary">
                    <i class="bx bx-edit"></i> Edit Report
                </a>
            <?php endif; ?>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bx bx-export"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="<?= url("energy/sustainability/{$report['id']}/export/pdf") ?>">
                            <i class="bx bxs-file-pdf me-2"></i> PDF Document
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url("energy/sustainability/{$report['id']}/export/excel") ?>">
                            <i class="bx bxs-file-doc me-2"></i> Excel Spreadsheet
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url("energy/sustainability/{$report['id']}/export/word") ?>">
                            <i class="bx bxs-file-doc me-2"></i> Word Document
                        </a>
                    </li>
                </ul>
            </div>
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                <i class="bx bx-printer"></i> Print
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Report Metadata -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Report Type</small>
                            <strong><?= ucfirst($report['type']) ?></strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Period</small>
                            <strong><?= formatReportPeriod($report['start_date'], $report['end_date']) ?></strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Created By</small>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xs me-2">
                                    <img src="<?= $report['creator']['avatar_url'] ?>" alt="Avatar" class="rounded-circle">
                                </div>
                                <?= htmlspecialchars($report['creator']['name']) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Last Updated</small>
                            <strong><?= timeAgo($report['updated_at']) ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Executive Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Executive Summary</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?= nl2br(htmlspecialchars($report['description'])) ?></p>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Key Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2">Energy Consumption</h6>
                                    <canvas id="energyChart" height="200"></canvas>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Consumption</span>
                                            <strong><?= number_format($report['metrics']['energy']['total']) ?> kWh</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Change from Previous Period</span>
                                            <strong class="text-<?= $report['metrics']['energy']['change'] >= 0 ? 'danger' : 'success' ?>">
                                                <?= $report['metrics']['energy']['change'] ?>%
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2">Carbon Emissions</h6>
                                    <canvas id="emissionsChart" height="200"></canvas>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Emissions</span>
                                            <strong><?= number_format($report['metrics']['emissions']['total']) ?> tCO2e</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Change from Previous Period</span>
                                            <strong class="text-<?= $report['metrics']['emissions']['change'] >= 0 ? 'danger' : 'success' ?>">
                                                <?= $report['metrics']['emissions']['change'] ?>%
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2">Cost Analysis</h6>
                                    <canvas id="costsChart" height="200"></canvas>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Costs</span>
                                            <strong>$<?= number_format($report['metrics']['costs']['total']) ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Cost Savings</span>
                                            <strong class="text-success">
                                                $<?= number_format($report['metrics']['costs']['savings']) ?>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2">Projects Impact</h6>
                                    <canvas id="projectsChart" height="200"></canvas>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Active Projects</span>
                                            <strong><?= $report['metrics']['projects']['active'] ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Completed Projects</span>
                                            <strong><?= $report['metrics']['projects']['completed'] ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Sections -->
            <?php foreach ($report['sections'] as $section): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= htmlspecialchars($section['title']) ?></h5>
                    </div>
                    <div class="card-body">
                        <?= nl2br(htmlspecialchars($section['content'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Attachments -->
            <?php if (!empty($report['attachments'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Attachments</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($report['attachments'] as $attachment): ?>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-file fs-2 me-2"></i>
                                                <div>
                                                    <h6 class="mb-1"><?= htmlspecialchars($attachment['name']) ?></h6>
                                                    <small class="text-muted"><?= formatFileSize($attachment['size']) ?></small>
                                                </div>
                                            </div>
                                            <a href="<?= url("energy/sustainability/attachment/{$attachment['id']}") ?>" 
                                               class="btn btn-outline-secondary btn-sm w-100 mt-2">
                                                <i class="bx bx-download"></i> Download
                                            </a>
                                        </div>
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
            <!-- Report Status -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Status</h5>
                        <span class="badge bg-<?= getStatusClass($report['status']) ?>">
                            <?= ucfirst($report['status']) ?>
                        </span>
                    </div>
                    <?php if ($report['status'] === 'scheduled'): ?>
                        <small class="text-muted d-block mb-2">Scheduled Publication</small>
                        <strong><?= formatDateTime($report['publish_date']) ?></strong>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Report Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($report['timeline'] as $event): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between mb-1">
                                        <strong><?= htmlspecialchars($event['action']) ?></strong>
                                        <small class="text-muted"><?= timeAgo($event['timestamp']) ?></small>
                                    </div>
                                    <p class="mb-0">
                                        <span class="text-muted">by</span>
                                        <?= htmlspecialchars($event['user']['name']) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Report Access -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Access Control</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Visibility</small>
                        <strong><?= ucfirst($report['visibility']) ?></strong>
                    </div>
                    <?php if ($report['visibility'] === 'restricted' && !empty($report['access'])): ?>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-2">Access List</small>
                            <div class="list-group">
                                <?php foreach ($report['access'] as $access): ?>
                                    <div class="list-group-item d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <img src="<?= $access['avatar_url'] ?>" alt="Avatar" class="rounded-circle">
                                        </div>
                                        <div>
                                            <?= htmlspecialchars($access['name']) ?>
                                            <small class="text-muted d-block">
                                                <?= ucfirst($access['type']) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Charts
    const charts = {
        energy: new Chart(document.getElementById('energyChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode($report['metrics']['energy']['labels']) ?>,
                datasets: [{
                    label: 'Energy Consumption (kWh)',
                    data: <?= json_encode($report['metrics']['energy']['values']) ?>,
                    borderColor: '#0d6efd',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        }),
        emissions: new Chart(document.getElementById('emissionsChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode($report['metrics']['emissions']['labels']) ?>,
                datasets: [{
                    label: 'Carbon Emissions (tCO2e)',
                    data: <?= json_encode($report['metrics']['emissions']['values']) ?>,
                    borderColor: '#198754',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        }),
        costs: new Chart(document.getElementById('costsChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($report['metrics']['costs']['labels']) ?>,
                datasets: [{
                    label: 'Costs ($)',
                    data: <?= json_encode($report['metrics']['costs']['values']) ?>,
                    backgroundColor: '#0dcaf0'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        }),
        projects: new Chart(document.getElementById('projectsChart'), {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Planned'],
                datasets: [{
                    data: [
                        <?= $report['metrics']['projects']['completed'] ?>,
                        <?= $report['metrics']['projects']['in_progress'] ?>,
                        <?= $report['metrics']['projects']['planned'] ?>
                    ],
                    backgroundColor: ['#198754', '#ffc107', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        })
    };
});

function getStatusClass(status) {
    return {
        'draft': 'secondary',
        'in_review': 'info',
        'published': 'success',
        'scheduled': 'primary',
        'archived': 'warning'
    }[status] || 'secondary';
}

function formatReportPeriod(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    return `${start.toLocaleDateString()} - ${end.toLocaleDateString()}`;
}

function formatDateTime(datetime) {
    return new Date(datetime).toLocaleString();
}

function formatFileSize(bytes) {
    const units = ['B', 'KB', 'MB', 'GB'];
    let size = bytes;
    let unitIndex = 0;
    
    while (size >= 1024 && unitIndex < units.length - 1) {
        size /= 1024;
        unitIndex++;
    }
    
    return `${size.toFixed(1)} ${units[unitIndex]}`;
}

function timeAgo(timestamp) {
    const date = new Date(timestamp);
    const seconds = Math.floor((new Date() - date) / 1000);
    const intervals = {
        year: 31536000,
        month: 2592000,
        week: 604800,
        day: 86400,
        hour: 3600,
        minute: 60
    };

    for (let [unit, secondsInUnit] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / secondsInUnit);
        if (interval >= 1) {
            return interval + ' ' + unit + (interval === 1 ? '' : 's') + ' ago';
        }
    }
    return 'just now';
}
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
}

.timeline {
    position: relative;
    padding: 0;
    list-style: none;
}

.timeline-item {
    position: relative;
    padding-left: 24px;
    margin-bottom: 24px;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #0d6efd;
}

.timeline-content {
    padding-bottom: 24px;
    border-bottom: 1px solid #e9ecef;
}

.timeline-item:last-child .timeline-content {
    border-bottom: none;
    padding-bottom: 0;
}

.avatar {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-xs {
    width: 24px;
    height: 24px;
}

.avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card {
    margin-bottom: 1.5rem;
}

.card-header {
    background-color: transparent;
    border-bottom: 1px solid rgba(0,0,0,0.125);
}

.badge {
    padding: 0.5em 0.75em;
}

.list-group-item {
    padding: 0.75rem 1rem;
}

@media (max-width: 768px) {
    .timeline-item {
        padding-left: 20px;
    }

    .timeline-marker {
        width: 10px;
        height: 10px;
    }
}
</style> 