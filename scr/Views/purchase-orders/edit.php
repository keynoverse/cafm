<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Edit Purchase Order</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('purchase-orders') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Purchase Orders
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url("purchase-orders/{$order['id']}/edit") ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">PO Number</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($order['po_number']) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select <?= $this->hasError('supplier_id') ? 'is-invalid' : '' ?>" required>
                                <option value="">Select a supplier</option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier['id'] ?>" 
                                        <?= $this->old('supplier_id', $order['supplier_id']) == $supplier['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($supplier['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('supplier_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('supplier_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select <?= $this->hasError('status') ? 'is-invalid' : '' ?>" required>
                                <option value="draft" <?= $this->old('status', $order['status']) === 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="pending" <?= $this->old('status', $order['status']) === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $this->old('status', $order['status']) === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="ordered" <?= $this->old('status', $order['status']) === 'ordered' ? 'selected' : '' ?>>Ordered</option>
                                <option value="received" <?= $this->old('status', $order['status']) === 'received' ? 'selected' : '' ?>>Received</option>
                                <option value="cancelled" <?= $this->old('status', $order['status']) === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <?php if ($this->hasError('status')): ?>
                                <div class="invalid-feedback"><?= $this->error('status') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control <?= $this->hasError('notes') ? 'is-invalid' : '' ?>" 
                                rows="3"><?= $this->old('notes', $order['notes']) ?></textarea>
                            <?php if ($this->hasError('notes')): ?>
                                <div class="invalid-feedback"><?= $this->error('notes') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="mb-3">Order Items</h5>
                        <div id="order-items">
                            <?php foreach ($order['items'] as $index => $item): ?>
                                <div class="order-item mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Item <span class="text-danger">*</span></label>
                                            <select name="items[<?= $index ?>][inventory_id]" class="form-select item-select" required>
                                                <option value="">Select an item</option>
                                                <?php foreach ($inventory as $inventoryItem): ?>
                                                    <option value="<?= $inventoryItem['id'] ?>" 
                                                        data-price="<?= $inventoryItem['price'] ?>"
                                                        <?= $item['inventory_id'] == $inventoryItem['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($inventoryItem['name']) ?> (<?= htmlspecialchars($inventoryItem['sku']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                            <input type="number" name="items[<?= $index ?>][quantity]" class="form-control quantity" 
                                                min="1" value="<?= $item['quantity'] ?>" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Unit Price <span class="text-danger">*</span></label>
                                            <input type="number" name="items[<?= $index ?>][unit_price]" class="form-control unit-price" 
                                                step="0.01" min="0" value="<?= $item['unit_price'] ?>" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Total Price</label>
                                            <input type="text" class="form-control total-price" value="<?= $item['total_price'] ?>" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger d-block remove-item">
                                                <i class='bx bx-trash'></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-secondary" id="add-item">
                            <i class='bx bx-plus'></i> Add Item
                        </button>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 offset-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Order Summary</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span id="subtotal">$<?= number_format($order['total_amount'], 2) ?></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Total Amount:</span>
                                    <span class="fw-bold" id="total-amount">$<?= number_format($order['total_amount'], 2) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Update Purchase Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderItems = document.getElementById('order-items');
    const addItemBtn = document.getElementById('add-item');
    let itemCount = <?= count($order['items']) ?>;

    // Add new item row
    addItemBtn.addEventListener('click', function() {
        const template = orderItems.querySelector('.order-item').cloneNode(true);
        
        // Update input names
        template.querySelectorAll('select, input').forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${itemCount}]`);
            if (!input.classList.contains('total-price')) {
                input.value = '';
            }
        });

        // Add event listeners
        addItemEventListeners(template);

        orderItems.appendChild(template);
        itemCount++;
    });

    // Add event listeners to all items
    document.querySelectorAll('.order-item').forEach(addItemEventListeners);

    // Function to add event listeners to an item row
    function addItemEventListeners(itemRow) {
        const itemSelect = itemRow.querySelector('.item-select');
        const quantityInput = itemRow.querySelector('.quantity');
        const unitPriceInput = itemRow.querySelector('.unit-price');
        const totalPriceInput = itemRow.querySelector('.total-price');
        const removeBtn = itemRow.querySelector('.remove-item');

        // Update unit price when item is selected
        itemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.dataset.price) {
                unitPriceInput.value = selectedOption.dataset.price;
                calculateTotal(itemRow);
            }
        });

        // Calculate total when quantity or unit price changes
        quantityInput.addEventListener('input', () => calculateTotal(itemRow));
        unitPriceInput.addEventListener('input', () => calculateTotal(itemRow));

        // Remove item row
        removeBtn.addEventListener('click', function() {
            itemRow.remove();
            calculateOrderTotal();
        });
    }

    // Calculate total for a single item
    function calculateTotal(itemRow) {
        const quantity = parseFloat(itemRow.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(itemRow.querySelector('.unit-price').value) || 0;
        const total = quantity * unitPrice;
        itemRow.querySelector('.total-price').value = total.toFixed(2);
        calculateOrderTotal();
    }

    // Calculate total for the entire order
    function calculateOrderTotal() {
        let total = 0;
        document.querySelectorAll('.total-price').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('subtotal').textContent = `$${total.toFixed(2)}`;
        document.getElementById('total-amount').textContent = `$${total.toFixed(2)}`;
    }
});
</script> 