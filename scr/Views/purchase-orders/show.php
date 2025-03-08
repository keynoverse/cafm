<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Purchase Order Details</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('purchase-orders') ?>" class="btn btn-secondary me-2">
                <i class='bx bx-arrow-back'></i> Back to Purchase Orders
            </a>
            <?php if ($order['status'] === 'draft'): ?>
                <a href="<?= $this->url("purchase-orders/{$order['id']}/edit") ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Order
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Purchase Order Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Basic Information</h5>
                            <table class="table">
                                <tr>
                                    <th>PO Number</th>
                                    <td><?= htmlspecialchars($order['po_number']) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $this->getStatusBadgeColor($order['status']) ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td><?= htmlspecialchars($order['created_by_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Supplier Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Company Name</th>
                                    <td>
                                        <a href="<?= $this->url("suppliers/{$order['supplier_id']}") ?>">
                                            <?= htmlspecialchars($order['supplier_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Contact Person</th>
                                    <td><?= htmlspecialchars($order['supplier_contact']) ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>
                                        <a href="mailto:<?= htmlspecialchars($order['supplier_email']) ?>">
                                            <?= htmlspecialchars($order['supplier_email']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>
                                        <a href="tel:<?= htmlspecialchars($order['supplier_phone']) ?>">
                                            <?= htmlspecialchars($order['supplier_phone']) ?>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= $this->url("inventory/{$item['inventory_id']}") ?>">
                                                <?= htmlspecialchars($item['item_name']) ?>
                                            </a>
                                        </td>
                                        <td><?= htmlspecialchars($item['item_sku']) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>$<?= number_format($item['unit_price'], 2) ?></td>
                                        <td>$<?= number_format($item['total_price'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total Amount:</th>
                                    <th>$<?= number_format($order['total_amount'], 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if ($order['notes']): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Notes</h5>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
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
                        <?php if ($order['status'] === 'draft'): ?>
                            <form method="POST" action="<?= $this->url("purchase-orders/{$order['id']}/status") ?>" class="d-grid">
                                <?= $this->csrf_field() ?>
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="btn btn-warning">
                                    <i class='bx bx-send'></i> Submit for Approval
                                </button>
                            </form>
                        <?php elseif ($order['status'] === 'pending' && $this->user['role'] === 'admin'): ?>
                            <form method="POST" action="<?= $this->url("purchase-orders/{$order['id']}/status") ?>" class="d-grid">
                                <?= $this->csrf_field() ?>
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-success">
                                    <i class='bx bx-check'></i> Approve Order
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if (in_array($order['status'], ['draft', 'pending'])): ?>
                            <form method="POST" action="<?= $this->url("purchase-orders/{$order['id']}/status") ?>" class="d-grid">
                                <?= $this->csrf_field() ?>
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn btn-danger">
                                    <i class='bx bx-x'></i> Cancel Order
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if ($order['status'] === 'draft'): ?>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class='bx bx-trash'></i> Delete Order
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Order Timeline</h5>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date"><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></div>
                            <div class="timeline-content">
                                <p class="mb-0">Purchase order created by <?= htmlspecialchars($order['created_by_name']) ?></p>
                            </div>
                        </div>
                        <?php if ($order['approved_by']): ?>
                            <div class="timeline-item">
                                <div class="timeline-date"><?= date('M d, Y H:i', strtotime($order['updated_at'])) ?></div>
                                <div class="timeline-content">
                                    <p class="mb-0">Order approved by <?= htmlspecialchars($order['approved_by_name']) ?></p>
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
                <h5 class="modal-title">Delete Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this purchase order? This action cannot be undone.</p>
                <p class="text-danger">Note: Only draft orders can be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?= $this->url("purchase-orders/{$order['id']}/delete") ?>" class="d-inline">
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