<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Supplier Details</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('suppliers') ?>" class="btn btn-secondary me-2">
                <i class='bx bx-arrow-back'></i> Back to Suppliers
            </a>
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url("suppliers/{$supplier['id']}/edit") ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Supplier
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Supplier Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Basic Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Company Name</th>
                                    <td><?= htmlspecialchars($supplier['name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Contact Person</th>
                                    <td><?= htmlspecialchars($supplier['contact_person']) ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>
                                        <a href="mailto:<?= htmlspecialchars($supplier['email']) ?>">
                                            <?= htmlspecialchars($supplier['email']) ?>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Contact Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Phone</th>
                                    <td>
                                        <a href="tel:<?= htmlspecialchars($supplier['phone']) ?>">
                                            <?= htmlspecialchars($supplier['phone']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?= nl2br(htmlspecialchars($supplier['address'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td><?= date('M d, Y', strtotime($supplier['created_at'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Items -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Inventory Items</h5>
                        <span class="badge bg-primary"><?= count($inventoryItems) ?> Items</span>
                    </div>
                    
                    <?php if (empty($inventoryItems)): ?>
                        <p class="text-muted">No inventory items found for this supplier.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inventoryItems as $item): ?>
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
                                            <td><?= $item['quantity'] ?></td>
                                            <td>$<?= number_format($item['price'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Purchase Orders -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Purchase Orders</h5>
                        <span class="badge bg-primary"><?= count($purchaseOrders) ?> Orders</span>
                    </div>
                    
                    <?php if (empty($purchaseOrders)): ?>
                        <p class="text-muted">No purchase orders found for this supplier.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Status</th>
                                        <th>Total Amount</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($purchaseOrders as $order): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= $this->url("purchase-orders/{$order['id']}") ?>">
                                                    <?= htmlspecialchars($order['po_number']) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $this->getStatusBadgeColor($order['status']) ?>">
                                                    <?= ucfirst($order['status']) ?>
                                                </span>
                                            </td>
                                            <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                            <td><?= htmlspecialchars($order['created_by_name']) ?></td>
                                            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
                        <a href="<?= $this->url("purchase-orders/create?supplier_id={$supplier['id']}") ?>" class="btn btn-primary">
                            <i class='bx bx-plus'></i> Create Purchase Order
                        </a>
                        <a href="mailto:<?= htmlspecialchars($supplier['email']) ?>" class="btn btn-info">
                            <i class='bx bx-envelope'></i> Send Email
                        </a>
                        <a href="tel:<?= htmlspecialchars($supplier['phone']) ?>" class="btn btn-success">
                            <i class='bx bx-phone'></i> Call Supplier
                        </a>

                        <?php if ($this->user['role'] === 'admin'): ?>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class='bx bx-trash'></i> Delete Supplier
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Statistics</h5>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Inventory Items</span>
                            <span class="badge bg-primary rounded-pill"><?= $supplier['inventory_count'] ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Purchase Orders</span>
                            <span class="badge bg-primary rounded-pill"><?= $supplier['purchase_order_count'] ?></span>
                        </div>
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
                <h5 class="modal-title">Delete Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this supplier? This action cannot be undone.</p>
                <p class="text-danger">Note: Suppliers with associated inventory items or purchase orders cannot be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?= $this->url("suppliers/{$supplier['id']}/delete") ?>" class="d-inline">
                    <?= $this->csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
function getStatusBadgeColor($status) {
    switch ($status) {
        case 'draft':
            return 'secondary';
        case 'pending':
            return 'warning';
        case 'approved':
            return 'info';
        case 'ordered':
            return 'primary';
        case 'received':
            return 'success';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
}
?> 