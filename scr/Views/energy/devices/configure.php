<?php $this->layout('layouts/app', ['title' => 'Configure Device']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <h4 class="mb-0">Configure Device</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                <li class="breadcrumb-item"><a href="<?= url('energy/devices') ?>">Smart Devices</a></li>
                <li class="breadcrumb-item">
                    <a href="<?= url("energy/devices/{$device['id']}") ?>">
                        <?= htmlspecialchars($device['device_name']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item active">Configure</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Device Configuration -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Device Configuration</h5>
                </div>
                <div class="card-body">
                    <form id="configForm" action="<?= url("energy/devices/{$device['id']}/configure") ?>" method="POST">
                        <!-- Network Settings -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Network Settings</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="ip_address" class="form-label">IP Address</label>
                                    <input type="text" class="form-control" id="ip_address" name="ip_address"
                                           value="<?= htmlspecialchars($device['ip_address']) ?>"
                                           pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$">
                                </div>
                                <div class="col-md-6">
                                    <label for="protocol" class="form-label">Communication Protocol</label>
                                    <select class="form-select" id="protocol" name="protocol">
                                        <option value="mqtt" <?= $device['protocol'] === 'mqtt' ? 'selected' : '' ?>>MQTT</option>
                                        <option value="modbus" <?= $device['protocol'] === 'modbus' ? 'selected' : '' ?>>Modbus</option>
                                        <option value="bacnet" <?= $device['protocol'] === 'bacnet' ? 'selected' : '' ?>>BACnet</option>
                                        <option value="http" <?= $device['protocol'] === 'http' ? 'selected' : '' ?>>HTTP/REST</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="port" class="form-label">Port</label>
                                    <input type="number" class="form-control" id="port" name="port"
                                           value="<?= htmlspecialchars($device['port']) ?>"
                                           min="1" max="65535">
                                </div>
                                <div class="col-md-6">
                                    <label for="update_interval" class="form-label">Update Interval (seconds)</label>
                                    <input type="number" class="form-control" id="update_interval" name="update_interval"
                                           value="<?= htmlspecialchars($device['update_interval']) ?>"
                                           min="1">
                                </div>
                            </div>
                        </div>

                        <!-- Data Collection -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Data Collection</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Data Points</label>
                                    <div class="table-responsive">
                                        <table class="table table-sm" id="dataPointsTable">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Unit</th>
                                                    <th>Enabled</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($device['data_points'] as $point): ?>
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm"
                                                                   name="points[<?= $point['id'] ?>][name]"
                                                                   value="<?= htmlspecialchars($point['name']) ?>">
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm"
                                                                    name="points[<?= $point['id'] ?>][type]">
                                                                <option value="numeric" <?= $point['type'] === 'numeric' ? 'selected' : '' ?>>Numeric</option>
                                                                <option value="boolean" <?= $point['type'] === 'boolean' ? 'selected' : '' ?>>Boolean</option>
                                                                <option value="string" <?= $point['type'] === 'string' ? 'selected' : '' ?>>String</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm"
                                                                   name="points[<?= $point['id'] ?>][unit]"
                                                                   value="<?= htmlspecialchars($point['unit']) ?>">
                                                        </td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                       name="points[<?= $point['id'] ?>][enabled]"
                                                                       <?= $point['enabled'] ? 'checked' : '' ?>>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-outline-danger remove-point">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="addDataPoint">
                                        <i class="bx bx-plus"></i> Add Data Point
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Alerts & Thresholds -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Alerts & Thresholds</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="alerts_enabled"
                                               name="alerts_enabled" <?= $device['alerts_enabled'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="alerts_enabled">Enable Alerts</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-2"></i>
                                        Configure thresholds for each data point in the device settings to trigger alerts.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Settings -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Advanced Settings</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="custom_config" class="form-label">Custom Configuration</label>
                                    <textarea class="form-control font-monospace" id="custom_config" name="custom_config"
                                              rows="5" placeholder="Enter JSON configuration..."><?= htmlspecialchars($device['custom_config']) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-warning" id="restartDevice">
                                <i class="bx bx-refresh"></i> Restart Device
                            </button>
                            <div>
                                <a href="<?= url("energy/devices/{$device['id']}") ?>" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Save Configuration
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Device Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Device Status</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted d-block">Connection Status</small>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-wifi me-2"></i>
                                <span class="badge bg-<?= $device['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($device['status']) ?>
                                </span>
                            </div>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">Last Communication</small>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-time me-2"></i>
                                <?= date('M j, Y g:i A', strtotime($device['last_communication'])) ?>
                            </div>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">Firmware Version</small>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-chip me-2"></i>
                                <?= htmlspecialchars($device['firmware_version']) ?>
                                <?php if ($device['firmware_update_available']): ?>
                                    <span class="badge bg-warning ms-2">Update Available</span>
                                <?php endif; ?>
                            </div>
                        </li>
                        <li>
                            <small class="text-muted d-block">Data Points</small>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-data me-2"></i>
                                <?= count(array_filter($device['data_points'], fn($p) => $p['enabled'])) ?> Active /
                                <?= count($device['data_points']) ?> Total
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Connection Log -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Connection Log</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshLog">
                        <i class="bx bx-refresh"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="connection-log" style="max-height: 300px; overflow-y: auto;">
                        <?php foreach ($device['connection_log'] as $log): ?>
                            <div class="log-entry mb-2">
                                <small class="text-muted">
                                    <?= date('g:i:s A', strtotime($log['timestamp'])) ?>
                                </small>
                                <div class="log-message text-<?= $log['type'] === 'error' ? 'danger' : 
                                                              ($log['type'] === 'warning' ? 'warning' : 'dark') ?>">
                                    <?= htmlspecialchars($log['message']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Restart Confirmation Modal -->
<div class="modal fade" id="restartModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Restart Device</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to restart this device? This will temporarily interrupt data collection.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmRestart">
                    <i class="bx bx-refresh"></i> Restart Device
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const configForm = document.getElementById('configForm');
    const dataPointsTable = document.getElementById('dataPointsTable');
    const addDataPointBtn = document.getElementById('addDataPoint');
    const restartDeviceBtn = document.getElementById('restartDevice');
    const confirmRestartBtn = document.getElementById('confirmRestart');
    const refreshLogBtn = document.getElementById('refreshLog');
    const restartModal = new bootstrap.Modal(document.getElementById('restartModal'));

    // Add new data point
    addDataPointBtn.addEventListener('click', function() {
        const newRow = document.createElement('tr');
        const pointId = 'new_' + Date.now();
        newRow.innerHTML = `
            <td>
                <input type="text" class="form-control form-control-sm"
                       name="points[${pointId}][name]" required>
            </td>
            <td>
                <select class="form-select form-select-sm" name="points[${pointId}][type]">
                    <option value="numeric">Numeric</option>
                    <option value="boolean">Boolean</option>
                    <option value="string">String</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm"
                       name="points[${pointId}][unit]">
            </td>
            <td>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input"
                           name="points[${pointId}][enabled]" checked>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger remove-point">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;
        dataPointsTable.querySelector('tbody').appendChild(newRow);
    });

    // Remove data point
    dataPointsTable.addEventListener('click', function(e) {
        if (e.target.closest('.remove-point')) {
            if (confirm('Are you sure you want to remove this data point?')) {
                e.target.closest('tr').remove();
            }
        }
    });

    // Form validation
    configForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate IP address
        const ipInput = document.getElementById('ip_address');
        if (ipInput.value && !ipInput.checkValidity()) {
            alert('Please enter a valid IP address');
            ipInput.focus();
            return;
        }

        // Validate custom configuration JSON
        const customConfig = document.getElementById('custom_config');
        if (customConfig.value) {
            try {
                JSON.parse(customConfig.value);
            } catch (error) {
                alert('Invalid JSON in custom configuration');
                customConfig.focus();
                return;
            }
        }

        // Submit form
        this.submit();
    });

    // Device restart
    restartDeviceBtn.addEventListener('click', function() {
        restartModal.show();
    });

    confirmRestartBtn.addEventListener('click', function() {
        fetch(`<?= url("energy/devices/{$device['id']}/restart") ?>`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  location.reload();
              } else {
                  alert('Failed to restart device: ' + data.message);
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('Failed to restart device');
          })
          .finally(() => {
              restartModal.hide();
          });
    });

    // Refresh connection log
    refreshLogBtn.addEventListener('click', function() {
        fetch(`<?= url("energy/devices/{$device['id']}/log") ?>`)
            .then(response => response.json())
            .then(data => {
                const logContainer = document.querySelector('.connection-log');
                logContainer.innerHTML = data.log.map(entry => `
                    <div class="log-entry mb-2">
                        <small class="text-muted">
                            ${new Date(entry.timestamp).toLocaleTimeString()}
                        </small>
                        <div class="log-message text-${entry.type === 'error' ? 'danger' : 
                                                      (entry.type === 'warning' ? 'warning' : 'dark')}">
                            ${entry.message}
                        </div>
                    </div>
                `).join('');
            });
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

.connection-log {
    font-family: monospace;
    font-size: 0.875rem;
}

.log-entry {
    border-left: 3px solid #e9ecef;
    padding-left: 0.5rem;
}

.log-message {
    word-break: break-word;
}

@media (max-width: 768px) {
    .card:hover {
        transform: none;
    }
}
</style> 