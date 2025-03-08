<?php $this->layout('layouts/app', ['title' => 'Device Alerts - ' . htmlspecialchars($device['device_name'])]) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <h4 class="mb-0">Device Alerts</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                <li class="breadcrumb-item"><a href="<?= url('energy/devices') ?>">Smart Devices</a></li>
                <li class="breadcrumb-item">
                    <a href="<?= url("energy/devices/{$device['id']}") ?>">
                        <?= htmlspecialchars($device['device_name']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item active">Alerts</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Alert Rules -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Alert Rules</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRuleModal">
                        <i class="bx bx-plus"></i> Add Rule
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data Point</th>
                                    <th>Condition</th>
                                    <th>Threshold</th>
                                    <th>Duration</th>
                                    <th>Severity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alert_rules as $rule): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($rule['data_point_name']) ?></td>
                                        <td>
                                            <?= ucfirst($rule['condition']) ?>
                                            <?php if ($rule['condition'] === 'between'): ?>
                                                (<?= $rule['min_value'] ?> - <?= $rule['max_value'] ?>)
                                            <?php else: ?>
                                                <?= $rule['threshold'] ?>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($rule['unit']) ?>
                                        </td>
                                        <td>
                                            <?php if ($rule['condition'] === 'between'): ?>
                                                <?= $rule['min_value'] ?> - <?= $rule['max_value'] ?>
                                            <?php else: ?>
                                                <?= $rule['threshold'] ?>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($rule['unit']) ?>
                                        </td>
                                        <td>
                                            <?php if ($rule['duration']): ?>
                                                <?= $rule['duration'] ?> seconds
                                            <?php else: ?>
                                                Immediate
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= getSeverityClass($rule['severity']) ?>">
                                                <?= ucfirst($rule['severity']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input toggle-rule"
                                                       data-rule-id="<?= $rule['id'] ?>"
                                                       <?= $rule['enabled'] ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary edit-rule"
                                                        data-rule="<?= htmlspecialchars(json_encode($rule)) ?>">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger delete-rule"
                                                        data-rule-id="<?= $rule['id'] ?>">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (empty($alert_rules)): ?>
                        <div class="text-center text-muted py-3">
                            No alert rules configured. Click "Add Rule" to create one.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Alerts -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Alerts</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($recent_alerts as $alert): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-<?= getSeverityClass($alert['severity']) ?>"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1"><?= htmlspecialchars($alert['title']) ?></h6>
                                        <small class="text-muted">
                                            <?= timeAgo($alert['timestamp']) ?>
                                        </small>
                                    </div>
                                    <p class="mb-1"><?= htmlspecialchars($alert['message']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            Value: <?= number_format($alert['value'], 2) ?> <?= htmlspecialchars($alert['unit']) ?>
                                        </small>
                                        <?php if ($alert['status'] === 'active'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-secondary acknowledge-alert"
                                                    data-alert-id="<?= $alert['id'] ?>">
                                                Acknowledge
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (empty($recent_alerts)): ?>
                        <div class="text-center text-muted py-3">
                            No recent alerts.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Alert Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Alert Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="alert-stat">
                                <h6 class="text-muted mb-2">Active Alerts</h6>
                                <h3 class="mb-0">
                                    <?= $stats['active_alerts'] ?>
                                    <small class="text-<?= $stats['active_alerts'] > 0 ? 'danger' : 'success' ?>">
                                        <i class="bx bx-<?= $stats['active_alerts'] > 0 ? 'bell' : 'check-circle' ?>"></i>
                                    </small>
                                </h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="alert-stat">
                                <h6 class="text-muted mb-2">Last 24h</h6>
                                <h3 class="mb-0">
                                    <?= $stats['alerts_24h'] ?>
                                    <small class="text-muted">
                                        <i class="bx bx-time"></i>
                                    </small>
                                </h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="alert-stat">
                                <h6 class="text-muted mb-2">This Week</h6>
                                <h3 class="mb-0">
                                    <?= $stats['alerts_week'] ?>
                                    <small class="text-<?= $stats['week_trend'] > 0 ? 'danger' : 'success' ?>">
                                        <?= abs($stats['week_trend']) ?>%
                                    </small>
                                </h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="alert-stat">
                                <h6 class="text-muted mb-2">Avg Response</h6>
                                <h3 class="mb-0">
                                    <?= formatDuration($stats['avg_response_time']) ?>
                                    <small class="text-muted">
                                        <i class="bx bx-timer"></i>
                                    </small>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- Alert Distribution -->
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Alert Distribution</h6>
                        <div class="alert-distribution">
                            <?php foreach ($stats['distribution'] as $severity => $count): ?>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small>
                                            <span class="badge bg-<?= getSeverityClass($severity) ?>">
                                                <?= ucfirst($severity) ?>
                                            </span>
                                        </small>
                                        <small class="text-muted"><?= $count ?></small>
                                    </div>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-<?= getSeverityClass($severity) ?>"
                                             style="width: <?= ($count / $stats['total_alerts']) * 100 ?>%">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notification Settings</h5>
                </div>
                <div class="card-body">
                    <form id="notificationForm" action="<?= url("energy/devices/{$device['id']}/alerts/notifications") ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Notification Methods</label>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="email_notifications"
                                       name="notifications[email]" <?= $notifications['email'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="email_notifications">
                                    Email Notifications
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="sms_notifications"
                                       name="notifications[sms]" <?= $notifications['sms'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sms_notifications">
                                    SMS Notifications
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="push_notifications"
                                       name="notifications[push]" <?= $notifications['push'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="push_notifications">
                                    Push Notifications
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notification Levels</label>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="critical_notifications"
                                       name="notifications[levels][critical]" <?= $notifications['levels']['critical'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="critical_notifications">
                                    Critical Alerts
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="warning_notifications"
                                       name="notifications[levels][warning]" <?= $notifications['levels']['warning'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="warning_notifications">
                                    Warning Alerts
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="info_notifications"
                                       name="notifications[levels][info]" <?= $notifications['levels']['info'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="info_notifications">
                                    Info Alerts
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="quiet_hours" class="form-label">Quiet Hours</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="time" class="form-control" id="quiet_hours_start"
                                           name="notifications[quiet_hours][start]"
                                           value="<?= $notifications['quiet_hours']['start'] ?>">
                                </div>
                                <div class="col-6">
                                    <input type="time" class="form-control" id="quiet_hours_end"
                                           name="notifications[quiet_hours][end]"
                                           value="<?= $notifications['quiet_hours']['end'] ?>">
                                </div>
                            </div>
                            <small class="text-muted">
                                Only critical alerts will be sent during quiet hours
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Save Notification Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Rule Modal -->
<div class="modal fade" id="ruleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ruleModalTitle">Add Alert Rule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="ruleForm">
                    <input type="hidden" id="ruleId" name="id">
                    <div class="mb-3">
                        <label for="dataPoint" class="form-label">Data Point</label>
                        <select class="form-select" id="dataPoint" name="data_point_id" required>
                            <option value="">Select Data Point</option>
                            <?php foreach ($device['data_points'] as $point): ?>
                                <?php if ($point['enabled']): ?>
                                    <option value="<?= $point['id'] ?>" data-unit="<?= htmlspecialchars($point['unit']) ?>">
                                        <?= htmlspecialchars($point['name']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="condition" class="form-label">Condition</label>
                        <select class="form-select" id="condition" name="condition" required>
                            <option value="above">Above</option>
                            <option value="below">Below</option>
                            <option value="equal">Equal to</option>
                            <option value="between">Between</option>
                            <option value="not_between">Not Between</option>
                        </select>
                    </div>
                    <div class="mb-3" id="singleThreshold">
                        <label for="threshold" class="form-label">Threshold</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="threshold" name="threshold" step="any">
                            <span class="input-group-text unit-label"></span>
                        </div>
                    </div>
                    <div class="mb-3 d-none" id="rangeThreshold">
                        <label class="form-label">Range</label>
                        <div class="row g-2">
                            <div class="col">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="minValue" name="min_value" step="any"
                                           placeholder="Min">
                                    <span class="input-group-text unit-label"></span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="maxValue" name="max_value" step="any"
                                           placeholder="Max">
                                    <span class="input-group-text unit-label"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration (seconds)</label>
                        <input type="number" class="form-control" id="duration" name="duration" min="0"
                               placeholder="Leave empty for immediate alert">
                        <small class="text-muted">
                            Condition must persist for this duration before triggering an alert
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="severity" class="form-label">Severity</label>
                        <select class="form-select" id="severity" name="severity" required>
                            <option value="info">Info</option>
                            <option value="warning">Warning</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Alert Message</label>
                        <textarea class="form-control" id="message" name="message" rows="2" required></textarea>
                        <small class="text-muted">
                            Use {value} and {unit} placeholders in your message
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveRule">Save Rule</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ruleModal = new bootstrap.Modal(document.getElementById('ruleModal'));
    const ruleForm = document.getElementById('ruleForm');
    const saveRuleBtn = document.getElementById('saveRule');
    const dataPointSelect = document.getElementById('dataPoint');
    const conditionSelect = document.getElementById('condition');
    const singleThresholdDiv = document.getElementById('singleThreshold');
    const rangeThresholdDiv = document.getElementById('rangeThreshold');
    const unitLabels = document.querySelectorAll('.unit-label');
    const notificationForm = document.getElementById('notificationForm');

    // Update unit labels when data point changes
    dataPointSelect.addEventListener('change', function() {
        const unit = this.options[this.selectedIndex].dataset.unit || '';
        unitLabels.forEach(label => label.textContent = unit);
    });

    // Toggle threshold inputs based on condition
    conditionSelect.addEventListener('change', function() {
        const isRange = this.value === 'between' || this.value === 'not_between';
        singleThresholdDiv.classList.toggle('d-none', isRange);
        rangeThresholdDiv.classList.toggle('d-none', !isRange);
    });

    // Add/Edit Rule
    document.querySelectorAll('.edit-rule').forEach(button => {
        button.addEventListener('click', function() {
            const rule = JSON.parse(this.dataset.rule);
            document.getElementById('ruleModalTitle').textContent = 'Edit Alert Rule';
            document.getElementById('ruleId').value = rule.id;
            document.getElementById('dataPoint').value = rule.data_point_id;
            document.getElementById('condition').value = rule.condition;
            document.getElementById('threshold').value = rule.threshold;
            document.getElementById('minValue').value = rule.min_value;
            document.getElementById('maxValue').value = rule.max_value;
            document.getElementById('duration').value = rule.duration;
            document.getElementById('severity').value = rule.severity;
            document.getElementById('message').value = rule.message;
            
            // Trigger change events to update UI
            dataPointSelect.dispatchEvent(new Event('change'));
            conditionSelect.dispatchEvent(new Event('change'));
            
            ruleModal.show();
        });
    });

    // Save Rule
    saveRuleBtn.addEventListener('click', function() {
        if (!ruleForm.checkValidity()) {
            ruleForm.reportValidity();
            return;
        }

        const formData = new FormData(ruleForm);
        const ruleId = formData.get('id');
        const url = ruleId ? 
            `<?= url("energy/devices/{$device['id']}/alerts/rules") ?>/${ruleId}` :
            `<?= url("energy/devices/{$device['id']}/alerts/rules") ?>`;

        fetch(url, {
            method: ruleId ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  location.reload();
              } else {
                  alert('Failed to save rule: ' + data.message);
              }
          });
    });

    // Delete Rule
    document.querySelectorAll('.delete-rule').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this alert rule?')) {
                const ruleId = this.dataset.ruleId;
                fetch(`<?= url("energy/devices/{$device['id']}/alerts/rules") ?>/${ruleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          location.reload();
                      } else {
                          alert('Failed to delete rule: ' + data.message);
                      }
                  });
            }
        });
    });

    // Toggle Rule
    document.querySelectorAll('.toggle-rule').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const ruleId = this.dataset.ruleId;
            fetch(`<?= url("energy/devices/{$device['id']}/alerts/rules") ?>/${ruleId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    enabled: this.checked
                })
            }).then(response => response.json())
              .then(data => {
                  if (!data.success) {
                      this.checked = !this.checked;
                      alert('Failed to update rule status: ' + data.message);
                  }
              });
        });
    });

    // Acknowledge Alert
    document.querySelectorAll('.acknowledge-alert').forEach(button => {
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

    // Save Notification Settings
    notificationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(Object.fromEntries(new FormData(this)))
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Notification settings saved successfully');
              } else {
                  alert('Failed to save notification settings: ' + data.message);
              }
          });
    });
});

function getSeverityClass(severity) {
    return {
        'critical': 'danger',
        'warning': 'warning',
        'info': 'info'
    }[severity] || 'secondary';
}

function formatDuration(seconds) {
    if (seconds < 60) return seconds + 's';
    if (seconds < 3600) return Math.floor(seconds / 60) + 'm';
    return Math.floor(seconds / 3600) + 'h';
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

.alert-stat {
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
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
}
</style> 