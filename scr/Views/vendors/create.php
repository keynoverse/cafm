<?php $this->layout('layouts/app', ['title' => 'Add New Vendor']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Add New Vendor</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('vendors') ?>">Vendor Directory</a></li>
                    <li class="breadcrumb-item active">Add New Vendor</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <form id="vendorForm" action="<?= url('vendors') ?>" method="POST" enctype="multipart/form-data">
                <!-- Company Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Company Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="business_type" class="form-label">Business Type</label>
                                <select class="form-select" id="business_type" name="business_type" required>
                                    <option value="">Select Type</option>
                                    <option value="corporation">Corporation</option>
                                    <option value="llc">LLC</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="sole_proprietorship">Sole Proprietorship</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tax_id" class="form-label">Tax ID / EIN</label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id">
                            </div>
                            <div class="col-md-6">
                                <label for="registration_number" class="form-label">Registration Number</label>
                                <input type="text" class="form-control" id="registration_number" name="registration_number">
                            </div>
                            <div class="col-12">
                                <label for="categories" class="form-label">Categories</label>
                                <select class="form-select" id="categories" name="categories[]" multiple required>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Select one or more categories that best describe the vendor's services</div>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Company Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <label for="logo" class="form-label">Company Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                <div class="form-text">Upload company logo (PNG, JPG, SVG)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="primary_contact_name" class="form-label">Primary Contact Name</label>
                                <input type="text" class="form-control" id="primary_contact_name" name="primary_contact_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="primary_contact_title" class="form-label">Job Title</label>
                                <input type="text" class="form-control" id="primary_contact_title" name="primary_contact_title">
                            </div>
                            <div class="col-md-6">
                                <label for="primary_contact_email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="primary_contact_email" name="primary_contact_email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="primary_contact_phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="primary_contact_phone" name="primary_contact_phone" required>
                            </div>
                            <div class="col-md-6">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website" placeholder="https://">
                            </div>
                            <div class="col-md-6">
                                <label for="communication_preference" class="form-label">Preferred Communication</label>
                                <select class="form-select" id="communication_preference" name="communication_preference">
                                    <option value="email">Email</option>
                                    <option value="phone">Phone</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Address Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="address_line1" class="form-label">Address Line 1</label>
                                <input type="text" class="form-control" id="address_line1" name="address_line1" required>
                            </div>
                            <div class="col-12">
                                <label for="address_line2" class="form-label">Address Line 2</label>
                                <input type="text" class="form-control" id="address_line2" name="address_line2">
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-6">
                                <label for="state" class="form-label">State/Province</label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>
                            <div class="col-md-6">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                            </div>
                            <div class="col-md-6">
                                <label for="country" class="form-label">Country</label>
                                <select class="form-select" id="country" name="country" required>
                                    <option value="">Select Country</option>
                                    <?php foreach ($countries as $code => $name): ?>
                                        <option value="<?= $code ?>"><?= htmlspecialchars($name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Financial Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
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
                                <label for="currency" class="form-label">Preferred Currency</label>
                                <select class="form-select" id="currency" name="currency">
                                    <option value="USD">USD - US Dollar</option>
                                    <option value="EUR">EUR - Euro</option>
                                    <option value="GBP">GBP - British Pound</option>
                                    <option value="CAD">CAD - Canadian Dollar</option>
                                    <option value="AUD">AUD - Australian Dollar</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name">
                            </div>
                            <div class="col-md-6">
                                <label for="bank_account_number" class="form-label">Account Number</label>
                                <input type="text" class="form-control" id="bank_account_number" name="bank_account_number">
                            </div>
                            <div class="col-md-6">
                                <label for="bank_routing_number" class="form-label">Routing Number</label>
                                <input type="text" class="form-control" id="bank_routing_number" name="bank_routing_number">
                            </div>
                            <div class="col-md-6">
                                <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                                <input type="number" class="form-control" id="tax_rate" name="tax_rate" step="0.01" min="0" max="100">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Documents</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="tax_document" class="form-label">Tax Document (W-9/W-8)</label>
                                <input type="file" class="form-control" id="tax_document" name="tax_document" accept=".pdf,.doc,.docx">
                            </div>
                            <div class="col-12">
                                <label for="insurance_document" class="form-label">Insurance Certificate</label>
                                <input type="file" class="form-control" id="insurance_document" name="insurance_document" accept=".pdf,.doc,.docx">
                            </div>
                            <div class="col-12">
                                <label for="additional_documents" class="form-label">Additional Documents</label>
                                <input type="file" class="form-control" id="additional_documents" name="additional_documents[]" multiple>
                                <div class="form-text">Upload any additional supporting documents</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="pending" selected>Pending</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="approval_required" class="form-label">Approval Required</label>
                                <select class="form-select" id="approval_required" name="approval_required">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="notes" class="form-label">Internal Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Vendor</button>
                            <button type="button" class="btn btn-outline-primary" id="saveDraft">Save as Draft</button>
                            <a href="<?= url('vendors') ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Vendor Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h3 class="mb-1"><?= number_format($stats['total_vendors']) ?></h3>
                                <small class="text-muted">Total Vendors</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h3 class="mb-1"><?= number_format($stats['active_vendors']) ?></h3>
                                <small class="text-muted">Active Vendors</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h3 class="mb-1"><?= number_format($stats['pending_vendors']) ?></h3>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <h3 class="mb-1"><?= $currency ?><?= number_format($stats['total_spend'], 0) ?>K</h3>
                                <small class="text-muted">Total Spend</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Help & Guidelines</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Required Documents</h6>
                        <ul class="small text-muted mb-0">
                            <li>W-9 form for US vendors</li>
                            <li>W-8 form for international vendors</li>
                            <li>Insurance certificate</li>
                            <li>Business license</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <h6>File Requirements</h6>
                        <ul class="small text-muted mb-0">
                            <li>Logo: PNG, JPG, SVG (max 2MB)</li>
                            <li>Documents: PDF, DOC, DOCX (max 10MB)</li>
                            <li>Multiple files allowed for additional documents</li>
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
    const form = document.getElementById('vendorForm');
    const saveDraftBtn = document.getElementById('saveDraft');

    // Initialize select2 for multiple select
    if (typeof $.fn.select2 !== 'undefined') {
        $('#categories').select2({
            placeholder: 'Select categories',
            allowClear: true
        });

        $('#country').select2({
            placeholder: 'Select country'
        });
    }

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

.select2-container {
    width: 100% !important;
}

@media (max-width: 992px) {
    .col-lg-8,
    .col-lg-4 {
        width: 100%;
    }
}
</style> 