<?php $this->layout('layouts/app', ['title' => 'Create Maintenance Request']) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('maintenance-requests') ?>">Maintenance Requests</a></li>
                    <li class="breadcrumb-item active">Create Request</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Maintenance Request</h5>
        </div>
        <div class="card-body">
            <form action="<?= url('maintenance-requests/create') ?>" method="POST" id="maintenanceRequestForm">
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
                    <div class="form-text">Select the equipment that requires maintenance</div>
                </div>

                <!-- Request Title -->
                <div class="mb-3">
                    <label for="title" class="form-label required">Request Title</label>
                    <input type="text" class="form-control" id="title" name="title" 
                           required maxlength="255" value="<?= old('title') ?>"
                           placeholder="Brief description of the maintenance needed">
                    <div class="form-text">Enter a clear, concise title for your maintenance request</div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label required">Detailed Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="4" required placeholder="Provide detailed information about the maintenance needed..."><?= old('description') ?></textarea>
                    <div class="form-text">Describe the issue or maintenance needed in detail</div>
                </div>

                <!-- Priority -->
                <div class="mb-3">
                    <label for="priority" class="form-label required">Priority Level</label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="">Select Priority</option>
                        <?php foreach ($priorities as $value => $label): ?>
                            <option value="<?= $value ?>" <?= old('priority') === $value ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Select the urgency level of this maintenance request</div>
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <label for="category" class="form-label required">Category</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= old('category') == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Select the type of maintenance needed</div>
                </div>

                <!-- Requested Completion Date -->
                <div class="mb-3">
                    <label for="requested_completion_date" class="form-label required">Requested Completion Date</label>
                    <input type="date" class="form-control" id="requested_completion_date" 
                           name="requested_completion_date" required min="<?= date('Y-m-d') ?>"
                           value="<?= old('requested_completion_date') ?>">
                    <div class="form-text">When would you like this maintenance to be completed by?</div>
                </div>

                <!-- Additional Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" 
                              rows="3" placeholder="Any additional information or special instructions..."><?= old('notes') ?></textarea>
                    <div class="form-text">Provide any additional information that might be helpful</div>
                </div>

                <!-- Attachments -->
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments</label>
                    <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                    <div class="form-text">Upload any relevant documents or images (optional)</div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Submit Request
                    </button>
                    <a href="<?= url('maintenance-requests') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-x"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('maintenanceRequestForm');
    const prioritySelect = document.getElementById('priority');
    const requestedDateInput = document.getElementById('requested_completion_date');

    // Set minimum date for requested_completion_date
    requestedDateInput.min = new Date().toISOString().split('T')[0];

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Required fields validation
        ['asset_id', 'title', 'description', 'priority', 'category', 'requested_completion_date'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Priority-specific validation
        if (prioritySelect.value === 'urgent') {
            const description = document.getElementById('description').value.trim();
            if (description.length < 50) {
                document.getElementById('description').classList.add('is-invalid');
                alert('For urgent requests, please provide a more detailed description (at least 50 characters)');
                isValid = false;
            }
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

    // Priority change handler
    prioritySelect.addEventListener('change', function() {
        const descriptionField = document.getElementById('description');
        if (this.value === 'urgent') {
            descriptionField.setAttribute('minlength', '50');
            descriptionField.closest('.mb-3').querySelector('.form-text').innerHTML = 
                'For urgent requests, please provide a detailed description (minimum 50 characters)';
        } else {
            descriptionField.removeAttribute('minlength');
            descriptionField.closest('.mb-3').querySelector('.form-text').innerHTML = 
                'Describe the issue or maintenance needed in detail';
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