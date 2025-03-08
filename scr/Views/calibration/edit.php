<?php $this->layout('layouts/app', ['title' => 'Edit Calibration Record']) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('calibration') ?>">Calibration</a></li>
                    <li class="breadcrumb-item">
                        <a href="<?= url("calibration/{$calibration['id']}") ?>">
                            <?= htmlspecialchars($calibration['asset_name']) ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Edit Calibration Record</h5>
            <div>
                <span class="badge bg-<?= $calibration['status'] === 'pending' ? 'secondary' : 
                    ($calibration['status'] === 'in_progress' ? 'info' : 
                    ($calibration['status'] === 'completed' ? 'success' : 'danger')) ?>">
                    <?= ucfirst(str_replace('_', ' ', $calibration['status'])) ?>
                </span>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= url("calibration/{$calibration['id']}") ?>" method="POST" id="calibrationForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <!-- Asset Selection -->
                <div class="mb-3">
                    <label for="asset_id" class="form-label required">Equipment</label>
                    <select class="form-select" id="asset_id" name="asset_id" required>
                        <option value="">Select Equipment</option>
                        <?php foreach ($assets as $asset): ?>
                            <option value="<?= $asset['id'] ?>" <?= $asset['id'] === $calibration['asset_id'] ? 'selected' : '' ?>>
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
                           required maxlength="100" value="<?= htmlspecialchars($calibration['calibration_type']) ?>">
                    <div class="form-text">Enter the type of calibration to be performed</div>
                </div>

                <!-- Calibration Standard -->
                <div class="mb-3">
                    <label for="calibration_standard" class="form-label required">Calibration Standard</label>
                    <input type="text" class="form-control" id="calibration_standard" name="calibration_standard" 
                           required maxlength="255" value="<?= htmlspecialchars($calibration['calibration_standard']) ?>">
                    <div class="form-text">Enter the standard or reference used for calibration</div>
                </div>

                <!-- Next Calibration Date -->
                <div class="mb-3">
                    <label for="next_calibration_date" class="form-label required">Next Calibration Date</label>
                    <input type="date" class="form-control" id="next_calibration_date" 
                           name="next_calibration_date" required value="<?= $calibration['next_calibration_date'] ?>">
                    <div class="form-text">When should this equipment be calibrated next?</div>
                </div>

                <!-- Assigned To -->
                <div class="mb-3">
                    <label for="performed_by" class="form-label">Assign To</label>
                    <select class="form-select" id="performed_by" name="performed_by">
                        <option value="">Select Technician</option>
                        <?php foreach ($technicians as $tech): ?>
                            <option value="<?= $tech['id'] ?>" <?= $tech['id'] === $calibration['performed_by'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tech['first_name'] . ' ' . $tech['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Select a technician to perform the calibration (optional)</div>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label required">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php 
                        $statuses = [
                            'pending' => 'Pending',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                            'failed' => 'Failed'
                        ];
                        foreach ($statuses as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $value === $calibration['status'] ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Current status of the calibration</div>
                </div>

                <!-- Calibration Result -->
                <div class="mb-3">
                    <label for="calibration_result" class="form-label">Calibration Result</label>
                    <textarea class="form-control" id="calibration_result" name="calibration_result" 
                              rows="3"><?= htmlspecialchars($calibration['calibration_result']) ?></textarea>
                    <div class="form-text">Enter the results of the calibration (if completed)</div>
                </div>

                <!-- Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" 
                              rows="3"><?= htmlspecialchars($calibration['notes']) ?></textarea>
                    <div class="form-text">Provide any additional notes or special instructions</div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Save Changes
                    </button>
                    <a href="<?= url("calibration/{$calibration['id']}") ?>" class="btn btn-outline-secondary">
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
    const statusSelect = document.getElementById('status');
    const calibrationResultTextarea = document.getElementById('calibration_result');

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Required fields validation
        ['asset_id', 'calibration_type', 'calibration_standard', 'next_calibration_date', 'status'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Validate calibration result is provided when status is completed
        if (statusSelect.value === 'completed' && !calibrationResultTextarea.value.trim()) {
            calibrationResultTextarea.classList.add('is-invalid');
            isValid = false;
            alert('Please provide calibration results for completed calibration');
        }

        if (!isValid) {
            e.preventDefault();
            if (!document.querySelector('.is-invalid')) {
                alert('Please fill in all required fields');
            }
        }
    });

    // Clear validation on input
    form.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });

    // Handle status change
    statusSelect.addEventListener('change', function() {
        if (this.value === 'completed') {
            calibrationResultTextarea.setAttribute('required', '');
            calibrationResultTextarea.closest('.mb-3').querySelector('.form-label').classList.add('required');
        } else {
            calibrationResultTextarea.removeAttribute('required');
            calibrationResultTextarea.closest('.mb-3').querySelector('.form-label').classList.remove('required');
        }
    });

    // Initialize status-dependent fields
    if (statusSelect.value === 'completed') {
        calibrationResultTextarea.setAttribute('required', '');
        calibrationResultTextarea.closest('.mb-3').querySelector('.form-label').classList.add('required');
    }
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