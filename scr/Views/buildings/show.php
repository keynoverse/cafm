<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Building Details</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('buildings') ?>" class="btn btn-secondary me-2">
                <i class='bx bx-arrow-back'></i> Back to Buildings
            </a>
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url("buildings/{$building['id']}/edit") ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Building
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Building Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Building Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <td><?= htmlspecialchars($building['name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $building['status'] === 'active' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($building['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Floors</th>
                                    <td><?= $building['total_floors'] ?></td>
                                </tr>
                                <tr>
                                    <th>Total Rooms</th>
                                    <td><?= $building['total_rooms'] ?></td>
                                </tr>
                                <tr>
                                    <th>Total Locations</th>
                                    <td><?= $building['total_locations'] ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Address Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Address</th>
                                    <td><?= htmlspecialchars($building['address']) ?></td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td><?= htmlspecialchars($building['city']) ?></td>
                                </tr>
                                <tr>
                                    <th>State/Province</th>
                                    <td><?= htmlspecialchars($building['state']) ?></td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td><?= htmlspecialchars($building['country']) ?></td>
                                </tr>
                                <tr>
                                    <th>Postal Code</th>
                                    <td><?= htmlspecialchars($building['postal_code']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <?php if ($building['description']): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Description</h5>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($building['description'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Building Hierarchy -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Building Hierarchy</h5>
                    <div class="building-hierarchy">
                        <?php foreach ($hierarchy['floors'] as $floor): ?>
                            <div class="floor mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class='bx bx-layer text-primary me-2'></i>
                                    <h6 class="mb-0"><?= htmlspecialchars($floor['name']) ?></h6>
                                </div>
                                <?php foreach ($floor['rooms'] as $room): ?>
                                    <div class="room ms-4 mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class='bx bx-door-open text-secondary me-2'></i>
                                            <span><?= htmlspecialchars($room['name']) ?></span>
                                        </div>
                                        <?php if (!empty($room['locations'])): ?>
                                            <div class="locations ms-4">
                                                <?php foreach ($room['locations'] as $location): ?>
                                                    <div class="location mb-1">
                                                        <div class="d-flex align-items-center">
                                                            <i class='bx bx-map text-info me-2'></i>
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
                                <i class='bx bx-trash'></i> Delete Building
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Building Timeline -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Building Timeline</h5>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date"><?= date('M d, Y H:i', strtotime($building['created_at'])) ?></div>
                            <div class="timeline-content">
                                <p class="mb-0">Building created</p>
                            </div>
                        </div>
                        <?php if ($building['updated_at'] !== $building['created_at']): ?>
                            <div class="timeline-item">
                                <div class="timeline-date"><?= date('M d, Y H:i', strtotime($building['updated_at'])) ?></div>
                                <div class="timeline-content">
                                    <p class="mb-0">Building updated</p>
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
                <h5 class="modal-title">Delete Building</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this building? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?= $this->url("buildings/{$building['id']}/delete") ?>" class="d-inline">
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

.building-hierarchy {
    max-height: 600px;
    overflow-y: auto;
}

.building-hierarchy .floor,
.building-hierarchy .room,
.building-hierarchy .location {
    position: relative;
}

.building-hierarchy .floor:before,
.building-hierarchy .room:before {
    content: '';
    position: absolute;
    left: -1rem;
    top: 0;
    bottom: 0;
    width: 1px;
    background: #e9ecef;
}

.building-hierarchy .floor:last-child:before {
    display: none;
}

.building-hierarchy .room:last-child:before {
    display: none;
}
</style> 