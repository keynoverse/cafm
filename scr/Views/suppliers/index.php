<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Supplier Management</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('suppliers/create') ?>" class="btn btn-primary">
                <i class='bx bx-plus'></i> Add Supplier
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Suppliers</h6>
                    <h2 class="mb-0"><?= $stats['total_suppliers'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Inventory Items</h6>
                    <h2 class="mb-0"><?= $stats['total_inventory_items'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Purchase Orders</h6>
                    <h2 class="mb-0"><?= $stats['total_purchase_orders'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Purchases</h6>
                    <h2 class="mb-0">$<?= number_format($stats['total_purchase_amount'], 2) ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $this->url('suppliers') ?>" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="query" class="form-control" placeholder="Search suppliers..." 
                            value="<?= $_GET['query'] ?? '' ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-search'></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Inventory Items</th>
                            <th>Purchase Orders</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($suppliers)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No suppliers found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($suppliers as $supplier): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url("suppliers/{$supplier['id']}") ?>">
                                            <?= htmlspecialchars($supplier['name']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($supplier['contact_person']) ?></td>
                                    <td>
                                        <a href="mailto:<?= htmlspecialchars($supplier['email']) ?>">
                                            <?= htmlspecialchars($supplier['email']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="tel:<?= htmlspecialchars($supplier['phone']) ?>">
                                            <?= htmlspecialchars($supplier['phone']) ?>
                                        </a>
                                    </td>
                                    <td><?= $supplier['inventory_count'] ?></td>
                                    <td><?= $supplier['purchase_order_count'] ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= $this->url("suppliers/{$supplier['id']}") ?>" 
                                                class="btn btn-sm btn-info" title="View Details">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <?php if ($this->user['role'] === 'admin'): ?>
                                                <a href="<?= $this->url("suppliers/{$supplier['id']}/edit") ?>" 
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger delete-supplier" 
                                                    data-id="<?= $supplier['id'] ?>" title="Delete">
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
            <?php if (!empty($suppliers)): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url("suppliers?page=" . ($currentPage - 1)) ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= ceil(count($suppliers) / 10); $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $this->url("suppliers?page={$i}") ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < ceil(count($suppliers) / 10)): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url("suppliers?page=" . ($currentPage + 1)) ?>">
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
                <h5 class="modal-title">Delete Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this supplier? This action cannot be undone.</p>
                <p class="text-danger">Note: Suppliers with associated inventory items or purchase orders cannot be deleted.</p>
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
    document.querySelectorAll('.delete-supplier').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const modal = document.getElementById('deleteModal');
            const form = modal.querySelector('form');
            form.action = '<?= $this->url('suppliers/') ?>' + id + '/delete';
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