<?php $this->layout('layouts/app', ['title' => htmlspecialchars($device['device_name'])]) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0"><?= htmlspecialchars($device['device_name']) ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('energy/devices') ?>">Smart Devices</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($device['device_name']) ?></li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <?php if ($this->user->hasPermission('edit_smart_device')): ?>
                <a href="<?= url("energy/devices/{$device['id']}/edit") ?>" class="btn btn-outline-primary">
                    <i class="bx bx-edit"></i> Edit Device
                </a>
            <?php endif; ?>
            <?php if ($this->user->hasPermission('configure_smart_device')): ?>
                <a href="<?= url("energy/devices/{$device['id']}/configure") ?>" class="btn btn-outline-secondary">
                    <i class="bx bx-cog"></i> Configure
                </a>
            <?php endif; ?>
            <a href="<?= url("energy/devices/{$device['id']}/readings") ?>" class="btn btn-outline-info">
                <i class="bx bx-line-chart"></i> View Readings
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Device Overview -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Device Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th class="ps-0">Type:</th>
                                    <td>
                                        <span class="badge bg-<?= getTypeClass($device['device_type']) ?>">
                                            <?= ucfirst($device['device_type']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Location:</th>
                                    <td><?= htmlspecialchars($device['location']) ?></td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Installation Date:</th>
                                    <td><?= date('M j, Y', strtotime($device['installation_date'])) ?></td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Last Maintenance:</th>
                                    <td>
                                        <?= date('M j, Y', strtotime($device['last_maintenance'])) ?>
                                        <?php if (strtotime($device['next_maintenance']) < strtotime('+30 days')): ?>
                                            <span class="badge bg-warning ms-2">
                                                Maintenance Due
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th class="ps-0">Status:</th>
                                    <td>
                                        <span class="badge bg-<?= getStatusClass($device['status']) ?>">
                                            <?= ucfirst($device['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Protocol:</th>
                                    <td><?= strtoupper($device['protocol']) ?></td>
                                </tr>
                                <tr>
                                    <th class="ps-0">IP Address:</th>
                                    <td><?= htmlspecialchars($device['ip_address']) ?></td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Firmware:</th>
                                    <td>
                                        <?= htmlspecialchars($device['firmware_version']) ?>
                                        <?php if ($device['firmware_update_available']): ?>
                                            <span class="badge bg-warning ms-2">Update Available</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Readings -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Latest Readings</h5>
                    <a href="<?= url("energy/devices/{$device['id']}/readings") ?>" class="btn btn-sm btn-link">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data Point</th>
                                    <th>Last Value</th>
                                    <th>24h Trend</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($device['data_points'] as $point): ?>
                                    <?php if ($point['enabled']): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($point['name']) ?></td>
                                            <td>
                                                <?= number_format($point['last_value'], 2) ?>
                                                <small><?= htmlspecialchars($point['unit']) ?></small>
                                            </td>
                                            <td>
                                                <small class="text-<?= $point['trend'] > 0 ? 'danger' : 'success' ?>">
                                                    <i class="bx bx-trending-<?= $point['trend'] > 0 ? 'up' : 'down' ?>"></i>
                                                    <?= abs($point['trend']) ?>%
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= getStatusClass($point['status']) ?>">
                                                    <?= ucfirst($point['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Alerts -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Alerts</h5>
                    <span class="badge bg-<?= count($alerts) > 0 ? 'danger' : 'success' ?>">
                        <?= count($alerts) ?> Active
                    </span>
                </div>
                <div class="card-body">
                    <?php if (empty($alerts)): ?>
                        <p class="text-muted mb-0">No active alerts</p>
                    <?php else: ?>
                        <div class="timeline">
                            <?php foreach ($alerts as $alert): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-<?= getAlertClass($alert['severity']) ?>"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1"><?= htmlspecialchars($alert['title']) ?></h6>
                                            <small class="text-muted">
                                                <?= timeAgo($alert['timestamp']) ?>
                                            </small>
                                        </div>
                                        <p class="mb-1"><?= htmlspecialchars($alert['message']) ?></p>
                                        <?php if ($alert['status'] === 'active'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-secondary acknowledge-alert"
                                                    data-alert-id="<?= $alert['id'] ?>">
                                                Acknowledge
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <button type="button" class="list-group-item list-group-item-action" id="testConnection">
                            <i class="bx bx-wifi me-2"></i> Test Connection
                        </button>
                        <button type="button" class="list-group-item list-group-item-action" id="calibrateDevice">
                            <i class="bx bx-crosshair me-2"></i> Calibrate Device
                        </button>
                        <button type="button" class="list-group-item list-group-item-action" id="updateFirmware"
                                <?= !$device['firmware_update_available'] ? 'disabled' : '' ?>>
                            <i class="bx bx-upload me-2"></i> Update Firmware
                        </button>
                        <button type="button" class="list-group-item list-group-item-action" id="scheduleMaintenance">
                            <i class="bx bx-calendar me-2"></i> Schedule Maintenance
                        </button>
                    </div>
                </div>
            </div>

            <!-- Health Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Health Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Overall Health</h6>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-<?= getHealthClass($device['health_score']) ?>"
                                 style="width: <?= $device['health_score'] ?>%">
                            </div>
                        </div>
                        <small class="text-muted">
                            Health Score: <?= $device['health_score'] ?>%
                        </small>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Data Quality</h6>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-<?= getQualityClass($device['data_quality']) ?>"
                                 style="width: <?= $device['data_quality'] ?>%">
                            </div>
                        </div>
                        <small class="text-muted">
                            Quality Score: <?= $device['data_quality'] ?>%
                        </small>
                    </div>
                    <div>
                        <h6 class="text-muted mb-2">Uptime</h6>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-<?= getUptimeClass($device['uptime']) ?>"
                                 style="width: <?= $device['uptime'] ?>%">
                            </div>
                        </div>
                        <small class="text-muted">
                            Uptime: <?= $device['uptime'] ?>%
                        </small>
                    </div>
                </div>
            </div>

            <!-- Maintenance History -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Maintenance History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($maintenance_history as $record): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-1"><?= htmlspecialchars($record['type']) ?></h6>
                                    <small class="text-muted">
                                        <?= date('M j, Y', strtotime($record['date'])) ?>
                                    </small>
                                </div>
                                <p class="mb-1"><?= htmlspecialchars($record['description']) ?></p>
                                <small>
                                    By: <?= htmlspecialchars($record['technician']) ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Schedule Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Maintenance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="maintenanceForm">
                    <div class="mb-3">
                        <label for="maintenanceType" class="form-label">Maintenance Type</label>
                        <select class="form-select" id="maintenanceType" required>
                            <option value="routine">Routine Inspection</option>
                            <option value="calibration">Calibration</option>
                            <option value="repair">Repair</option>
                            <option value="upgrade">Hardware Upgrade</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="maintenanceDate" class="form-label">Scheduled Date</label>
                        <input type="date" class="form-control" id="maintenanceDate" required
                               min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="maintenanceNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="maintenanceNotes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="scheduleMaintenanceBtn">Schedule</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const testConnectionBtn = document.getElementById('testConnection');
    const calibrateDeviceBtn = document.getElementById('calibrateDevice');
    const updateFirmwareBtn = document.getElementById('updateFirmware');
    const scheduleMaintBtn = document.getElementById('scheduleMaintenance');
    const maintenanceModal = new bootstrap.Modal(document.getElementById('maintenanceModal'));
    const scheduleMaintenanceBtn = document.getElementById('scheduleMaintenanceBtn');
    const acknowledgeButtons = document.querySelectorAll('.acknowledge-alert');

    // Test Connection
    testConnectionBtn.addEventListener('click', function() {
        this.disabled = true;
        fetch(`<?= url("energy/devices/{$device['id']}/test") ?>`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => response.json())
          .then(data => {
              alert(data.message);
          })
          .finally(() => {
              this.disabled = false;
          });
    });

    // Calibrate Device
    calibrateDeviceBtn.addEventListener('click', function() {
        if (confirm('Start device calibration? This may take several minutes.')) {
            this.disabled = true;
            fetch(`<?= url("energy/devices/{$device['id']}/calibrate") ?>`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      location.reload();
                  } else {
                      alert('Calibration failed: ' + data.message);
                  }
              })
              .finally(() => {
                  this.disabled = false;
              });
        }
    });

    // Update Firmware
    updateFirmwareBtn.addEventListener('click', function() {
        if (confirm('Install firmware update? The device will restart after installation.')) {
            this.disabled = true;
            fetch(`<?= url("energy/devices/{$device['id']}/update") ?>`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      location.reload();
                  } else {
                      alert('Update failed: ' + data.message);
                  }
              })
              .finally(() => {
                  this.disabled = false;
              });
        }
    });

    // Schedule Maintenance
    scheduleMaintBtn.addEventListener('click', function() {
        maintenanceModal.show();
    });

    scheduleMaintenanceBtn.addEventListener('click', function() {
        const form = document.getElementById('maintenanceForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const data = {
            type: document.getElementById('maintenanceType').value,
            date: document.getElementById('maintenanceDate').value,
            notes: document.getElementById('maintenanceNotes').value
        };

        fetch(`<?= url("energy/devices/{$device['id']}/maintenance/schedule") ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  location.reload();
              } else {
                  alert('Failed to schedule maintenance: ' + data.message);
              }
          });
    });

    // Acknowledge Alerts
    acknowledgeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const alertId = this.dataset.alertId;
            fetch(`<?= url("energy/devices/{$device['id']}/alerts/{alertId}/acknowledge") ?>`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      this.closest('.timeline-item').remove();
                  }
              });
        });
    });
});

function getTypeClass(type) {
    return {
        'sensor': 'info',
        'meter': 'primary',
        'controller': 'success',
        'gateway': 'warning'
    }[type] || 'secondary';
}

function getStatusClass(status) {
    return {
        'active': 'success',
        'inactive': 'secondary',
        'maintenance': 'warning',
        'error': 'danger'
    }[status] || 'secondary';
}

function getAlertClass(severity) {
    return {
        'critical': 'danger',
        'warning': 'warning',
        'info': 'info'
    }[severity] || 'secondary';
}

function getHealthClass(score) {
    if (score >= 90) return 'success';
    if (score >= 70) return 'warning';
    return 'danger';
}

function getQualityClass(quality) {
    if (quality >= 90) return 'success';
    if (quality >= 70) return 'warning';
    return 'danger';
}

function getUptimeClass(uptime) {
    if (uptime >= 99) return 'success';
    if (uptime >= 95) return 'warning';
    return 'danger';
}

function timeAgo(timestamp) {
    const seconds = Math.floor((new Date() - new Date(timestamp)) / 1000);
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
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.list-group-item {
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
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
}

.timeline-content {
    padding-bottom: 24px;
    border-bottom: 1px solid #e9ecef;
}

.timeline-item:last-child .timeline-content {
    border-bottom: none;
    padding-bottom: 0;
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