<?php $this->layout('layouts/app', ['title' => 'Energy Management Dashboard']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Energy Management Dashboard</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Energy Management</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <?php if ($this->user->hasPermission('create_energy_consumption')): ?>
                <a href="<?= url('energy/consumption/create') ?>" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Record Consumption
                </a>
            <?php endif; ?>
            <?php if ($this->user->hasPermission('create_utility_bill')): ?>
                <a href="<?= url('energy/bills/create') ?>" class="btn btn-outline-primary">
                    <i class="bx bx-receipt"></i> Add Utility Bill
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Total Energy Consumption</h6>
                    <h2 class="card-title mb-0"><?= number_format($stats['total_consumption'], 2) ?> kWh</h2>
                    <div class="mt-2">
                        <small class="<?= $stats['consumption_trend'] < 0 ? 'text-success' : 'text-warning' ?>">
                            <i class="bx bx-trending-<?= $stats['consumption_trend'] < 0 ? 'down' : 'up' ?>"></i>
                            <?= abs($stats['consumption_trend']) ?>% vs last month
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Cost Savings</h6>
                    <h2 class="card-title mb-0">$<?= number_format($stats['cost_savings'], 2) ?></h2>
                    <div class="mt-2">
                        <small class="text-white">
                            <i class="bx bx-calendar"></i> This Year
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Carbon Footprint</h6>
                    <h2 class="card-title mb-0"><?= number_format($stats['carbon_footprint'], 2) ?> tCO2e</h2>
                    <div class="mt-2">
                        <small class="<?= $stats['emissions_trend'] < 0 ? 'text-success' : 'text-warning' ?>">
                            <i class="bx bx-trending-<?= $stats['emissions_trend'] < 0 ? 'down' : 'up' ?>"></i>
                            <?= abs($stats['emissions_trend']) ?>% vs last month
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Active Projects</h6>
                    <h2 class="card-title mb-0"><?= $stats['active_projects'] ?></h2>
                    <div class="mt-2">
                        <small class="text-dark">
                            <i class="bx bx-bulb"></i> <?= $stats['completed_projects'] ?> Completed
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row">
        <!-- Energy Consumption Chart -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Energy Consumption Trends</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-period="daily">Daily</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary active" data-period="weekly">Weekly</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-period="monthly">Monthly</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="consumptionChart" height="300"></canvas>
                </div>
            </div>

            <!-- Cost Analysis -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Cost Analysis</h5>
                    <select class="form-select form-select-sm w-auto" id="costPeriod">
                        <option value="3">Last 3 Months</option>
                        <option value="6">Last 6 Months</option>
                        <option value="12" selected>Last 12 Months</option>
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="costChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- Recent Bills -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Bills</h5>
                    <a href="<?= url('energy/bills') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($recentBills as $bill): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?= htmlspecialchars($bill['utility_type']) ?></h6>
                                <small class="text-<?= getStatusClass($bill['payment_status']) ?>">
                                    <?= ucfirst($bill['payment_status']) ?>
                                </small>
                            </div>
                            <p class="mb-1">$<?= number_format($bill['amount'], 2) ?></p>
                            <small class="text-muted">Due: <?= date('M j, Y', strtotime($bill['due_date'])) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Active Projects</h5>
                    <a href="<?= url('energy/projects') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($activeProjects as $project): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?= htmlspecialchars($project['title']) ?></h6>
                                <small class="text-<?= getPriorityClass($project['priority']) ?>">
                                    <?= ucfirst($project['priority']) ?>
                                </small>
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: <?= $project['progress'] ?>%"
                                     aria-valuenow="<?= $project['progress'] ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted">
                                Estimated Savings: $<?= number_format($project['estimated_savings'], 2) ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Smart Building Status -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Smart Building Status</h5>
                    <a href="<?= url('energy/devices') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h6 class="text-muted">Connected Devices</h6>
                            <h3><?= $deviceStats['connected'] ?>/<?= $deviceStats['total'] ?></h3>
                        </div>
                        <div class="col-6 mb-3">
                            <h6 class="text-muted">Data Points Today</h6>
                            <h3><?= number_format($deviceStats['data_points']) ?></h3>
                        </div>
                    </div>
                    <div class="alert alert-<?= $deviceStats['system_status'] === 'normal' ? 'success' : 'warning' ?> mb-0">
                        <i class="bx bx-info-circle"></i>
                        System Status: <?= ucfirst($deviceStats['system_status']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Consumption Chart
    const consumptionCtx = document.getElementById('consumptionChart').getContext('2d');
    const consumptionChart = new Chart(consumptionCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($consumptionData['labels']) ?>,
            datasets: [{
                label: 'Electricity (kWh)',
                data: <?= json_encode($consumptionData['electricity']) ?>,
                borderColor: '#0d6efd',
                tension: 0.4
            }, {
                label: 'Gas (m³)',
                data: <?= json_encode($consumptionData['gas']) ?>,
                borderColor: '#dc3545',
                tension: 0.4
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

    // Cost Chart
    const costCtx = document.getElementById('costChart').getContext('2d');
    const costChart = new Chart(costCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($costData['labels']) ?>,
            datasets: [{
                label: 'Actual Cost ($)',
                data: <?= json_encode($costData['actual']) ?>,
                backgroundColor: '#0d6efd'
            }, {
                label: 'Projected Cost ($)',
                data: <?= json_encode($costData['projected']) ?>,
                backgroundColor: '#6c757d'
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

    // Period Buttons Handler
    document.querySelectorAll('[data-period]').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelector('[data-period].active').classList.remove('active');
            this.classList.add('active');
            updateConsumptionChart(this.dataset.period);
        });
    });

    // Cost Period Handler
    document.getElementById('costPeriod').addEventListener('change', function() {
        updateCostChart(this.value);
    });

    function updateConsumptionChart(period) {
        fetch(`<?= url('energy/api/consumption-data') ?>?period=${period}`)
            .then(response => response.json())
            .then(data => {
                consumptionChart.data.labels = data.labels;
                consumptionChart.data.datasets[0].data = data.electricity;
                consumptionChart.data.datasets[1].data = data.gas;
                consumptionChart.update();
            });
    }

    function updateCostChart(months) {
        fetch(`<?= url('energy/api/cost-analysis') ?>?months=${months}`)
            .then(response => response.json())
            .then(data => {
                costChart.data.labels = data.labels;
                costChart.data.datasets[0].data = data.actual;
                costChart.data.datasets[1].data = data.projected;
                costChart.update();
            });
    }
});

function getStatusClass(status) {
    return {
        'pending': 'warning',
        'paid': 'success',
        'overdue': 'danger',
        'disputed': 'info'
    }[status] || 'secondary';
}

function getPriorityClass(priority) {
    return {
        'low': 'success',
        'medium': 'warning',
        'high': 'danger'
    }[priority] || 'secondary';
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

.progress {
    background-color: #e9ecef;
    border-radius: 0.25rem;
}

.progress-bar {
    transition: width 0.6s ease;
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

    .card:hover {
        transform: none;
    }
}
</style> 