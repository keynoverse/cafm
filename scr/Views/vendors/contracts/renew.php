<?php $this->layout('layouts/app', ['title' => 'Renew Contract - ' . htmlspecialchars($contract['title'])]) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Renew Contract</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('vendors') ?>">Vendor Directory</a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}") ?>"><?= htmlspecialchars($vendor['company_name']) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}/contracts") ?>">Contracts</a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>"><?= htmlspecialchars($contract['title']) ?></a></li>
                    <li class="breadcrumb-item active">Renew Contract</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <form id="renewalForm" action="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/renew") ?>" method="POST" enctype="multipart/form-data">
                <!-- Contract Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Contract Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="title" class="form-label">Contract Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($contract['title']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= date('Y-m-d', strtotime($suggested_start_date)) ?>" required>
                                <div class="form-text">Suggested start date is the day after current contract ends</div>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= date('Y-m-d', strtotime($suggested_end_date)) ?>" required>
                                <div class="form-text">Suggested duration matches the current contract</div>
                            </div>
                            <div class="col-md-6">
                                <label for="contract_type" class="form-label">Contract Type</label>
                                <select class="form-select" id="contract_type" name="contract_type" required>
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
                                    <option value="draft">Draft</option>
                                    <option value="active" selected>Active</option>
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
                                <label for="value_change" class="form-label">Value Change</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="value_change" name="value_change" step="0.01" value="0">
                                    <select class="form-select" id="value_change_type" name="value_change_type" style="max-width: 100px;">
                                        <option value="percentage">%</option>
                                        <option value="amount"><?= $currency ?></option>
                                    </select>
                                </div>
                                <div class="form-text">Enter increase/decrease in value</div>
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
                                <label for="contract_file" class="form-label">Contract Document</label>
                                <input type="file" class="form-control" id="contract_file" name="contract_file" accept=".pdf,.doc,.docx">
                                <div class="form-text">Upload the signed renewal contract document (PDF, DOC, DOCX)</div>
                            </div>
                            <div class="col-12">
                                <label for="attachments" class="form-label">Additional Attachments</label>
                                <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                                <div class="form-text">Upload any supporting documents</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Renewal Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Renewal Settings</h5>
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
                            <button type="submit" class="btn btn-primary">Create Renewal</button>
                            <button type="button" class="btn btn-outline-primary" id="saveDraft">Save as Draft</button>
                            <a href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Current Contract -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Current Contract</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Contract #</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($contract['contract_number']) ?></dd>

                        <dt class="col-sm-5">Status</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-<?= getContractStatusClass($contract['status']) ?>">
                                <?= ucfirst($contract['status']) ?>
                            </span>
                        </dd>

                        <dt class="col-sm-5">Period</dt>
                        <dd class="col-sm-7">
                            <?= date('M d, Y', strtotime($contract['start_date'])) ?> -
                            <?= date('M d, Y', strtotime($contract['end_date'])) ?>
                        </dd>

                        <dt class="col-sm-5">Value</dt>
                        <dd class="col-sm-7"><?= $currency ?><?= number_format($contract['contract_value'], 2) ?></dd>
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

            <!-- Help Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Help & Guidelines</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Contract Renewal</h6>
                        <ul class="small text-muted mb-0">
                            <li>Review and update contract terms</li>
                            <li>Adjust financial details if needed</li>
                            <li>Upload new signed documents</li>
                            <li>Set renewal notifications</li>
                        </ul>
                    </div>
                    <div>
                        <h6>Need Help?</h6>
                        <p class="small text-muted mb-0">Contact support at:<br>support@example.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('renewalForm');
    const saveDraftBtn = document.getElementById('saveDraft');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const contractValue = document.getElementById('contract_value');
    const valueChange = document.getElementById('value_change');
    const valueChangeType = document.getElementById('value_change_type');

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

    // Handle value change calculations
    function updateContractValue() {
        const baseValue = <?= $contract['contract_value'] ?>;
        const change = parseFloat(valueChange.value) || 0;
        
        if (valueChangeType.value === 'percentage') {
            contractValue.value = baseValue * (1 + (change / 100));
        } else {
            contractValue.value = baseValue + change;
        }
    }

    valueChange.addEventListener('input', updateContractValue);
    valueChangeType.addEventListener('change', updateContractValue);

    // Handle save as draft
    saveDraftBtn.addEventListener('click', function() {
        const formData = new FormData(form);
        formData.append('status', 'draft');
        
        fetch(form.action, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  window.location.href = data.redirect;
              } else {
                  alert(data.message || 'Failed to save draft');
              }
          });
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

@media (max-width: 992px) {
    .col-lg-8,
    .col-lg-4 {
        width: 100%;
    }
}
</style> 