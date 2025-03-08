<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Edit Room</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('rooms') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Rooms
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url("rooms/{$room['id']}/edit") ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Room Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?= $this->hasError('name') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('name', $room['name']) ?>" required>
                            <?php if ($this->hasError('name')): ?>
                                <div class="invalid-feedback"><?= $this->error('name') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Floor <span class="text-danger">*</span></label>
                            <select name="floor_id" class="form-select <?= $this->hasError('floor_id') ? 'is-invalid' : '' ?>" required>
                                <option value="">Select a floor</option>
                                <?php foreach ($floors as $floor): ?>
                                    <option value="<?= $floor['id'] ?>" 
                                        <?= $this->old('floor_id', $room['floor_id']) == $floor['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($floor['building_name'] . ' - ' . $floor['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('floor_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('floor_id') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Additional Details</h5>

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select <?= $this->hasError('status') ? 'is-invalid' : '' ?>" required>
                                <option value="active" <?= $this->old('status', $room['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $this->old('status', $room['status']) === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <?php if ($this->hasError('status')): ?>
                                <div class="invalid-feedback"><?= $this->error('status') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control <?= $this->hasError('description') ? 'is-invalid' : '' ?>" 
                                rows="3"><?= $this->old('description', $room['description']) ?></textarea>
                            <?php if ($this->hasError('description')): ?>
                                <div class="invalid-feedback"><?= $this->error('description') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Update Room
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 