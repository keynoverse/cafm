<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Create Purchase Order</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('purchase-orders') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Purchase Orders
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url('purchase-orders/create') ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select <?= $this->hasError('supplier_id') ? 'is-invalid' : '' ?>" required>
                                <option value="">Select a supplier</option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier['id'] ?>" <?= $this->old('supplier_id') == $supplier['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($supplier['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('supplier_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('supplier_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control <?= $this->hasError('notes') ? 'is-invalid' : '' ?>" 
                                rows="3"><?= $this->old('notes') ?></textarea>
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
                            <div class="order-item mb-3">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Item <span class="text-danger">*</span></label>
                                        <select name="items[0][inventory_id]" class="form-select item-select" required>
                                            <option value="">Select an item</option>
                                            <?php foreach ($inventory as $item): ?>
                                                <option value="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>">
                                                    <?= htmlspecialchars($item['name']) ?> (<?= htmlspecialchars($item['sku']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" name="items[0][quantity]" class="form-control quantity" 
                                            min="1" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Unit Price <span class="text-danger">*</span></label>
                                        <input type="number" name="items[0][unit_price]" class="form-control unit-price" 
                                            step="0.01" min="0" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Total Price</label>
                                        <input type="text" class="form-control total-price" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger d-block remove-item" style="display: none;">
                                            <i class='bx bx-trash'></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
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
                                    <span id="subtotal">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Total Amount:</span>
                                    <span class="fw-bold" id="total-amount">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Create Purchase Order
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
    let itemCount = 1;

    // Add new item row
    addItemBtn.addEventListener('click', function() {
        const template = orderItems.querySelector('.order-item').cloneNode(true);
        
        // Update input names
        template.querySelectorAll('select, input').forEach(input => {
            input.name = input.name.replace('[0]', `[${itemCount}]`);
            input.value = '';
        });

        // Show remove button
        template.querySelector('.remove-item').style.display = 'block';

        // Add event listeners
        addItemEventListeners(template);

        orderItems.appendChild(template);
        itemCount++;
    });

    // Add event listeners to initial item
    addItemEventListeners(orderItems.querySelector('.order-item'));

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
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                itemRow.remove();
                calculateOrderTotal();
            });
        }
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