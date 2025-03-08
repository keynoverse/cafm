<?php $this->layout('layouts/app', ['title' => 'Edit Vendor']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Edit Vendor</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('vendors') ?>">Vendor Directory</a></li>
                    <li class="breadcrumb-item active">Edit Vendor</li>
                </ol>
            </nav>
        </div>
    </div>

    <form id="editVendorForm" action="<?= url("vendors/{$vendor['id']}/update") ?>" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="row">
            <!-- Main Form Content -->
            <div class="col-lg-8">
                <!-- Company Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Company Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="company_name" name="company_name" value="<?= htmlspecialchars($vendor['company_name']) ?>" required>
                                <div class="invalid-feedback">Please enter company name</div>
                            </div>
                            <div class="col-md-6">
                                <label for="business_type" class="form-label">Business Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="business_type" name="business_type" value="<?= htmlspecialchars($vendor['business_type']) ?>" required>
                                <div class="invalid-feedback">Please enter business type</div>
                            </div>
                            <div class="col-md-6">
                                <label for="registration_number" class="form-label">Registration Number</label>
                                <input type="text" class="form-control" id="registration_number" name="registration_number" value="<?= htmlspecialchars($vendor['registration_number'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="tax_id" class="form-label">Tax ID</label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id" value="<?= htmlspecialchars($vendor['tax_id'] ?? '') ?>">
                            </div>
                            <div class="col-12">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website" placeholder="https://" value="<?= htmlspecialchars($vendor['website'] ?? '') ?>">
                                <div class="invalid-feedback">Please enter a valid URL</div>
                            </div>
                            <div class="col-12">
                                <label for="categories" class="form-label">Categories <span class="text-danger">*</span></label>
                                <select class="form-select" id="categories" name="categories[]" multiple required>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= in_array($category['id'], array_column($vendor['categories'], 'id')) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Please select at least one category</div>
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
                            <!-- Primary Contact -->
                            <div class="col-12">
                                <h6 class="fw-semibold">Primary Contact</h6>
                            </div>
                            <div class="col-md-4">
                                <label for="primary_contact_name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="primary_contact_name" name="primary_contact_name" value="<?= htmlspecialchars($vendor['primary_contact_name']) ?>" required>
                                <div class="invalid-feedback">Please enter contact name</div>
                            </div>
                            <div class="col-md-4">
                                <label for="primary_contact_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="primary_contact_email" name="primary_contact_email" value="<?= htmlspecialchars($vendor['primary_contact_email']) ?>" required>
                                <div class="invalid-feedback">Please enter a valid email</div>
                            </div>
                            <div class="col-md-4">
                                <label for="primary_contact_phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="primary_contact_phone" name="primary_contact_phone" value="<?= htmlspecialchars($vendor['primary_contact_phone']) ?>" required>
                                <div class="invalid-feedback">Please enter phone number</div>
                            </div>

                            <!-- Secondary Contact -->
                            <div class="col-12">
                                <h6 class="fw-semibold mt-2">Secondary Contact</h6>
                            </div>
                            <div class="col-md-4">
                                <label for="secondary_contact_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="secondary_contact_name" name="secondary_contact_name" value="<?= htmlspecialchars($vendor['secondary_contact_name'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="secondary_contact_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="secondary_contact_email" name="secondary_contact_email" value="<?= htmlspecialchars($vendor['secondary_contact_email'] ?? '') ?>">
                                <div class="invalid-feedback">Please enter a valid email</div>
                            </div>
                            <div class="col-md-4">
                                <label for="secondary_contact_phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="secondary_contact_phone" name="secondary_contact_phone" value="<?= htmlspecialchars($vendor['secondary_contact_phone'] ?? '') ?>">
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
                                <label for="billing_address" class="form-label">Billing Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="billing_address" name="billing_address" rows="3" required><?= htmlspecialchars($vendor['billing_address']) ?></textarea>
                                <div class="invalid-feedback">Please enter billing address</div>
                            </div>
                            <div class="col-12">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="same_as_billing" <?= $vendor['shipping_address'] === $vendor['billing_address'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="same_as_billing">
                                        Shipping address same as billing
                                    </label>
                                </div>
                            </div>
                            <div class="col-12" id="shipping_address_container" style="<?= $vendor['shipping_address'] === $vendor['billing_address'] ? 'display: none;' : '' ?>">
                                <label for="shipping_address" class="form-label">Shipping Address</label>
                                <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3"><?= htmlspecialchars($vendor['shipping_address']) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="payment_terms" class="form-label">Payment Terms</label>
                                <input type="text" class="form-control" id="payment_terms" name="payment_terms" placeholder="e.g., Net 30" value="<?= htmlspecialchars($vendor['payment_terms'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label">Preferred Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method">
                                    <option value="">Select payment method</option>
                                    <option value="bank_transfer" <?= ($vendor['payment_method'] ?? '') === 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                                    <option value="check" <?= ($vendor['payment_method'] ?? '') === 'check' ? 'selected' : '' ?>>Check</option>
                                    <option value="credit_card" <?= ($vendor['payment_method'] ?? '') === 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name" value="<?= htmlspecialchars($vendor['bank_name'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="bank_account_number" class="form-label">Bank Account Number</label>
                                <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" value="<?= htmlspecialchars($vendor['bank_account_number'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Additional Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= htmlspecialchars($vendor['notes'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Vendor Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" <?= $vendor['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="active" <?= $vendor['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $vendor['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="blacklisted" <?= $vendor['status'] === 'blacklisted' ? 'selected' : '' ?>>Blacklisted</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <?php if ($vendor['logo_path']): ?>
                                <div class="mb-3">
                                    <label class="form-label">Current Logo</label>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= asset($vendor['logo_path']) ?>" alt="Current Logo" class="rounded me-2" style="max-width: 100px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="remove_logo" name="remove_logo">
                                            <label class="form-check-label" for="remove_logo">Remove logo</label>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <label for="logo" class="form-label">Update Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            <div class="form-text">Recommended size: 200x200px</div>
                        </div>
                    </div>
                </div>

                <!-- Services Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Services</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addService">
                            <i class="bx bx-plus"></i> Add Service
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="services_container">
                            <?php foreach ($vendor['services'] as $index => $service): ?>
                                <div class="service-item border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="mb-0">Service Details</h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-service">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row g-2">
                                        <input type="hidden" name="services[<?= $index ?>][id]" value="<?= $service['id'] ?>">
                                        <div class="col-12">
                                            <input type="text" class="form-control form-control-sm" name="services[<?= $index ?>][service_name]" placeholder="Service Name" value="<?= htmlspecialchars($service['service_name']) ?>" required>
                                        </div>
                                        <div class="col-12">
                                            <textarea class="form-control form-control-sm" name="services[<?= $index ?>][description]" rows="2" placeholder="Description"><?= htmlspecialchars($service['description'] ?? '') ?></textarea>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-select form-select-sm" name="services[<?= $index ?>][rate_type]" required>
                                                <option value="">Rate Type</option>
                                                <option value="hourly" <?= $service['rate_type'] === 'hourly' ? 'selected' : '' ?>>Hourly</option>
                                                <option value="fixed" <?= $service['rate_type'] === 'fixed' ? 'selected' : '' ?>>Fixed</option>
                                                <option value="variable" <?= $service['rate_type'] === 'variable' ? 'selected' : '' ?>>Variable</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <input type="number" class="form-control" name="services[<?= $index ?>][rate_amount]" placeholder="Rate" step="0.01" value="<?= $service['rate_amount'] ?>">
                                                <select class="form-select" name="services[<?= $index ?>][currency]" style="max-width: 80px;">
                                                    <option value="USD" <?= ($service['currency'] ?? 'USD') === 'USD' ? 'selected' : '' ?>>USD</option>
                                                    <option value="EUR" <?= ($service['currency'] ?? '') === 'EUR' ? 'selected' : '' ?>>EUR</option>
                                                    <option value="GBP" <?= ($service['currency'] ?? '') === 'GBP' ? 'selected' : '' ?>>GBP</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card mb-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bx bx-save"></i> Update Vendor
                        </button>
                        <a href="<?= url('vendors') ?>" class="btn btn-outline-secondary w-100">
                            <i class="bx bx-arrow-back"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Service Template -->
<template id="service_template">
    <div class="service-item border rounded p-3 mb-3">
        <div class="d-flex justify-content-between mb-2">
            <h6 class="mb-0">Service Details</h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-service">
                <i class="bx bx-trash"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-12">
                <input type="text" class="form-control form-control-sm" name="services[{index}][service_name]" placeholder="Service Name" required>
            </div>
            <div class="col-12">
                <textarea class="form-control form-control-sm" name="services[{index}][description]" rows="2" placeholder="Description"></textarea>
            </div>
            <div class="col-6">
                <select class="form-select form-select-sm" name="services[{index}][rate_type]" required>
                    <option value="">Rate Type</option>
                    <option value="hourly">Hourly</option>
                    <option value="fixed">Fixed</option>
                    <option value="variable">Variable</option>
                </select>
            </div>
            <div class="col-6">
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control" name="services[{index}][rate_amount]" placeholder="Rate" step="0.01">
                    <select class="form-select" name="services[{index}][currency]" style="max-width: 80px;">
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editVendorForm');
    const sameAsBilling = document.getElementById('same_as_billing');
    const shippingContainer = document.getElementById('shipping_address_container');
    const addServiceBtn = document.getElementById('addService');
    const servicesContainer = document.getElementById('services_container');
    const serviceTemplate = document.getElementById('service_template');
    let serviceIndex = <?= count($vendor['services']) ?>;

    // Form validation
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Handle shipping address toggle
    sameAsBilling.addEventListener('change', function() {
        shippingContainer.style.display = this.checked ? 'none' : 'block';
        const shippingAddress = document.getElementById('shipping_address');
        if (this.checked) {
            shippingAddress.value = document.getElementById('billing_address').value;
        } else {
            shippingAddress.value = '';
        }
    });

    // Add service
    addServiceBtn.addEventListener('click', function() {
        const serviceHtml = serviceTemplate.innerHTML
            .replace(/{index}/g, serviceIndex++);
        const wrapper = document.createElement('div');
        wrapper.innerHTML = serviceHtml;
        servicesContainer.appendChild(wrapper.firstElementChild);
    });

    // Remove service
    servicesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-service')) {
            const serviceItem = e.target.closest('.service-item');
            const serviceId = serviceItem.querySelector('input[name$="[id]"]')?.value;
            
            if (serviceId) {
                // Add hidden input to track deleted services
                const deletedInput = document.createElement('input');
                deletedInput.type = 'hidden';
                deletedInput.name = 'deleted_services[]';
                deletedInput.value = serviceId;
                form.appendChild(deletedInput);
            }
            
            serviceItem.remove();
        }
    });

    // Initialize Select2 for categories
    if (typeof $.fn.select2 !== 'undefined') {
        $('#categories').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select categories',
            width: '100%'
        });
    }
});
</script>

<style>
.was-validated .form-control:invalid,
.was-validated .form-select:invalid {
    border-color: var(--bs-danger);
}

.was-validated .form-control:valid,
.was-validated .form-select:valid {
    border-color: var(--bs-success);
}

.service-item {
    background-color: var(--bs-gray-100);
}

@media (max-width: 992px) {
    .col-lg-8,
    .col-lg-4 {
        width: 100%;
    }
}
</style> 