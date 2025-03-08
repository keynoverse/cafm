<?php $this->layout('layouts/app', ['title' => 'Create Calibration Record']) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('calibration') ?>">Calibration</a></li>
                    <li class="breadcrumb-item active">Create Record</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Calibration Record</h5>
        </div>
        <div class="card-body">
            <form action="<?= url('calibration') ?>" method="POST" id="calibrationForm">
                <?= csrf_field() ?>

                <!-- Asset Selection -->
                <div class="mb-3">
                    <label for="asset_id" class="form-label required">Equipment</label>
                    <select class="form-select" id="asset_id" name="asset_id" required>
                        <option value="">Select Equipment</option>
                        <?php foreach ($assets as $asset): ?>
                            <option value="<?= $asset['id'] ?>">
                                <?= htmlspecialchars($asset['name']) ?> 
                                (<?= $asset['asset_tag'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Select the equipment that requires calibration</div>
                </div>

                <!-- Calibration Type -->
                <div class="mb-3">
                    <label for="calibration_type" class="form-label required">Calibration Type</label>
                    <input type="text" class="form-control" id="calibration_type" name="calibration_type" 
                           required maxlength="100" placeholder="e.g., Temperature Calibration">
                    <div class="form-text">Enter the type of calibration to be performed</div>
                </div>

                <!-- Calibration Standard -->
                <div class="mb-3">
                    <label for="calibration_standard" class="form-label required">Calibration Standard</label>
                    <input type="text" class="form-control" id="calibration_standard" name="calibration_standard" 
                           required maxlength="255" placeholder="e.g., ISO 17025">
                    <div class="form-text">Enter the standard or reference used for calibration</div>
                </div>

                <!-- Next Calibration Date -->
                <div class="mb-3">
                    <label for="next_calibration_date" class="form-label required">Next Calibration Date</label>
                    <input type="date" class="form-control" id="next_calibration_date" 
                           name="next_calibration_date" required min="<?= date('Y-m-d') ?>">
                    <div class="form-text">When should this equipment be calibrated next?</div>
                </div>

                <!-- Assigned To -->
                <div class="mb-3">
                    <label for="performed_by" class="form-label">Assign To</label>
                    <select class="form-select" id="performed_by" name="performed_by">
                        <option value="">Select Technician</option>
                        <?php foreach ($technicians as $tech): ?>
                            <option value="<?= $tech['id'] ?>">
                                <?= htmlspecialchars($tech['first_name'] . ' ' . $tech['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Select a technician to perform the calibration (optional)</div>
                </div>

                <!-- Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" 
                              rows="3" placeholder="Enter any additional notes or instructions..."><?= old('notes') ?></textarea>
                    <div class="form-text">Provide any additional notes or special instructions</div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Create Record
                    </button>
                    <a href="<?= url('calibration') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-x"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('calibrationForm');
    const nextCalibrationDateInput = document.getElementById('next_calibration_date');

    // Set minimum date for next_calibration_date
    nextCalibrationDateInput.min = new Date().toISOString().split('T')[0];

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Required fields validation
        ['asset_id', 'calibration_type', 'calibration_standard', 'next_calibration_date'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });

    // Clear validation on input
    form.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
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