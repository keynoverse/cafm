<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Preventive Maintenance Task Details</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('preventive-maintenance') ?>" class="btn btn-secondary me-2">
                <i class='bx bx-arrow-back'></i> Back to Tasks
            </a>
            <?php if ($this->user['role'] === 'admin'): ?>
                <a href="<?= $this->url("preventive-maintenance/{$task['id']}/edit") ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Task
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Task Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Task Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Title</th>
                                    <td><?= htmlspecialchars($task['title']) ?></td>
                                </tr>
                                <tr>
                                    <th>Asset</th>
                                    <td>
                                        <a href="<?= $this->url("assets/{$task['asset_id']}") ?>">
                                            <?= htmlspecialchars($task['asset_name']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Frequency</th>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= ucfirst($task['frequency']) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title mb-4">Schedule Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Assigned To</th>
                                    <td>
                                        <?php if ($task['assigned_to']): ?>
                                            <?= htmlspecialchars($task['assigned_to_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td><?= htmlspecialchars($task['created_by_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td><?= date('M d, Y H:i', strtotime($task['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Last Performed</th>
                                    <td>
                                        <?php if ($task['last_performed']): ?>
                                            <?= date('M d, Y', strtotime($task['last_performed'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Never</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Next Due</th>
                                    <td>
                                        <?php
                                        $nextDue = strtotime($task['next_due']);
                                        $isOverdue = $nextDue < time();
                                        ?>
                                        <span class="<?= $isOverdue ? 'text-danger' : '' ?>">
                                            <?= date('M d, Y', $nextDue) ?>
                                            <?php if ($isOverdue): ?>
                                                <i class='bx bx-time-five'></i> Overdue
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Description</h5>
                    <div class="description">
                        <?= nl2br(htmlspecialchars($task['description'])) ?>
                    </div>
                </div>
            </div>

            <!-- History -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Task History</h5>
                    <div class="history">
                        <?php if (empty($task['history'])): ?>
                            <p class="text-muted">No history available.</p>
                        <?php else: ?>
                            <div class="timeline">
                                <?php foreach ($task['history'] as $entry): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-date">
                                            <?= date('M d, Y H:i', strtotime($entry['created_at'])) ?>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?= htmlspecialchars($entry['user_name']) ?></strong>
                                                    <span class="badge bg-secondary ms-2"><?= ucfirst($entry['action']) ?></span>
                                                </div>
                                            </div>
                                            <?php if ($entry['details']): ?>
                                                <p class="mb-0 mt-2"><?= htmlspecialchars($entry['details']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
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
                        <button type="button" class="btn btn-success mark-completed">
                            <i class='bx bx-check'></i> Mark as Completed
                        </button>

                        <?php if ($this->user['role'] === 'admin'): ?>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class='bx bx-trash'></i> Delete Task
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Related Tasks -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Related Tasks</h5>
                    <?php if (empty($relatedTasks)): ?>
                        <p class="text-muted">No related tasks found.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($relatedTasks as $related): ?>
                                <a href="<?= $this->url("preventive-maintenance/{$related['id']}") ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= htmlspecialchars($related['title']) ?></h6>
                                        <small><?= date('M d, Y', strtotime($related['next_due'])) ?></small>
                                    </div>
                                    <p class="mb-1"><?= htmlspecialchars(substr($related['description'], 0, 100)) ?>...</p>
                                    <small>
                                        <span class="badge bg-info">
                                            <?= ucfirst($related['frequency']) ?>
                                        </span>
                                    </small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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
                <h5 class="modal-title">Delete Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this preventive maintenance task? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?= $this->url("preventive-maintenance/{$task['id']}/delete") ?>" class="d-inline">
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
    background: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle mark as completed
    document.querySelector('.mark-completed').addEventListener('click', function() {
        if (confirm('Are you sure you want to mark this task as completed?')) {
            fetch('<?= $this->url("preventive-maintenance/{$task['id']}/complete") ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': '<?= $this->csrf_token() ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to mark task as completed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while marking the task as completed');
            });
        }
    });
});
</script> 