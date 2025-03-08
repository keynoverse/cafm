<?php $this->layout('layouts/app', ['title' => 'Create SLA']) ?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('sla') ?>">SLA Management</a></li>
                    <li class="breadcrumb-item active">Create SLA</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Service Level Agreement</h5>
        </div>
        <div class="card-body">
            <form action="<?= url('sla/create') ?>" method="POST" id="slaForm">
                <?= csrf_field() ?>

                <!-- SLA Name -->
                <div class="mb-3">
                    <label for="name" class="form-label required">SLA Name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           required maxlength="255" value="<?= old('name') ?>"
                           placeholder="Enter SLA name">
                    <div class="form-text">Enter a descriptive name for this SLA</div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label required">Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="3" required><?= old('description') ?></textarea>
                    <div class="form-text">Provide a detailed description of this SLA</div>
                </div>

                <!-- Priority -->
                <div class="mb-3">
                    <label for="priority" class="form-label required">Priority Level</label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="">Select Priority</option>
                        <option value="low" <?= old('priority') === 'low' ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= old('priority') === 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= old('priority') === 'high' ? 'selected' : '' ?>>High</option>
                        <option value="urgent" <?= old('priority') === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                    </select>
                    <div class="form-text">Select the priority level for this SLA</div>
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
                    <div class="form-text">Select the category this SLA applies to</div>
                </div>

                <!-- Response Time -->
                <div class="mb-3">
                    <label for="response_time" class="form-label required">Response Time (hours)</label>
                    <input type="number" class="form-control" id="response_time" name="response_time" 
                           required min="1" value="<?= old('response_time') ?>"
                           placeholder="Enter response time in hours">
                    <div class="form-text">Maximum time allowed to respond to a request</div>
                </div>

                <!-- Resolution Time -->
                <div class="mb-3">
                    <label for="resolution_time" class="form-label required">Resolution Time (hours)</label>
                    <input type="number" class="form-control" id="resolution_time" name="resolution_time" 
                           required min="1" value="<?= old('resolution_time') ?>"
                           placeholder="Enter resolution time in hours">
                    <div class="form-text">Maximum time allowed to resolve a request</div>
                </div>

                <!-- Escalation Time -->
                <div class="mb-3">
                    <label for="escalation_time" class="form-label required">Escalation Time (hours)</label>
                    <input type="number" class="form-control" id="escalation_time" name="escalation_time" 
                           required min="1" value="<?= old('escalation_time') ?>"
                           placeholder="Enter escalation time in hours">
                    <div class="form-text">Time after which the request should be escalated</div>
                </div>

                <!-- Business Hours Only -->
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="business_hours_only" 
                               name="business_hours_only" value="1" <?= old('business_hours_only') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="business_hours_only">
                            Apply during business hours only
                        </label>
                    </div>
                    <div class="form-text">If checked, SLA times will only be counted during business hours</div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Create SLA
                    </button>
                    <a href="<?= url('sla') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-x"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('slaForm');
    const prioritySelect = document.getElementById('priority');
    const responseTimeInput = document.getElementById('response_time');
    const resolutionTimeInput = document.getElementById('resolution_time');
    const escalationTimeInput = document.getElementById('escalation_time');

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Required fields validation
        ['name', 'description', 'priority', 'category', 'response_time', 'resolution_time', 'escalation_time']
            .forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

        // Time validations
        const responseTime = parseInt(responseTimeInput.value);
        const resolutionTime = parseInt(resolutionTimeInput.value);
        const escalationTime = parseInt(escalationTimeInput.value);

        if (responseTime >= resolutionTime) {
            responseTimeInput.classList.add('is-invalid');
            resolutionTimeInput.classList.add('is-invalid');
            alert('Response time must be less than resolution time');
            isValid = false;
        }

        if (escalationTime >= resolutionTime) {
            escalationTimeInput.classList.add('is-invalid');
            resolutionTimeInput.classList.add('is-invalid');
            alert('Escalation time must be less than resolution time');
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

    // Priority change handler
    prioritySelect.addEventListener('change', function() {
        const suggestedTimes = {
            'low': { response: 24, resolution: 72, escalation: 48 },
            'medium': { response: 12, resolution: 48, escalation: 24 },
            'high': { response: 4, resolution: 24, escalation: 8 },
            'urgent': { response: 1, resolution: 4, escalation: 2 }
        };

        if (this.value && suggestedTimes[this.value]) {
            const times = suggestedTimes[this.value];
            if (!responseTimeInput.value) responseTimeInput.value = times.response;
            if (!resolutionTimeInput.value) resolutionTimeInput.value = times.resolution;
            if (!escalationTimeInput.value) escalationTimeInput.value = times.escalation;
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