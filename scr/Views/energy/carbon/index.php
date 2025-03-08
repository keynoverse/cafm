<?php $this->layout('layouts/app', ['title' => 'Carbon Footprint Tracking']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Carbon Footprint Tracking</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item active">Carbon Footprint</li>
                </ol>
            </nav>
        </div>
        <?php if ($this->user->hasPermission('create_carbon_footprint')): ?>
            <a href="<?= url('energy/carbon/create') ?>" class="btn btn-primary">
                <i class="bx bx-plus"></i> Record Emissions
            </a>
        <?php endif; ?>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Emissions</h6>
                    <h2 class="card-title mb-0"><?= number_format($summary['total_emissions'], 2) ?> tCO2e</h2>
                    <div class="mt-2">
                        <small class="text-<?= $summary['emissions_trend'] < 0 ? 'success' : 'warning' ?>">
                            <i class="bx bx-trending-<?= $summary['emissions_trend'] < 0 ? 'down' : 'up' ?>"></i>
                            <?= abs($summary['emissions_trend']) ?>% vs last month
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Reduction Target</h6>
                    <h2 class="card-title mb-0"><?= number_format($summary['reduction_target'], 1) ?>%</h2>
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="bx bx-calendar"></i> Annual Goal
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Carbon Intensity</h6>
                    <h2 class="card-title mb-0"><?= number_format($summary['carbon_intensity'], 2) ?></h2>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bx bx-line-chart"></i> kgCO2e/m²
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Offset Credits</h6>
                    <h2 class="card-title mb-0"><?= number_format($summary['offset_credits'], 2) ?> tCO2e</h2>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="bx bx-check-circle"></i> Verified
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emissions Chart -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Emissions Trend</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-period="daily">Daily</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary active" data-period="monthly">Monthly</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-period="yearly">Yearly</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="emissionsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Emissions by Source</h5>
                </div>
                <div class="card-body">
                    <canvas id="sourcesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" class="form-control" id="search" 
                               placeholder="Search records..." value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="source_type">
                        <option value="">All Sources</option>
                        <option value="electricity">Electricity</option>
                        <option value="gas">Gas</option>
                        <option value="water">Water</option>
                        <option value="transportation">Transportation</option>
                        <option value="waste">Waste</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">Date Range</span>
                        <input type="date" class="form-control" id="startDate" name="start_date">
                        <input type="date" class="form-control" id="endDate" name="end_date">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                        Clear Filters
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-primary w-100" id="exportData">
                        <i class="bx bx-export"></i> Export Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Emissions Records Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Source</th>
                            <th>Amount</th>
                            <th>Period</th>
                            <th>Calculation Method</th>
                            <th>Recorded By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emissions as $emission): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-<?= getSourceClass($emission['source_type']) ?>">
                                        <?= ucfirst($emission['source_type']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= number_format($emission['emission_amount'], 2) ?>
                                    <?= htmlspecialchars($emission['emission_unit']) ?>
                                </td>
                                <td>
                                    <?= date('M j, Y', strtotime($emission['recording_period_start'])) ?> -
                                    <?= date('M j, Y', strtotime($emission['recording_period_end'])) ?>
                                </td>
                                <td><?= htmlspecialchars($emission['calculation_method']) ?></td>
                                <td>
                                    <?= htmlspecialchars($emission['recorded_by']) ?>
                                    <small class="d-block text-muted">
                                        <?= date('M j, Y g:i A', strtotime($emission['created_at'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= url("energy/carbon/{$emission['id']}") ?>" 
                                           class="btn btn-sm btn-outline-secondary"
                                           title="View Details">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <?php if ($this->user->hasPermission('edit_carbon_footprint')): ?>
                                            <a href="<?= url("energy/carbon/{$emission['id']}/edit") ?>" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit Record">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($emissions)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="bx bx-info-circle fs-4 mb-2"></i>
                                    <p class="mb-0">No emission records found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Emissions pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('energy/carbon', ['page' => $currentPage - 1]) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('energy/carbon', ['page' => $i]) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('energy/carbon', ['page' => $currentPage + 1]) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Emissions Trend Chart
    const emissionsCtx = document.getElementById('emissionsChart').getContext('2d');
    const emissionsChart = new Chart(emissionsCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartData['labels']) ?>,
            datasets: [{
                label: 'Emissions (tCO2e)',
                data: <?= json_encode($chartData['emissions']) ?>,
                borderColor: '#0d6efd',
                tension: 0.4,
                fill: false
            }, {
                label: 'Target',
                data: <?= json_encode($chartData['target']) ?>,
                borderColor: '#dc3545',
                borderDash: [5, 5],
                tension: 0.4,
                fill: false
            }]
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

    // Sources Pie Chart
    const sourcesCtx = document.getElementById('sourcesChart').getContext('2d');
    const sourcesChart = new Chart(sourcesCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_keys($sourceData)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($sourceData)) ?>,
                backgroundColor: [
                    '#0d6efd',
                    '#dc3545',
                    '#198754',
                    '#ffc107',
                    '#6c757d',
                    '#0dcaf0'
                ]
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
    });

    // Period Buttons Handler
    document.querySelectorAll('[data-period]').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelector('[data-period].active').classList.remove('active');
            this.classList.add('active');
            updateEmissionsChart(this.dataset.period);
        });
    });

    // Filter Form Handlers
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const sourceTypeSelect = document.getElementById('source_type');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const exportDataBtn = document.getElementById('exportData');

    function applyFilters() {
        const params = new URLSearchParams(window.location.search);
        
        if (searchInput.value) params.set('query', searchInput.value);
        else params.delete('query');
        
        if (sourceTypeSelect.value) params.set('source', sourceTypeSelect.value);
        else params.delete('source');
        
        if (startDateInput.value) params.set('start_date', startDateInput.value);
        else params.delete('start_date');
        
        if (endDateInput.value) params.set('end_date', endDateInput.value);
        else params.delete('end_date');
        
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    // Event listeners for filter changes
    [searchInput, sourceTypeSelect, startDateInput, endDateInput].forEach(element => {
        if (element.tagName === 'INPUT' && element.type === 'text') {
            element.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyFilters();
                }
            });
        } else {
            element.addEventListener('change', applyFilters);
        }
    });

    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });

    // Export data
    exportDataBtn.addEventListener('click', function() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', '1');
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    });

    // Set minimum date for end date based on start date
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
    });

    function updateEmissionsChart(period) {
        fetch(`<?= url('energy/api/emissions-data') ?>?period=${period}`)
            .then(response => response.json())
            .then(data => {
                emissionsChart.data.labels = data.labels;
                emissionsChart.data.datasets[0].data = data.emissions;
                emissionsChart.data.datasets[1].data = data.target;
                emissionsChart.update();
            });
    }
});

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
</script>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.table td {
    vertical-align: middle;
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
        opacity: 1;
    }
    
    .card:hover {
        transform: none;
    }

    .input-group {
        margin-bottom: 1rem;
    }
}
</style> 