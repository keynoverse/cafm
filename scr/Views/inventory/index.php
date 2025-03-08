<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Inventory Management</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('inventory/create') ?>" class="btn btn-primary">
                <i class='bx bx-plus'></i> Add Item
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Items</h6>
                    <h2 class="mb-0"><?= count($items) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Low Stock Items</h6>
                    <h2 class="mb-0 text-danger"><?= count($lowStockItems) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Value</h6>
                    <h2 class="mb-0">$0.00</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Categories</h6>
                    <h2 class="mb-0"><?= count($categories) ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $this->url('inventory') ?>" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $filters['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Location</label>
                    <select name="location_id" class="form-select">
                        <option value="">All Locations</option>
                        <?php foreach ($locations as $location): ?>
                            <option value="<?= $location['id'] ?>" <?= $filters['location_id'] == $location['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($location['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class='bx bx-filter'></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No inventory items found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url("inventory/{$item['id']}") ?>">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($item['sku']) ?></td>
                                    <td>
                                        <?php if ($item['category_id']): ?>
                                            <?= htmlspecialchars($item['category_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Uncategorized</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($item['location_id']): ?>
                                            <?= htmlspecialchars($item['location_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">No Location</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $isLowStock = $item['quantity'] <= $item['min_quantity'];
                                        ?>
                                        <span class="<?= $isLowStock ? 'text-danger' : '' ?>">
                                            <?= $item['quantity'] ?>
                                            <?php if ($isLowStock): ?>
                                                <i class='bx bx-error-circle'></i>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($item['unit']) ?></td>
                                    <td>$<?= number_format($item['price'], 2) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= $this->url("inventory/{$item['id']}") ?>" 
                                                class="btn btn-sm btn-info" title="View Details">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <?php if ($this->user['role'] === 'admin'): ?>
                                                <a href="<?= $this->url("inventory/{$item['id']}/edit") ?>" 
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger delete-item" 
                                                    data-id="<?= $item['id'] ?>" title="Delete">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (!empty($items)): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url("inventory?page=" . ($currentPage - 1)) ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= ceil(count($items) / 10); $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $this->url("inventory?page={$i}") ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < ceil(count($items) / 10)): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url("inventory?page=" . ($currentPage + 1)) ?>">
                                    Next
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this inventory item? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="" class="d-inline">
                    <?= $this->csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmation
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const modal = document.getElementById('deleteModal');
            const form = modal.querySelector('form');
            form.action = '<?= $this->url('inventory/') ?>' + id + '/delete';
            new bootstrap.Modal(modal).show();
        });
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script> 