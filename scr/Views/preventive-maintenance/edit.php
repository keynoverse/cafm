<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Edit Preventive Maintenance Task</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('preventive-maintenance') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Tasks
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url("preventive-maintenance/{$task['id']}/edit") ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control <?= $this->hasError('title') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('title', $task['title']) ?>" required>
                            <?php if ($this->hasError('title')): ?>
                                <div class="invalid-feedback"><?= $this->error('title') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Asset <span class="text-danger">*</span></label>
                            <select name="asset_id" class="form-select <?= $this->hasError('asset_id') ? 'is-invalid' : '' ?>" required>
                                <option value="">Select Asset</option>
                                <?php foreach ($assets as $asset): ?>
                                    <option value="<?= $asset['id'] ?>" <?= $this->old('asset_id', $task['asset_id']) == $asset['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($asset['name']) ?> (<?= htmlspecialchars($asset['asset_tag']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('asset_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('asset_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Frequency <span class="text-danger">*</span></label>
                            <select name="frequency" class="form-select <?= $this->hasError('frequency') ? 'is-invalid' : '' ?>" required>
                                <option value="">Select Frequency</option>
                                <option value="daily" <?= $this->old('frequency', $task['frequency']) == 'daily' ? 'selected' : '' ?>>Daily</option>
                                <option value="weekly" <?= $this->old('frequency', $task['frequency']) == 'weekly' ? 'selected' : '' ?>>Weekly</option>
                                <option value="monthly" <?= $this->old('frequency', $task['frequency']) == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                <option value="quarterly" <?= $this->old('frequency', $task['frequency']) == 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                                <option value="yearly" <?= $this->old('frequency', $task['frequency']) == 'yearly' ? 'selected' : '' ?>>Yearly</option>
                            </select>
                            <?php if ($this->hasError('frequency')): ?>
                                <div class="invalid-feedback"><?= $this->error('frequency') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Assigned To</label>
                            <select name="assigned_to" class="form-select <?= $this->hasError('assigned_to') ? 'is-invalid' : '' ?>">
                                <option value="">Select User</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>" <?= $this->old('assigned_to', $task['assigned_to']) == $user['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('assigned_to')): ?>
                                <div class="invalid-feedback"><?= $this->error('assigned_to') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Next Due Date <span class="text-danger">*</span></label>
                            <input type="date" name="next_due" class="form-control <?= $this->hasError('next_due') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('next_due', $task['next_due']) ?>" required>
                            <?php if ($this->hasError('next_due')): ?>
                                <div class="invalid-feedback"><?= $this->error('next_due') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Last Performed</label>
                            <input type="date" class="form-control" value="<?= $task['last_performed'] ? date('Y-m-d', strtotime($task['last_performed'])) : '' ?>" disabled>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Description</h5>

                        <div class="mb-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control <?= $this->hasError('description') ? 'is-invalid' : '' ?>" 
                                rows="10" required><?= $this->old('description', $task['description']) ?></textarea>
                            <?php if ($this->hasError('description')): ?>
                                <div class="invalid-feedback"><?= $this->error('description') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 