<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Asset Details</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('assets') ?>" class="btn btn-secondary me-2">
                <i class='bx bx-arrow-back'></i> Back to Assets
            </a>
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url("assets/{$asset['id']}/edit") ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Asset
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Asset Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Asset Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <td><?= htmlspecialchars($asset['name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>
                                        <a href="<?= $this->url("locations/{$asset['location_id']}") ?>">
                                            <?= htmlspecialchars($asset['location_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Room</th>
                                    <td>
                                        <a href="<?= $this->url("rooms/{$asset['room_id']}") ?>">
                                            <?= htmlspecialchars($asset['room_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Floor</th>
                                    <td>
                                        <a href="<?= $this->url("floors/{$asset['floor_id']}") ?>">
                                            <?= htmlspecialchars($asset['floor_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Building</th>
                                    <td>
                                        <a href="<?= $this->url("buildings/{$asset['building_id']}") ?>">
                                            <?= htmlspecialchars($asset['building_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td><?= ucfirst(str_replace('_', ' ', $asset['type'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Model</th>
                                    <td><?= htmlspecialchars($asset['model']) ?></td>
                                </tr>
                                <tr>
                                    <th>Serial Number</th>
                                    <td><?= htmlspecialchars($asset['serial_number']) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $asset['status'] === 'active' ? 'success' : 
                                            ($asset['status'] === 'maintenance' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($asset['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Asset Details -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Asset Details</h5>
                    <table class="table">
                        <tr>
                            <th>Purchase Date</th>
                            <td><?= $asset['purchase_date'] ? date('M d, Y', strtotime($asset['purchase_date'])) : '-' ?></td>
                        </tr>
                        <tr>
                            <th>Purchase Price</th>
                            <td><?= $asset['purchase_price'] ? '$' . number_format($asset['purchase_price'], 2) : '-' ?></td>
                        </tr>
                        <tr>
                            <th>Warranty Expiry</th>
                            <td><?= $asset['warranty_expiry'] ? date('M d, Y', strtotime($asset['warranty_expiry'])) : '-' ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Description -->
            <?php if ($asset['description']): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Description</h5>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($asset['description'])) ?></p>
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
                                <i class='bx bx-trash'></i> Delete Asset
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Asset Timeline -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Asset Timeline</h5>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date"><?= date('M d, Y H:i', strtotime($asset['created_at'])) ?></div>
                            <div class="timeline-content">
                                <p class="mb-0">Asset created</p>
                            </div>
                        </div>
                        <?php if ($asset['updated_at'] !== $asset['created_at']): ?>
                            <div class="timeline-item">
                                <div class="timeline-date"><?= date('M d, Y H:i', strtotime($asset['updated_at'])) ?></div>
                                <div class="timeline-content">
                                    <p class="mb-0">Asset updated</p>
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
                <h5 class="modal-title">Delete Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this asset? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?= $this->url("assets/{$asset['id']}/delete") ?>" class="d-inline">
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