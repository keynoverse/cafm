<?php $this->layout('layouts/app', ['title' => 'New Contract - ' . htmlspecialchars($vendor['company_name'])]) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">New Contract</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('vendors') ?>">Vendor Directory</a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}") ?>"><?= htmlspecialchars($vendor['company_name']) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}/contracts") ?>">Contracts</a></li>
                    <li class="breadcrumb-item active">New Contract</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <form id="contractForm" action="<?= url("vendors/{$vendor['id']}/contracts") ?>" method="POST" enctype="multipart/form-data">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contract_number" class="form-label">Contract Number</label>
                                <input type="text" class="form-control" id="contract_number" name="contract_number" required>
                                <div class="form-text">Unique identifier for this contract</div>
                            </div>
                            <div class="col-md-6">
                                <label for="title" class="form-label">Contract Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="contract_type" class="form-label">Contract Type</label>
                                <select class="form-select" id="contract_type" name="contract_type" required>
                                    <option value="">Select Type</option>
                                    <option value="service">Service Contract</option>
                                    <option value="maintenance">Maintenance Contract</option>
                                    <option value="supply">Supply Contract</option>
                                    <option value="lease">Lease Agreement</option>
                                    <option value="license">License Agreement</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
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
                                    <input type="number" class="form-control" id="contract_value" name="contract_value" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="billing_frequency" class="form-label">Billing Frequency</label>
                                <select class="form-select" id="billing_frequency" name="billing_frequency">
                                    <option value="">Select Frequency</option>
                                    <option value="one_time">One Time</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="semi_annual">Semi-Annual</option>
                                    <option value="annual">Annual</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="payment_terms" class="form-label">Payment Terms</label>
                                <select class="form-select" id="payment_terms" name="payment_terms">
                                    <option value="">Select Terms</option>
                                    <option value="immediate">Immediate</option>
                                    <option value="net_15">Net 15</option>
                                    <option value="net_30">Net 30</option>
                                    <option value="net_45">Net 45</option>
                                    <option value="net_60">Net 60</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                                <input type="number" class="form-control" id="tax_rate" name="tax_rate" step="0.01" min="0" max="100">
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
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                                <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="5"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label for="contract_file" class="form-label">Contract Document</label>
                                <input type="file" class="form-control" id="contract_file" name="contract_file" accept=".pdf,.doc,.docx">
                                <div class="form-text">Upload the signed contract document (PDF, DOC, DOCX)</div>
                            </div>
                            <div class="col-md-12">
                                <label for="attachments" class="form-label">Additional Attachments</label>
                                <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                                <div class="form-text">Upload any supporting documents</div>
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
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="renewal_reminder_days" class="form-label">Reminder Days Before Expiry</label>
                                <input type="number" class="form-control" id="renewal_reminder_days" name="renewal_reminder_days" min="0" value="30">
                            </div>
                            <div class="col-12">
                                <label for="notification_emails" class="form-label">Notification Emails</label>
                                <input type="text" class="form-control" id="notification_emails" name="notification_emails" placeholder="email1@example.com, email2@example.com">
                                <div class="form-text">Comma-separated email addresses for notifications</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Contract</button>
                            <button type="button" class="btn btn-outline-primary" id="saveDraft">Save as Draft</button>
                            <a href="<?= url("vendors/{$vendor['id']}/contracts") ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Info -->
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
                        <h6>Contract Number Format</h6>
                        <p class="small text-muted mb-0">Use format: VND-YYYY-XXXX<br>Example: VND-2024-0001</p>
                    </div>
                    <div class="mb-3">
                        <h6>Required Documents</h6>
                        <ul class="small text-muted mb-0">
                            <li>Signed contract document (PDF/DOC)</li>
                            <li>Supporting documentation</li>
                            <li>Terms and conditions</li>
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
    const form = document.getElementById('contractForm');
    const saveDraftBtn = document.getElementById('saveDraft');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const contractNumber = document.getElementById('contract_number');

    // Generate suggested contract number
    if (!contractNumber.value) {
        const today = new Date();
        const year = today.getFullYear();
        fetch(`<?= url("vendors/{$vendor['id']}/contracts/next-number") ?>`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    contractNumber.value = data.number;
                }
            });
    }

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