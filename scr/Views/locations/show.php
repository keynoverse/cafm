<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Location Details</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('locations') ?>" class="btn btn-secondary me-2">
                <i class='bx bx-arrow-back'></i> Back to Locations
            </a>
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url("locations/{$location['id']}/edit") ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Location
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Location Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Location Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <td><?= htmlspecialchars($location['name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Room</th>
                                    <td>
                                        <a href="<?= $this->url("rooms/{$location['room_id']}") ?>">
                                            <?= htmlspecialchars($location['room_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Floor</th>
                                    <td>
                                        <a href="<?= $this->url("floors/{$location['floor_id']}") ?>">
                                            <?= htmlspecialchars($location['floor_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Building</th>
                                    <td>
                                        <a href="<?= $this->url("buildings/{$location['building_id']}") ?>">
                                            <?= htmlspecialchars($location['building_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td><?= ucfirst(str_replace('_', ' ', $location['type'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $location['status'] === 'active' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($location['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <?php if ($location['description']): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Description</h5>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($location['description'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <?php if ($this->user['role'] === 'admin'): ?>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class='bx bx-trash'></i> Delete Location
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Location Timeline -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Location Timeline</h5>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date"><?= date('M d, Y H:i', strtotime($location['created_at'])) ?></div>
                            <div class="timeline-content">
                                <p class="mb-0">Location created</p>
                            </div>
                        </div>
                        <?php if ($location['updated_at'] !== $location['created_at']): ?>
                            <div class="timeline-item">
                                <div class="timeline-date"><?= date('M d, Y H:i', strtotime($location['updated_at'])) ?></div>
                                <div class="timeline-content">
                                    <p class="mb-0">Location updated</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this location? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?= $this->url("locations/{$location['id']}/delete") ?>" class="d-inline">
                    <?= $this->csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 20px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item:after {
    content: '';
    position: absolute;
    left: -4px;
    top: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #0d6efd;
}

.timeline-date {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 5px;
}

.timeline-content {
    font-size: 0.875rem;
}
</style> 