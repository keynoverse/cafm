<?php $this->layout('layouts/app', ['title' => 'Create Sustainability Report']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <h4 class="mb-0">Create Sustainability Report</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                <li class="breadcrumb-item"><a href="<?= url('energy/sustainability') ?>">Sustainability Reports</a></li>
                <li class="breadcrumb-item active">Create Report</li>
            </ol>
        </nav>
    </div>

    <form id="reportForm" action="<?= url('energy/sustainability') ?>" method="POST" enctype="multipart/form-data">
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Report Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Report Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="monthly">Monthly Report</option>
                                    <option value="quarterly">Quarterly Report</option>
                                    <option value="annual">Annual Report</option>
                                    <option value="custom">Custom Report</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="template" class="form-label">Report Template</label>
                                <select class="form-select" id="template" name="template" required>
                                    <option value="standard">Standard Template</option>
                                    <option value="detailed">Detailed Template</option>
                                    <option value="executive">Executive Summary</option>
                                    <option value="compliance">Compliance Report</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="end_date" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Report Components</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="includeEnergy" name="components[energy]" checked>
                                        <label class="form-check-label" for="includeEnergy">
                                            Energy Consumption Analysis
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="includeEmissions" name="components[emissions]" checked>
                                        <label class="form-check-label" for="includeEmissions">
                                            Carbon Emissions Data
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="includeProjects" name="components[projects]" checked>
                                        <label class="form-check-label" for="includeProjects">
                                            Efficiency Projects Status
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="includeCosts" name="components[costs]" checked>
                                        <label class="form-check-label" for="includeCosts">
                                            Cost Analysis
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Executive Summary</label>
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                            <small class="text-muted">
                                Provide a brief overview of the report's key findings and objectives
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Additional Sections</label>
                            <div id="sections">
                                <!-- Dynamic sections will be added here -->
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="addSection">
                                <i class="bx bx-plus"></i> Add Section
                            </button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Attachments</label>
                            <div class="dropzone" id="attachmentsDropzone"></div>
                            <small class="text-muted">
                                Upload supporting documents, charts, or additional data
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Data Preview</h5>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Energy Consumption</h6>
                                        <canvas id="energyChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Carbon Emissions</h6>
                                        <canvas id="emissionsChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Cost Analysis</h6>
                                        <canvas id="costsChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Projects Impact</h6>
                                        <canvas id="projectsChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Report Settings</h5>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="draft">Save as Draft</option>
                                <option value="in_review">Submit for Review</option>
                                <option value="scheduled">Schedule Publication</option>
                            </select>
                        </div>

                        <div class="mb-3 d-none" id="scheduleSettings">
                            <label for="publishDate" class="form-label">Publication Date</label>
                            <input type="datetime-local" class="form-control" id="publishDate" name="publish_date">
                        </div>

                        <div class="mb-3">
                            <label for="visibility" class="form-label">Visibility</label>
                            <select class="form-select" id="visibility" name="visibility">
                                <option value="internal">Internal Only</option>
                                <option value="public">Public</option>
                                <option value="restricted">Restricted Access</option>
                            </select>
                        </div>

                        <div class="mb-3 d-none" id="accessSettings">
                            <label class="form-label">Access Control</label>
                            <div class="list-group" id="accessList">
                                <!-- Access control options will be added here -->
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="addAccess">
                                <i class="bx bx-plus"></i> Add User/Group
                            </button>
                        </div>

                        <div class="mb-3">
                            <label for="exportFormat" class="form-label">Export Format</label>
                            <select class="form-select" id="exportFormat" name="export_format">
                                <option value="pdf">PDF Document</option>
                                <option value="excel">Excel Spreadsheet</option>
                                <option value="word">Word Document</option>
                            </select>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Notifications</label>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="notifyReviewers" name="notifications[reviewers]" checked>
                                <label class="form-check-label" for="notifyReviewers">
                                    Notify Reviewers
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="notifyStakeholders" name="notifications[stakeholders]" checked>
                                <label class="form-check-label" for="notifyStakeholders">
                                    Notify Stakeholders
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="notifyPublic" name="notifications[public]">
                                <label class="form-check-label" for="notifyPublic">
                                    Public Announcement
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Create Report
                            </button>
                            <a href="<?= url('energy/sustainability') ?>" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Report Summary</h5>
                        <div class="summary-stats">
                            <div class="mb-3">
                                <small class="text-muted d-block">Period Coverage</small>
                                <strong id="periodSummary">-</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Energy Consumption</small>
                                <strong id="energySummary">-</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Carbon Emissions</small>
                                <strong id="emissionsSummary">-</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Cost Impact</small>
                                <strong id="costSummary">-</strong>
                            </div>
                            <div>
                                <small class="text-muted d-block">Active Projects</small>
                                <strong id="projectsSummary">-</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Section Template -->
<template id="sectionTemplate">
    <div class="section-item mb-3">
        <div class="d-flex align-items-center mb-2">
            <input type="text" class="form-control form-control-sm me-2" name="sections[{index}][title]" placeholder="Section Title" required>
            <button type="button" class="btn btn-outline-danger btn-sm remove-section">
                <i class="bx bx-trash"></i>
            </button>
        </div>
        <textarea class="form-control" name="sections[{index}][content]" rows="3" placeholder="Section Content"></textarea>
    </div>
</template>

<!-- Access Control Template -->
<template id="accessTemplate">
    <div class="access-item list-group-item d-flex align-items-center">
        <select class="form-select form-select-sm me-2" name="access[]" required>
            <option value="">Select User/Group</option>
            <?php foreach ($users as $user): ?>
                <option value="user_<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
            <?php endforeach; ?>
            <?php foreach ($groups as $group): ?>
                <option value="group_<?= $group['id'] ?>"><?= htmlspecialchars($group['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" class="btn btn-outline-danger btn-sm remove-access">
            <i class="bx bx-x"></i>
        </button>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportForm = document.getElementById('reportForm');
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    const status = document.getElementById('status');
    const visibility = document.getElementById('visibility');
    const scheduleSettings = document.getElementById('scheduleSettings');
    const accessSettings = document.getElementById('accessSettings');
    const sectionsContainer = document.getElementById('sections');
    const accessList = document.getElementById('accessList');

    // Initialize Dropzone
    const attachmentsDropzone = new Dropzone('#attachmentsDropzone', {
        url: '<?= url('energy/sustainability/upload-attachment') ?>',
        paramName: 'file',
        maxFilesize: 10,
        acceptedFiles: '.pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg',
        addRemoveLinks: true
    });

    // Initialize Charts
    const charts = {
        energy: new Chart(document.getElementById('energyChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Energy Consumption',
                    data: [],
                    borderColor: '#0d6efd'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        }),
        emissions: new Chart(document.getElementById('emissionsChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Carbon Emissions',
                    data: [],
                    borderColor: '#198754'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        }),
        costs: new Chart(document.getElementById('costsChart'), {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Cost Analysis',
                    data: [],
                    backgroundColor: '#0dcaf0'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        }),
        projects: new Chart(document.getElementById('projectsChart'), {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Planned'],
                datasets: [{
                    data: [0, 0, 0],
                    backgroundColor: ['#198754', '#ffc107', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        })
    };

    // Date range validation
    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        updateReportData();
    });

    endDate.addEventListener('change', function() {
        startDate.max = this.value;
        updateReportData();
    });

    // Status change handler
    status.addEventListener('change', function() {
        scheduleSettings.classList.toggle('d-none', this.value !== 'scheduled');
    });

    // Visibility change handler
    visibility.addEventListener('change', function() {
        accessSettings.classList.toggle('d-none', this.value !== 'restricted');
    });

    // Add section handler
    document.getElementById('addSection').addEventListener('click', function() {
        const template = document.getElementById('sectionTemplate').content.cloneNode(true);
        const index = sectionsContainer.children.length;
        template.querySelector('.section-item').dataset.index = index;
        
        // Update input names
        template.querySelectorAll('[name*="{index}"]').forEach(input => {
            input.name = input.name.replace('{index}', index);
        });

        sectionsContainer.appendChild(template);
    });

    // Remove section handler
    sectionsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-section')) {
            e.target.closest('.section-item').remove();
        }
    });

    // Add access control handler
    document.getElementById('addAccess').addEventListener('click', function() {
        const template = document.getElementById('accessTemplate').content.cloneNode(true);
        accessList.appendChild(template);
    });

    // Remove access control handler
    accessList.addEventListener('click', function(e) {
        if (e.target.closest('.remove-access')) {
            e.target.closest('.access-item').remove();
        }
    });

    // Update report data and charts
    function updateReportData() {
        if (!startDate.value || !endDate.value) return;

        fetch(`<?= url('energy/sustainability/preview-data') ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                start_date: startDate.value,
                end_date: endDate.value
            })
        }).then(response => response.json())
          .then(data => {
              // Update summary
              document.getElementById('periodSummary').textContent = `${formatDate(startDate.value)} - ${formatDate(endDate.value)}`;
              document.getElementById('energySummary').textContent = `${data.energy.total.toLocaleString()} kWh`;
              document.getElementById('emissionsSummary').textContent = `${data.emissions.total.toLocaleString()} tCO2e`;
              document.getElementById('costSummary').textContent = `$${data.costs.total.toLocaleString()}`;
              document.getElementById('projectsSummary').textContent = `${data.projects.active} Active`;

              // Update charts
              updateChart(charts.energy, data.energy.labels, data.energy.values);
              updateChart(charts.emissions, data.emissions.labels, data.emissions.values);
              updateChart(charts.costs, data.costs.labels, data.costs.values);
              updateProjectsChart(charts.projects, data.projects);
          });
    }

    function updateChart(chart, labels, values) {
        chart.data.labels = labels;
        chart.data.datasets[0].data = values;
        chart.update();
    }

    function updateProjectsChart(chart, data) {
        chart.data.datasets[0].data = [
            data.completed,
            data.in_progress,
            data.planned
        ];
        chart.update();
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString();
    }

    // Form submission
    reportForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        attachmentsDropzone.files.forEach(file => {
            formData.append('attachments[]', file);
        });

        fetch(this.action, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  window.location.href = data.redirect;
              } else {
                  alert('Failed to create report: ' + data.message);
              }
          });
    });
});
</script>

<style>
.dropzone {
    border: 2px dashed #dee2e6;
    border-radius: 0.375rem;
    padding: 2rem;
    text-align: center;
    background: #f8f9fa;
    cursor: pointer;
}

.dropzone:hover {
    border-color: #0d6efd;
}

.summary-stats strong {
    font-size: 1.1rem;
}

.section-item {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
}

.access-item {
    padding: 0.5rem;
}

.access-item .form-select {
    width: auto;
    flex: 1;
}

@media (max-width: 768px) {
    .access-item {
        flex-direction: column;
    }
    
    .access-item .form-select {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style> 