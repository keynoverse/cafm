<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Create Floor</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('floors') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Floors
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url('floors/create') ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Floor Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?= $this->hasError('name') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('name') ?>" required>
                            <?php if ($this->hasError('name')): ?>
                                <div class="invalid-feedback"><?= $this->error('name') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Building <span class="text-danger">*</span></label>
                            <select name="building_id" class="form-select <?= $this->hasError('building_id') ? 'is-invalid' : '' ?>" required>
                                <option value="">Select a building</option>
                                <?php foreach ($buildings as $building): ?>
                                    <option value="<?= $building['id'] ?>" <?= $this->old('building_id') == $building['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($building['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('building_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('building_id') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Additional Details</h5>

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
                        <i class='bx bx-save'></i> Create Floor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 