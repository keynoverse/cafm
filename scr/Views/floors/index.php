<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Floors</h1>
        </div>
        <div class="col text-end">
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url('floors/create') ?>" class="btn btn-primary">
                    <i class='bx bx-plus'></i> New Floor
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Floors</h6>
                    <h2 class="mb-0"><?= $stats['total_floors'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Active Floors</h6>
                    <h2 class="mb-0"><?= $stats['active_floors'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Inactive Floors</h6>
                    <h2 class="mb-0"><?= $stats['inactive_floors'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Buildings</h6>
                    <h2 class="mb-0"><?= $stats['total_buildings'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $this->url('floors') ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="query" class="form-control" placeholder="Search floors..." 
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

    <!-- Floors Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Building</th>
                            <th>Status</th>
                            <th>Rooms</th>
                            <th>Locations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($floors)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No floors found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($floors as $floor): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url("floors/{$floor['id']}") ?>">
                                            <?= htmlspecialchars($floor['name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= $this->url("buildings/{$floor['building_id']}") ?>">
                                            <?= htmlspecialchars($floor['building_name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $floor['status'] === 'active' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($floor['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= $floor['total_rooms'] ?></td>
                                    <td><?= $floor['total_locations'] ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= $this->url("floors/{$floor['id']}") ?>" 
                                                class="btn btn-sm btn-info" title="View Details">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <?php if ($this->user['role'] === 'admin'): ?>
                                                <a href="<?= $this->url("floors/{$floor['id']}/edit") ?>" 
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal<?= $floor['id'] ?>"
                                                    title="Delete">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <?php if ($this->user['role'] === 'admin'): ?>
                                    <div class="modal fade" id="deleteModal<?= $floor['id'] ?>" tabindex="-1">
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
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (!empty($floors)): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url('floors', ['page' => $currentPage - 1]) ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="page-item active">
                            <span class="page-link"><?= $currentPage ?></span>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="<?= $this->url('floors', ['page' => $currentPage + 1]) ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div> 