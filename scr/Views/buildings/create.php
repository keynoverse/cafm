<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Create Building</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('buildings') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Buildings
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url('buildings/create') ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Building Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?= $this->hasError('name') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('name') ?>" required>
                            <?php if ($this->hasError('name')): ?>
                                <div class="invalid-feedback"><?= $this->error('name') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control <?= $this->hasError('address') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('address') ?>" required>
                            <?php if ($this->hasError('address')): ?>
                                <div class="invalid-feedback"><?= $this->error('address') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control <?= $this->hasError('city') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('city') ?>" required>
                            <?php if ($this->hasError('city')): ?>
                                <div class="invalid-feedback"><?= $this->error('city') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">State/Province <span class="text-danger">*</span></label>
                            <input type="text" name="state" class="form-control <?= $this->hasError('state') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('state') ?>" required>
                            <?php if ($this->hasError('state')): ?>
                                <div class="invalid-feedback"><?= $this->error('state') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Additional Details</h5>

                        <div class="mb-3">
                            <label class="form-label">Country <span class="text-danger">*</span></label>
                            <input type="text" name="country" class="form-control <?= $this->hasError('country') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('country') ?>" required>
                            <?php if ($this->hasError('country')): ?>
                                <div class="invalid-feedback"><?= $this->error('country') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Postal Code <span class="text-danger">*</span></label>
                            <input type="text" name="postal_code" class="form-control <?= $this->hasError('postal_code') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('postal_code') ?>" required>
                            <?php if ($this->hasError('postal_code')): ?>
                                <div class="invalid-feedback"><?= $this->error('postal_code') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select <?= $this->hasError('status') ? 'is-invalid' : '' ?>" required>
                                <option value="active" <?= $this->old('status', 'active') === 'active' ? 'selected' : '' ?>>Active</option>
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
                        <i class='bx bx-save'></i> Create Building
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 