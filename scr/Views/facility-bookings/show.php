<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Facility Booking Details</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('facility-bookings') ?>" class="btn btn-secondary me-2">
                <i class='bx bx-arrow-back'></i> Back to Bookings
            </a>
            <?php if ($booking['status'] === 'pending'): ?>
                <a href="<?= $this->url("facility-bookings/{$booking['id']}/edit") ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Booking
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Booking Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Booking Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $this->getStatusBadgeColor($booking['status']) ?>">
                                            <?= ucfirst($booking['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Booked By</th>
                                    <td><?= htmlspecialchars($booking['booked_by_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Start Time</th>
                                    <td><?= date('M d, Y H:i', strtotime($booking['start_time'])) ?></td>
                                </tr>
                                <tr>
                                    <th>End Time</th>
                                    <td><?= date('M d, Y H:i', strtotime($booking['end_time'])) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Facility Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Facility Name</th>
                                    <td><?= htmlspecialchars($booking['facility_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Building</th>
                                    <td><?= htmlspecialchars($booking['building']) ?></td>
                                </tr>
                                <tr>
                                    <th>Floor</th>
                                    <td><?= htmlspecialchars($booking['floor']) ?></td>
                                </tr>
                                <tr>
                                    <th>Room</th>
                                    <td><?= htmlspecialchars($booking['room']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purpose -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Purpose</h5>
                    <p class="mb-0"><?= nl2br(htmlspecialchars($booking['purpose'])) ?></p>
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
                        <?php if ($booking['status'] === 'pending' && $this->user['role'] === 'admin'): ?>
                            <form method="POST" action="<?= $this->url("facility-bookings/{$booking['id']}/status") ?>" class="d-grid">
                                <?= $this->csrf_field() ?>
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-success">
                                    <i class='bx bx-check'></i> Approve Booking
                                </button>
                            </form>

                            <form method="POST" action="<?= $this->url("facility-bookings/{$booking['id']}/status") ?>" class="d-grid">
                                <?= $this->csrf_field() ?>
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger">
                                    <i class='bx bx-x'></i> Reject Booking
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if (in_array($booking['status'], ['pending', 'approved'])): ?>
                            <form method="POST" action="<?= $this->url("facility-bookings/{$booking['id']}/status") ?>" class="d-grid">
                                <?= $this->csrf_field() ?>
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn btn-warning">
                                    <i class='bx bx-x'></i> Cancel Booking
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if ($this->user['role'] === 'admin'): ?>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class='bx bx-trash'></i> Delete Booking
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Booking Timeline -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Booking Timeline</h5>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date"><?= date('M d, Y H:i', strtotime($booking['created_at'])) ?></div>
                            <div class="timeline-content">
                                <p class="mb-0">Booking created by <?= htmlspecialchars($booking['booked_by_name']) ?></p>
                            </div>
                        </div>
                        <?php if ($booking['approved_by']): ?>
                            <div class="timeline-item">
                                <div class="timeline-date"><?= date('M d, Y H:i', strtotime($booking['updated_at'])) ?></div>
                                <div class="timeline-content">
                                    <p class="mb-0">Booking <?= $booking['status'] ?> by <?= htmlspecialchars($booking['approved_by_name']) ?></p>
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
                <h5 class="modal-title">Delete Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this facility booking? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?= $this->url("facility-bookings/{$booking['id']}/delete") ?>" class="d-inline">
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
        case 'pending':
            return 'warning';
        case 'approved':
            return 'success';
        case 'rejected':
            return 'danger';
        case 'cancelled':
            return 'secondary';
        default:
            return 'secondary';
    }
}
?> 