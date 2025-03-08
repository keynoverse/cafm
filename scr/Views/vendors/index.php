<?php $this->layout('layouts/app', ['title' => 'Vendor Directory']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Vendor Directory</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Vendor Directory</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (hasPermission('create_vendor')): ?>
                <a href="<?= url('vendors/create') ?>" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Add Vendor
                </a>
            <?php endif; ?>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importVendorsModal">
                <i class="bx bx-import"></i> Import
            </button>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bx bx-export"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="<?= url('vendors/export/excel') ?>">
                            <i class="bx bxs-file-export me-2"></i> Export to Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url('vendors/export/pdf') ?>">
                            <i class="bx bxs-file-pdf me-2"></i> Export to PDF
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-primary-subtle rounded">
                            <i class="bx bx-buildings fs-3 text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">Total Vendors</h6>
                            <h4 class="mb-0"><?= number_format($stats['total_vendors']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-success-subtle rounded">
                            <i class="bx bx-check-circle fs-3 text-success"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">Active</h6>
                            <h4 class="mb-0"><?= number_format($stats['active_vendors']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-warning-subtle rounded">
                            <i class="bx bx-file fs-3 text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">Active Contracts</h6>
                            <h4 class="mb-0"><?= number_format($stats['active_contracts']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg bg-info-subtle rounded">
                            <i class="bx bx-dollar-circle fs-3 text-info"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1">Total Spend</h6>
                            <h4 class="mb-0"><?= $currency ?><?= number_format($stats['total_spend'], 2) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search vendors..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= ($filters['category'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="rating" class="form-label">Rating</label>
                    <select class="form-select" id="rating" name="rating">
                        <option value="">All Ratings</option>
                        <option value="5" <?= ($filters['rating'] ?? '') === '5' ? 'selected' : '' ?>>5 Stars</option>
                        <option value="4" <?= ($filters['rating'] ?? '') === '4' ? 'selected' : '' ?>>4+ Stars</option>
                        <option value="3" <?= ($filters['rating'] ?? '') === '3' ? 'selected' : '' ?>>3+ Stars</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="sort" class="form-label">Sort By</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="name_asc" <?= ($filters['sort'] ?? '') === 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                        <option value="name_desc" <?= ($filters['sort'] ?? '') === 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                        <option value="rating_desc" <?= ($filters['sort'] ?? '') === 'rating_desc' ? 'selected' : '' ?>>Rating (High-Low)</option>
                        <option value="spend_desc" <?= ($filters['sort'] ?? '') === 'spend_desc' ? 'selected' : '' ?>>Spend (High-Low)</option>
                        <option value="contracts_desc" <?= ($filters['sort'] ?? '') === 'contracts_desc' ? 'selected' : '' ?>>Active Contracts</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-filter-alt"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Vendors Grid -->
    <div class="row g-4">
        <?php foreach ($vendors as $vendor): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">
                                    <a href="<?= url("vendors/{$vendor['id']}") ?>" class="text-body">
                                        <?= htmlspecialchars($vendor['company_name']) ?>
                                    </a>
                                </h5>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-<?= getVendorStatusClass($vendor['status']) ?>">
                                        <?= ucfirst($vendor['status']) ?>
                                    </span>
                                    <?php if ($vendor['rating']): ?>
                                        <div class="rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bx bxs-star<?= $i <= $vendor['rating'] ? ' text-warning' : ' text-muted' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-icon" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}") ?>">
                                            <i class="bx bx-show me-2"></i> View Details
                                        </a>
                                    </li>
                                    <?php if (hasPermission('edit_vendor')): ?>
                                        <li>
                                            <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/edit") ?>">
                                                <i class="bx bx-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts") ?>">
                                            <i class="bx bx-file me-2"></i> View Contracts
                                        </a>
                                    </li>
                                    <?php if (hasPermission('delete_vendor')): ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger delete-vendor" href="#" data-id="<?= $vendor['id'] ?>">
                                                <i class="bx bx-trash me-2"></i> Delete
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bx bx-category text-muted me-2"></i>
                                <span><?= htmlspecialchars($vendor['category_name']) ?></span>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <i class="bx bx-user text-muted me-2"></i>
                                <span><?= htmlspecialchars($vendor['primary_contact_name']) ?></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-envelope text-muted me-2"></i>
                                <a href="mailto:<?= htmlspecialchars($vendor['primary_contact_email']) ?>" class="text-body">
                                    <?= htmlspecialchars($vendor['primary_contact_email']) ?>
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-file text-muted me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Active Contracts</small>
                                        <span class="fw-semibold"><?= number_format($vendor['active_contracts']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-dollar-circle text-muted me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Total Spend</small>
                                        <span class="fw-semibold"><?= $currency ?><?= number_format($vendor['total_spend'], 2) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($pagination['total_pages'] > 1): ?>
        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination">
                    <li class="page-item <?= $pagination['current_page'] === 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= url('vendors', ['page' => $pagination['current_page'] - 1] + $filters) ?>">
                            Previous
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="<?= url('vendors', ['page' => $i] + $filters) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $pagination['current_page'] === $pagination['total_pages'] ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= url('vendors', ['page' => $pagination['current_page'] + 1] + $filters) ?>">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Import Vendors Modal -->
<div class="modal fade" id="importVendorsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Vendors</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="importForm" action="<?= url('vendors/import') ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="importFile" class="form-label">Choose File</label>
                        <input type="file" class="form-control" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">
                            Supported formats: Excel (.xlsx, .xls) and CSV (.csv)
                        </small>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="updateExisting" name="update_existing">
                            <label class="form-check-label" for="updateExisting">
                                Update existing vendors
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <a href="<?= url('vendors/import/template') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-download"></i> Download Template
                        </a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="importSubmit">Import</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const importForm = document.getElementById('importForm');
    const importSubmit = document.getElementById('importSubmit');

    // Handle filter form submission
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const params = new URLSearchParams(formData);
        window.location.href = `<?= url('vendors') ?>?${params.toString()}`;
    });

    // Reset filters
    filterForm.addEventListener('reset', function() {
        setTimeout(() => {
            window.location.href = '<?= url('vendors') ?>';
        }, 0);
    });

    // Handle vendor deletion
    document.querySelectorAll('.delete-vendor').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this vendor? This action cannot be undone.')) {
                const vendorId = this.dataset.id;
                fetch(`<?= url('vendors') ?>/${vendorId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          window.location.reload();
                      } else {
                          alert(data.message || 'Failed to delete vendor');
                      }
                  });
            }
        });
    });

    // Handle vendor import
    importSubmit.addEventListener('click', function() {
        if (!importForm.checkValidity()) {
            importForm.reportValidity();
            return;
        }

        const formData = new FormData(importForm);
        
        fetch(importForm.action, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Vendors imported successfully');
                  window.location.reload();
              } else {
                  alert(data.message || 'Failed to import vendors');
              }
          });
    });
});

function getVendorStatusClass(status) {
    return {
        'active': 'success',
        'inactive': 'danger',
        'pending': 'warning'
    }[status] || 'secondary';
}
</script>

<style>
.avatar {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-lg {
    width: 56px;
    height: 56px;
    font-size: 1.5rem;
}

.rating {
    font-size: 0.875rem;
}

.badge {
    padding: 0.5em 0.75em;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-icon {
    padding: 0.25rem 0.5rem;
    line-height: 1;
}

@media (max-width: 768px) {
    .col-md-6 {
        width: 100%;
    }
}
</style> 