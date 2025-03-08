<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Buildings</h1>
        </div>
        <div class="col text-end">
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url('buildings/create') ?>" class="btn btn-primary">
                    <i class='bx bx-plus'></i> New Building
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Buildings</h6>
                    <h2 class="mb-0"><?= $stats['total_buildings'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Active Buildings</h6>
                    <h2 class="mb-0"><?= $stats['active_buildings'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Cities</h6>
                    <h2 class="mb-0"><?= $stats['total_cities'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">States</h6>
                    <h2 class="mb-0"><?= $stats['total_states'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $this->url('buildings') ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="query" class="form-control" placeholder="Search buildings..." 
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

    <!-- Buildings Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Status</th>
                            <th>Floors</th>
                            <th>Rooms</th>
                            <th>Locations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($buildings)): ?>
                            <tr>
                                <td colspan="9" class="text-center">No buildings found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($buildings as $building): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url("buildings/{$building['id']}") ?>">
                                            <?= htmlspecialchars($building['name']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($building['address']) ?></td>
                                    <td><?= htmlspecialchars($building['city']) ?></td>
                                    <td><?= htmlspecialchars($building['state']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $building['status'] === 'active' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($building['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= $building['total_floors'] ?></td>
                                    <td><?= $building['total_rooms'] ?></td>
                                    <td><?= $building['total_locations'] ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= $this->url("buildings/{$building['id']}") ?>" 
                                                class="btn btn-sm btn-info" title="View Details">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <?php if ($this->user['role'] === 'admin'): ?>
                                                <a href="<?= $this->url("buildings/{$building['id']}/edit") ?>" 
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal<?= $building['id'] ?>"
                                                    title="Delete">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <?php if ($this->user['role'] === 'admin'): ?>
                                    <div class="modal fade" id="deleteModal<?= $building['id'] ?>" tabindex="-1">
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
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (!empty($buildings)): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url('buildings', ['page' => $currentPage - 1]) ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="page-item active">
                            <span class="page-link"><?= $currentPage ?></span>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="<?= $this->url('buildings', ['page' => $currentPage + 1]) ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div> 