<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Create Asset</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('assets') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Assets
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url('assets/create') ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Asset Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?= $this->hasError('name') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('name') ?>" required>
                            <?php if ($this->hasError('name')): ?>
                                <div class="invalid-feedback"><?= $this->error('name') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Location <span class="text-danger">*</span></label>
                            <select name="location_id" class="form-select <?= $this->hasError('location_id') ? 'is-invalid' : '' ?>" required>
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

                        <div class="mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select <?= $this->hasError('type') ? 'is-invalid' : '' ?>" required>
                                <option value="">Select a type</option>
                                <option value="furniture" <?= $this->old('type') === 'furniture' ? 'selected' : '' ?>>Furniture</option>
                                <option value="equipment" <?= $this->old('type') === 'equipment' ? 'selected' : '' ?>>Equipment</option>
                                <option value="electronics" <?= $this->old('type') === 'electronics' ? 'selected' : '' ?>>Electronics</option>
                                <option value="other" <?= $this->old('type') === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                            <?php if ($this->hasError('type')): ?>
                                <div class="invalid-feedback"><?= $this->error('type') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control <?= $this->hasError('model') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('model') ?>">
                            <?php if ($this->hasError('model')): ?>
                                <div class="invalid-feedback"><?= $this->error('model') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control <?= $this->hasError('serial_number') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('serial_number') ?>">
                            <?php if ($this->hasError('serial_number')): ?>
                                <div class="invalid-feedback"><?= $this->error('serial_number') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Additional Details</h5>

                        <div class="mb-3">
                            <label class="form-label">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control <?= $this->hasError('purchase_date') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('purchase_date') ?>">
                            <?php if ($this->hasError('purchase_date')): ?>
                                <div class="invalid-feedback"><?= $this->error('purchase_date') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Purchase Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="purchase_price" class="form-control <?= $this->hasError('purchase_price') ? 'is-invalid' : '' ?>" 
                                    value="<?= $this->old('purchase_price') ?>" step="0.01" min="0">
                            </div>
                            <?php if ($this->hasError('purchase_price')): ?>
                                <div class="invalid-feedback"><?= $this->error('purchase_price') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Warranty Expiry</label>
                            <input type="date" name="warranty_expiry" class="form-control <?= $this->hasError('warranty_expiry') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('warranty_expiry') ?>">
                            <?php if ($this->hasError('warranty_expiry')): ?>
                                <div class="invalid-feedback"><?= $this->error('warranty_expiry') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select <?= $this->hasError('status') ? 'is-invalid' : '' ?>" required>
                                <option value="active" <?= $this->old('status', 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="maintenance" <?= $this->old('status') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                                <option value="inactive" <?= $this->old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <?php if ($this->hasError('status')): ?>
                                <div class="invalid-feedback"><?= $this->error('status') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control <?= $this->hasError('description') ? 'is-invalid' : '' ?>" 
                                rows="3"><?= $this->old('description') ?></textarea>
                            <?php if ($this->hasError('description')): ?>
                                <div class="invalid-feedback"><?= $this->error('description') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Create Asset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format specifications as JSON
    const specificationsTextarea = document.querySelector('textarea[name="specifications"]');
    if (specificationsTextarea) {
        specificationsTextarea.addEventListener('input', function() {
            try {
                const json = JSON.parse(this.value);
                this.value = JSON.stringify(json, null, 2);
            } catch (e) {
                // Invalid JSON, leave as is
            }
        });
    }
});
</script> 