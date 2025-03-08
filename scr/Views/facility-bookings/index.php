<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Facility Bookings</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('facility-bookings/create') ?>" class="btn btn-primary">
                <i class='bx bx-plus'></i> New Booking
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Bookings</h6>
                    <h2 class="mb-0"><?= $stats['total_bookings'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Pending</h6>
                    <h2 class="mb-0"><?= $stats['pending_bookings'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Approved</h6>
                    <h2 class="mb-0"><?= $stats['approved_bookings'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Rejected</h6>
                    <h2 class="mb-0"><?= $stats['rejected_bookings'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $this->url('facility-bookings') ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="query" class="form-control" placeholder="Search bookings..." 
                        value="<?= $_GET['query'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class='bx bx-search'></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Facility</th>
                            <th>Booked By</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No facility bookings found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url("facility-bookings/{$booking['id']}") ?>">
                                            <?= htmlspecialchars($booking['facility_name']) ?>
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($booking['building']) ?> - 
                                            <?= htmlspecialchars($booking['floor']) ?> - 
                                            <?= htmlspecialchars($booking['room']) ?>
                                        </small>
                                    </td>
                                    <td><?= htmlspecialchars($booking['booked_by_name']) ?></td>
                                    <td><?= date('M d, Y H:i', strtotime($booking['start_time'])) ?></td>
                                    <td><?= date('M d, Y H:i', strtotime($booking['end_time'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $this->getStatusBadgeColor($booking['status']) ?>">
                                            <?= ucfirst($booking['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= $this->url("facility-bookings/{$booking['id']}") ?>" 
                                                class="btn btn-sm btn-info" title="View Details">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <?php if ($booking['status'] === 'pending'): ?>
                                                <a href="<?= $this->url("facility-bookings/{$booking['id']}/edit") ?>" 
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->user['role'] === 'admin'): ?>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal<?= $booking['id'] ?>"
                                                    title="Delete">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <?php if ($this->user['role'] === 'admin'): ?>
                                    <div class="modal fade" id="deleteModal<?= $booking['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Booking</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this facility booking?</p>
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
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (!empty($bookings)): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $this->url('facility-bookings', ['page' => $currentPage - 1]) ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="page-item active">
                            <span class="page-link"><?= $currentPage ?></span>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="<?= $this->url('facility-bookings', ['page' => $currentPage + 1]) ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

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