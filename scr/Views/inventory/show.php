<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Inventory Item Details</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('inventory') ?>" class="btn btn-secondary me-2">
                <i class='bx bx-arrow-back'></i> Back to Inventory
            </a>
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url("inventory/{$item['id']}/edit") ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Item
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Item Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Basic Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                </tr>
                                <tr>
                                    <th>SKU</th>
                                    <td><?= htmlspecialchars($item['sku']) ?></td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>
                                        <?php if ($item['category_id']): ?>
                                            <?= htmlspecialchars($item['category_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Uncategorized</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>
                                        <?php if ($item['location_id']): ?>
                                            <?= htmlspecialchars($item['location_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">No Location</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td>
                                        <?php if ($item['supplier_id']): ?>
                                            <?= htmlspecialchars($item['supplier_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">No Supplier</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Stock Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Quantity</th>
                                    <td>
                                        <?php
                                        $isLowStock = $item['quantity'] <= $item['min_quantity'];
                                        ?>
                                        <span class="<?= $isLowStock ? 'text-danger' : '' ?>">
                                            <?= $item['quantity'] ?> <?= htmlspecialchars($item['unit']) ?>
                                            <?php if ($isLowStock): ?>
                                                <i class='bx bx-error-circle'></i> Low Stock
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Minimum Quantity</th>
                                    <td><?= $item['min_quantity'] ?> <?= htmlspecialchars($item['unit']) ?></td>
                                </tr>
                                <tr>
                                    <th>Unit</th>
                                    <td><?= htmlspecialchars($item['unit']) ?></td>
                                </tr>
                                <tr>
                                    <th>Price</th>
                                    <td>$<?= number_format($item['price'], 2) ?></td>
                                </tr>
                                <tr>
                                    <th>Total Value</th>
                                    <td>$<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Description</h5>
                    <div class="description">
                        <?= nl2br(htmlspecialchars($item['description'])) ?>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Transaction History</h5>
                    <?php if (empty($item['transactions'])): ?>
                        <p class="text-muted">No transaction history available.</p>
                    <?php else: ?>
                        <div class="timeline">
                            <?php foreach ($item['transactions'] as $transaction): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date">
                                        <?= date('M d, Y H:i', strtotime($transaction['created_at'])) ?>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?= htmlspecialchars($transaction['user_name']) ?></strong>
                                                <span class="badge bg-secondary ms-2"><?= ucfirst($transaction['type']) ?></span>
                                            </div>
                                        </div>
                                        <p class="mb-0 mt-2">
                                            Quantity: <?= $transaction['quantity'] ?> <?= htmlspecialchars($item['unit']) ?>
                                            <br>
                                            New Quantity: <?= $transaction['new_quantity'] ?> <?= htmlspecialchars($item['unit']) ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStockModal">
                            <i class='bx bx-plus'></i> Add Stock
                        </button>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#removeStockModal">
                            <i class='bx bx-minus'></i> Remove Stock
                        </button>

                        <?php if ($this->user['role'] === 'admin'): ?>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class='bx bx-trash'></i> Delete Item
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addStockForm" method="POST" action="<?= $this->url("inventory/{$item['id']}/quantity") ?>">
                    <?= $this->csrf_field() ?>
                    <input type="hidden" name="type" value="add">
                    
                    <div class="mb-3">
                        <label class="form-label">Quantity to Add</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addStockForm" class="btn btn-success">Add Stock</button>
            </div>
        </div>
    </div>
</div>

<!-- Remove Stock Modal -->
<div class="modal fade" id="removeStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="removeStockForm" method="POST" action="<?= $this->url("inventory/{$item['id']}/quantity") ?>">
                    <?= $this->csrf_field() ?>
                    <input type="hidden" name="type" value="remove">
                    
                    <div class="mb-3">
                        <label class="form-label">Quantity to Remove</label>
                        <input type="number" name="quantity" class="form-control" min="1" max="<?= $item['quantity'] ?>" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="removeStockForm" class="btn btn-warning">Remove Stock</button>
            </div>
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
                <form method="POST" action="<?= $this->url("inventory/{$item['id']}/delete") ?>" class="d-inline">
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
    background: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
}
</style> 