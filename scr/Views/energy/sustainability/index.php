<?php $this->layout('layouts/app', ['title' => 'Sustainability Reports']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Sustainability Reports</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item active">Sustainability Reports</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= url('energy/sustainability/create') ?>" class="btn btn-primary">
                <i class="bx bx-plus"></i> Create Report
            </a>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#reportSettingsModal">
                <i class="bx bx-cog"></i> Report Settings
            </button>
        </div>
    </div>

    <!-- Report Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-primary-subtle rounded">
                            <i class="bx bx-file fs-3 text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">Total Reports</h6>
                            <h4 class="mb-0"><?= $stats['total_reports'] ?></h4>
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
                            <h6 class="mb-1">Published</h6>
                            <h4 class="mb-0"><?= $stats['published_reports'] ?></h4>
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
                            <i class="bx bx-edit fs-3 text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">In Progress</h6>
                            <h4 class="mb-0"><?= $stats['draft_reports'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-info-subtle rounded">
                            <i class="bx bx-time fs-3 text-info"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">Scheduled</h6>
                            <h4 class="mb-0"><?= $stats['scheduled_reports'] ?></h4>
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
                    <label for="reportType" class="form-label">Report Type</label>
                    <select class="form-select" id="reportType" name="type">
                        <option value="">All Types</option>
                        <option value="monthly">Monthly Report</option>
                        <option value="quarterly">Quarterly Report</option>
                        <option value="annual">Annual Report</option>
                        <option value="custom">Custom Report</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="in_review">In Review</option>
                        <option value="published">Published</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="dateRange" class="form-label">Date Range</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="startDate" name="start_date">
                        <span class="input-group-text">to</span>
                        <input type="date" class="form-control" id="endDate" name="end_date">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bx bx-filter-alt"></i> Filter
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="bx bx-reset"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-file me-2"></i>
                                        <a href="<?= url("energy/sustainability/{$report['id']}") ?>">
                                            <?= htmlspecialchars($report['title']) ?>
                                        </a>
                                    </div>
                                </td>
                                <td><?= ucfirst($report['type']) ?></td>
                                <td><?= formatReportPeriod($report['start_date'], $report['end_date']) ?></td>
                                <td>
                                    <span class="badge bg-<?= getStatusClass($report['status']) ?>">
                                        <?= ucfirst($report['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <img src="<?= $report['creator']['avatar_url'] ?>" alt="Avatar" class="rounded-circle">
                                        </div>
                                        <?= htmlspecialchars($report['creator']['name']) ?>
                                    </div>
                                </td>
                                <td><?= timeAgo($report['updated_at']) ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="<?= url("energy/sustainability/{$report['id']}") ?>">
                                                    <i class="bx bx-show me-2"></i> View
                                                </a>
                                            </li>
                                            <?php if ($report['status'] !== 'published'): ?>
                                                <li>
                                                    <a class="dropdown-item" href="<?= url("energy/sustainability/{$report['id']}/edit") ?>">
                                                        <i class="bx bx-edit me-2"></i> Edit
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <li>
                                                <a class="dropdown-item" href="<?= url("energy/sustainability/{$report['id']}/download") ?>">
                                                    <i class="bx bx-download me-2"></i> Download
                                                </a>
                                            </li>
                                            <?php if ($report['status'] === 'draft'): ?>
                                                <li>
                                                    <a class="dropdown-item text-success publish-report" href="#" data-id="<?= $report['id'] ?>">
                                                        <i class="bx bx-check-circle me-2"></i> Publish
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($report['status'] !== 'archived'): ?>
                                                <li>
                                                    <a class="dropdown-item text-secondary archive-report" href="#" data-id="<?= $report['id'] ?>">
                                                        <i class="bx bx-archive me-2"></i> Archive
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger delete-report" href="#" data-id="<?= $report['id'] ?>">
                                                    <i class="bx bx-trash me-2"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($reports)): ?>
                <div class="text-center text-muted py-4">
                    <i class="bx bx-file fs-1"></i>
                    <p class="mb-0">No reports found</p>
                </div>
            <?php endif; ?>
        </div>
        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="card-footer">
                <nav>
                    <ul class="pagination mb-0 justify-content-end">
                        <li class="page-item <?= $pagination['current_page'] === 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('energy/sustainability', ['page' => $pagination['current_page'] - 1]) ?>">
                                Previous
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('energy/sustainability', ['page' => $i]) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $pagination['current_page'] === $pagination['total_pages'] ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('energy/sustainability', ['page' => $pagination['current_page'] + 1]) ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Report Settings Modal -->
<div class="modal fade" id="reportSettingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reportSettingsForm">
                    <div class="mb-3">
                        <label class="form-label">Automatic Report Generation</label>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="autoMonthly" name="auto_generate[monthly]"
                                   <?= $settings['auto_generate']['monthly'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="autoMonthly">
                                Generate Monthly Reports
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="autoQuarterly" name="auto_generate[quarterly]"
                                   <?= $settings['auto_generate']['quarterly'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="autoQuarterly">
                                Generate Quarterly Reports
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="autoAnnual" name="auto_generate[annual]"
                                   <?= $settings['auto_generate']['annual'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="autoAnnual">
                                Generate Annual Reports
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Report Components</label>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="includeEnergy" name="components[energy]"
                                   <?= $settings['components']['energy'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="includeEnergy">
                                Energy Consumption Analysis
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="includeEmissions" name="components[emissions]"
                                   <?= $settings['components']['emissions'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="includeEmissions">
                                Carbon Emissions Data
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="includeProjects" name="components[projects]"
                                   <?= $settings['components']['projects'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="includeProjects">
                                Efficiency Projects Status
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="includeCosts" name="components[costs]"
                                   <?= $settings['components']['costs'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="includeCosts">
                                Cost Analysis
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reportTemplate" class="form-label">Default Template</label>
                        <select class="form-select" id="reportTemplate" name="default_template">
                            <option value="standard" <?= $settings['default_template'] === 'standard' ? 'selected' : '' ?>>
                                Standard Template
                            </option>
                            <option value="detailed" <?= $settings['default_template'] === 'detailed' ? 'selected' : '' ?>>
                                Detailed Template
                            </option>
                            <option value="executive" <?= $settings['default_template'] === 'executive' ? 'selected' : '' ?>>
                                Executive Summary
                            </option>
                            <option value="compliance" <?= $settings['default_template'] === 'compliance' ? 'selected' : '' ?>>
                                Compliance Report
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="exportFormat" class="form-label">Default Export Format</label>
                        <select class="form-select" id="exportFormat" name="export_format">
                            <option value="pdf" <?= $settings['export_format'] === 'pdf' ? 'selected' : '' ?>>PDF</option>
                            <option value="excel" <?= $settings['export_format'] === 'excel' ? 'selected' : '' ?>>Excel</option>
                            <option value="word" <?= $settings['export_format'] === 'word' ? 'selected' : '' ?>>Word</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSettings">Save Settings</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const reportSettingsForm = document.getElementById('reportSettingsForm');
    const saveSettingsBtn = document.getElementById('saveSettings');

    // Initialize date range inputs
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    
    startDate.addEventListener('change', function() {
        endDate.min = this.value;
    });

    endDate.addEventListener('change', function() {
        startDate.max = this.value;
    });

    // Filter form submission
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const params = new URLSearchParams(formData);
        window.location.href = `<?= url('energy/sustainability') ?>?${params.toString()}`;
    });

    // Reset filters
    filterForm.addEventListener('reset', function() {
        setTimeout(() => {
            window.location.href = '<?= url('energy/sustainability') ?>';
        }, 0);
    });

    // Save report settings
    saveSettingsBtn.addEventListener('click', function() {
        const formData = new FormData(reportSettingsForm);
        
        fetch('<?= url('energy/sustainability/settings') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Settings saved successfully');
                  bootstrap.Modal.getInstance(document.getElementById('reportSettingsModal')).hide();
              } else {
                  alert('Failed to save settings: ' + data.message);
              }
          });
    });

    // Publish report
    document.querySelectorAll('.publish-report').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to publish this report?')) {
                const reportId = this.dataset.id;
                fetch(`<?= url('energy/sustainability') ?>/${reportId}/publish`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          location.reload();
                      } else {
                          alert('Failed to publish report: ' + data.message);
                      }
                  });
            }
        });
    });

    // Archive report
    document.querySelectorAll('.archive-report').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to archive this report?')) {
                const reportId = this.dataset.id;
                fetch(`<?= url('energy/sustainability') ?>/${reportId}/archive`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          location.reload();
                      } else {
                          alert('Failed to archive report: ' + data.message);
                      }
                  });
            }
        });
    });

    // Delete report
    document.querySelectorAll('.delete-report').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this report? This action cannot be undone.')) {
                const reportId = this.dataset.id;
                fetch(`<?= url('energy/sustainability') ?>/${reportId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          location.reload();
                      } else {
                          alert('Failed to delete report: ' + data.message);
                      }
                  });
            }
        });
    });
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
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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

.timeline-item:last-child .timeline-content {
    border-bottom: none;
    padding-bottom: 0;
}

@media (max-width: 768px) {
    .card:hover {
        transform: none;
    }
}
</style> 