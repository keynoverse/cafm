<?php $this->layout('layouts/app', ['title' => 'Edit Maintenance Request']) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('maintenance-requests') ?>">Maintenance Requests</a></li>
                    <li class="breadcrumb-item">
                        <a href="<?= url("maintenance-requests/{$request['id']}") ?>">
                            <?= htmlspecialchars($request['title']) ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Edit Maintenance Request</h5>
            <div>
                <span class="badge bg-<?= $request['status'] === 'pending' ? 'secondary' : 
                    ($request['status'] === 'approved' ? 'info' : 
                    ($request['status'] === 'in_progress' ? 'primary' : 
                    ($request['status'] === 'completed' ? 'success' : 'danger'))) ?>">
                    <?= ucfirst(str_replace('_', ' ', $request['status'])) ?>
                </span>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= url("maintenance-requests/{$request['id']}/edit") ?>" method="POST" id="maintenanceRequestForm">
                <?= csrf_field() ?>

                <!-- Asset Selection -->
                <div class="mb-3">
                    <label for="asset_id" class="form-label required">Equipment/Asset</label>
                    <select class="form-select" id="asset_id" name="asset_id" required>
                        <option value="">Select Equipment</option>
                        <?php foreach ($assets as $asset): ?>
                            <option value="<?= $asset['id'] ?>" <?= $request['asset_id'] == $asset['id'] ? 'selected' : '' ?>>
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
                           required maxlength="255" value="<?= htmlspecialchars($request['title']) ?>"
                           placeholder="Brief description of the maintenance needed">
                    <div class="form-text">Enter a clear, concise title for your maintenance request</div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label required">Detailed Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="4" required placeholder="Provide detailed information about the maintenance needed..."><?= htmlspecialchars($request['description']) ?></textarea>
                    <div class="form-text">Describe the issue or maintenance needed in detail</div>
                </div>

                <!-- Priority -->
                <div class="mb-3">
                    <label for="priority" class="form-label required">Priority Level</label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="">Select Priority</option>
                        <?php foreach ($priorities as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $request['priority'] === $value ? 'selected' : '' ?>>
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
                            <option value="<?= $category['id'] ?>" <?= $request['category_id'] == $category['id'] ? 'selected' : '' ?>>
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
                           name="requested_completion_date" required
                           value="<?= date('Y-m-d', strtotime($request['requested_completion_date'])) ?>">
                    <div class="form-text">When would you like this maintenance to be completed by?</div>
                </div>

                <!-- Status -->
                <?php if ($this->user->hasPermission('update_maintenance_request_status')): ?>
                    <div class="mb-3">
                        <label for="status" class="form-label required">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" <?= $request['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= $request['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="in_progress" <?= $request['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="completed" <?= $request['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="rejected" <?= $request['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                        <div class="form-text">Update the current status of this maintenance request</div>
                    </div>

                    <!-- Rejection Reason (shown only when status is rejected) -->
                    <div class="mb-3" id="rejectionReasonContainer" style="display: none;">
                        <label for="rejection_reason" class="form-label required">Rejection Reason</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="3" placeholder="Please provide a reason for rejecting this request..."><?= htmlspecialchars($request['rejection_reason'] ?? '') ?></textarea>
                        <div class="form-text">Explain why this maintenance request is being rejected</div>
                    </div>
                <?php endif; ?>

                <!-- Additional Notes -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" 
                              rows="3" placeholder="Any additional information or special instructions..."><?= htmlspecialchars($request['notes']) ?></textarea>
                    <div class="form-text">Provide any additional information that might be helpful</div>
                </div>

                <!-- Current Attachments -->
                <?php if (!empty($request['attachments'])): ?>
                    <div class="mb-3">
                        <label class="form-label">Current Attachments</label>
                        <div class="list-group">
                            <?php foreach ($request['attachments'] as $attachment): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="<?= url("maintenance-requests/attachments/{$attachment['id']}") ?>" target="_blank">
                                        <?= htmlspecialchars($attachment['filename']) ?>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-attachment" 
                                            data-id="<?= $attachment['id'] ?>">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- New Attachments -->
                <div class="mb-3">
                    <label for="attachments" class="form-label">Add Attachments</label>
                    <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                    <div class="form-text">Upload any additional documents or images (optional)</div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Save Changes
                    </button>
                    <a href="<?= url("maintenance-requests/{$request['id']}") ?>" class="btn btn-outline-secondary">
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
    const statusSelect = document.getElementById('status');
    const rejectionReasonContainer = document.getElementById('rejectionReasonContainer');
    const rejectionReasonTextarea = document.getElementById('rejection_reason');

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

        // Status-specific validation
        if (statusSelect && statusSelect.value === 'rejected') {
            if (!rejectionReasonTextarea.value.trim()) {
                rejectionReasonTextarea.classList.add('is-invalid');
                alert('Please provide a reason for rejecting this request');
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

    // Status change handler
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'rejected') {
                rejectionReasonContainer.style.display = 'block';
                rejectionReasonTextarea.setAttribute('required', '');
            } else {
                rejectionReasonContainer.style.display = 'none';
                rejectionReasonTextarea.removeAttribute('required');
            }
        });

        // Initialize status-dependent fields
        if (statusSelect.value === 'rejected') {
            rejectionReasonContainer.style.display = 'block';
            rejectionReasonTextarea.setAttribute('required', '');
        }
    }

    // Handle attachment deletion
    document.querySelectorAll('.delete-attachment').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to delete this attachment?')) {
                fetch(`<?= url('maintenance-requests') ?>/attachments/${id}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': '<?= csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.list-group-item').remove();
                    } else {
                        alert('Failed to delete attachment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the attachment');
                });
            }
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

.list-group-item {
    transition: background-color 0.3s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

@media (max-width: 768px) {
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style> 