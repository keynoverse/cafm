<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Room Details</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('rooms') ?>" class="btn btn-secondary me-2">
                <i class='bx bx-arrow-back'></i> Back to Rooms
            </a>
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url("rooms/{$room['id']}/edit") ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Room
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Room Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Room Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <td><?= htmlspecialchars($room['name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Floor</th>
                                    <td>
                                        <a href="<?= $this->url("floors/{$room['floor_id']}") ?>">
                                            <?= htmlspecialchars($room['floor_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Building</th>
                                    <td>
                                        <a href="<?= $this->url("buildings/{$room['building_id']}") ?>">
                                            <?= htmlspecialchars($room['building_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $room['status'] === 'active' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($room['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Locations</th>
                                    <td><?= $room['total_locations'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <?php if ($room['description']): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Description</h5>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($room['description'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Room Hierarchy -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Room Hierarchy</h5>
                    <div class="room-hierarchy">
                        <?php if (!empty($hierarchy['locations'])): ?>
                            <?php foreach ($hierarchy['locations'] as $location): ?>
                                <div class="location mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class='bx bx-map text-secondary me-2'></i>
                                        <span>
                                            <?= htmlspecialchars($location['name']) ?>
                                            <span class="badge bg-<?= $location['status'] === 'active' ? 'success' : 'danger' ?> ms-1">
                                                <?= ucfirst($location['status']) ?>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">No locations found in this room.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
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
                                <i class='bx bx-trash'></i> Delete Room
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Room Timeline -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Room Timeline</h5>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date"><?= date('M d, Y H:i', strtotime($room['created_at'])) ?></div>
                            <div class="timeline-content">
                                <p class="mb-0">Room created</p>
                            </div>
                        </div>
                        <?php if ($room['updated_at'] !== $room['created_at']): ?>
                            <div class="timeline-item">
                                <div class="timeline-date"><?= date('M d, Y H:i', strtotime($room['updated_at'])) ?></div>
                                <div class="timeline-content">
                                    <p class="mb-0">Room updated</p>
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
                <h5 class="modal-title">Delete Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this room? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?= $this->url("rooms/{$room['id']}/delete") ?>" class="d-inline">
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

.room-hierarchy {
    max-height: 600px;
    overflow-y: auto;
}

.room-hierarchy .location {
    position: relative;
    padding-left: 1rem;
}

.room-hierarchy .location:before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 1px;
    background: #e9ecef;
}
</style> 