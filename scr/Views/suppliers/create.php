<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Add New Supplier</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('suppliers') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Suppliers
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $this->url('suppliers/create') ?>" data-validate>
                <?= $this->csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?= $this->hasError('name') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('name') ?>" required>
                            <?php if ($this->hasError('name')): ?>
                                <div class="invalid-feedback"><?= $this->error('name') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contact Person <span class="text-danger">*</span></label>
                            <input type="text" name="contact_person" class="form-control <?= $this->hasError('contact_person') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('contact_person') ?>" required>
                            <?php if ($this->hasError('contact_person')): ?>
                                <div class="invalid-feedback"><?= $this->error('contact_person') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control <?= $this->hasError('email') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('email') ?>" required>
                            <?php if ($this->hasError('email')): ?>
                                <div class="invalid-feedback"><?= $this->error('email') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Contact Information</h5>

                        <div class="mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control <?= $this->hasError('phone') ? 'is-invalid' : '' ?>" 
                                value="<?= $this->old('phone') ?>" required>
                            <?php if ($this->hasError('phone')): ?>
                                <div class="invalid-feedback"><?= $this->error('phone') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control <?= $this->hasError('address') ? 'is-invalid' : '' ?>" 
                                rows="3" required><?= $this->old('address') ?></textarea>
                            <?php if ($this->hasError('address')): ?>
                                <div class="invalid-feedback"><?= $this->error('address') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Create Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 