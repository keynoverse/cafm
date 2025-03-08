<?php $this->layout('layouts/app', ['title' => 'Energy Analytics']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Energy Analytics</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item active">Analytics</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary" id="exportData">
                <i class="bx bx-export"></i> Export Data
            </button>
            <button type="button" class="btn btn-outline-primary" id="printReport">
                <i class="bx bx-printer"></i> Print Report
            </button>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">Date Range</span>
                        <input type="date" class="form-control" id="startDate" name="start_date">
                        <input type="date" class="form-control" id="endDate" name="end_date">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="comparison">
                        <option value="previous">vs Previous Period</option>
                        <option value="year">vs Same Period Last Year</option>
                        <option value="target">vs Target</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="interval">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly" selected>Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary w-100" id="applyFilters">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Energy Efficiency Index</h6>
                    <h2 class="card-title mb-0"><?= number_format($kpi['efficiency_index'], 2) ?></h2>
                    <div class="mt-2">
                        <small class="text-<?= $kpi['efficiency_trend'] > 0 ? 'success' : 'warning' ?>">
                            <i class="bx bx-trending-<?= $kpi['efficiency_trend'] > 0 ? 'up' : 'down' ?>"></i>
                            <?= abs($kpi['efficiency_trend']) ?>% vs previous
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Energy Intensity</h6>
                    <h2 class="card-title mb-0"><?= number_format($kpi['energy_intensity'], 2) ?> kWh/m²</h2>
                    <div class="mt-2">
                        <small class="text-<?= $kpi['intensity_trend'] < 0 ? 'success' : 'warning' ?>">
                            <i class="bx bx-trending-<?= $kpi['intensity_trend'] < 0 ? 'down' : 'up' ?>"></i>
                            <?= abs($kpi['intensity_trend']) ?>% vs previous
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Cost per Unit</h6>
                    <h2 class="card-title mb-0">$<?= number_format($kpi['cost_per_unit'], 3) ?>/kWh</h2>
                    <div class="mt-2">
                        <small class="text-<?= $kpi['cost_trend'] < 0 ? 'success' : 'warning' ?>">
                            <i class="bx bx-trending-<?= $kpi['cost_trend'] < 0 ? 'down' : 'up' ?>"></i>
                            <?= abs($kpi['cost_trend']) ?>% vs previous
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Carbon Intensity</h6>
                    <h2 class="card-title mb-0"><?= number_format($kpi['carbon_intensity'], 2) ?> kgCO2e/m²</h2>
                    <div class="mt-2">
                        <small class="text-<?= $kpi['carbon_trend'] < 0 ? 'success' : 'warning' ?>">
                            <i class="bx bx-trending-<?= $kpi['carbon_trend'] < 0 ? 'down' : 'up' ?>"></i>
                            <?= abs($kpi['carbon_trend']) ?>% vs previous
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Charts -->
    <div class="row">
        <!-- Energy Consumption vs Target -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Energy Consumption vs Target</h5>
                </div>
                <div class="card-body">
                    <canvas id="consumptionChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Energy Cost Analysis -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Energy Cost Analysis</h5>
                </div>
                <div class="card-body">
                    <canvas id="costChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Energy Mix -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Energy Mix</h5>
                </div>
                <div class="card-body">
                    <canvas id="energyMixChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Peak Load Analysis -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Peak Load Analysis</h5>
                </div>
                <div class="card-body">
                    <canvas id="peakLoadChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Efficiency Projects Impact -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Efficiency Projects Impact</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Implementation Date</th>
                            <th>Energy Savings</th>
                            <th>Cost Savings</th>
                            <th>ROI</th>
                            <th>Carbon Reduction</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                            <tr>
                                <td>
                                    <a href="<?= url("energy/projects/{$project['id']}") ?>">
                                        <?= htmlspecialchars($project['title']) ?>
                                    </a>
                                </td>
                                <td><?= date('M j, Y', strtotime($project['completion_date'])) ?></td>
                                <td><?= number_format($project['energy_savings']) ?> kWh</td>
                                <td>$<?= number_format($project['cost_savings'], 2) ?></td>
                                <td><?= number_format($project['roi'], 1) ?>%</td>
                                <td><?= number_format($project['carbon_reduction'], 2) ?> tCO2e</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Efficiency Recommendations</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($recommendations as $recommendation): ?>
                    <div class="col-md-4 mb-3">
                        <div class="alert alert-<?= $recommendation['priority'] === 'high' ? 'danger' : 
                                                  ($recommendation['priority'] === 'medium' ? 'warning' : 'info') ?> mb-0">
                            <h6 class="alert-heading">
                                <i class="bx bx-bulb me-2"></i>
                                <?= htmlspecialchars($recommendation['title']) ?>
                            </h6>
                            <p class="mb-1"><?= htmlspecialchars($recommendation['description']) ?></p>
                            <small>
                                Potential Savings: <?= number_format($recommendation['potential_savings']) ?> kWh
                                ($<?= number_format($recommendation['cost_savings'], 2) ?>)
                            </small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date range inputs
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const comparisonSelect = document.getElementById('comparison');
    const intervalSelect = document.getElementById('interval');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const exportDataBtn = document.getElementById('exportData');
    const printReportBtn = document.getElementById('printReport');

    // Consumption Chart
    const consumptionCtx = document.getElementById('consumptionChart').getContext('2d');
    const consumptionChart = new Chart(consumptionCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($charts['consumption']['labels']) ?>,
            datasets: [{
                label: 'Actual',
                data: <?= json_encode($charts['consumption']['actual']) ?>,
                borderColor: '#0d6efd',
                tension: 0.4
            }, {
                label: 'Target',
                data: <?= json_encode($charts['consumption']['target']) ?>,
                borderColor: '#dc3545',
                borderDash: [5, 5],
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Cost Chart
    const costCtx = document.getElementById('costChart').getContext('2d');
    const costChart = new Chart(costCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($charts['cost']['labels']) ?>,
            datasets: [{
                label: 'Fixed Cost',
                data: <?= json_encode($charts['cost']['fixed']) ?>,
                backgroundColor: '#0d6efd'
            }, {
                label: 'Variable Cost',
                data: <?= json_encode($charts['cost']['variable']) ?>,
                backgroundColor: '#6c757d'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { stacked: true },
                y: { stacked: true }
            }
        }
    });

    // Energy Mix Chart
    const mixCtx = document.getElementById('energyMixChart').getContext('2d');
    const mixChart = new Chart(mixCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_keys($charts['mix'])) ?>,
            datasets: [{
                data: <?= json_encode(array_values($charts['mix'])) ?>,
                backgroundColor: ['#0d6efd', '#dc3545', '#ffc107', '#198754', '#6c757d']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Peak Load Chart
    const peakCtx = document.getElementById('peakLoadChart').getContext('2d');
    const peakChart = new Chart(peakCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($charts['peak']['labels']) ?>,
            datasets: [{
                label: 'Peak Load',
                data: <?= json_encode($charts['peak']['data']) ?>,
                borderColor: '#dc3545',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Filter handlers
    applyFiltersBtn.addEventListener('click', function() {
        updateCharts();
    });

    function updateCharts() {
        const params = new URLSearchParams({
            start_date: startDateInput.value,
            end_date: endDateInput.value,
            comparison: comparisonSelect.value,
            interval: intervalSelect.value
        });

        fetch(`<?= url('energy/analytics/data') ?>?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                updateConsumptionChart(data.consumption);
                updateCostChart(data.cost);
                updateMixChart(data.mix);
                updatePeakChart(data.peak);
            });
    }

    // Export and Print handlers
    exportDataBtn.addEventListener('click', function() {
        const params = new URLSearchParams({
            start_date: startDateInput.value,
            end_date: endDateInput.value,
            format: 'xlsx'
        });
        window.location.href = `<?= url('energy/analytics/export') ?>?${params.toString()}`;
    });

    printReportBtn.addEventListener('click', function() {
        window.print();
    });

    // Set minimum date for end date based on start date
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
    });
});
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

@media print {
    .btn-group,
    .card-header {
        display: none !important;
    }

    .card {
        break-inside: avoid;
    }
}

@media (max-width: 768px) {
    .card:hover {
        transform: none;
    }

    .btn-group {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .btn-group .btn {
        margin-bottom: 0.5rem;
    }
}
</style> 