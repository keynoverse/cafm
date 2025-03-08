<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Locations</h1>
        </div>
        <div class="col text-end">
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url('locations/create') ?>" class="btn btn-primary">
                    <i class='bx bx-plus'></i> New Location
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Locations</h6>
                    <h2 class="mb-0"><?= $stats['total_locations'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Active Locations</h6>
                    <h2 class="mb-0"><?= $stats['active_locations'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Inactive Locations</h6>
                    <h2 class="mb-0"><?= $stats['inactive_locations'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Rooms</h6>
                    <h2 class="mb-0"><?= $stats['total_rooms'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $this->url('locations') ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="query" class="form-control" placeholder="Search locations..." 
                        value="<?= $_GET['query'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class='bx bx-search'></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Locations Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Room</th>
                            <th>Floor</th>
                            <th>Building</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($locations)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No locations found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($locations as $location): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url("locations/{$location['id']}") ?>">
                                            <?= htmlspecialchars($location['name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= $this->url("rooms/{$location['room_id']}") ?>">
                                            <?= htmlspecialchars($location['room_name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= $this->url("floors/{$location['floor_id']}") ?>">
                                            <?= htmlspecialchars($location['floor_name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= $this->url("buildings/{$location['building_id']}") ?>">
                                            <?= htmlspecialchars($location['building_name']) ?>
                                        </a>
                                    </td>
                                    <td><?= ucfirst(str_replace('_', ' ', $location['type'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $location['status'] === 'active' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($location['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= $this->url("locations/{$location['id']}") ?>" 
                                                class="btn btn-sm btn-info" title="View Details">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <?php if ($this->user['role'] === 'admin'): ?>
                                                <a href="<?= $this->url("locations/{$location['id']}/edit") ?>" 
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal<?= $location['id'] ?>"
                                                    title="Delete">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <?php if ($this->user['role'] === 'admin'): ?>
                                    <div class="modal fade" id="deleteModal<?= $location['id'] ?>" tabindex="-1">
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
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (!empty($locations)): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url('locations', ['page' => $currentPage - 1]) ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="page-item active">
                            <span class="page-link"><?= $currentPage ?></span>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="<?= $this->url('locations', ['page' => $currentPage + 1]) ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.location-tree {
    max-height: 600px;
    overflow-y: auto;
}

.location-tree .building,
.location-tree .floor,
.location-tree .room,
.location-tree .location {
    position: relative;
}

.location-tree .building:before,
.location-tree .floor:before,
.location-tree .room:before {
    content: '';
    position: absolute;
    left: -1rem;
    top: 0;
    bottom: 0;
    width: 1px;
    background: #e9ecef;
}

.location-tree .building:last-child:before {
    display: none;
}

.location-tree .floor:last-child:before,
.location-tree .room:last-child:before {
    display: none;
}
</style> 