<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Edit Location</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('locations') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Locations
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url("locations/{$location['id']}/edit") ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Location Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?= $this->hasError('name') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('name', $location['name']) ?>" required>
                            <?php if ($this->hasError('name')): ?>
                                <div class="invalid-feedback"><?= $this->error('name') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Room <span class="text-danger">*</span></label>
                            <select name="room_id" class="form-select <?= $this->hasError('room_id') ? 'is-invalid' : '' ?>" required>
                                <option value="">Select a room</option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?= $room['id'] ?>" 
                                        <?= $this->old('room_id', $location['room_id']) == $room['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($room['building_name'] . ' - ' . $room['floor_name'] . ' - ' . $room['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('room_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('room_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select <?= $this->hasError('type') ? 'is-invalid' : '' ?>" required>
                                <option value="">Select a type</option>
                                <option value="desk" <?= $this->old('type', $location['type']) === 'desk' ? 'selected' : '' ?>>Desk</option>
                                <option value="cabinet" <?= $this->old('type', $location['type']) === 'cabinet' ? 'selected' : '' ?>>Cabinet</option>
                                <option value="storage" <?= $this->old('type', $location['type']) === 'storage' ? 'selected' : '' ?>>Storage</option>
                                <option value="other" <?= $this->old('type', $location['type']) === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                            <?php if ($this->hasError('type')): ?>
                                <div class="invalid-feedback"><?= $this->error('type') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Additional Details</h5>

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select <?= $this->hasError('status') ? 'is-invalid' : '' ?>" required>
                                <option value="active" <?= $this->old('status', $location['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $this->old('status', $location['status']) === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <?php if ($this->hasError('status')): ?>
                                <div class="invalid-feedback"><?= $this->error('status') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control <?= $this->hasError('description') ? 'is-invalid' : '' ?>" 
                                rows="3"><?= $this->old('description', $location['description']) ?></textarea>
                            <?php if ($this->hasError('description')): ?>
                                <div class="invalid-feedback"><?= $this->error('description') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Update Location
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buildingSelect = document.querySelector('select[name="building_id"]');
    const floorSelect = document.querySelector('select[name="floor_id"]');
    const roomSelect = document.querySelector('select[name="room_id"]');

    // Reset dependent selects when building changes
    buildingSelect.addEventListener('change', function() {
        floorSelect.innerHTML = '<option value="">Select a floor</option>';
        roomSelect.innerHTML = '<option value="">Select a room</option>';
        
        if (this.value) {
            fetch(`<?= $this->url('locations/floors') ?>?building_id=${this.value}`)
                .then(response => response.json())
                .then(data => {
                    data.floors.forEach(floor => {
                        const option = document.createElement('option');
                        option.value = floor.id;
                        option.textContent = floor.name;
                        floorSelect.appendChild(option);
                    });
                });
        }
    });

    // Reset room select when floor changes
    floorSelect.addEventListener('change', function() {
        roomSelect.innerHTML = '<option value="">Select a room</option>';
        
        if (this.value) {
            fetch(`<?= $this->url('locations/rooms') ?>?floor_id=${this.value}`)
                .then(response => response.json())
                .then(data => {
                    data.rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = room.name;
                        roomSelect.appendChild(option);
                    });
                });
        }
    });

    // Trigger change events if values are pre-selected
    if (buildingSelect.value) {
        buildingSelect.dispatchEvent(new Event('change'));
        if (floorSelect.value) {
            floorSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script> 