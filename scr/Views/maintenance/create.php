<?php $this->layout('layouts/app', ['title' => 'Create Maintenance Schedule']) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('maintenance') ?>">Maintenance</a></li>
                    <li class="breadcrumb-item active">Create Schedule</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Maintenance Schedule</h5>
        </div>
        <div class="card-body">
            <form action="<?= url('maintenance') ?>" method="POST" id="maintenanceForm">
                <?= csrf_field() ?>

                <!-- Asset Selection -->
                <div class="mb-3">
                    <label for="asset_id" class="form-label required">Asset</label>
                    <select class="form-select" id="asset_id" name="asset_id" required>
                        <option value="">Select Asset</option>
                        <?php foreach ($assets as $asset): ?>
                            <option value="<?= $asset['id'] ?>">
                                <?= htmlspecialchars($asset['name']) ?> 
                                (<?= $asset['asset_tag'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Select the asset that requires maintenance</div>
                </div>

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label required">Title</label>
                    <input type="text" class="form-control" id="title" name="title" 
                           required maxlength="255" placeholder="e.g., Monthly HVAC Filter Replacement">
                    <div class="form-text">Enter a descriptive title for the maintenance schedule</div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="3" placeholder="Enter detailed maintenance instructions..."><?= old('description') ?></textarea>
                    <div class="form-text">Provide detailed instructions or notes about the maintenance task</div>
                </div>

                <!-- Frequency -->
                <div class="mb-3">
                    <label for="frequency" class="form-label required">Frequency</label>
                    <select class="form-select" id="frequency" name="frequency" required>
                        <option value="">Select Frequency</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="bi_weekly">Bi-Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="semi_annual">Semi-Annual</option>
                        <option value="annual">Annual</option>
                    </select>
                    <div class="form-text">How often should this maintenance be performed?</div>
                </div>

                <!-- Next Due Date -->
                <div class="mb-3">
                    <label for="next_due_date" class="form-label required">Next Due Date</label>
                    <input type="date" class="form-control" id="next_due_date" 
                           name="next_due_date" required min="<?= date('Y-m-d') ?>">
                    <div class="form-text">When should this maintenance task be performed next?</div>
                </div>

                <!-- Assigned To -->
                <div class="mb-3">
                    <label for="assigned_to" class="form-label">Assign To</label>
                    <select class="form-select" id="assigned_to" name="assigned_to">
                        <option value="">Select Technician</option>
                        <?php foreach ($technicians as $tech): ?>
                            <option value="<?= $tech['id'] ?>">
                                <?= htmlspecialchars($tech['first_name'] . ' ' . $tech['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Select a technician to assign this maintenance task to (optional)</div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Create Schedule
                    </button>
                    <a href="<?= url('maintenance') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-x"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('maintenanceForm');
    const frequencySelect = document.getElementById('frequency');
    const nextDueDateInput = document.getElementById('next_due_date');

    // Set minimum date for next_due_date
    nextDueDateInput.min = new Date().toISOString().split('T')[0];

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Required fields validation
        ['asset_id', 'title', 'frequency', 'next_due_date'].forEach(fieldId => {
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