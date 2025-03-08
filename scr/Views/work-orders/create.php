<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Create Work Order</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('work-orders') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Work Orders
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url('work-orders/create') ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Work Order Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control <?= $this->hasError('title') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('title') ?>" required>
                            <?php if ($this->hasError('title')): ?>
                                <div class="invalid-feedback"><?= $this->error('title') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control <?= $this->hasError('description') ? 'is-invalid' : '' ?>" 
                                rows="3" required><?= $this->old('description') ?></textarea>
                            <?php if ($this->hasError('description')): ?>
                                <div class="invalid-feedback"><?= $this->error('description') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Priority <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select <?= $this->hasError('priority') ? 'is-invalid' : '' ?>" required>
                                <option value="">Select a priority</option>
                                <option value="low" <?= $this->old('priority') === 'low' ? 'selected' : '' ?>>Low</option>
                                <option value="medium" <?= $this->old('priority') === 'medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="high" <?= $this->old('priority') === 'high' ? 'selected' : '' ?>>High</option>
                                <option value="urgent" <?= $this->old('priority') === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                            </select>
                            <?php if ($this->hasError('priority')): ?>
                                <div class="invalid-feedback"><?= $this->error('priority') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select <?= $this->hasError('status') ? 'is-invalid' : '' ?>" required>
                                <option value="pending" <?= $this->old('status', 'pending') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="in_progress" <?= $this->old('status') === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                <option value="completed" <?= $this->old('status') === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= $this->old('status') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <?php if ($this->hasError('status')): ?>
                                <div class="invalid-feedback"><?= $this->error('status') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Asset</label>
                            <select name="asset_id" class="form-select <?= $this->hasError('asset_id') ? 'is-invalid' : '' ?>">
                                <option value="">Select an asset</option>
                                <?php foreach ($assets as $asset): ?>
                                    <option value="<?= $asset['id'] ?>" <?= $this->old('asset_id') == $asset['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($asset['name']) ?> 
                                        (<?= htmlspecialchars($asset['location_name']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('asset_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('asset_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <select name="location_id" class="form-select <?= $this->hasError('location_id') ? 'is-invalid' : '' ?>">
                                <option value="">Select a location</option>
                                <?php foreach ($locations as $location): ?>
                                    <option value="<?= $location['id'] ?>" <?= $this->old('location_id') == $location['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($location['building_name'] . ' - ' . $location['floor_name'] . ' - ' . $location['room_name'] . ' - ' . $location['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('location_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('location_id') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Additional Details</h5>

                        <div class="mb-3">
                            <label class="form-label">Assigned To</label>
                            <select name="assigned_to" class="form-select <?= $this->hasError('assigned_to') ? 'is-invalid' : '' ?>">
                                <option value="">Select a technician</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>" <?= $this->old('assigned_to') == $user['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('assigned_to')): ?>
                                <div class="invalid-feedback"><?= $this->error('assigned_to') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control <?= $this->hasError('due_date') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('due_date') ?>">
                            <?php if ($this->hasError('due_date')): ?>
                                <div class="invalid-feedback"><?= $this->error('due_date') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estimated Cost</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="estimated_cost" class="form-control <?= $this->hasError('estimated_cost') ? 'is-invalid' : '' ?>" 
                                    value="<?= $this->old('estimated_cost') ?>" step="0.01" min="0">
                            </div>
                            <?php if ($this->hasError('estimated_cost')): ?>
                                <div class="invalid-feedback"><?= $this->error('estimated_cost') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Actual Cost</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="actual_cost" class="form-control <?= $this->hasError('actual_cost') ? 'is-invalid' : '' ?>" 
                                    value="<?= $this->old('actual_cost') ?>" step="0.01" min="0">
                            </div>
                            <?php if ($this->hasError('actual_cost')): ?>
                                <div class="invalid-feedback"><?= $this->error('actual_cost') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Create Work Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Disable location selection if asset is selected
    const assetSelect = document.querySelector('select[name="asset_id"]');
    const locationSelect = document.querySelector('select[name="location_id"]');

    assetSelect.addEventListener('change', function() {
        if (this.value) {
            locationSelect.value = '';
            locationSelect.disabled = true;
        } else {
            locationSelect.disabled = false;
        }
    });

    // Disable asset selection if location is selected
    locationSelect.addEventListener('change', function() {
        if (this.value) {
            assetSelect.value = '';
            assetSelect.disabled = true;
        } else {
            assetSelect.disabled = false;
        }
    });

    // Initialize the disabled state
    if (assetSelect.value) {
        locationSelect.disabled = true;
    } else if (locationSelect.value) {
        assetSelect.disabled = true;
    }
});
</script> 