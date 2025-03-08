<?php $this->layout('layouts/app', ['title' => 'Device Readings']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Device Readings</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('energy/devices') ?>">Smart Devices</a></li>
                    <li class="breadcrumb-item">
                        <a href="<?= url("energy/devices/{$device['id']}") ?>">
                            <?= htmlspecialchars($device['device_name']) ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Readings</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary" id="exportReadings">
                <i class="bx bx-export"></i> Export Data
            </button>
            <button type="button" class="btn btn-outline-secondary" id="refreshReadings">
                <i class="bx bx-refresh"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Last Reading</h6>
                    <h2 class="card-title mb-0" id="lastReadingValue">
                        <?= number_format($stats['last_reading']['value'], 2) ?>
                        <small><?= htmlspecialchars($stats['last_reading']['unit']) ?></small>
                    </h2>
                    <div class="mt-2">
                        <small class="text-muted" id="lastReadingTime">
                            <i class="bx bx-time"></i>
                            <?= date('g:i:s A', strtotime($stats['last_reading']['timestamp'])) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">24h Average</h6>
                    <h2 class="card-title mb-0">
                        <?= number_format($stats['daily_average'], 2) ?>
                        <small><?= htmlspecialchars($device['data_points'][0]['unit']) ?></small>
                    </h2>
                    <div class="mt-2">
                        <small class="text-<?= $stats['daily_trend'] > 0 ? 'danger' : 'success' ?>">
                            <i class="bx bx-trending-<?= $stats['daily_trend'] > 0 ? 'up' : 'down' ?>"></i>
                            <?= abs($stats['daily_trend']) ?>% vs yesterday
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Peak Value</h6>
                    <h2 class="card-title mb-0">
                        <?= number_format($stats['peak_value'], 2) ?>
                        <small><?= htmlspecialchars($device['data_points'][0]['unit']) ?></small>
                    </h2>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bx bx-calendar"></i>
                            <?= date('M j, Y g:i A', strtotime($stats['peak_timestamp'])) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Data Quality</h6>
                    <h2 class="card-title mb-0">
                        <?= number_format($stats['data_quality'], 1) ?>%
                    </h2>
                    <div class="mt-2">
                        <small class="text-<?= $stats['data_quality'] >= 95 ? 'success' : 
                                            ($stats['data_quality'] >= 80 ? 'warning' : 'danger') ?>">
                            <i class="bx bx-check-circle"></i>
                            <?= $stats['missing_points'] ?> missing points
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">Date Range</span>
                        <input type="date" class="form-control" id="startDate" name="start_date"
                               value="<?= date('Y-m-d', strtotime('-7 days')) ?>">
                        <input type="date" class="form-control" id="endDate" name="end_date"
                               value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="dataPoint" name="data_point">
                        <?php foreach ($device['data_points'] as $point): ?>
                            <?php if ($point['enabled']): ?>
                                <option value="<?= $point['id'] ?>"
                                        data-unit="<?= htmlspecialchars($point['unit']) ?>">
                                    <?= htmlspecialchars($point['name']) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="interval" name="interval">
                        <option value="raw">Raw Data</option>
                        <option value="minute">1 Minute Average</option>
                        <option value="hour" selected>1 Hour Average</option>
                        <option value="day">Daily Average</option>
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

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Reading History</h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary active" data-chart-type="line">
                            <i class="bx bx-line-chart"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-chart-type="bar">
                            <i class="bx bx-bar-chart"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="readingsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Distribution Analysis</h5>
                </div>
                <div class="card-body">
                    <canvas id="distributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Readings Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Detailed Readings</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Value</th>
                            <th>Quality</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody id="readingsTableBody">
                        <?php foreach ($readings as $reading): ?>
                            <tr>
                                <td><?= date('M j, Y g:i:s A', strtotime($reading['timestamp'])) ?></td>
                                <td>
                                    <?= number_format($reading['value'], 2) ?>
                                    <small><?= htmlspecialchars($reading['unit']) ?></small>
                                </td>
                                <td>
                                    <div class="progress" style="height: 5px; width: 100px;">
                                        <div class="progress-bar bg-<?= getQualityClass($reading['quality']) ?>"
                                             style="width: <?= $reading['quality'] ?>%"></div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?= getStatusClass($reading['status']) ?>">
                                        <?= ucfirst($reading['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($reading['notes']): ?>
                                        <i class="bx bx-info-circle" data-bs-toggle="tooltip"
                                           title="<?= htmlspecialchars($reading['notes']) ?>"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Readings pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="#" data-page="<?= $currentPage - 1 ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="#" data-page="<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="#" data-page="<?= $currentPage + 1 ?>">Next</a>
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
    const filterForm = document.getElementById('filterForm');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const dataPointSelect = document.getElementById('dataPoint');
    const intervalSelect = document.getElementById('interval');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const exportReadingsBtn = document.getElementById('exportReadings');
    const refreshReadingsBtn = document.getElementById('refreshReadings');
    const chartTypeButtons = document.querySelectorAll('[data-chart-type]');

    // Initialize charts
    const readingsChart = new Chart(document.getElementById('readingsChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= json_encode(array_map(fn($r) => date('M j, g:i A', strtotime($r['timestamp'])), $readings)) ?>,
            datasets: [{
                label: 'Reading Value',
                data: <?= json_encode(array_map(fn($r) => $r['value'], $readings)) ?>,
                borderColor: '#0d6efd',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toFixed(2) + ' ' + 
                                   dataPointSelect.options[dataPointSelect.selectedIndex].dataset.unit;
                        }
                    }
                }
            }
        }
    });

    const distributionChart = new Chart(document.getElementById('distributionChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($distribution['labels']) ?>,
            datasets: [{
                label: 'Frequency',
                data: <?= json_encode($distribution['values']) ?>,
                backgroundColor: '#0d6efd'
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

    // Update charts and table
    function updateData() {
        const params = new URLSearchParams({
            start_date: startDateInput.value,
            end_date: endDateInput.value,
            data_point: dataPointSelect.value,
            interval: intervalSelect.value
        });

        fetch(`<?= url("energy/devices/{$device['id']}/readings/data") ?>?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                // Update readings chart
                readingsChart.data.labels = data.readings.map(r => 
                    new Date(r.timestamp).toLocaleString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: 'numeric'
                    })
                );
                readingsChart.data.datasets[0].data = data.readings.map(r => r.value);
                readingsChart.update();

                // Update distribution chart
                distributionChart.data.labels = data.distribution.labels;
                distributionChart.data.datasets[0].data = data.distribution.values;
                distributionChart.update();

                // Update readings table
                const tableBody = document.getElementById('readingsTableBody');
                tableBody.innerHTML = data.readings.map(reading => `
                    <tr>
                        <td>${new Date(reading.timestamp).toLocaleString()}</td>
                        <td>
                            ${reading.value.toFixed(2)}
                            <small>${reading.unit}</small>
                        </td>
                        <td>
                            <div class="progress" style="height: 5px; width: 100px;">
                                <div class="progress-bar bg-${getQualityClass(reading.quality)}"
                                     style="width: ${reading.quality}%"></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-${getStatusClass(reading.status)}">
                                ${reading.status.charAt(0).toUpperCase() + reading.status.slice(1)}
                            </span>
                        </td>
                        <td>
                            ${reading.notes ? `
                                <i class="bx bx-info-circle" data-bs-toggle="tooltip"
                                   title="${reading.notes}"></i>
                            ` : ''}
                        </td>
                    </tr>
                `).join('');

                // Reinitialize tooltips
                const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltips.map(function (tooltipTrigger) {
                    return new bootstrap.Tooltip(tooltipTrigger);
                });
            });
    }

    // Event handlers
    applyFiltersBtn.addEventListener('click', updateData);

    refreshReadingsBtn.addEventListener('click', function() {
        this.disabled = true;
        updateData();
        setTimeout(() => this.disabled = false, 5000);
    });

    exportReadingsBtn.addEventListener('click', function() {
        const params = new URLSearchParams({
            start_date: startDateInput.value,
            end_date: endDateInput.value,
            data_point: dataPointSelect.value,
            interval: intervalSelect.value,
            format: 'csv'
        });
        window.location.href = `<?= url("energy/devices/{$device['id']}/readings/export") ?>?${params.toString()}`;
    });

    chartTypeButtons.forEach(button => {
        button.addEventListener('click', function() {
            chartTypeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            readingsChart.config.type = this.dataset.chartType;
            readingsChart.update();
        });
    });

    // Set minimum date for end date based on start date
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
    });

    // Auto-refresh readings every minute
    setInterval(updateData, 60000);
});

function getQualityClass(quality) {
    if (quality >= 90) return 'success';
    if (quality >= 70) return 'warning';
    return 'danger';
}

function getStatusClass(status) {
    return {
        'normal': 'success',
        'warning': 'warning',
        'error': 'danger',
        'calibrating': 'info',
        'maintenance': 'secondary'
    }[status] || 'secondary';
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

.progress {
    border-radius: 2px;
}

@media (max-width: 768px) {
    .card:hover {
        transform: none;
    }

    .btn-group {
        width: 100%;
    }

    .btn-group .btn {
        flex: 1;
    }
}
</style> 