<?php $this->layout('layouts/app', ['title' => htmlspecialchars($contract['title']) . ' - Contract Details']) ?>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0"><?= htmlspecialchars($contract['title']) ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('vendors') ?>">Vendor Directory</a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}") ?>"><?= htmlspecialchars($vendor['company_name']) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= url("vendors/{$vendor['id']}/contracts") ?>">Contracts</a></li>
                    <li class="breadcrumb-item active">Contract Details</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (hasPermission('edit_contract')): ?>
                <a href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/edit") ?>" class="btn btn-primary">
                    <i class="bx bx-edit"></i> Edit Contract
                </a>
            <?php endif; ?>
            <?php if ($contract['status'] === 'active'): ?>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#terminateModal">
                    <i class="bx bx-x-circle"></i> Terminate
                </button>
            <?php endif; ?>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i> More Actions
                </button>
                <ul class="dropdown-menu">
                    <?php if ($contract['status'] === 'active'): ?>
                        <li>
                            <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/renew") ?>">
                                <i class="bx bx-refresh me-2"></i> Renew Contract
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/export/pdf") ?>">
                            <i class="bx bxs-file-pdf me-2"></i> Export as PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/history") ?>">
                            <i class="bx bx-history me-2"></i> View History
                        </a>
                    </li>
                    <?php if (hasPermission('delete_contract')): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger delete-contract" href="#" data-id="<?= $contract['id'] ?>">
                                <i class="bx bx-trash me-2"></i> Delete Contract
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Contract Overview -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Contract Overview</h5>
                    <span class="badge bg-<?= getContractStatusClass($contract['status']) ?>">
                        <?= ucfirst($contract['status']) ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Contract Number</h6>
                            <p class="mb-0 fw-semibold"><?= htmlspecialchars($contract['contract_number']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Contract Type</h6>
                            <p class="mb-0"><?= ucfirst($contract['contract_type']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Start Date</h6>
                            <p class="mb-0"><?= date('M d, Y', strtotime($contract['start_date'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">End Date</h6>
                            <p class="mb-0">
                                <?= date('M d, Y', strtotime($contract['end_date'])) ?>
                                <?php
                                $days_remaining = (strtotime($contract['end_date']) - time()) / (60 * 60 * 24);
                                if ($days_remaining > 0 && $days_remaining <= 30):
                                ?>
                                    <span class="badge bg-warning ms-2">Expires in <?= ceil($days_remaining) ?> days</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-12">
                            <h6 class="text-muted mb-1">Description</h6>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($contract['description'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Financial Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Contract Value</h6>
                            <p class="mb-0 fw-semibold"><?= $currency ?><?= number_format($contract['contract_value'], 2) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Billing Frequency</h6>
                            <p class="mb-0"><?= ucfirst(str_replace('_', ' ', $contract['billing_frequency'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Payment Terms</h6>
                            <p class="mb-0"><?= ucfirst(str_replace('_', ' ', $contract['payment_terms'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Tax Rate</h6>
                            <p class="mb-0"><?= number_format($contract['tax_rate'], 2) ?>%</p>
                        </div>
                    </div>

                    <?php if (!empty($contract['payments'])): ?>
                        <hr>
                        <h6>Payment History</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice #</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contract['payments'] as $payment): ?>
                                        <tr>
                                            <td><?= date('M d, Y', strtotime($payment['date'])) ?></td>
                                            <td>
                                                <a href="<?= url("invoices/{$payment['invoice_id']}") ?>">
                                                    <?= htmlspecialchars($payment['invoice_number']) ?>
                                                </a>
                                            </td>
                                            <td><?= $currency ?><?= number_format($payment['amount'], 2) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $payment['status'] === 'paid' ? 'success' : 'warning' ?>">
                                                    <?= ucfirst($payment['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Documents -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Documents</h5>
                </div>
                <div class="card-body">
                    <h6>Contract Document</h6>
                    <?php if ($contract['file_path']): ?>
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <a href="<?= asset($contract['file_path']) ?>" class="btn btn-outline-primary" target="_blank">
                                <i class="bx bx-file"></i> View Contract Document
                            </a>
                            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                                <i class="bx bx-printer"></i> Print
                            </button>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-4">No contract document uploaded</p>
                    <?php endif; ?>

                    <?php if (!empty($contract['attachments'])): ?>
                        <h6>Additional Documents</h6>
                        <div class="list-group">
                            <?php foreach ($contract['attachments'] as $attachment): ?>
                                <a href="<?= asset($attachment['file_path']) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" target="_blank">
                                    <div>
                                        <i class="bx bx-file me-2"></i>
                                        <?= htmlspecialchars($attachment['filename']) ?>
                                        <br>
                                        <small class="text-muted">
                                            Added <?= date('M d, Y', strtotime($attachment['created_at'])) ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">
                                        <?= formatFileSize($attachment['file_size']) ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Terms & Conditions</h5>
                </div>
                <div class="card-body">
                    <?= nl2br(htmlspecialchars($contract['terms_conditions'])) ?>
                </div>
            </div>

            <!-- Notes & Comments -->
            <?php if (!empty($contract['notes'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notes & Comments</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php foreach ($contract['notes'] as $note): ?>
                                <div class="timeline-item">
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="fw-semibold"><?= htmlspecialchars($note['user_name']) ?></span>
                                                <small class="text-muted ms-2">
                                                    <?= date('M d, Y H:i', strtotime($note['created_at'])) ?>
                                                </small>
                                            </div>
                                            <?php if ($note['user_id'] === $current_user_id): ?>
                                                <button type="button" class="btn btn-link btn-sm text-danger delete-note" data-id="<?= $note['id'] ?>">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <p class="mb-0"><?= nl2br(htmlspecialchars($note['content'])) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <form id="noteForm" class="d-flex gap-2">
                            <input type="text" class="form-control" id="note" name="note" placeholder="Add a note...">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contract Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contract Status</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Created On</dt>
                        <dd class="col-sm-7"><?= date('M d, Y', strtotime($contract['created_at'])) ?></dd>

                        <dt class="col-sm-5">Last Modified</dt>
                        <dd class="col-sm-7"><?= date('M d, Y', strtotime($contract['updated_at'])) ?></dd>

                        <dt class="col-sm-5">Days Remaining</dt>
                        <dd class="col-sm-7">
                            <?php if ($days_remaining > 0): ?>
                                <span class="badge bg-<?= $days_remaining <= 30 ? 'warning' : 'success' ?>">
                                    <?= ceil($days_remaining) ?> days
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">Expired</span>
                            <?php endif; ?>
                        </dd>

                        <?php if ($contract['terminated_at']): ?>
                            <dt class="col-sm-5">Terminated On</dt>
                            <dd class="col-sm-7"><?= date('M d, Y', strtotime($contract['terminated_at'])) ?></dd>

                            <dt class="col-sm-5">Termination Reason</dt>
                            <dd class="col-sm-7"><?= htmlspecialchars($contract['termination_reason']) ?></dd>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>

            <!-- Renewal Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Renewal Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Auto Renewal</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-<?= $contract['auto_renewal'] ? 'success' : 'secondary' ?>">
                                <?= $contract['auto_renewal'] ? 'Yes' : 'No' ?>
                            </span>
                        </dd>

                        <dt class="col-sm-5">Reminder</dt>
                        <dd class="col-sm-7">
                            <?= $contract['renewal_reminder_days'] ?> days before expiry
                        </dd>

                        <dt class="col-sm-5">Notifications</dt>
                        <dd class="col-sm-7">
                            <?php
                            $emails = array_map('trim', explode(',', $contract['notification_emails']));
                            foreach ($emails as $email):
                            ?>
                                <div class="badge bg-light text-dark"><?= htmlspecialchars($email) ?></div>
                            <?php endforeach; ?>
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Vendor Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Vendor Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Company</dt>
                        <dd class="col-sm-8">
                            <a href="<?= url("vendors/{$vendor['id']}") ?>">
                                <?= htmlspecialchars($vendor['company_name']) ?>
                            </a>
                        </dd>

                        <dt class="col-sm-4">Contact</dt>
                        <dd class="col-sm-8">
                            <?= htmlspecialchars($vendor['primary_contact_name']) ?><br>
                            <small class="text-muted">
                                <?= htmlspecialchars($vendor['primary_contact_email']) ?><br>
                                <?= htmlspecialchars($vendor['primary_contact_phone']) ?>
                            </small>
                        </dd>

                        <dt class="col-sm-4">Address</dt>
                        <dd class="col-sm-8">
                            <small class="text-muted">
                                <?= htmlspecialchars($vendor['address_line1']) ?><br>
                                <?php if ($vendor['address_line2']): ?>
                                    <?= htmlspecialchars($vendor['address_line2']) ?><br>
                                <?php endif; ?>
                                <?= htmlspecialchars($vendor['city']) ?>, <?= htmlspecialchars($vendor['state']) ?> <?= htmlspecialchars($vendor['postal_code']) ?><br>
                                <?= htmlspecialchars($vendor['country']) ?>
                            </small>
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Recent Changes -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Changes</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($contract['history'])): ?>
                        <div class="timeline">
                            <?php foreach (array_slice($contract['history'], 0, 5) as $history): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date text-muted">
                                        <?= date('M d, Y H:i', strtotime($history['created_at'])) ?>
                                    </div>
                                    <div class="timeline-content">
                                        <p class="mb-0">
                                            <?= htmlspecialchars($history['description']) ?>
                                            <br>
                                            <small class="text-muted">by <?= htmlspecialchars($history['user_name']) ?></small>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/history") ?>" class="btn btn-link btn-sm">
                                View Full History
                            </a>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No changes recorded</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terminate Contract Modal -->
<div class="modal fade" id="terminateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terminate Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="terminateForm">
                    <div class="mb-3">
                        <label for="termination_reason" class="form-label">Reason for Termination</label>
                        <textarea class="form-control" id="termination_reason" name="termination_reason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="termination_date" class="form-label">Termination Date</label>
                        <input type="date" class="form-control" id="termination_date" name="termination_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmTermination">Terminate</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const terminateModal = new bootstrap.Modal(document.getElementById('terminateModal'));
    const noteForm = document.getElementById('noteForm');

    // Handle contract termination
    document.getElementById('confirmTermination')?.addEventListener('click', function() {
        const form = document.getElementById('terminateForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        fetch(`<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/terminate") ?>`, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  window.location.reload();
              } else {
                  alert(data.message || 'Failed to terminate contract');
              }
          });
    });

    // Handle contract deletion
    document.querySelector('.delete-contract')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this contract? This action cannot be undone.')) {
            fetch(`<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}") ?>`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      window.location.href = '<?= url("vendors/{$vendor['id']}/contracts") ?>';
                  } else {
                      alert(data.message || 'Failed to delete contract');
                  }
              });
        }
    });

    // Handle note deletion
    document.querySelectorAll('.delete-note').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this note?')) {
                const noteId = this.dataset.id;
                fetch(`<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/notes") ?>/${noteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          this.closest('.timeline-item').remove();
                      } else {
                          alert(data.message || 'Failed to delete note');
                      }
                  });
            }
        });
    });

    // Handle note submission
    noteForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        fetch(`<?= url("vendors/{$vendor['id']}/contracts/{$contract['id']}/notes") ?>`, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  window.location.reload();
              } else {
                  alert(data.message || 'Failed to add note');
              }
          });
    });
});

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 1.5rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    background-color: #dee2e6;
    border: 2px solid #fff;
}

.timeline-item:after {
    content: '';
    position: absolute;
    left: -1.1875rem;
    top: 1rem;
    bottom: 0;
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item:last-child:after {
    display: none;
}

.timeline-date {
    font-size: 0.875em;
    margin-bottom: 0.25rem;
}

.badge {
    padding: 0.5em 0.75em;
}

.list-group-item {
    padding: 1rem;
}

@media (max-width: 992px) {
    .col-lg-8,
    .col-lg-4 {
        width: 100%;
    }
}

@media print {
    .no-print {
        display: none !important;
    }
}
</style> 