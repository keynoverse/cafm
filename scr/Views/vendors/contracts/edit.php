<?php $this->layout('layouts/app', ['title' => 'Edit Contract - ' . htmlspecialchars($contract['title'])]) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Edit Contract</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('vendors') ?>">Vendor Directory</a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}") ?>"><?= htmlspecialchars($vendor['company_name']) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}/contracts") ?>">Contracts</a></li>
                    <li class="breadcrumb-item active">Edit Contract</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if ($contract['status'] === 'active'): ?>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#terminateModal">
                    <i class="bx bx-x-circle"></i> Terminate
                </button>
            <?php endif; ?>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i> More Actions
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>">
                            <i class="bx bx-show me-2"></i> View Details
                        </a>
                    </li>
                    <?php if ($contract['status'] === 'active'): ?>
                        <li>
                            <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/renew") ?>">
                                <i class="bx bx-refresh me-2"></i> Renew Contract
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/history") ?>">
                            <i class="bx bx-history me-2"></i> View History
                        </a>
                    </li>
                    <?php if (hasPermission('delete_contract')): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger delete-contract" href="#" data-id="<?= $contract['id'] ?>">
                                <i class="bx bx-trash me-2"></i> Delete Contract
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <form id="contractForm" action="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT">
                
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Basic Information</h5>
                        <span class="badge bg-<?= getContractStatusClass($contract['status']) ?>">
                            <?= ucfirst($contract['status']) ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contract_number" class="form-label">Contract Number</label>
                                <input type="text" class="form-control" id="contract_number" name="contract_number" value="<?= htmlspecialchars($contract['contract_number']) ?>" readonly>
                                <div class="form-text">Contract number cannot be modified</div>
                            </div>
                            <div class="col-md-6">
                                <label for="title" class="form-label">Contract Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($contract['title']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= date('Y-m-d', strtotime($contract['start_date'])) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= date('Y-m-d', strtotime($contract['end_date'])) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="contract_type" class="form-label">Contract Type</label>
                                <select class="form-select" id="contract_type" name="contract_type" required>
                                    <option value="">Select Type</option>
                                    <option value="service" <?= $contract['contract_type'] === 'service' ? 'selected' : '' ?>>Service Contract</option>
                                    <option value="maintenance" <?= $contract['contract_type'] === 'maintenance' ? 'selected' : '' ?>>Maintenance Contract</option>
                                    <option value="supply" <?= $contract['contract_type'] === 'supply' ? 'selected' : '' ?>>Supply Contract</option>
                                    <option value="lease" <?= $contract['contract_type'] === 'lease' ? 'selected' : '' ?>>Lease Agreement</option>
                                    <option value="license" <?= $contract['contract_type'] === 'license' ? 'selected' : '' ?>>License Agreement</option>
                                    <option value="other" <?= $contract['contract_type'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft" <?= $contract['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="active" <?= $contract['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="expired" <?= $contract['status'] === 'expired' ? 'selected' : '' ?>>Expired</option>
                                    <option value="terminated" <?= $contract['status'] === 'terminated' ? 'selected' : '' ?>>Terminated</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Financial Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contract_value" class="form-label">Contract Value</label>
                                <div class="input-group">
                                    <span class="input-group-text"><?= $currency ?></span>
                                    <input type="number" class="form-control" id="contract_value" name="contract_value" step="0.01" value="<?= $contract['contract_value'] ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="billing_frequency" class="form-label">Billing Frequency</label>
                                <select class="form-select" id="billing_frequency" name="billing_frequency">
                                    <option value="">Select Frequency</option>
                                    <option value="one_time" <?= $contract['billing_frequency'] === 'one_time' ? 'selected' : '' ?>>One Time</option>
                                    <option value="monthly" <?= $contract['billing_frequency'] === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                    <option value="quarterly" <?= $contract['billing_frequency'] === 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                                    <option value="semi_annual" <?= $contract['billing_frequency'] === 'semi_annual' ? 'selected' : '' ?>>Semi-Annual</option>
                                    <option value="annual" <?= $contract['billing_frequency'] === 'annual' ? 'selected' : '' ?>>Annual</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="payment_terms" class="form-label">Payment Terms</label>
                                <select class="form-select" id="payment_terms" name="payment_terms">
                                    <option value="">Select Terms</option>
                                    <option value="immediate" <?= $contract['payment_terms'] === 'immediate' ? 'selected' : '' ?>>Immediate</option>
                                    <option value="net_15" <?= $contract['payment_terms'] === 'net_15' ? 'selected' : '' ?>>Net 15</option>
                                    <option value="net_30" <?= $contract['payment_terms'] === 'net_30' ? 'selected' : '' ?>>Net 30</option>
                                    <option value="net_45" <?= $contract['payment_terms'] === 'net_45' ? 'selected' : '' ?>>Net 45</option>
                                    <option value="net_60" <?= $contract['payment_terms'] === 'net_60' ? 'selected' : '' ?>>Net 60</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                                <input type="number" class="form-control" id="tax_rate" name="tax_rate" step="0.01" min="0" max="100" value="<?= $contract['tax_rate'] ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contract Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Contract Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($contract['description']) ?></textarea>
                            </div>
                            <div class="col-12">
                                <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                                <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="5"><?= htmlspecialchars($contract['terms_conditions']) ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Current Contract Document</label>
                                <?php if ($contract['file_path']): ?>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <a href="<?= asset($contract['file_path']) ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                                            <i class="bx bx-file"></i> View Current Document
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="removeDocument">
                                            <i class="bx bx-trash"></i> Remove
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-3">No document uploaded</p>
                                <?php endif; ?>
                                
                                <label for="contract_file" class="form-label">Upload New Document</label>
                                <input type="file" class="form-control" id="contract_file" name="contract_file" accept=".pdf,.doc,.docx">
                                <div class="form-text">Upload a new contract document to replace the current one</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Current Attachments</label>
                                <?php if (!empty($contract['attachments'])): ?>
                                    <div class="list-group mb-3">
                                        <?php foreach ($contract['attachments'] as $attachment): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="<?= asset($attachment['file_path']) ?>" target="_blank">
                                                    <?= htmlspecialchars($attachment['filename']) ?>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-attachment" data-id="<?= $attachment['id'] ?>">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-3">No attachments uploaded</p>
                                <?php endif; ?>
                                
                                <label for="attachments" class="form-label">Upload New Attachments</label>
                                <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                                <div class="form-text">Upload additional supporting documents</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Renewal & Notifications -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Renewal & Notifications</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="auto_renewal" class="form-label">Auto Renewal</label>
                                <select class="form-select" id="auto_renewal" name="auto_renewal">
                                    <option value="0" <?= !$contract['auto_renewal'] ? 'selected' : '' ?>>No</option>
                                    <option value="1" <?= $contract['auto_renewal'] ? 'selected' : '' ?>>Yes</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="renewal_reminder_days" class="form-label">Reminder Days Before Expiry</label>
                                <input type="number" class="form-control" id="renewal_reminder_days" name="renewal_reminder_days" min="0" value="<?= $contract['renewal_reminder_days'] ?>">
                            </div>
                            <div class="col-12">
                                <label for="notification_emails" class="form-label">Notification Emails</label>
                                <input type="text" class="form-control" id="notification_emails" name="notification_emails" value="<?= htmlspecialchars($contract['notification_emails']) ?>" placeholder="email1@example.com, email2@example.com">
                                <div class="form-text">Comma-separated email addresses for notifications</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contract Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contract Status</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Created On</dt>
                        <dd class="col-sm-7"><?= date('M d, Y', strtotime($contract['created_at'])) ?></dd>

                        <dt class="col-sm-5">Last Modified</dt>
                        <dd class="col-sm-7"><?= date('M d, Y', strtotime($contract['updated_at'])) ?></dd>

                        <dt class="col-sm-5">Days Remaining</dt>
                        <dd class="col-sm-7">
                            <?php
                            $days_remaining = (strtotime($contract['end_date']) - time()) / (60 * 60 * 24);
                            if ($days_remaining > 0):
                            ?>
                                <span class="badge bg-<?= $days_remaining <= 30 ? 'warning' : 'success' ?>">
                                    <?= ceil($days_remaining) ?> days
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">Expired</span>
                            <?php endif; ?>
                        </dd>

                        <?php if ($contract['terminated_at']): ?>
                            <dt class="col-sm-5">Terminated On</dt>
                            <dd class="col-sm-7"><?= date('M d, Y', strtotime($contract['terminated_at'])) ?></dd>

                            <dt class="col-sm-5">Termination Reason</dt>
                            <dd class="col-sm-7"><?= htmlspecialchars($contract['termination_reason']) ?></dd>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>

            <!-- Vendor Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Vendor Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Company</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($vendor['company_name']) ?></dd>

                        <dt class="col-sm-4">Contact</dt>
                        <dd class="col-sm-8">
                            <?= htmlspecialchars($vendor['primary_contact_name']) ?><br>
                            <small class="text-muted">
                                <?= htmlspecialchars($vendor['primary_contact_email']) ?><br>
                                <?= htmlspecialchars($vendor['primary_contact_phone']) ?>
                            </small>
                        </dd>

                        <dt class="col-sm-4">Active Contracts</dt>
                        <dd class="col-sm-8"><?= number_format($stats['active_contracts']) ?></dd>

                        <dt class="col-sm-4">Total Value</dt>
                        <dd class="col-sm-8"><?= $currency ?><?= number_format($stats['total_contract_value'], 2) ?></dd>
                    </dl>
                </div>
            </div>

            <!-- Recent Changes -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Changes</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($contract['history'])): ?>
                        <div class="timeline">
                            <?php foreach (array_slice($contract['history'], 0, 5) as $history): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date text-muted">
                                        <?= date('M d, Y H:i', strtotime($history['created_at'])) ?>
                                    </div>
                                    <div class="timeline-content">
                                        <p class="mb-0">
                                            <?= htmlspecialchars($history['description']) ?>
                                            <br>
                                            <small class="text-muted">by <?= htmlspecialchars($history['user_name']) ?></small>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/history") ?>" class="btn btn-link btn-sm">
                                View Full History
                            </a>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No changes recorded</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terminate Contract Modal -->
<div class="modal fade" id="terminateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terminate Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="terminateForm">
                    <div class="mb-3">
                        <label for="termination_reason" class="form-label">Reason for Termination</label>
                        <textarea class="form-control" id="termination_reason" name="termination_reason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="termination_date" class="form-label">Termination Date</label>
                        <input type="date" class="form-control" id="termination_date" name="termination_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmTermination">Terminate</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contractForm');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const terminateModal = new bootstrap.Modal(document.getElementById('terminateModal'));
    
    // Validate end date is after start date
    endDate.addEventListener('change', function() {
        if (startDate.value && this.value) {
            if (new Date(this.value) <= new Date(startDate.value)) {
                alert('End date must be after start date');
                this.value = '';
            }
        }
    });

    startDate.addEventListener('change', function() {
        if (endDate.value && this.value) {
            if (new Date(endDate.value) <= new Date(this.value)) {
                alert('End date must be after start date');
                endDate.value = '';
            }
        }
    });

    // Handle document removal
    const removeDocumentBtn = document.getElementById('removeDocument');
    if (removeDocumentBtn) {
        removeDocumentBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove the current contract document?')) {
                fetch(`<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/document") ?>`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          window.location.reload();
                      } else {
                          alert(data.message || 'Failed to remove document');
                      }
                  });
            }
        });
    }

    // Handle attachment removal
    document.querySelectorAll('.remove-attachment').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this attachment?')) {
                const attachmentId = this.dataset.id;
                fetch(`<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/attachments") ?>/${attachmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          this.closest('.list-group-item').remove();
                      } else {
                          alert(data.message || 'Failed to remove attachment');
                      }
                  });
            }
        });
    });

    // Handle contract termination
    document.getElementById('confirmTermination').addEventListener('click', function() {
        const form = document.getElementById('terminateForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        fetch(`<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/terminate") ?>`, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  window.location.reload();
              } else {
                  alert(data.message || 'Failed to terminate contract');
              }
          });
    });

    // Handle contract deletion
    document.querySelector('.delete-contract')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this contract? This action cannot be undone.')) {
            fetch(`<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      window.location.href = '<?= url("vendors/{$vendor['id']}/contracts") ?>';
                  } else {
                      alert(data.message || 'Failed to delete contract');
                  }
              });
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Custom validation here if needed
        
        this.submit();
    });
});
</script>

<style>
.form-text {
    font-size: 0.875em;
    color: #6c757d;
}

.card {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 500;
}

.timeline {
    position: relative;
    padding-left: 1.5rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    background-color: #dee2e6;
    border: 2px solid #fff;
}

.timeline-item:after {
    content: '';
    position: absolute;
    left: -1.1875rem;
    top: 1rem;
    bottom: 0;
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item:last-child:after {
    display: none;
}

.timeline-date {
    font-size: 0.875em;
    margin-bottom: 0.25rem;
}

@media (max-width: 992px) {
    .col-lg-8,
    .col-lg-4 {
        width: 100%;
    }
}
</style> 