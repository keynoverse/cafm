<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Edit Inventory Item</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('inventory') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Inventory
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url("inventory/{$item['id']}/edit") ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?= $this->hasError('name') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('name', $item['name']) ?>" required>
                            <?php if ($this->hasError('name')): ?>
                                <div class="invalid-feedback"><?= $this->error('name') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control <?= $this->hasError('sku') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('sku', $item['sku']) ?>" required>
                            <?php if ($this->hasError('sku')): ?>
                                <div class="invalid-feedback"><?= $this->error('sku') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select <?= $this->hasError('category_id') ? 'is-invalid' : '' ?>">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= $this->old('category_id', $item['category_id']) == $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('category_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('category_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <select name="location_id" class="form-select <?= $this->hasError('location_id') ? 'is-invalid' : '' ?>">
                                <option value="">Select Location</option>
                                <?php foreach ($locations as $location): ?>
                                    <option value="<?= $location['id'] ?>" <?= $this->old('location_id', $item['location_id']) == $location['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($location['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('location_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('location_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <select name="supplier_id" class="form-select <?= $this->hasError('supplier_id') ? 'is-invalid' : '' ?>">
                                <option value="">Select Supplier</option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier['id'] ?>" <?= $this->old('supplier_id', $item['supplier_id']) == $supplier['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($supplier['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($this->hasError('supplier_id')): ?>
                                <div class="invalid-feedback"><?= $this->error('supplier_id') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Stock Information -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Stock Information</h5>

                        <div class="mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control <?= $this->hasError('quantity') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('quantity', $item['quantity']) ?>" min="0" required>
                            <?php if ($this->hasError('quantity')): ?>
                                <div class="invalid-feedback"><?= $this->error('quantity') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Minimum Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="min_quantity" class="form-control <?= $this->hasError('min_quantity') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('min_quantity', $item['min_quantity']) ?>" min="0" required>
                            <?php if ($this->hasError('min_quantity')): ?>
                                <div class="invalid-feedback"><?= $this->error('min_quantity') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                            <input type="text" name="unit" class="form-control <?= $this->hasError('unit') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('unit', $item['unit']) ?>" required>
                            <?php if ($this->hasError('unit')): ?>
                                <div class="invalid-feedback"><?= $this->error('unit') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price" class="form-control <?= $this->hasError('price') ? 'is-invalid' : '' ?>" 
                                    value="<?= $this->old('price', $item['price']) ?>" min="0" step="0.01" required>
                            </div>
                            <?php if ($this->hasError('price')): ?>
                                <div class="invalid-feedback"><?= $this->error('price') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control <?= $this->hasError('description') ? 'is-invalid' : '' ?>" 
                                rows="3" required><?= $this->old('description', $item['description']) ?></textarea>
                            <?php if ($this->hasError('description')): ?>
                                <div class="invalid-feedback"><?= $this->error('description') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 