<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Floor Details</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('floors') ?>" class="btn btn-secondary me-2">
                <i class='bx bx-arrow-back'></i> Back to Floors
            </a>
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url("floors/{$floor['id']}/edit") ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Floor
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Floor Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Floor Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <td><?= htmlspecialchars($floor['name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Building</th>
                                    <td>
                                        <a href="<?= $this->url("buildings/{$floor['building_id']}") ?>">
                                            <?= htmlspecialchars($floor['building_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $floor['status'] === 'active' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($floor['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Rooms</th>
                                    <td><?= $floor['total_rooms'] ?></td>
                                </tr>
                                <tr>
                                    <th>Total Locations</th>
                                    <td><?= $floor['total_locations'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <?php if ($floor['description']): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Description</h5>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($floor['description'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Floor Hierarchy -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Floor Hierarchy</h5>
                    <div class="floor-hierarchy">
                        <?php foreach ($hierarchy['rooms'] as $room): ?>
                            <div class="room mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class='bx bx-door-open text-primary me-2'></i>
                                    <h6 class="mb-0"><?= htmlspecialchars($room['name']) ?></h6>
                                </div>
                                <?php if (!empty($room['locations'])): ?>
                                    <div class="locations ms-4">
                                        <?php foreach ($room['locations'] as $location): ?>
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
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
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
                                <i class='bx bx-trash'></i> Delete Floor
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Floor Timeline -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Floor Timeline</h5>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date"><?= date('M d, Y H:i', strtotime($floor['created_at'])) ?></div>
                            <div class="timeline-content">
                                <p class="mb-0">Floor created</p>
                            </div>
                        </div>
                        <?php if ($floor['updated_at'] !== $floor['created_at']): ?>
                            <div class="timeline-item">
                                <div class="timeline-date"><?= date('M d, Y H:i', strtotime($floor['updated_at'])) ?></div>
                                <div class="timeline-content">
                                    <p class="mb-0">Floor updated</p>
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
                <h5 class="modal-title">Delete Floor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this floor? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?= $this->url("floors/{$floor['id']}/delete") ?>" class="d-inline">
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

.floor-hierarchy {
    max-height: 600px;
    overflow-y: auto;
}

.floor-hierarchy .room,
.floor-hierarchy .location {
    position: relative;
}

.floor-hierarchy .room:before {
    content: '';
    position: absolute;
    left: -1rem;
    top: 0;
    bottom: 0;
    width: 1px;
    background: #e9ecef;
}

.floor-hierarchy .room:last-child:before {
    display: none;
}
</style> 