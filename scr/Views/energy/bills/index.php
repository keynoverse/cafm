<?php $this->layout('layouts/app', ['title' => 'Utility Bills']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Utility Bills</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('energy') ?>">Energy Management</a></li>
                    <li class="breadcrumb-item active">Utility Bills</li>
                </ol>
            </nav>
        </div>
        <?php if ($this->user->hasPermission('create_utility_bill')): ?>
            <a href="<?= url('energy/bills/create') ?>" class="btn btn-primary">
                <i class="bx bx-plus"></i> Add Bill
            </a>
        <?php endif; ?>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Bills</h6>
                    <h2 class="card-title mb-0">$<?= number_format($summary['total_amount'], 2) ?></h2>
                    <div class="mt-2">
                        <small class="text-<?= $summary['amount_trend'] < 0 ? 'success' : 'warning' ?>">
                            <i class="bx bx-trending-<?= $summary['amount_trend'] < 0 ? 'down' : 'up' ?>"></i>
                            <?= abs($summary['amount_trend']) ?>% vs last month
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Pending Payment</h6>
                    <h2 class="card-title mb-0">$<?= number_format($summary['pending_amount'], 2) ?></h2>
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="bx bx-calendar"></i> <?= $summary['pending_count'] ?> Bills
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Overdue</h6>
                    <h2 class="card-title mb-0">$<?= number_format($summary['overdue_amount'], 2) ?></h2>
                    <div class="mt-2">
                        <small class="text-danger">
                            <i class="bx bx-time"></i> <?= $summary['overdue_count'] ?> Bills
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Paid This Month</h6>
                    <h2 class="card-title mb-0">$<?= number_format($summary['paid_amount'], 2) ?></h2>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="bx bx-check"></i> <?= $summary['paid_count'] ?> Bills
                        </small>
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
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" class="form-control" id="search" 
                               placeholder="Search bills..." value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="utility_type">
                        <option value="">All Types</option>
                        <option value="electricity">Electricity</option>
                        <option value="gas">Gas</option>
                        <option value="water">Water</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="status">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                        <option value="disputed">Disputed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">Date Range</span>
                        <input type="date" class="form-control" id="startDate" name="start_date">
                        <input type="date" class="form-control" id="endDate" name="end_date">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                        Clear Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bills Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Bill Number</th>
                            <th>Type</th>
                            <th>Period</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bills as $bill): ?>
                            <tr>
                                <td>
                                    <a href="<?= url("energy/bills/{$bill['id']}") ?>">
                                        <?= htmlspecialchars($bill['bill_number']) ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-<?= getTypeClass($bill['utility_type']) ?>">
                                        <?= ucfirst($bill['utility_type']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('M j, Y', strtotime($bill['billing_period_start'])) ?> -
                                    <?= date('M j, Y', strtotime($bill['billing_period_end'])) ?>
                                </td>
                                <td>$<?= number_format($bill['amount'], 2) ?></td>
                                <td>
                                    <?= date('M j, Y', strtotime($bill['due_date'])) ?>
                                    <?php if ($bill['payment_status'] === 'overdue'): ?>
                                        <small class="d-block text-danger">
                                            <?= getDaysOverdue($bill['due_date']) ?> days overdue
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= getStatusClass($bill['payment_status']) ?>">
                                        <?= ucfirst($bill['payment_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= url("energy/bills/{$bill['id']}") ?>" 
                                           class="btn btn-sm btn-outline-secondary"
                                           title="View Details">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <?php if ($this->user->hasPermission('edit_utility_bill')): ?>
                                            <a href="<?= url("energy/bills/{$bill['id']}/edit") ?>" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit Bill">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($bill['payment_status'] === 'pending' && $this->user->hasPermission('mark_bill_paid')): ?>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success mark-paid"
                                                    data-id="<?= $bill['id'] ?>"
                                                    title="Mark as Paid">
                                                <i class="bx bx-check"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($bills)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bx bx-info-circle fs-4 mb-2"></i>
                                    <p class="mb-0">No utility bills found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Bills pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('energy/bills', ['page' => $currentPage - 1]) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('energy/bills', ['page' => $i]) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= url('energy/bills', ['page' => $currentPage + 1]) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const utilityTypeSelect = document.getElementById('utility_type');
    const statusSelect = document.getElementById('status');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const clearFiltersBtn = document.getElementById('clearFilters');

    function applyFilters() {
        const params = new URLSearchParams(window.location.search);
        
        if (searchInput.value) params.set('query', searchInput.value);
        else params.delete('query');
        
        if (utilityTypeSelect.value) params.set('type', utilityTypeSelect.value);
        else params.delete('type');
        
        if (statusSelect.value) params.set('status', statusSelect.value);
        else params.delete('status');
        
        if (startDateInput.value) params.set('start_date', startDateInput.value);
        else params.delete('start_date');
        
        if (endDateInput.value) params.set('end_date', endDateInput.value);
        else params.delete('end_date');
        
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    // Event listeners for filter changes
    [searchInput, utilityTypeSelect, statusSelect, startDateInput, endDateInput].forEach(element => {
        if (element.tagName === 'INPUT' && element.type === 'text') {
            element.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyFilters();
                }
            });
        } else {
            element.addEventListener('change', applyFilters);
        }
    });

    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });

    // Set minimum date for end date based on start date
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
    });

    // Mark as Paid functionality
    document.querySelectorAll('.mark-paid').forEach(button => {
        button.addEventListener('click', function() {
            const billId = this.dataset.id;
            if (confirm('Are you sure you want to mark this bill as paid?')) {
                fetch(`<?= url('energy/bills') ?>/${billId}/mark-paid`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': '<?= csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Failed to mark bill as paid');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while marking the bill as paid');
                });
            }
        });
    });
});

function getTypeClass(type) {
    return {
        'electricity': 'primary',
        'gas': 'danger',
        'water': 'info',
        'other': 'secondary'
    }[type] || 'secondary';
}

function getStatusClass(status) {
    return {
        'pending': 'warning',
        'paid': 'success',
        'overdue': 'danger',
        'disputed': 'info'
    }[status] || 'secondary';
}

function getDaysOverdue(dueDate) {
    const due = new Date(dueDate);
    const now = new Date();
    const diffTime = Math.abs(now - due);
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
}
</script>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.table td {
    vertical-align: middle;
}

.btn-group {
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

tr:hover .btn-group {
    opacity: 1;
}

@media (max-width: 768px) {
    .btn-group {
        opacity: 1;
    }
    
    .card:hover {
        transform: none;
    }

    .input-group {
        margin-bottom: 1rem;
    }
}
</style> 