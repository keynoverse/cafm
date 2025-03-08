<?php $this->layout('layouts/app', ['title' => 'Add Smart Device']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <h4 class="mb-0">Add Smart Device</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                <li class="breadcrumb-item"><a href="<?= url('energy/devices') ?>">Smart Devices</a></li>
                <li class="breadcrumb-item active">Add Device</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <form id="deviceForm" action="<?= url('energy/devices') ?>" method="POST" class="needs-validation" novalidate>
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="device_name" class="form-label">Device Name</label>
                                <input type="text" class="form-control" id="device_name" name="device_name"
                                       required maxlength="100">
                                <div class="invalid-feedback">
                                    Please provide a device name.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="device_type" class="form-label">Device Type</label>
                                <select class="form-select" id="device_type" name="device_type" required>
                                    <option value="">Select Type</option>
                                    <option value="sensor">Sensor</option>
                                    <option value="meter">Meter</option>
                                    <option value="controller">Controller</option>
                                    <option value="gateway">Gateway</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a device type.
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location"
                                       required maxlength="200">
                                <div class="invalid-feedback">
                                    Please provide a location.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="asset_id" class="form-label">Associated Asset</label>
                                <select class="form-select" id="asset_id" name="asset_id">
                                    <option value="">Select Asset</option>
                                    <?php foreach ($assets as $asset): ?>
                                        <option value="<?= $asset['id'] ?>">
                                            <?= htmlspecialchars($asset['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="installation_date" class="form-label">Installation Date</label>
                                <input type="date" class="form-control" id="installation_date" name="installation_date"
                                       value="<?= date('Y-m-d') ?>" required>
                                <div class="invalid-feedback">
                                    Please provide an installation date.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Network Configuration -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Network Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="protocol" class="form-label">Communication Protocol</label>
                                <select class="form-select" id="protocol" name="protocol" required>
                                    <option value="">Select Protocol</option>
                                    <option value="mqtt">MQTT</option>
                                    <option value="modbus">Modbus</option>
                                    <option value="bacnet">BACnet</option>
                                    <option value="http">HTTP/REST</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a protocol.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="ip_address" class="form-label">IP Address</label>
                                <input type="text" class="form-control" id="ip_address" name="ip_address"
                                       pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                                       required>
                                <div class="invalid-feedback">
                                    Please provide a valid IP address.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="port" class="form-label">Port</label>
                                <input type="number" class="form-control" id="port" name="port"
                                       min="1" max="65535" required>
                                <div class="invalid-feedback">
                                    Please provide a valid port number (1-65535).
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="mac_address" class="form-label">MAC Address</label>
                                <input type="text" class="form-control" id="mac_address" name="mac_address"
                                       pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$">
                                <div class="invalid-feedback">
                                    Please provide a valid MAC address.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Points -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Data Points</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addDataPoint">
                            <i class="bx bx-plus"></i> Add Data Point
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataPointsTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Unit</th>
                                        <th>Update Interval (s)</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data points will be added here dynamically -->
                                </tbody>
                            </table>
                        </div>
                        <div id="noDataPoints" class="text-center text-muted py-3">
                            No data points configured. Click "Add Data Point" to begin.
                        </div>
                    </div>
                </div>

                <!-- Advanced Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Advanced Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="firmware_version" class="form-label">Firmware Version</label>
                                <input type="text" class="form-control" id="firmware_version" name="firmware_version">
                            </div>
                            <div class="col-md-6">
                                <label for="update_interval" class="form-label">Default Update Interval (seconds)</label>
                                <input type="number" class="form-control" id="update_interval" name="update_interval"
                                       min="1" value="60">
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="alerts_enabled"
                                           name="alerts_enabled" checked>
                                    <label class="form-check-label" for="alerts_enabled">Enable Alerts</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="custom_config" class="form-label">Custom Configuration (JSON)</label>
                                <textarea class="form-control font-monospace" id="custom_config" name="custom_config"
                                          rows="3" placeholder="Enter JSON configuration..."></textarea>
                                <div class="invalid-feedback">
                                    Please provide valid JSON configuration.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-primary" id="testConnection">
                                <i class="bx bx-wifi"></i> Test Connection
                            </button>
                            <div>
                                <a href="<?= url('energy/devices') ?>" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Save Device
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Connection Status -->
            <div class="card mb-4" id="connectionStatus" style="display: none;">
                <div class="card-header">
                    <h5 class="card-title mb-0">Connection Status</h5>
                </div>
                <div class="card-body">
                    <div id="connectionDetails"></div>
                </div>
            </div>

            <!-- Help & Guidelines -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Help & Guidelines</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Device Types</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <span class="badge bg-info">Sensor</span>
                                <small class="d-block text-muted">
                                    Collects environmental or equipment data
                                </small>
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-primary">Meter</span>
                                <small class="d-block text-muted">
                                    Measures energy consumption or utility usage
                                </small>
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-success">Controller</span>
                                <small class="d-block text-muted">
                                    Controls equipment or systems
                                </small>
                            </li>
                            <li>
                                <span class="badge bg-warning">Gateway</span>
                                <small class="d-block text-muted">
                                    Connects multiple devices or networks
                                </small>
                            </li>
                        </ul>
                    </div>
                    <div class="mb-4">
                        <h6>Protocols</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong>MQTT:</strong>
                                <small class="d-block text-muted">
                                    Lightweight messaging protocol for IoT devices
                                </small>
                            </li>
                            <li class="mb-2">
                                <strong>Modbus:</strong>
                                <small class="d-block text-muted">
                                    Industrial communication protocol
                                </small>
                            </li>
                            <li class="mb-2">
                                <strong>BACnet:</strong>
                                <small class="d-block text-muted">
                                    Building automation and control protocol
                                </small>
                            </li>
                            <li>
                                <strong>HTTP/REST:</strong>
                                <small class="d-block text-muted">
                                    Web-based API communication
                                </small>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h6>Tips</h6>
                        <ul class="text-muted small">
                            <li>Use descriptive names for easy identification</li>
                            <li>Configure data points based on device capabilities</li>
                            <li>Test connection before saving</li>
                            <li>Enable alerts for important data points</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deviceForm = document.getElementById('deviceForm');
    const dataPointsTable = document.getElementById('dataPointsTable');
    const addDataPointBtn = document.getElementById('addDataPoint');
    const noDataPointsDiv = document.getElementById('noDataPoints');
    const testConnectionBtn = document.getElementById('testConnection');
    const connectionStatus = document.getElementById('connectionStatus');
    const connectionDetails = document.getElementById('connectionDetails');
    const customConfig = document.getElementById('custom_config');

    // Form validation
    deviceForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!this.checkValidity()) {
            e.stopPropagation();
            this.classList.add('was-validated');
            return;
        }

        // Validate custom configuration JSON
        if (customConfig.value) {
            try {
                JSON.parse(customConfig.value);
            } catch (error) {
                customConfig.setCustomValidity('Invalid JSON format');
                customConfig.reportValidity();
                return;
            }
        }

        // Validate at least one data point
        if (dataPointsTable.querySelector('tbody').children.length === 0) {
            alert('Please add at least one data point');
            return;
        }

        // Submit form
        this.submit();
    });

    // Add data point
    addDataPointBtn.addEventListener('click', function() {
        const pointId = 'new_' + Date.now();
        const row = document.createElement('tr');
        row.innerHTML = `
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
                <input type="number" class="form-control form-control-sm"
                       name="points[${pointId}][interval]"
                       value="60" min="1">
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger remove-point">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;
        dataPointsTable.querySelector('tbody').appendChild(row);
        noDataPointsDiv.style.display = 'none';
    });

    // Remove data point
    dataPointsTable.addEventListener('click', function(e) {
        if (e.target.closest('.remove-point')) {
            e.target.closest('tr').remove();
            if (dataPointsTable.querySelector('tbody').children.length === 0) {
                noDataPointsDiv.style.display = 'block';
            }
        }
    });

    // Test connection
    testConnectionBtn.addEventListener('click', function() {
        const ipAddress = document.getElementById('ip_address').value;
        const port = document.getElementById('port').value;
        const protocol = document.getElementById('protocol').value;

        if (!ipAddress || !port || !protocol) {
            alert('Please fill in network configuration details first');
            return;
        }

        this.disabled = true;
        connectionStatus.style.display = 'none';

        fetch(`<?= url('energy/devices/test-connection') ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                ip_address: ipAddress,
                port: port,
                protocol: protocol
            })
        }).then(response => response.json())
          .then(data => {
              connectionStatus.style.display = 'block';
              connectionDetails.innerHTML = `
                  <div class="d-flex align-items-center mb-2">
                      <i class="bx bx-wifi me-2"></i>
                      <span class="badge bg-${data.success ? 'success' : 'danger'}">
                          ${data.success ? 'Connected' : 'Failed'}
                      </span>
                  </div>
                  <p class="mb-0 small">${data.message}</p>
              `;
          })
          .finally(() => {
              this.disabled = false;
          });
    });

    // Protocol-specific port defaults
    document.getElementById('protocol').addEventListener('change', function() {
        const portInput = document.getElementById('port');
        const defaultPorts = {
            'mqtt': 1883,
            'modbus': 502,
            'bacnet': 47808,
            'http': 80
        };
        if (defaultPorts[this.value] && !portInput.value) {
            portInput.value = defaultPorts[this.value];
        }
    });

    // MAC address formatting
    document.getElementById('mac_address').addEventListener('input', function() {
        let value = this.value.replace(/[^0-9A-Fa-f]/g, '');
        let formatted = '';
        for (let i = 0; i < value.length && i < 12; i++) {
            if (i > 0 && i % 2 === 0) formatted += ':';
            formatted += value[i];
        }
        this.value = formatted;
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

@media (max-width: 768px) {
    .card:hover {
        transform: none;
    }
}
</style> 