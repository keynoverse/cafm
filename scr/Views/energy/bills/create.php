<?php $this->layout('layouts/app', ['title' => 'Add Utility Bill']) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('energy/bills') ?>">Utility Bills</a></li>
                    <li class="breadcrumb-item active">Add Bill</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Add Utility Bill</h5>
        </div>
        <div class="card-body">
            <form action="<?= url('energy/bills/create') ?>" method="POST" id="billForm" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Bill Number -->
                <div class="mb-3">
                    <label for="bill_number" class="form-label required">Bill Number</label>
                    <input type="text" class="form-control" id="bill_number" name="bill_number" 
                           required maxlength="50" value="<?= old('bill_number') ?>"
                           placeholder="Enter bill number">
                    <div class="form-text">Enter the unique bill identification number</div>
                </div>

                <!-- Utility Type -->
                <div class="mb-3">
                    <label for="utility_type" class="form-label required">Utility Type</label>
                    <select class="form-select" id="utility_type" name="utility_type" required>
                        <option value="">Select Type</option>
                        <option value="electricity" <?= old('utility_type') === 'electricity' ? 'selected' : '' ?>>Electricity</option>
                        <option value="gas" <?= old('utility_type') === 'gas' ? 'selected' : '' ?>>Gas</option>
                        <option value="water" <?= old('utility_type') === 'water' ? 'selected' : '' ?>>Water</option>
                        <option value="other" <?= old('utility_type') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                    <div class="form-text">Select the type of utility service</div>
                </div>

                <!-- Billing Period -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="billing_period_start" class="form-label required">Billing Period Start</label>
                        <input type="date" class="form-control" id="billing_period_start" 
                               name="billing_period_start" required 
                               value="<?= old('billing_period_start') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="billing_period_end" class="form-label required">Billing Period End</label>
                        <input type="date" class="form-control" id="billing_period_end" 
                               name="billing_period_end" required 
                               value="<?= old('billing_period_end') ?>">
                    </div>
                    <div class="col-12">
                        <div class="form-text">Select the start and end dates for the billing period</div>
                    </div>
                </div>

                <!-- Amount -->
                <div class="mb-3">
                    <label for="amount" class="form-label required">Bill Amount</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" id="amount" name="amount" 
                               required min="0" step="0.01" value="<?= old('amount') ?>"
                               placeholder="Enter bill amount">
                    </div>
                    <div class="form-text">Enter the total amount of the bill</div>
                </div>

                <!-- Consumption -->
                <div class="mb-3">
                    <label for="consumption_amount" class="form-label required">Consumption Amount</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="consumption_amount" 
                               name="consumption_amount" required min="0" step="0.01" 
                               value="<?= old('consumption_amount') ?>"
                               placeholder="Enter consumption amount">
                        <select class="form-select" id="consumption_unit" name="consumption_unit" 
                                required style="max-width: 100px;">
                            <option value="">Unit</option>
                            <option value="kWh" <?= old('consumption_unit') === 'kWh' ? 'selected' : '' ?>>kWh</option>
                            <option value="m³" <?= old('consumption_unit') === 'm³' ? 'selected' : '' ?>>m³</option>
                            <option value="L" <?= old('consumption_unit') === 'L' ? 'selected' : '' ?>>L</option>
                            <option value="other" <?= old('consumption_unit') === 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-text">Enter the consumption amount and select the appropriate unit</div>
                </div>

                <!-- Supplier -->
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
                    <div class="form-text">Select the utility service provider</div>
                </div>

                <!-- Due Date -->
                <div class="mb-3">
                    <label for="due_date" class="form-label required">Due Date</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" 
                           required value="<?= old('due_date') ?>">
                    <div class="form-text">Select the payment due date</div>
                </div>

                <!-- Payment Status -->
                <div class="mb-3">
                    <label for="payment_status" class="form-label required">Payment Status</label>
                    <select class="form-select" id="payment_status" name="payment_status" required>
                        <option value="">Select Status</option>
                        <option value="pending" <?= old('payment_status') === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="paid" <?= old('payment_status') === 'paid' ? 'selected' : '' ?>>Paid</option>
                        <option value="overdue" <?= old('payment_status') === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                        <option value="disputed" <?= old('payment_status') === 'disputed' ? 'selected' : '' ?>>Disputed</option>
                    </select>
                    <div class="form-text">Select the current payment status</div>
                </div>

                <!-- Payment Date -->
                <div class="mb-3" id="paymentDateContainer" style="display: none;">
                    <label for="payment_date" class="form-label">Payment Date</label>
                    <input type="date" class="form-control" id="payment_date" name="payment_date" 
                           value="<?= old('payment_date') ?>">
                    <div class="form-text">Select the date when payment was made (if already paid)</div>
                </div>

                <!-- Bill Attachments -->
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments</label>
                    <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                    <div class="form-text">Upload bill documents, receipts, etc. (optional)</div>
                </div>

                <!-- Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" 
                              rows="3"><?= old('notes') ?></textarea>
                    <div class="form-text">Any additional notes or comments about this bill</div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Save Bill
                    </button>
                    <a href="<?= url('energy/bills') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-x"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('billForm');
    const utilityTypeSelect = document.getElementById('utility_type');
    const consumptionUnitSelect = document.getElementById('consumption_unit');
    const billingPeriodStartInput = document.getElementById('billing_period_start');
    const billingPeriodEndInput = document.getElementById('billing_period_end');
    const dueDateInput = document.getElementById('due_date');
    const paymentStatusSelect = document.getElementById('payment_status');
    const paymentDateContainer = document.getElementById('paymentDateContainer');
    const paymentDateInput = document.getElementById('payment_date');

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Required fields validation
        ['bill_number', 'utility_type', 'billing_period_start', 'billing_period_end', 
         'amount', 'consumption_amount', 'consumption_unit', 'supplier_id', 'due_date', 
         'payment_status'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Date validations
        const startDate = new Date(billingPeriodStartInput.value);
        const endDate = new Date(billingPeriodEndInput.value);
        const dueDate = new Date(dueDateInput.value);

        if (endDate <= startDate) {
            billingPeriodEndInput.classList.add('is-invalid');
            alert('Billing period end date must be after start date');
            isValid = false;
        }

        if (dueDate < endDate) {
            dueDateInput.classList.add('is-invalid');
            alert('Due date should be after billing period end date');
            isValid = false;
        }

        // Payment date validation if paid
        if (paymentStatusSelect.value === 'paid' && !paymentDateInput.value) {
            paymentDateInput.classList.add('is-invalid');
            alert('Payment date is required when status is paid');
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

    // Update consumption units based on utility type
    utilityTypeSelect.addEventListener('change', function() {
        consumptionUnitSelect.innerHTML = '<option value="">Unit</option>';
        
        const units = {
            'electricity': [
                { value: 'kWh', label: 'kWh' },
                { value: 'MWh', label: 'MWh' }
            ],
            'gas': [
                { value: 'm³', label: 'm³' },
                { value: 'ft³', label: 'ft³' }
            ],
            'water': [
                { value: 'm³', label: 'm³' },
                { value: 'L', label: 'L' },
                { value: 'gal', label: 'gal' }
            ],
            'other': [
                { value: 'other', label: 'Other' }
            ]
        };

        if (this.value && units[this.value]) {
            units[this.value].forEach(unit => {
                const option = new Option(unit.label, unit.value);
                consumptionUnitSelect.add(option);
            });
        }
    });

    // Show/hide payment date based on status
    paymentStatusSelect.addEventListener('change', function() {
        paymentDateContainer.style.display = this.value === 'paid' ? 'block' : 'none';
        paymentDateInput.required = this.value === 'paid';
    });

    // Set minimum dates
    billingPeriodStartInput.addEventListener('change', function() {
        billingPeriodEndInput.min = this.value;
        if (billingPeriodEndInput.value && billingPeriodEndInput.value < this.value) {
            billingPeriodEndInput.value = this.value;
        }
    });

    billingPeriodEndInput.addEventListener('change', function() {
        dueDateInput.min = this.value;
        if (dueDateInput.value && dueDateInput.value < this.value) {
            dueDateInput.value = this.value;
        }
    });

    // Initialize file input validation
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

    // Initialize payment date visibility
    paymentDateContainer.style.display = paymentStatusSelect.value === 'paid' ? 'block' : 'none';
    paymentDateInput.required = paymentStatusSelect.value === 'paid';
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

    .input-group {
        flex-direction: column;
    }

    .input-group > * {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .input-group > :last-child {
        margin-bottom: 0;
    }
}
</style> 