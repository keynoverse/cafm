<?php $this->layout('layouts/app', ['title' => 'Create Warranty']) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('warranties') ?>">Warranty Management</a></li>
                    <li class="breadcrumb-item active">Create Warranty</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Warranty</h5>
        </div>
        <div class="card-body">
            <form action="<?= url('warranties/create') ?>" method="POST" id="warrantyForm" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Asset Selection -->
                <div class="mb-3">
                    <label for="asset_id" class="form-label required">Equipment/Asset</label>
                    <select class="form-select" id="asset_id" name="asset_id" required>
                        <option value="">Select Equipment</option>
                        <?php foreach ($assets as $asset): ?>
                            <option value="<?= $asset['id'] ?>" <?= old('asset_id') == $asset['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($asset['name']) ?> 
                                (<?= $asset['asset_tag'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Select the equipment this warranty applies to</div>
                </div>

                <!-- Supplier Selection -->
                <div class="mb-3">
                    <label for="supplier_id" class="form-label required">Supplier</label>
                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                        <option value="">Select Supplier</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= $supplier['id'] ?>" <?= old('supplier_id') == $supplier['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($supplier['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Select the warranty provider/supplier</div>
                </div>

                <!-- Warranty Type -->
                <div class="mb-3">
                    <label for="warranty_type" class="form-label required">Warranty Type</label>
                    <select class="form-select" id="warranty_type" name="warranty_type" required>
                        <option value="">Select Type</option>
                        <?php foreach ($warrantyTypes as $type): ?>
                            <option value="<?= $type['id'] ?>" <?= old('warranty_type') == $type['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Select the type of warranty coverage</div>
                </div>

                <!-- Contract Number -->
                <div class="mb-3">
                    <label for="contract_number" class="form-label required">Contract Number</label>
                    <input type="text" class="form-control" id="contract_number" name="contract_number" 
                           required maxlength="50" value="<?= old('contract_number') ?>"
                           placeholder="Enter warranty contract number">
                    <div class="form-text">Enter the unique warranty contract number</div>
                </div>

                <!-- Start Date -->
                <div class="mb-3">
                    <label for="start_date" class="form-label required">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           required value="<?= old('start_date') ?>">
                    <div class="form-text">Select the warranty start date</div>
                </div>

                <!-- End Date -->
                <div class="mb-3">
                    <label for="end_date" class="form-label required">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           required value="<?= old('end_date') ?>">
                    <div class="form-text">Select the warranty end date</div>
                </div>

                <!-- Cost -->
                <div class="mb-3">
                    <label for="cost" class="form-label">Cost</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" id="cost" name="cost" 
                               min="0" step="0.01" value="<?= old('cost') ?>"
                               placeholder="Enter warranty cost">
                    </div>
                    <div class="form-text">Enter the cost of the warranty (if applicable)</div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-3">
                    <label for="terms_conditions" class="form-label required">Terms & Conditions</label>
                    <textarea class="form-control" id="terms_conditions" name="terms_conditions" 
                              rows="4" required><?= old('terms_conditions') ?></textarea>
                    <div class="form-text">Enter the warranty terms and conditions</div>
                </div>

                <!-- Coverage Details -->
                <div class="mb-3">
                    <label for="coverage_details" class="form-label required">Coverage Details</label>
                    <textarea class="form-control" id="coverage_details" name="coverage_details" 
                              rows="4" required><?= old('coverage_details') ?></textarea>
                    <div class="form-text">Specify what is covered under this warranty</div>
                </div>

                <!-- Attachments -->
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments</label>
                    <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                    <div class="form-text">Upload warranty documents, certificates, etc. (optional)</div>
                </div>

                <!-- Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" 
                              rows="3"><?= old('notes') ?></textarea>
                    <div class="form-text">Any additional notes or comments about this warranty</div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Create Warranty
                    </button>
                    <a href="<?= url('warranties') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-x"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('warrantyForm');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Required fields validation
        ['asset_id', 'supplier_id', 'warranty_type', 'contract_number', 'start_date', 'end_date', 
         'terms_conditions', 'coverage_details'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Date validation
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (endDate <= startDate) {
            endDateInput.classList.add('is-invalid');
            alert('End date must be after start date');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields correctly');
        }
    });

    // Clear validation on input
    form.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });

    // Set minimum date for end date based on start date
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value <= this.value) {
            endDateInput.value = '';
        }
    });

    // Initialize file input with preview
    const attachmentsInput = document.getElementById('attachments');
    attachmentsInput.addEventListener('change', function() {
        const maxSize = 5 * 1024 * 1024; // 5MB
        let totalSize = 0;

        Array.from(this.files).forEach(file => {
            totalSize += file.size;
        });

        if (totalSize > maxSize) {
            alert('Total file size exceeds 5MB limit');
            this.value = '';
        }
    });
});
</script>

<style>
.required:after {
    content: " *";
    color: red;
}

.form-label {
    font-weight: 500;
}

.is-invalid {
    border-color: #dc3545;
}

.is-invalid + .form-text {
    color: #dc3545;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

@media (max-width: 768px) {
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style> 