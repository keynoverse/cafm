<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Work Order Details</h1>
        </div>
        <div class="col text-end">
            <a href="<?= $this->url('work-orders') ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Work Orders
            </a>
            <?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
                <a href="<?= $this->url("work-orders/{$workOrder['id']}/edit") ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit Work Order
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Work Order Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Work Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th style="width: 150px;">Title</th>
                                    <td><?= htmlspecialchars($workOrder['title']) ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?= nl2br(htmlspecialchars($workOrder['description'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Priority</th>
                                    <td>
                                        <span class="badge bg-<?= $workOrder['priority'] === 'urgent' ? 'danger' : 
                                            ($workOrder['priority'] === 'high' ? 'warning' : 
                                            ($workOrder['priority'] === 'medium' ? 'info' : 'success')) ?>">
                                            <?= ucfirst($workOrder['priority']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $workOrder['status'] === 'completed' ? 'success' : 
                                            ($workOrder['status'] === 'in_progress' ? 'primary' : 
                                            ($workOrder['status'] === 'cancelled' ? 'danger' : 'secondary')) ?>">
                                            <?= ucfirst(str_replace('_', ' ', $workOrder['status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th style="width: 150px;">Assigned To</th>
                                    <td>
                                        <?php if ($workOrder['assigned_to_first_name']): ?>
                                            <?= htmlspecialchars($workOrder['assigned_to_first_name'] . ' ' . $workOrder['assigned_to_last_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Due Date</th>
                                    <td>
                                        <?php if ($workOrder['due_date']): ?>
                                            <?= date('M d, Y', strtotime($workOrder['due_date'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">No due date</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estimated Cost</th>
                                    <td>
                                        <?php if ($workOrder['estimated_cost']): ?>
                                            $<?= number_format($workOrder['estimated_cost'], 2) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not specified</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Actual Cost</th>
                                    <td>
                                        <?php if ($workOrder['actual_cost']): ?>
                                            $<?= number_format($workOrder['actual_cost'], 2) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not specified</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checklist Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Task Checklist</h5>
                    <?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            <i class='bx bx-plus'></i> Add Task
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <!-- Task Progress -->
                    <div class="task-progress mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Task Progress</h6>
                            <span class="badge bg-primary" id="taskProgressPercentage">
                                <?php
                                $totalTasks = count($tasks);
                                $completedTasks = count(array_filter($tasks, function($task) {
                                    return $task['completed'];
                                }));
                                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                echo $percentage . '% Complete';
                                ?>
                            </span>
                        </div>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                style="width: <?= $percentage ?>%" 
                                aria-valuenow="<?= $percentage ?>" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span><?= $completedTasks ?> of <?= $totalTasks ?> tasks completed</span>
                            <span><?= $totalTasks - $completedTasks ?> tasks remaining</span>
                        </div>
                    </div>

                    <!-- Task Filters -->
                    <div class="task-filters mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-search'></i></span>
                                    <input type="text" class="form-control" id="taskSearch" placeholder="Search tasks...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="taskPriorityFilter">
                                    <option value="">All Priorities</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="taskStatusFilter">
                                    <option value="">All Statuses</option>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" id="taskSort">
                                    <option value="priority">Sort by Priority</option>
                                    <option value="due_date">Sort by Due Date</option>
                                    <option value="assigned">Sort by Assigned To</option>
                                    <option value="created">Sort by Created Date</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <?php if (empty($tasks)): ?>
                        <p class="text-muted mb-0">No tasks defined for this work order.</p>
                    <?php else: ?>
                        <div class="tasks-list">
                            <?php foreach ($tasks as $task): ?>
                                <div class="task-item mb-3" id="task-<?= $task['id'] ?>" 
                                    data-priority="<?= $task['priority'] ?>"
                                    data-status="<?= $task['completed'] ? 'completed' : 'pending' ?>"
                                    data-due-date="<?= $task['due_date'] ?>"
                                    data-assigned-to="<?= $task['assigned_to_first_name'] . ' ' . $task['assigned_to_last_name'] ?>"
                                    data-created="<?= $task['created_at'] ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check me-3">
                                            <input class="form-check-input task-checkbox" type="checkbox" 
                                                id="task-checkbox-<?= $task['id'] ?>" 
                                                <?= $task['completed'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="task-checkbox-<?= $task['id'] ?>"></label>
                                        </div>
                                        <div class="task-content flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="d-flex align-items-center mb-1">
                                                        <h6 class="mb-0 <?= $task['completed'] ? 'text-muted text-decoration-line-through' : '' ?>">
                                                            <?= htmlspecialchars($task['description']) ?>
                                                        </h6>
                                                        <?php if ($task['priority']): ?>
                                                            <span class="badge bg-<?= $task['priority'] === 'urgent' ? 'danger' : 
                                                                ($task['priority'] === 'high' ? 'warning' : 
                                                                ($task['priority'] === 'medium' ? 'info' : 'success')) ?> ms-2">
                                                                <?= ucfirst($task['priority']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <?php if ($task['assigned_to_first_name']): ?>
                                                            <small class="text-muted me-3">
                                                                <i class='bx bx-user'></i> <?= htmlspecialchars($task['assigned_to_first_name'] . ' ' . $task['assigned_to_last_name']) ?>
                                                            </small>
                                                        <?php endif; ?>
                                                        <?php if ($task['due_date']): ?>
                                                            <small class="text-muted me-3">
                                                                <i class='bx bx-calendar'></i> Due: <?= date('M d, Y', strtotime($task['due_date'])) ?>
                                                            </small>
                                                        <?php endif; ?>
                                                        <div class="task-time-tracking">
                                                            <small class="text-muted me-2">
                                                                <i class='bx bx-time'></i> Time: <span class="task-total-time">0:00:00</span>
                                                            </small>
                                                            <button type="button" class="btn btn-sm btn-outline-primary task-timer-control" data-task-id="<?= $task['id'] ?>">
                                                                <i class='bx bx-play'></i> Start
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="task-actions">
                                                    <?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
                                                        <button type="button" class="btn btn-link text-primary task-dependencies-button" data-task-id="<?= $task['id'] ?>">
                                                            <i class='bx bx-link'></i>
                                                        </button>
                                                        <button type="button" class="btn btn-link text-primary task-notes-button" data-task-id="<?= $task['id'] ?>">
                                                            <i class='bx bx-note'></i>
                                                        </button>
                                                        <button type="button" class="btn btn-link text-danger delete-task" data-task-id="<?= $task['id'] ?>">
                                                            <i class='bx bx-trash'></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="task-time-history mt-2" id="task-time-history-<?= $task['id'] ?>">
                                                <!-- Time history entries will be loaded here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Location Information -->
            <?php if ($workOrder['asset_name'] || $workOrder['location_name']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Location Information</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($workOrder['asset_name']): ?>
                            <div class="mb-3">
                                <h6>Asset</h6>
                                <p class="mb-0">
                                    <a href="<?= $this->url("assets/{$workOrder['asset_id']}") ?>">
                                        <?= htmlspecialchars($workOrder['asset_name']) ?>
                                    </a>
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if ($workOrder['location_name']): ?>
                            <div class="mb-3">
                                <h6>Location</h6>
                                <p class="mb-0">
                                    <a href="<?= $this->url("locations/{$workOrder['location_id']}") ?>">
                                        <?= htmlspecialchars($workOrder['building_name']) ?> &gt;
                                        <?= htmlspecialchars($workOrder['floor_name']) ?> &gt;
                                        <?= htmlspecialchars($workOrder['room_name']) ?> &gt;
                                        <?= htmlspecialchars($workOrder['location_name']) ?>
                                    </a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Cost Comparison -->
            <?php if ($workOrder['estimated_cost'] || $workOrder['actual_cost']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Cost Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="cost-card">
                                    <h6 class="text-muted mb-2">Estimated Cost</h6>
                                    <div class="cost-amount">
                                        $<?= number_format($workOrder['estimated_cost'] ?? 0, 2) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cost-card">
                                    <h6 class="text-muted mb-2">Actual Cost</h6>
                                    <div class="cost-amount <?= $workOrder['actual_cost'] > ($workOrder['estimated_cost'] ?? 0) ? 'text-danger' : 'text-success' ?>">
                                        $<?= number_format($workOrder['actual_cost'] ?? 0, 2) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($workOrder['actual_cost'] && $workOrder['estimated_cost']): ?>
                            <?php
                            $difference = $workOrder['actual_cost'] - $workOrder['estimated_cost'];
                            $percentage = ($difference / $workOrder['estimated_cost']) * 100;
                            ?>
                            <div class="cost-difference mt-4">
                                <h6 class="text-muted mb-2">Cost Difference</h6>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-<?= $difference > 0 ? 'danger' : 'success' ?> me-2">
                                        <?= $difference > 0 ? '+' : '' ?><?= number_format($difference, 2) ?>
                                    </span>
                                    <span class="text-<?= $difference > 0 ? 'danger' : 'success' ?>">
                                        (<?= $difference > 0 ? '+' : '' ?><?= number_format($percentage, 1) ?>%)
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Completion Metrics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Completion Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $createdDate = strtotime($workOrder['created_at']);
                        $dueDate = $workOrder['due_date'] ? strtotime($workOrder['due_date']) : null;
                        $completedDate = $workOrder['status'] === 'completed' ? strtotime($workOrder['updated_at']) : null;
                        $cancelledDate = $workOrder['status'] === 'cancelled' ? strtotime($workOrder['updated_at']) : null;
                        
                        // Calculate completion time
                        $completionTime = null;
                        if ($completedDate) {
                            $completionTime = $completedDate - $createdDate;
                        }
                        
                        // Calculate time to due date
                        $timeToDue = null;
                        if ($dueDate) {
                            $timeToDue = $dueDate - $createdDate;
                        }
                        
                        // Calculate efficiency score
                        $efficiencyScore = null;
                        if ($completionTime && $timeToDue) {
                            $efficiencyScore = ($timeToDue / $completionTime) * 100;
                        }
                        ?>
                        
                        <!-- Time Metrics -->
                        <div class="col-md-6">
                            <div class="metric-card">
                                <h6 class="text-muted mb-3">Time Metrics</h6>
                                <div class="metric-item mb-3">
                                    <div class="metric-label">Created</div>
                                    <div class="metric-value">
                                        <?= date('M d, Y H:i', $createdDate) ?>
                                    </div>
                                </div>
                                <?php if ($dueDate): ?>
                                    <div class="metric-item mb-3">
                                        <div class="metric-label">Due Date</div>
                                        <div class="metric-value">
                                            <?= date('M d, Y', $dueDate) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($completedDate): ?>
                                    <div class="metric-item mb-3">
                                        <div class="metric-label">Completed</div>
                                        <div class="metric-value">
                                            <?= date('M d, Y H:i', $completedDate) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($cancelledDate): ?>
                                    <div class="metric-item mb-3">
                                        <div class="metric-label">Cancelled</div>
                                        <div class="metric-value">
                                            <?= date('M d, Y H:i', $cancelledDate) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Duration Metrics -->
                        <div class="col-md-6">
                            <div class="metric-card">
                                <h6 class="text-muted mb-3">Duration Metrics</h6>
                                <?php if ($completionTime): ?>
                                    <div class="metric-item mb-3">
                                        <div class="metric-label">Completion Time</div>
                                        <div class="metric-value">
                                            <?php
                                            $days = floor($completionTime / (24 * 60 * 60));
                                            $hours = floor(($completionTime % (24 * 60 * 60)) / (60 * 60));
                                            $minutes = floor(($completionTime % (60 * 60)) / 60);
                                            
                                            $duration = [];
                                            if ($days > 0) $duration[] = $days . ' day' . ($days > 1 ? 's' : '');
                                            if ($hours > 0) $duration[] = $hours . ' hour' . ($hours > 1 ? 's' : '');
                                            if ($minutes > 0) $duration[] = $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                                            
                                            echo implode(', ', $duration);
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($timeToDue): ?>
                                    <div class="metric-item mb-3">
                                        <div class="metric-label">Time to Due Date</div>
                                        <div class="metric-value">
                                            <?php
                                            $days = floor($timeToDue / (24 * 60 * 60));
                                            $hours = floor(($timeToDue % (24 * 60 * 60)) / (60 * 60));
                                            $minutes = floor(($timeToDue % (60 * 60)) / 60);
                                            
                                            $duration = [];
                                            if ($days > 0) $duration[] = $days . ' day' . ($days > 1 ? 's' : '');
                                            if ($hours > 0) $duration[] = $hours . ' hour' . ($hours > 1 ? 's' : '');
                                            if ($minutes > 0) $duration[] = $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                                            
                                            echo implode(', ', $duration);
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($efficiencyScore): ?>
                                    <div class="metric-item">
                                        <div class="metric-label">Efficiency Score</div>
                                        <div class="metric-value">
                                            <span class="badge bg-<?= $efficiencyScore >= 100 ? 'success' : 
                                                ($efficiencyScore >= 80 ? 'info' : 
                                                ($efficiencyScore >= 60 ? 'warning' : 'danger')) ?>">
                                                <?= number_format($efficiencyScore, 1) ?>%
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dependencies Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Dependencies</h5>
                    <?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addDependencyModal">
                            <i class='bx bx-plus'></i> Add Dependency
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($dependencies)): ?>
                        <p class="text-muted mb-0">No dependencies defined for this work order.</p>
                    <?php else: ?>
                        <div class="dependencies-list">
                            <?php foreach ($dependencies as $dependency): ?>
                                <div class="dependency-item mb-3" id="dependency-<?= $dependency['id'] ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="dependency-icon me-3">
                                                <i class='bx bx-link fs-4'></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">
                                                    <a href="<?= $this->url("work-orders/{$dependency['related_work_order_id']}") ?>" 
                                                        class="text-decoration-none">
                                                        <?= htmlspecialchars($dependency['related_work_order_title']) ?>
                                                    </a>
                                                </h6>
                                                <small class="text-muted">
                                                    <?= ucfirst($dependency['relationship_type']) ?> • 
                                                    Status: 
                                                    <span class="badge bg-<?= $dependency['related_work_order_status'] === 'completed' ? 'success' : 
                                                        ($dependency['related_work_order_status'] === 'in_progress' ? 'primary' : 
                                                        ($dependency['related_work_order_status'] === 'cancelled' ? 'danger' : 'secondary')) ?>">
                                                        <?= ucfirst(str_replace('_', ' ', $dependency['related_work_order_status'])) ?>
                                                    </span>
                                                </small>
                                            </div>
                                        </div>
                                        <?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
                                            <button type="button" class="btn btn-link text-danger delete-dependency" 
                                                data-dependency-id="<?= $dependency['id'] ?>">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tags Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tags & Categories</h5>
                    <?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTagModal">
                            <i class='bx bx-plus'></i> Add Tag
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($tags)): ?>
                        <p class="text-muted mb-0">No tags assigned to this work order.</p>
                    <?php else: ?>
                        <div class="tags-list">
                            <?php foreach ($tags as $tag): ?>
                                <div class="tag-item mb-2 me-2" id="tag-<?= $tag['id'] ?>">
                                    <span class="badge bg-<?= $tag['color'] ?>">
                                        <?= htmlspecialchars($tag['name']) ?>
                                        <?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
                                            <button type="button" class="btn btn-link text-white p-0 ms-1 delete-tag" 
                                                data-tag-id="<?= $tag['id'] ?>">
                                                <i class='bx bx-x'></i>
                                            </button>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Comments</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($comments)): ?>
                        <p class="text-muted mb-4">No comments yet. Be the first to add a comment.</p>
                    <?php else: ?>
                        <div class="comments-list mb-4">
                            <?php foreach ($comments as $comment): ?>
                                <div class="comment mb-4" id="comment-<?= $comment['id'] ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="avatar-circle me-2">
                                                    <?= strtoupper(substr($comment['user_first_name'], 0, 1) . substr($comment['user_last_name'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($comment['user_first_name'] . ' ' . $comment['user_last_name']) ?></h6>
                                                    <small class="text-muted">
                                                        <?= date('M d, Y H:i', strtotime($comment['created_at'])) ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="comment-content">
                                                <?= nl2br(htmlspecialchars($comment['content'])) ?>
                                            </div>
                                        </div>
                                        <?php if ($this->user['id'] === $comment['user_id'] || $this->user['role'] === 'admin'): ?>
                                            <button type="button" class="btn btn-link text-danger delete-comment" 
                                                data-comment-id="<?= $comment['id'] ?>">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Add Comment Form -->
                    <form method="POST" action="<?= $this->url("work-orders/{$workOrder['id']}/comments") ?>" class="comment-form">
                        <?= $this->csrf_field() ?>
                        <div class="mb-3">
                            <textarea name="content" class="form-control" rows="3" 
                                placeholder="Add a comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-comment-add'></i> Add Comment
                        </button>
                    </form>
                </div>
            </div>

            <!-- Attachments Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attachments</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($attachments)): ?>
                        <p class="text-muted mb-4">No attachments yet.</p>
                    <?php else: ?>
                        <div class="attachments-list mb-4">
                            <?php foreach ($attachments as $attachment): ?>
                                <div class="attachment-item mb-3" id="attachment-<?= $attachment['id'] ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="attachment-icon me-3">
                                                <?php
                                                $fileType = strtolower(pathinfo($attachment['filename'], PATHINFO_EXTENSION));
                                                $icon = 'bx-file';
                                                if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                    $icon = 'bx-image';
                                                } elseif (in_array($fileType, ['pdf'])) {
                                                    $icon = 'bx-file-pdf';
                                                } elseif (in_array($fileType, ['doc', 'docx'])) {
                                                    $icon = 'bx-file-doc';
                                                } elseif (in_array($fileType, ['xls', 'xlsx'])) {
                                                    $icon = 'bx-file-excel';
                                                }
                                                ?>
                                                <i class='bx <?= $icon ?> fs-4'></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">
                                                    <a href="<?= $this->url("work-orders/{$workOrder['id']}/attachments/{$attachment['id']}/download") ?>" 
                                                        class="text-decoration-none">
                                                        <?= htmlspecialchars($attachment['filename']) ?>
                                                    </a>
                                                </h6>
                                                <small class="text-muted">
                                                    <?= number_format($attachment['file_size'] / 1024, 2) ?> KB
                                                    • Uploaded by <?= htmlspecialchars($attachment['user_first_name'] . ' ' . $attachment['user_last_name']) ?>
                                                    • <?= date('M d, Y H:i', strtotime($attachment['created_at'])) ?>
                                                </small>
                                            </div>
                                        </div>
                                        <?php if ($this->user['id'] === $attachment['user_id'] || $this->user['role'] === 'admin'): ?>
                                            <button type="button" class="btn btn-link text-danger delete-attachment" 
                                                data-attachment-id="<?= $attachment['id'] ?>">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Upload Attachment Form -->
                    <form method="POST" action="<?= $this->url("work-orders/{$workOrder['id']}/attachments") ?>" 
                        class="attachment-form" enctype="multipart/form-data">
                        <?= $this->csrf_field() ?>
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Upload File</label>
                            <input type="file" class="form-control" id="attachment" name="attachment" required>
                            <div class="form-text">
                                Maximum file size: 10MB. Allowed types: images, PDF, Word, Excel.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-upload'></i> Upload Attachment
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($workOrder['status'] !== 'completed' && $workOrder['status'] !== 'cancelled'): ?>
                            <div class="mb-4">
                                <h6 class="mb-3">Update Status</h6>
                                <div class="d-grid gap-2">
                                    <?php if ($workOrder['status'] === 'pending'): ?>
                                        <button type="button" class="btn btn-primary update-status" data-status="in_progress">
                                            <i class='bx bx-time'></i> Start Work
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if ($workOrder['status'] === 'in_progress'): ?>
                                        <button type="button" class="btn btn-success update-status" data-status="completed">
                                            <i class='bx bx-check'></i> Mark as Completed
                                        </button>
                                    <?php endif; ?>

                                    <?php if (in_array($workOrder['status'], ['pending', 'in_progress'])): ?>
                                        <button type="button" class="btn btn-danger update-status" data-status="cancelled">
                                            <i class='bx bx-x'></i> Cancel Work Order
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Cost Tracking -->
                            <div class="mb-4">
                                <h6 class="mb-3">Update Cost</h6>
                                <form method="POST" action="<?= $this->url("work-orders/{$workOrder['id']}/cost") ?>" class="cost-form">
                                    <?= $this->csrf_field() ?>
                                    <div class="mb-3">
                                        <label for="actual_cost" class="form-label">Actual Cost</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="actual_cost" name="actual_cost" 
                                                value="<?= $workOrder['actual_cost'] ?>" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class='bx bx-dollar'></i> Update Cost
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>

                        <div class="d-grid gap-2">
                            <a href="<?= $this->url("work-orders/{$workOrder['id']}/edit") ?>" class="btn btn-primary">
                                <i class='bx bx-edit'></i> Edit Work Order
                            </a>
                            <?php if ($this->user['role'] === 'admin'): ?>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class='bx bx-trash'></i> Delete Work Order
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Work Order Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Work Order Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Work Order History -->
                        <?php if (!empty($workOrderHistory)): ?>
                            <?php foreach ($workOrderHistory as $history): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date">
                                        <?= date('M d, Y H:i', strtotime($history['created_at'])) ?>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Work Order Updated</h6>
                                        <p class="mb-0">
                                            <?php
                                            $changes = [];
                                            if ($history['title_changed']) {
                                                $changes[] = 'title';
                                            }
                                            if ($history['description_changed']) {
                                                $changes[] = 'description';
                                            }
                                            if ($history['priority_changed']) {
                                                $changes[] = 'priority';
                                            }
                                            if ($history['status_changed']) {
                                                $changes[] = 'status';
                                            }
                                            if ($history['assigned_to_changed']) {
                                                $changes[] = 'assignment';
                                            }
                                            if ($history['due_date_changed']) {
                                                $changes[] = 'due date';
                                            }
                                            if ($history['estimated_cost_changed']) {
                                                $changes[] = 'estimated cost';
                                            }
                                            if ($history['actual_cost_changed']) {
                                                $changes[] = 'actual cost';
                                            }
                                            if ($history['asset_id_changed']) {
                                                $changes[] = 'asset';
                                            }
                                            if ($history['location_id_changed']) {
                                                $changes[] = 'location';
                                            }
                                            ?>
                                            <?php if (count($changes) === 1): ?>
                                                <?= ucfirst($changes[0]) ?> was modified
                                            <?php else: ?>
                                                <?= implode(', ', array_slice($changes, 0, -1)) ?> and <?= end($changes) ?> were modified
                                            <?php endif; ?>
                                            <?php if ($history['user_first_name']): ?>
                                                by <?= htmlspecialchars($history['user_first_name'] . ' ' . $history['user_last_name']) ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Cost History -->
                        <?php if (!empty($costHistory)): ?>
                            <?php foreach ($costHistory as $history): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date">
                                        <?= date('M d, Y H:i', strtotime($history['created_at'])) ?>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Cost Updated</h6>
                                        <p class="mb-0">
                                            <span class="badge bg-info">
                                                $<?= number_format($history['new_cost'], 2) ?>
                                            </span>
                                            <?php if ($history['user_first_name']): ?>
                                                by <?= htmlspecialchars($history['user_first_name'] . ' ' . $history['user_last_name']) ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Status History -->
                        <?php if (!empty($statusHistory)): ?>
                            <?php foreach ($statusHistory as $history): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date">
                                        <?= date('M d, Y H:i', strtotime($history['created_at'])) ?>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Status Changed</h6>
                                        <p class="mb-0">
                                            <span class="badge bg-<?= $history['new_status'] === 'completed' ? 'success' : 
                                                ($history['new_status'] === 'in_progress' ? 'primary' : 
                                                ($history['new_status'] === 'cancelled' ? 'danger' : 'secondary')) ?>">
                                                <?= ucfirst(str_replace('_', ' ', $history['new_status'])) ?>
                                            </span>
                                            <?php if ($history['user_first_name']): ?>
                                                by <?= htmlspecialchars($history['user_first_name'] . ' ' . $history['user_last_name']) ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Creation -->
                        <div class="timeline-item">
                            <div class="timeline-date">
                                <?= date('M d, Y H:i', strtotime($workOrder['created_at'])) ?>
                            </div>
                            <div class="timeline-content">
                                <h6>Created</h6>
                                <p class="mb-0">
                                    By <?= htmlspecialchars($workOrder['created_by_first_name'] . ' ' . $workOrder['created_by_last_name']) ?>
                                </p>
                            </div>
                        </div>

                        <!-- Last Update -->
                        <?php if ($workOrder['updated_at'] !== $workOrder['created_at']): ?>
                            <div class="timeline-item">
                                <div class="timeline-date">
                                    <?= date('M d, Y H:i', strtotime($workOrder['updated_at'])) ?>
                                </div>
                                <div class="timeline-content">
                                    <h6>Last Updated</h6>
                                    <p class="mb-0">Work order details were modified</p>
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
<?php if ($this->user['role'] === 'admin'): ?>
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Work Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this work order? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="<?= $this->url("work-orders/{$workOrder['id']}/delete") ?>" class="d-inline">
                        <?= $this->csrf_field() ?>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Add Dependency Modal -->
<?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
    <div class="modal fade" id="addDependencyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Work Order Dependency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?= $this->url("work-orders/{$workOrder['id']}/dependencies") ?>" class="dependency-form">
                        <?= $this->csrf_field() ?>
                        <div class="mb-3">
                            <label for="related_work_order_id" class="form-label">Related Work Order</label>
                            <select class="form-select" id="related_work_order_id" name="related_work_order_id" required>
                                <option value="">Select a work order...</option>
                                <?php foreach ($availableWorkOrders as $availableWorkOrder): ?>
                                    <?php if ($availableWorkOrder['id'] !== $workOrder['id']): ?>
                                        <option value="<?= $availableWorkOrder['id'] ?>">
                                            <?= htmlspecialchars($availableWorkOrder['title']) ?> 
                                            (<?= ucfirst(str_replace('_', ' ', $availableWorkOrder['status'])) ?>)
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="relationship_type" class="form-label">Relationship Type</label>
                            <select class="form-select" id="relationship_type" name="relationship_type" required>
                                <option value="">Select a relationship type...</option>
                                <option value="blocks">Blocks</option>
                                <option value="blocked_by">Blocked By</option>
                                <option value="relates_to">Relates To</option>
                                <option value="duplicates">Duplicates</option>
                                <option value="duplicated_by">Duplicated By</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="dependency-form" class="btn btn-primary">Add Dependency</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Add Tag Modal -->
<?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
    <div class="modal fade" id="addTagModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?= $this->url("work-orders/{$workOrder['id']}/tags") ?>" class="tag-form">
                        <?= $this->csrf_field() ?>
                        <div class="mb-3">
                            <label for="tag_id" class="form-label">Select Tag</label>
                            <select class="form-select" id="tag_id" name="tag_id" required>
                                <option value="">Choose a tag...</option>
                                <?php foreach ($availableTags as $availableTag): ?>
                                    <?php if (!in_array($availableTag['id'], array_column($tags, 'id'))): ?>
                                        <option value="<?= $availableTag['id'] ?>" 
                                            data-color="<?= $availableTag['color'] ?>">
                                            <?= htmlspecialchars($availableTag['name']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="tag-form" class="btn btn-primary">Add Tag</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Add Task Modal -->
<?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
    <div class="modal fade" id="addTaskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?= $this->url("work-orders/{$workOrder['id']}/tasks") ?>" class="task-form">
                        <?= $this->csrf_field() ?>
                        <div class="mb-3">
                            <label for="task_description" class="form-label">Task Description</label>
                            <textarea class="form-control" id="task_description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="task_priority" class="form-label">Priority</label>
                                <select class="form-select" id="task_priority" name="priority">
                                    <option value="">Select priority...</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="task_due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="task_due_date" name="due_date">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="task_assigned_to" class="form-label">Assign To</label>
                            <select class="form-select" id="task_assigned_to" name="assigned_to">
                                <option value="">Select a user...</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>">
                                        <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="task-form" class="btn btn-primary">Add Task</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Add Task Notes Modal -->
<?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
    <div class="modal fade" id="taskNotesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Task Notes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?= $this->url("work-orders/{$workOrder['id']}/tasks/notes") ?>" class="task-notes-form">
                        <?= $this->csrf_field() ?>
                        <input type="hidden" id="task_notes_id" name="task_id">
                        <div class="mb-3">
                            <label for="task_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="task_notes" name="notes" rows="5" 
                                placeholder="Add notes about this task..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="task-notes-form" class="btn btn-primary">Save Notes</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Add Task Dependencies Modal -->
<?php if (in_array($this->user['role'], ['admin', 'technician'])): ?>
    <div class="modal fade" id="taskDependenciesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Task Dependencies</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?= $this->url("work-orders/{$workOrder['id']}/tasks/dependencies") ?>" class="task-dependencies-form">
                        <?= $this->csrf_field() ?>
                        <input type="hidden" id="task_dependencies_id" name="task_id">
                        <div class="mb-3">
                            <label class="form-label">Dependent Tasks</label>
                            <div class="task-dependencies-list mb-3">
                                <!-- Dependencies will be loaded here -->
                            </div>
                            <div class="input-group">
                                <select class="form-select" id="dependent_task_id" name="dependent_task_id">
                                    <option value="">Select a task...</option>
                                    <?php foreach ($tasks as $availableTask): ?>
                                        <option value="<?= $availableTask['id'] ?>">
                                            <?= htmlspecialchars($availableTask['description']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-outline-primary" id="addDependencyBtn">
                                    <i class='bx bx-plus'></i> Add
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Task Time Tracking Modal -->
<div class="modal fade" id="taskTimeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Time Tracking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="task-time-display mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Total Time</h6>
                        <span class="badge bg-primary" id="taskTotalTime">0:00:00</span>
                    </div>
                    <div class="task-time-history mt-3">
                        <!-- Time history will be loaded here -->
                    </div>
                </div>
                <div class="task-time-controls">
                    <button type="button" class="btn btn-success" id="startTimeTrackingBtn">
                        <i class='bx bx-play'></i> Start Tracking
                    </button>
                    <button type="button" class="btn btn-danger d-none" id="stopTimeTrackingBtn">
                        <i class='bx bx-stop'></i> Stop Tracking
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Task Analytics Section -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Task Analytics</h5>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary" id="refreshAnalytics">
                <i class='bx bx-refresh'></i> Refresh
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="exportAnalytics">
                <i class='bx bx-export'></i> Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Task Completion Rate -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Completion Rate</h6>
                        <canvas id="completionRateChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Task Priority Distribution -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Priority Distribution</h6>
                        <canvas id="priorityDistributionChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Task Time Analysis -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Time Analysis</h6>
                        <canvas id="timeAnalysisChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Task Assignment Distribution -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Assignment Distribution</h6>
                        <canvas id="assignmentDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Task Performance Metrics -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Performance Metrics</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Metric</th>
                                        <th>Value</th>
                                        <th>Trend</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="performanceMetricsTable">
                                    <!-- Performance metrics will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Automation Section -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Task Automation</h5>
        <?php if (in_array($this->user['role'], ['admin'])): ?>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAutomationModal">
                <i class='bx bx-plus'></i> Add Automation
            </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Trigger</th>
                        <th>Action</th>
                        <th>Status</th>
                        <th>Last Run</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="automationRulesTable">
                    <!-- Automation rules will be populated here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Automation Modal -->
<?php if (in_array($this->user['role'], ['admin'])): ?>
    <div class="modal fade" id="addAutomationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Task Automation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="automationForm">
                        <div class="mb-3">
                            <label for="automation_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="automation_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="automation_trigger" class="form-label">Trigger</label>
                            <select class="form-select" id="automation_trigger" required>
                                <option value="">Select trigger...</option>
                                <option value="task_created">Task Created</option>
                                <option value="task_completed">Task Completed</option>
                                <option value="task_overdue">Task Overdue</option>
                                <option value="task_assigned">Task Assigned</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="automation_condition" class="form-label">Condition</label>
                            <select class="form-select" id="automation_condition">
                                <option value="">Select condition...</option>
                                <option value="priority_high">Priority is High</option>
                                <option value="priority_urgent">Priority is Urgent</option>
                                <option value="assigned_to_me">Assigned to Me</option>
                                <option value="no_assignee">No Assignee</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="automation_action" class="form-label">Action</label>
                            <select class="form-select" id="automation_action" required>
                                <option value="">Select action...</option>
                                <option value="assign_to">Assign To</option>
                                <option value="set_priority">Set Priority</option>
                                <option value="add_note">Add Note</option>
                                <option value="create_subtask">Create Subtask</option>
                                <option value="send_notification">Send Notification</option>
                            </select>
                        </div>
                        <div class="mb-3" id="actionParams">
                            <!-- Action parameters will be dynamically added here -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveAutomation">Save Automation</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Task Reporting Section -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Task Reports</h5>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                <i class='bx bx-file'></i> Generate Report
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="scheduleReportBtn">
                <i class='bx bx-calendar'></i> Schedule Report
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Saved Reports -->
        <div class="mb-4">
            <h6 class="mb-3">Saved Reports</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Last Generated</th>
                            <th>Schedule</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="savedReportsTable">
                        <!-- Saved reports will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Report Templates -->
        <div>
            <h6 class="mb-3">Report Templates</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">Task Summary</h6>
                            <p class="card-text">Overview of task completion, priorities, and assignments</p>
                            <button type="button" class="btn btn-sm btn-outline-primary use-template" 
                                data-template="summary">
                                Use Template
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">Time Analysis</h6>
                            <p class="card-text">Detailed analysis of time spent on tasks</p>
                            <button type="button" class="btn btn-sm btn-outline-primary use-template" 
                                data-template="time">
                                Use Template
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">Performance Metrics</h6>
                            <p class="card-text">Key performance indicators and trends</p>
                            <button type="button" class="btn btn-sm btn-outline-primary use-template" 
                                data-template="performance">
                                Use Template
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Report Modal -->
<div class="modal fade" id="generateReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Task Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="report_name" class="form-label">Report Name</label>
                            <input type="text" class="form-control" id="report_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="report_type" class="form-label">Report Type</label>
                            <select class="form-select" id="report_type" required>
                                <option value="">Select type...</option>
                                <option value="summary">Task Summary</option>
                                <option value="time">Time Analysis</option>
                                <option value="performance">Performance Metrics</option>
                                <option value="custom">Custom Report</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="date" class="form-control" id="report_start_date" required>
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" id="report_end_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Report Sections</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="section_completion" checked>
                            <label class="form-check-label" for="section_completion">
                                Task Completion
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="section_priority" checked>
                            <label class="form-check-label" for="section_priority">
                                Priority Distribution
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="section_time" checked>
                            <label class="form-check-label" for="section_time">
                                Time Analysis
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="section_assignment" checked>
                            <label class="form-check-label" for="section_assignment">
                                Assignment Distribution
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="report_format" class="form-label">Format</label>
                        <select class="form-select" id="report_format" required>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="report_recipients" class="form-label">Recipients</label>
                        <select class="form-select" id="report_recipients" multiple>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>">
                                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="generateReportBtn">Generate Report</button>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Report Modal -->
<div class="modal fade" id="scheduleReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleForm">
                    <div class="mb-3">
                        <label for="schedule_name" class="form-label">Schedule Name</label>
                        <input type="text" class="form-control" id="schedule_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="schedule_frequency" class="form-label">Frequency</label>
                        <select class="form-select" id="schedule_frequency" required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="schedule_time" class="form-label">Time</label>
                        <input type="time" class="form-control" id="schedule_time" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="schedule_recipients" class="form-label">Recipients</label>
                        <select class="form-select" id="schedule_recipients" multiple>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>">
                                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="schedule_format" class="form-label">Format</label>
                        <select class="form-select" id="schedule_format" required>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveScheduleBtn">Save Schedule</button>
            </div>
        </div>
    </div>
</div>

<!-- Task Integration Section -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Task Integration</h5>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addIntegrationModal">
            <i class='bx bx-plus'></i> Add Integration
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Last Sync</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="integrationsTable">
                    <!-- Integrations will be populated here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Integration Modal -->
<div class="modal fade" id="addIntegrationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Task Integration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="integrationForm">
                    <div class="mb-3">
                        <label for="integration_service" class="form-label">Service</label>
                        <select class="form-select" id="integration_service" required>
                            <option value="">Select service...</option>
                            <option value="jira">Jira</option>
                            <option value="trello">Trello</option>
                            <option value="asana">Asana</option>
                            <option value="monday">Monday.com</option>
                            <option value="custom">Custom Integration</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="integration_name" class="form-label">Integration Name</label>
                        <input type="text" class="form-control" id="integration_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="integration_api_key" class="form-label">API Key</label>
                        <input type="password" class="form-control" id="integration_api_key" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="integration_secret" class="form-label">API Secret</label>
                        <input type="password" class="form-control" id="integration_secret" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="integration_sync_frequency" class="form-label">Sync Frequency</label>
                        <select class="form-select" id="integration_sync_frequency" required>
                            <option value="realtime">Real-time</option>
                            <option value="hourly">Hourly</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Sync Options</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sync_tasks" checked>
                            <label class="form-check-label" for="sync_tasks">
                                Sync Tasks
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sync_comments" checked>
                            <label class="form-check-label" for="sync_comments">
                                Sync Comments
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sync_attachments" checked>
                            <label class="form-check-label" for="sync_attachments">
                                Sync Attachments
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveIntegrationBtn">Save Integration</button>
            </div>
        </div>
    </div>
</div>

<!-- Task Dependencies Visualization -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Task Dependencies</h5>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary" id="refreshDependencies">
                <i class='bx bx-refresh'></i> Refresh
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="exportDependencies">
                <i class='bx bx-export'></i> Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="dependencies-container" id="dependenciesContainer">
            <!-- Dependencies visualization will be rendered here -->
        </div>
    </div>
</div>

<!-- Task Timeline View -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Task Timeline</h5>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary" id="timelineDay">
                Day
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" id="timelineWeek">
                Week
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary active" id="timelineMonth">
                Month
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="timeline-container" id="timelineContainer">
            <!-- Timeline will be rendered here -->
        </div>
    </div>
</div>

<!-- Task Resource Management -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Resource Management</h5>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addResourceModal">
            <i class='bx bx-plus'></i> Add Resource
        </button>
    </div>
    <div class="card-body">
        <!-- Resource Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Total Resources</h6>
                        <h3 class="mb-0" id="totalResources">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Available Resources</h6>
                        <h3 class="mb-0" id="availableResources">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Resource Utilization</h6>
                        <h3 class="mb-0" id="resourceUtilization">0%</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Resource Cost</h6>
                        <h3 class="mb-0" id="resourceCost">$0</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Resource List -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Resource</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Cost</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="resourcesTable">
                    <!-- Resources will be populated here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Resource Modal -->
<div class="modal fade" id="addResourceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Resource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="resourceForm">
                    <div class="mb-3">
                        <label for="resource_name" class="form-label">Resource Name</label>
                        <input type="text" class="form-control" id="resource_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resource_type" class="form-label">Resource Type</label>
                        <select class="form-select" id="resource_type" required>
                            <option value="">Select type...</option>
                            <option value="equipment">Equipment</option>
                            <option value="material">Material</option>
                            <option value="labor">Labor</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resource_cost" class="form-label">Cost</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="resource_cost" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resource_quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="resource_quantity" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resource_assigned_to" class="form-label">Assigned To</label>
                        <select class="form-select" id="resource_assigned_to">
                            <option value="">Select user...</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>">
                                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resource_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="resource_notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveResourceBtn">Save Resource</button>
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

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item::after {
    content: '';
    position: absolute;
    left: -4px;
    top: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #0d6efd;
}

.timeline-date {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 4px;
}

.timeline-content h6 {
    margin-bottom: 4px;
    font-size: 0.875rem;
}

.timeline-content p {
    font-size: 0.875rem;
    color: #6c757d;
}

.avatar-circle {
    width: 40px;
    height: 40px;
    background-color: #0d6efd;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
}

.comment-content {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-left: 52px;
}

.comment-form textarea {
    resize: none;
}

.delete-comment {
    padding: 0.25rem;
    line-height: 1;
}

.delete-comment:hover {
    color: #dc3545 !important;
}

.timeline-item .badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
    margin-right: 0.5rem;
}

.timeline-item .timeline-content p {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.cost-card {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.5rem;
    text-align: center;
}

.cost-amount {
    font-size: 1.5rem;
    font-weight: 600;
    color: #212529;
}

.cost-difference {
    text-align: center;
}

.cost-difference .badge {
    font-size: 1rem;
    padding: 0.5em 1em;
}

.attachment-item {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    transition: background-color 0.2s;
}

.attachment-item:hover {
    background-color: #e9ecef;
}

.attachment-icon {
    width: 40px;
    height: 40px;
    background-color: #e9ecef;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.attachment-item a {
    color: #212529;
}

.attachment-item a:hover {
    color: #0d6efd;
}

.delete-attachment {
    padding: 0.25rem;
    line-height: 1;
}

.delete-attachment:hover {
    color: #dc3545 !important;
}

.metric-card {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.5rem;
    height: 100%;
}

.metric-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.metric-item:last-child {
    border-bottom: none;
}

.metric-label {
    color: #6c757d;
    font-size: 0.875rem;
}

.metric-value {
    font-weight: 500;
    color: #212529;
}

.metric-value .badge {
    font-size: 0.875rem;
    padding: 0.35em 0.65em;
}

.dependency-item {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    transition: background-color 0.2s;
}

.dependency-item:hover {
    background-color: #e9ecef;
}

.dependency-icon {
    width: 40px;
    height: 40px;
    background-color: #e9ecef;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.dependency-item a {
    color: #212529;
}

.dependency-item a:hover {
    color: #0d6efd;
}

.delete-dependency {
    padding: 0.25rem;
    line-height: 1;
}

.delete-dependency:hover {
    color: #dc3545 !important;
}

.tag-item {
    display: inline-block;
}

.tag-item .badge {
    font-size: 0.875rem;
    padding: 0.5em 0.75em;
    font-weight: 500;
}

.tag-item .delete-tag {
    opacity: 0.8;
    transition: opacity 0.2s;
}

.tag-item .delete-tag:hover {
    opacity: 1;
}

.tag-item .badge {
    display: inline-flex;
    align-items: center;
}

.tag-item .btn-link {
    text-decoration: none;
    padding: 0;
    margin-left: 0.25rem;
}

.tag-item .btn-link:hover {
    text-decoration: none;
}

.task-item {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    transition: background-color 0.2s;
}

.task-item:hover {
    background-color: #e9ecef;
}

.task-item .form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    margin-top: 0.25rem;
}

.task-item .form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

.task-item .form-check-input:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

.task-item .task-content {
    min-width: 0;
}

.task-item h6 {
    margin-bottom: 0.25rem;
    word-break: break-word;
}

.delete-task {
    padding: 0.25rem;
    line-height: 1;
}

.delete-task:hover {
    color: #dc3545 !important;
}

.task-item .badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.task-item small {
    font-size: 0.75rem;
}

.task-item i {
    font-size: 0.875rem;
    vertical-align: middle;
    margin-right: 0.25rem;
}

.task-filters {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
}

.task-filters .input-group-text {
    background-color: white;
}

.task-filters .form-control,
.task-filters .form-select {
    border-color: #e9ecef;
}

.task-filters .form-control:focus,
.task-filters .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.task-item {
    transition: all 0.3s ease;
}

.task-item.hidden {
    display: none;
}

.task-item.highlight {
    background-color: #fff3cd;
}

.task-notes-button {
    padding: 0.25rem;
    line-height: 1;
    color: #6c757d;
    transition: color 0.2s;
}

.task-notes-button:hover {
    color: #0d6efd;
}

.task-notes-button.has-notes {
    color: #0d6efd;
}

.task-notes-preview {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.5rem;
    padding: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
    border-left: 3px solid #0d6efd;
}

.task-notes-preview.empty {
    display: none;
}

.task-progress {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
}

.task-progress .progress {
    background-color: #e9ecef;
    border-radius: 5px;
}

.task-progress .progress-bar {
    border-radius: 5px;
    transition: width 0.3s ease;
}

.task-progress .badge {
    font-size: 0.875rem;
    padding: 0.5em 0.75em;
}

.task-progress .small {
    font-size: 0.75rem;
}

.task-time-tracking {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.task-time-tracking .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.task-time-tracking .btn i {
    font-size: 0.875rem;
    margin-right: 0.25rem;
}

.task-time-tracking .btn.active {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.task-time-tracking .btn.active:hover {
    background-color: #bb2d3b;
    border-color: #b02a37;
}

.task-time-history {
    margin-top: 0.5rem;
    padding: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}

.task-time-history-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.25rem 0;
    border-bottom: 1px solid #e9ecef;
}

.task-time-history-item:last-child {
    border-bottom: none;
}

.task-time-history-item .time-duration {
    color: #6c757d;
}

.task-time-history-item .time-date {
    color: #6c757d;
    font-size: 0.7rem;
}

.task-dependencies-button {
    padding: 0.25rem;
    line-height: 1;
    color: #6c757d;
    transition: color 0.2s;
}

.task-dependencies-button:hover {
    color: #0d6efd;
}

.task-dependencies-button.has-dependencies {
    color: #0d6efd;
}

.task-dependencies-list {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #e9ecef;
    border-radius: 0.25rem;
    padding: 0.5rem;
}

.task-dependency-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
    margin-bottom: 0.5rem;
}

.task-dependency-item:last-child {
    margin-bottom: 0;
}

.task-dependency-item .task-description {
    flex-grow: 1;
    margin-right: 1rem;
    font-size: 0.875rem;
}

.task-dependency-item .delete-dependency {
    padding: 0.25rem;
    line-height: 1;
    color: #dc3545;
}

.task-dependency-item .delete-dependency:hover {
    color: #bb2d3b;
}

/* Add styles for analytics and automation */
.chart-container {
    position: relative;
    height: 300px;
    margin-bottom: 1rem;
}

.performance-metrics .trend-indicator {
    font-size: 1.2rem;
}

.automation-rule {
    transition: all 0.3s ease;
}

.automation-rule:hover {
    background-color: #f8f9fa;
}

.automation-rule .actions {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.automation-rule:hover .actions {
    opacity: 1;
}

#actionParams {
    transition: all 0.3s ease;
}

.analytics-card {
    transition: all 0.3s ease;
}

.analytics-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .chart-container {
        height: 250px;
    }
    
    .performance-metrics .trend-indicator {
        font-size: 1rem;
    }
}

/* Add styles for reporting and integration */
.report-template-card {
    transition: all 0.3s ease;
}

.report-template-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.integration-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.integration-status .status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.integration-status .status-indicator.active {
    background-color: #28a745;
}

.integration-status .status-indicator.inactive {
    background-color: #dc3545;
}

.integration-status .status-indicator.syncing {
    background-color: #ffc107;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.5;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.report-section {
    border-left: 3px solid #0d6efd;
    padding-left: 1rem;
    margin-bottom: 1rem;
}

.report-section h6 {
    color: #0d6efd;
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .report-template-card {
        margin-bottom: 1rem;
    }
}

/* Add styles for dependencies, timeline, and resources */
.dependencies-container {
    height: 500px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
}

.timeline-container {
    height: 400px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
}

.resource-card {
    transition: all 0.3s ease;
}

.resource-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.resource-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.resource-status .status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.resource-status .status-indicator.available {
    background-color: #28a745;
}

.resource-status .status-indicator.in-use {
    background-color: #ffc107;
}

.resource-status .status-indicator.maintenance {
    background-color: #dc3545;
}

@media (max-width: 768px) {
    .dependencies-container,
    .timeline-container {
        height: 300px;
    }
    
    .resource-card {
        margin-bottom: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle comment deletion
    document.querySelectorAll('.delete-comment').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            if (confirm('Are you sure you want to delete this comment?')) {
                fetch('<?= $this->url("work-orders/{$workOrder['id']}/comments/") ?>' + commentId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-Token': '<?= $this->csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('comment-' + commentId).remove();
                    } else {
                        alert('Failed to delete comment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the comment');
                });
            }
        });
    });

    // Handle comment form submission
    document.querySelector('.comment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new comment to the list
                const commentsList = document.querySelector('.comments-list');
                const newComment = document.createElement('div');
                newComment.className = 'comment mb-4';
                newComment.id = 'comment-' + data.comment.id;
                newComment.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar-circle me-2">
                                    ${data.comment.user_first_name[0].toUpperCase()}${data.comment.user_last_name[0].toUpperCase()}
                                </div>
                                <div>
                                    <h6 class="mb-0">${data.comment.user_first_name} ${data.comment.user_last_name}</h6>
                                    <small class="text-muted">${data.comment.created_at}</small>
                                </div>
                            </div>
                            <div class="comment-content">
                                ${data.comment.content.replace(/\n/g, '<br>')}
                            </div>
                        </div>
                        <button type="button" class="btn btn-link text-danger delete-comment" 
                            data-comment-id="${data.comment.id}">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                `;
                commentsList.insertBefore(newComment, commentsList.firstChild);
                form.reset();
            } else {
                alert('Failed to add comment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the comment');
        });
    });

    // Update status history after status change
    function updateStatusHistory(status, statusText) {
        const timeline = document.querySelector('.timeline');
        const newHistoryItem = document.createElement('div');
        newHistoryItem.className = 'timeline-item';
        newHistoryItem.innerHTML = `
            <div class="timeline-date">
                ${new Date().toLocaleString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric'
                })}
            </div>
            <div class="timeline-content">
                <h6>Status Changed</h6>
                <p class="mb-0">
                    <span class="badge bg-${status === 'completed' ? 'success' : 
                        (status === 'in_progress' ? 'primary' : 
                        (status === 'cancelled' ? 'danger' : 'secondary'))}">
                        ${statusText}
                    </span>
                    by <?= htmlspecialchars($this->user['first_name'] . ' ' . $this->user['last_name']) ?>
                </p>
            </div>
        `;
        timeline.insertBefore(newHistoryItem, timeline.firstChild);
    }

    // Update work order history after any change
    function updateWorkOrderHistory(changes) {
        const timeline = document.querySelector('.timeline');
        const newHistoryItem = document.createElement('div');
        newHistoryItem.className = 'timeline-item';
        newHistoryItem.innerHTML = `
            <div class="timeline-date">
                ${new Date().toLocaleString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric'
                })}
            </div>
            <div class="timeline-content">
                <h6>Work Order Updated</h6>
                <p class="mb-0">
                    ${changes.length === 1 ? 
                        changes[0].charAt(0).toUpperCase() + changes[0].slice(1) + ' was modified' :
                        changes.slice(0, -1).join(', ') + ' and ' + changes[changes.length - 1] + ' were modified'
                    }
                    by <?= htmlspecialchars($this->user['first_name'] . ' ' . $this->user['last_name']) ?>
                </p>
            </div>
        `;
        timeline.insertBefore(newHistoryItem, timeline.firstChild);
    }

    // Update status update handler
    document.querySelectorAll('.update-status').forEach(button => {
        button.addEventListener('click', function() {
            const status = this.dataset.status;
            const statusText = this.textContent.trim();
            
            if (confirm(`Are you sure you want to ${statusText.toLowerCase()}?`)) {
                fetch('<?= $this->url("work-orders/{$workOrder['id']}/status") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?= $this->csrf_token() ?>'
                    },
                    body: JSON.stringify({ status })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the status badge
                        const statusBadge = document.querySelector('.badge.bg-<?= $workOrder['status'] === 'completed' ? 'success' : 
                            ($workOrder['status'] === 'in_progress' ? 'primary' : 
                            ($workOrder['status'] === 'cancelled' ? 'danger' : 'secondary')) ?>');
                        
                        statusBadge.className = `badge bg-${status === 'completed' ? 'success' : 
                            (status === 'in_progress' ? 'primary' : 
                            (status === 'cancelled' ? 'danger' : 'secondary'))}`;
                        statusBadge.textContent = status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                        
                        // Add to status history
                        updateStatusHistory(status, status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()));
                        
                        // Add to work order history
                        updateWorkOrderHistory(['status']);
                        
                        // Reload the page to update the UI
                        location.reload();
                    } else {
                        alert('Failed to update status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the status');
                });
            }
        });
    });

    // Handle cost form submission
    document.querySelector('.cost-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the actual cost display in the table
                const actualCostCell = document.querySelector('th:contains("Actual Cost")').nextElementSibling;
                actualCostCell.innerHTML = `$${parseFloat(data.actual_cost).toFixed(2)}`;

                // Update the cost analysis section
                const actualCostAmount = document.querySelector('.cost-card:last-child .cost-amount');
                actualCostAmount.textContent = `$${parseFloat(data.actual_cost).toFixed(2)}`;
                actualCostAmount.className = `cost-amount ${parseFloat(data.actual_cost) > (<?= $workOrder['estimated_cost'] ?? 0 ?>) ? 'text-danger' : 'text-success'}`;

                // Update cost difference if both costs exist
                if (<?= $workOrder['estimated_cost'] ?>) {
                    const difference = parseFloat(data.actual_cost) - <?= $workOrder['estimated_cost'] ?>;
                    const percentage = (difference / <?= $workOrder['estimated_cost'] ?>) * 100;
                    
                    const costDifference = document.querySelector('.cost-difference');
                    if (!costDifference) {
                        const costAnalysis = document.querySelector('.cost-analysis');
                        const newDifference = document.createElement('div');
                        newDifference.className = 'cost-difference mt-4';
                        newDifference.innerHTML = `
                            <h6 class="text-muted mb-2">Cost Difference</h6>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-${difference > 0 ? 'danger' : 'success'} me-2">
                                    ${difference > 0 ? '+' : ''}${difference.toFixed(2)}
                                </span>
                                <span class="text-${difference > 0 ? 'danger' : 'success'}">
                                    (${difference > 0 ? '+' : ''}${percentage.toFixed(1)}%)
                                </span>
                            </div>
                        `;
                        costAnalysis.appendChild(newDifference);
                    } else {
                        const badge = costDifference.querySelector('.badge');
                        const percentageSpan = costDifference.querySelector('.text-success, .text-danger');
                        
                        badge.className = `badge bg-${difference > 0 ? 'danger' : 'success'} me-2`;
                        badge.textContent = `${difference > 0 ? '+' : ''}${difference.toFixed(2)}`;
                        
                        percentageSpan.className = `text-${difference > 0 ? 'danger' : 'success'}`;
                        percentageSpan.textContent = `(${difference > 0 ? '+' : ''}${percentage.toFixed(1)}%)`;
                    }
                }
                
                // Add to timeline
                const timeline = document.querySelector('.timeline');
                const newHistoryItem = document.createElement('div');
                newHistoryItem.className = 'timeline-item';
                newHistoryItem.innerHTML = `
                    <div class="timeline-date">
                        ${new Date().toLocaleString('en-US', { 
                            month: 'short', 
                            day: 'numeric', 
                            year: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric'
                        })}
                    </div>
                    <div class="timeline-content">
                        <h6>Cost Updated</h6>
                        <p class="mb-0">
                            <span class="badge bg-info">
                                $${parseFloat(data.actual_cost).toFixed(2)}
                            </span>
                            by <?= htmlspecialchars($this->user['first_name'] . ' ' . $this->user['last_name']) ?>
                        </p>
                    </div>
                `;
                timeline.insertBefore(newHistoryItem, timeline.firstChild);

                // Add to work order history
                updateWorkOrderHistory(['actual cost']);
            } else {
                alert('Failed to update cost');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the cost');
        });
    });

    // Handle attachment deletion
    document.querySelectorAll('.delete-attachment').forEach(button => {
        button.addEventListener('click', function() {
            const attachmentId = this.dataset.attachmentId;
            if (confirm('Are you sure you want to delete this attachment?')) {
                fetch('<?= $this->url("work-orders/{$workOrder['id']}/attachments/") ?>' + attachmentId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-Token': '<?= $this->csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('attachment-' + attachmentId).remove();
                    } else {
                        alert('Failed to delete attachment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the attachment');
                });
            }
        });
    });

    // Handle attachment form submission
    document.querySelector('.attachment-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new attachment to the list
                const attachmentsList = document.querySelector('.attachments-list');
                const newAttachment = document.createElement('div');
                newAttachment.className = 'attachment-item mb-3';
                newAttachment.id = 'attachment-' + data.attachment.id;
                newAttachment.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="attachment-icon me-3">
                                <i class='bx bx-file fs-4'></i>
                            </div>
                            <div>
                                <h6 class="mb-0">
                                    <a href="<?= $this->url("work-orders/{$workOrder['id']}/attachments/") ?>${data.attachment.id}/download" 
                                        class="text-decoration-none">
                                        ${data.attachment.filename}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    ${(data.attachment.file_size / 1024).toFixed(2)} KB
                                    • Uploaded by <?= htmlspecialchars($this->user['first_name'] . ' ' . $this->user['last_name']) ?>
                                    • ${data.attachment.created_at}
                                </small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-link text-danger delete-attachment" 
                            data-attachment-id="${data.attachment.id}">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                `;
                attachmentsList.insertBefore(newAttachment, attachmentsList.firstChild);
                form.reset();
            } else {
                alert('Failed to upload attachment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while uploading the attachment');
        });
    });

    // Handle dependency deletion
    document.querySelectorAll('.delete-dependency').forEach(button => {
        button.addEventListener('click', function() {
            const dependencyId = this.dataset.dependencyId;
            if (confirm('Are you sure you want to remove this dependency?')) {
                fetch('<?= $this->url("work-orders/{$workOrder['id']}/dependencies/") ?>' + dependencyId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-Token': '<?= $this->csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('dependency-' + dependencyId).remove();
                    } else {
                        alert('Failed to remove dependency');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while removing the dependency');
                });
            }
        });
    });

    // Handle dependency form submission
    document.querySelector('.dependency-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new dependency to the list
                const dependenciesList = document.querySelector('.dependencies-list');
                const newDependency = document.createElement('div');
                newDependency.className = 'dependency-item mb-3';
                newDependency.id = 'dependency-' + data.dependency.id;
                newDependency.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="dependency-icon me-3">
                                <i class='bx bx-link fs-4'></i>
                            </div>
                            <div>
                                <h6 class="mb-0">
                                    <a href="<?= $this->url("work-orders/") ?>${data.dependency.related_work_order_id}" 
                                        class="text-decoration-none">
                                        ${data.dependency.related_work_order_title}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    ${data.dependency.relationship_type.charAt(0).toUpperCase() + data.dependency.relationship_type.slice(1)} • 
                                    Status: 
                                    <span class="badge bg-${data.dependency.related_work_order_status === 'completed' ? 'success' : 
                                        (data.dependency.related_work_order_status === 'in_progress' ? 'primary' : 
                                        (data.dependency.related_work_order_status === 'cancelled' ? 'danger' : 'secondary'))}">
                                        ${data.dependency.related_work_order_status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                    </span>
                                </small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-link text-danger delete-dependency" 
                            data-dependency-id="${data.dependency.id}">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                `;
                dependenciesList.insertBefore(newDependency, dependenciesList.firstChild);
                
                // Close the modal and reset the form
                const modal = bootstrap.Modal.getInstance(document.getElementById('addDependencyModal'));
                modal.hide();
                form.reset();
            } else {
                alert('Failed to add dependency');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the dependency');
        });
    });

    // Handle tag deletion
    document.querySelectorAll('.delete-tag').forEach(button => {
        button.addEventListener('click', function() {
            const tagId = this.closest('.tag-item').dataset.tagId;
            if (confirm('Are you sure you want to remove this tag?')) {
                fetch('<?= $this->url("work-orders/{$workOrder['id']}/tags/") ?>' + tagId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-Token': '<?= $this->csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('tag-' + tagId).remove();
                    } else {
                        alert('Failed to remove tag');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while removing the tag');
                });
            }
        });
    });

    // Handle tag form submission
    document.querySelector('.tag-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const selectedOption = form.querySelector('#tag_id option:checked');

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new tag to the list
                const tagsList = document.querySelector('.tags-list');
                const newTag = document.createElement('div');
                newTag.className = 'tag-item mb-2 me-2';
                newTag.id = 'tag-' + data.tag.id;
                newTag.dataset.tagId = data.tag.id;
                newTag.innerHTML = `
                    <span class="badge bg-${data.tag.color}">
                        ${data.tag.name}
                        <button type="button" class="btn btn-link text-white p-0 ms-1 delete-tag" 
                            data-tag-id="${data.tag.id}">
                            <i class='bx bx-x'></i>
                        </button>
                    </span>
                `;
                tagsList.appendChild(newTag);
                
                // Close the modal and reset the form
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTagModal'));
                modal.hide();
                form.reset();
            } else {
                alert('Failed to add tag');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the tag');
        });
    });

    // Handle task form submission
    document.querySelector('.task-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new task to the list
                const tasksList = document.querySelector('.tasks-list');
                const newTask = document.createElement('div');
                newTask.className = 'task-item mb-3';
                newTask.id = 'task-' + data.task.id;
                newTask.innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="form-check me-3">
                            <input class="form-check-input task-checkbox" type="checkbox" 
                                id="task-checkbox-${data.task.id}">
                            <label class="form-check-label" for="task-checkbox-${data.task.id}"></label>
                        </div>
                        <div class="task-content flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="d-flex align-items-center mb-1">
                                        <h6 class="mb-0">${data.task.description}</h6>
                                        ${data.task.priority ? `
                                            <span class="badge bg-${data.task.priority === 'urgent' ? 'danger' : 
                                                (data.task.priority === 'high' ? 'warning' : 
                                                (data.task.priority === 'medium' ? 'info' : 'success'))} ms-2">
                                                ${data.task.priority.charAt(0).toUpperCase() + data.task.priority.slice(1)}
                                            </span>
                                        ` : ''}
                                    </div>
                                    <div class="d-flex align-items-center">
                                        ${data.task.assigned_to_first_name ? `
                                            <small class="text-muted me-3">
                                                <i class='bx bx-user'></i> ${data.task.assigned_to_first_name} ${data.task.assigned_to_last_name}
                                            </small>
                                        ` : ''}
                                        ${data.task.due_date ? `
                                            <small class="text-muted">
                                                <i class='bx bx-calendar'></i> Due: ${new Date(data.task.due_date).toLocaleDateString('en-US', {
                                                    month: 'short',
                                                    day: 'numeric',
                                                    year: 'numeric'
                                                })}
                                            </small>
                                        ` : ''}
                                    </div>
                                </div>
                                <button type="button" class="btn btn-link text-danger delete-task" 
                                    data-task-id="${data.task.id}">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                tasksList.insertBefore(newTask, tasksList.firstChild);
                
                // Close the modal and reset the form
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
                modal.hide();
                form.reset();
            } else {
                alert('Failed to add task');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the task');
        });
    });

    // Task filtering and sorting functionality
    const taskSearch = document.getElementById('taskSearch');
    const taskPriorityFilter = document.getElementById('taskPriorityFilter');
    const taskStatusFilter = document.getElementById('taskStatusFilter');
    const taskSort = document.getElementById('taskSort');
    const tasksList = document.querySelector('.tasks-list');
    const taskItems = document.querySelectorAll('.task-item');

    function filterAndSortTasks() {
        const searchTerm = taskSearch.value.toLowerCase();
        const priorityFilter = taskPriorityFilter.value;
        const statusFilter = taskStatusFilter.value;
        const sortBy = taskSort.value;

        // Convert task items to array for sorting
        const tasksArray = Array.from(taskItems);

        // Sort tasks
        tasksArray.sort((a, b) => {
            switch (sortBy) {
                case 'priority':
                    const priorityOrder = { urgent: 0, high: 1, medium: 2, low: 3 };
                    return priorityOrder[a.dataset.priority] - priorityOrder[b.dataset.priority];
                case 'due_date':
                    return new Date(a.dataset.dueDate) - new Date(b.dataset.dueDate);
                case 'assigned':
                    return a.dataset.assignedTo.localeCompare(b.dataset.assignedTo);
                case 'created':
                    return new Date(b.dataset.created) - new Date(a.dataset.created);
                default:
                    return 0;
            }
        });

        // Filter and display tasks
        tasksArray.forEach(task => {
            const description = task.querySelector('h6').textContent.toLowerCase();
            const priority = task.dataset.priority;
            const status = task.dataset.status;
            const assignedTo = task.dataset.assignedTo.toLowerCase();

            const matchesSearch = description.includes(searchTerm) || assignedTo.includes(searchTerm);
            const matchesPriority = !priorityFilter || priority === priorityFilter;
            const matchesStatus = !statusFilter || status === statusFilter;

            if (matchesSearch && matchesPriority && matchesStatus) {
                task.classList.remove('hidden');
                if (searchTerm) {
                    task.classList.add('highlight');
                } else {
                    task.classList.remove('highlight');
                }
            } else {
                task.classList.add('hidden');
            }
        });

        // Reorder tasks in the DOM
        tasksArray.forEach(task => {
            tasksList.appendChild(task);
        });
    }

    // Add event listeners for filters
    taskSearch.addEventListener('input', filterAndSortTasks);
    taskPriorityFilter.addEventListener('change', filterAndSortTasks);
    taskStatusFilter.addEventListener('change', filterAndSortTasks);
    taskSort.addEventListener('change', filterAndSortTasks);

    // Handle task notes functionality
    const taskNotesModal = document.getElementById('taskNotesModal');
    const taskNotesForm = document.querySelector('.task-notes-form');
    const taskNotesTextarea = document.getElementById('task_notes');
    const taskNotesId = document.getElementById('task_notes_id');

    // Add notes button to each task
    document.querySelectorAll('.task-item').forEach(task => {
        const taskId = task.id.split('-')[1];
        const taskContent = task.querySelector('.task-content');
        const taskActions = task.querySelector('.d-flex.justify-content-between');
        
        // Create notes button
        const notesButton = document.createElement('button');
        notesButton.type = 'button';
        notesButton.className = 'btn btn-link task-notes-button';
        notesButton.innerHTML = '<i class="bx bx-note"></i>';
        notesButton.dataset.taskId = taskId;
        
        // Add notes preview container
        const notesPreview = document.createElement('div');
        notesPreview.className = 'task-notes-preview empty';
        notesPreview.dataset.taskId = taskId;
        
        // Insert elements
        taskActions.insertBefore(notesButton, taskActions.lastElementChild);
        taskContent.appendChild(notesPreview);
        
        // Load existing notes
        loadTaskNotes(taskId);
    });

    // Handle notes button click
    document.addEventListener('click', function(e) {
        if (e.target.closest('.task-notes-button')) {
            const button = e.target.closest('.task-notes-button');
            const taskId = button.dataset.taskId;
            const notesPreview = document.querySelector(`.task-notes-preview[data-task-id="${taskId}"]`);
            
            // Set up the modal
            taskNotesId.value = taskId;
            taskNotesTextarea.value = notesPreview.textContent;
            
            // Show the modal
            const modal = new bootstrap.Modal(taskNotesModal);
            modal.show();
        }
    });

    // Handle notes form submission
    taskNotesForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the notes preview
                const notesPreview = document.querySelector(`.task-notes-preview[data-task-id="${data.task_id}"]`);
                const notesButton = document.querySelector(`.task-notes-button[data-task-id="${data.task_id}"]`);
                
                if (data.notes) {
                    notesPreview.textContent = data.notes;
                    notesPreview.classList.remove('empty');
                    notesButton.classList.add('has-notes');
                } else {
                    notesPreview.textContent = '';
                    notesPreview.classList.add('empty');
                    notesButton.classList.remove('has-notes');
                }
                
                // Close the modal
                const modal = bootstrap.Modal.getInstance(taskNotesModal);
                modal.hide();
            } else {
                alert('Failed to save notes');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the notes');
        });
    });

    // Function to load task notes
    function loadTaskNotes(taskId) {
        fetch(`<?= $this->url("work-orders/{$workOrder['id']}/tasks/") ?>${taskId}/notes`, {
            headers: {
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.notes) {
                const notesPreview = document.querySelector(`.task-notes-preview[data-task-id="${taskId}"]`);
                const notesButton = document.querySelector(`.task-notes-button[data-task-id="${taskId}"]`);
                
                notesPreview.textContent = data.notes;
                notesPreview.classList.remove('empty');
                notesButton.classList.add('has-notes');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Update task progress when tasks are completed or added
    function updateTaskProgress() {
        const tasks = document.querySelectorAll('.task-item');
        const completedTasks = document.querySelectorAll('.task-item .task-checkbox:checked');
        const totalTasks = tasks.length;
        const completedCount = completedTasks.length;
        const percentage = totalTasks > 0 ? Math.round((completedCount / totalTasks) * 100) : 0;

        // Update progress bar
        const progressBar = document.querySelector('.task-progress .progress-bar');
        progressBar.style.width = `${percentage}%`;
        progressBar.setAttribute('aria-valuenow', percentage);

        // Update percentage badge
        const percentageBadge = document.getElementById('taskProgressPercentage');
        percentageBadge.textContent = `${percentage}% Complete`;

        // Update task counts
        const taskCounts = document.querySelectorAll('.task-progress .small span');
        taskCounts[0].textContent = `${completedCount} of ${totalTasks} tasks completed`;
        taskCounts[1].textContent = `${totalTasks - completedCount} tasks remaining`;
    }

    // Add event listener for task checkbox changes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('task-checkbox')) {
            const taskId = e.target.id.split('-')[2];
            const completed = e.target.checked;

            fetch(`<?= $this->url("work-orders/{$workOrder['id']}/tasks/") ?>${taskId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?= $this->csrf_token() ?>'
                },
                body: JSON.stringify({ completed })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const taskItem = document.getElementById(`task-${taskId}`);
                    const taskTitle = taskItem.querySelector('h6');
                    
                    if (completed) {
                        taskTitle.classList.add('text-muted', 'text-decoration-line-through');
                    } else {
                        taskTitle.classList.remove('text-muted', 'text-decoration-line-through');
                    }
                    
                    updateTaskProgress();
                } else {
                    alert('Failed to update task status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the task status');
            });
        }
    });

    // Update progress when tasks are added or deleted
    document.addEventListener('taskAdded', updateTaskProgress);
    document.addEventListener('taskDeleted', updateTaskProgress);

    // Time tracking functionality
    const taskTimers = new Map();
    const taskTimeHistory = new Map();

    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const remainingSeconds = seconds % 60;
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    function updateTimerDisplay(taskId) {
        const timer = taskTimers.get(taskId);
        if (timer) {
            const timeDisplay = document.querySelector(`#task-${taskId} .task-total-time`);
            if (timeDisplay) {
                timeDisplay.textContent = formatTime(timer.totalSeconds);
            }
        }
    }

    function startTimer(taskId) {
        const timer = taskTimers.get(taskId) || {
            totalSeconds: 0,
            startTime: Date.now(),
            interval: null
        };

        if (!timer.interval) {
            timer.interval = setInterval(() => {
                timer.totalSeconds = Math.floor((Date.now() - timer.startTime) / 1000);
                updateTimerDisplay(taskId);
            }, 1000);
        }

        taskTimers.set(taskId, timer);
        updateTimerButton(taskId, true);
    }

    function stopTimer(taskId) {
        const timer = taskTimers.get(taskId);
        if (timer) {
            clearInterval(timer.interval);
            timer.interval = null;
            taskTimers.delete(taskId);
            updateTimerButton(taskId, false);
            saveTimeHistory(taskId, timer.totalSeconds);
        }
    }

    function updateTimerButton(taskId, isRunning) {
        const button = document.querySelector(`#task-${taskId} .task-timer-control`);
        if (button) {
            if (isRunning) {
                button.innerHTML = '<i class="bx bx-stop"></i> Stop';
                button.classList.add('active');
            } else {
                button.innerHTML = '<i class="bx bx-play"></i> Start';
                button.classList.remove('active');
            }
        }
    }

    function saveTimeHistory(taskId, seconds) {
        const history = taskTimeHistory.get(taskId) || [];
        history.push({
            duration: seconds,
            date: new Date().toISOString()
        });
        taskTimeHistory.set(taskId, history);
        updateTimeHistoryDisplay(taskId);
    }

    function updateTimeHistoryDisplay(taskId) {
        const historyContainer = document.querySelector(`#task-${taskId} .task-time-history`);
        if (!historyContainer) return;

        const history = taskTimeHistory.get(taskId) || [];
        if (history.length === 0) {
            historyContainer.innerHTML = '';
            return;
        }

        historyContainer.innerHTML = history.map(entry => `
            <div class="task-time-history-item">
                <span class="time-duration">${formatTime(entry.duration)}</span>
                <span class="time-date">${new Date(entry.date).toLocaleString()}</span>
            </div>
        `).join('');
    }

    // Handle timer control clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.task-timer-control')) {
            const button = e.target.closest('.task-timer-control');
            const taskId = button.dataset.taskId;
            const isRunning = button.classList.contains('active');

            if (isRunning) {
                stopTimer(taskId);
            } else {
                startTimer(taskId);
            }
        }
    });

    // Load existing time history for tasks
    document.querySelectorAll('.task-item').forEach(task => {
        const taskId = task.id.split('-')[1];
        loadTaskTimeHistory(taskId);
    });

    function loadTaskTimeHistory(taskId) {
        fetch(`<?= $this->url("work-orders/{$workOrder['id']}/tasks/") ?>${taskId}/time-history`, {
            headers: {
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.history) {
                taskTimeHistory.set(taskId, data.history);
                updateTimeHistoryDisplay(taskId);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Task Dependencies functionality
    const taskDependenciesModal = document.getElementById('taskDependenciesModal');
    const taskDependenciesForm = document.querySelector('.task-dependencies-form');
    const taskDependenciesId = document.getElementById('task_dependencies_id');
    const taskDependenciesList = document.querySelector('.task-dependencies-list');
    const addDependencyBtn = document.getElementById('addDependencyBtn');
    const dependentTaskSelect = document.getElementById('dependent_task_id');

    // Handle dependencies button click
    document.addEventListener('click', function(e) {
        if (e.target.closest('.task-dependencies-button')) {
            const button = e.target.closest('.task-dependencies-button');
            const taskId = button.dataset.taskId;
            
            // Set up the modal
            taskDependenciesId.value = taskId;
            
            // Load existing dependencies
            loadTaskDependencies(taskId);
            
            // Show the modal
            const modal = new bootstrap.Modal(taskDependenciesModal);
            modal.show();
        }
    });

    // Handle add dependency button click
    addDependencyBtn.addEventListener('click', function() {
        const taskId = taskDependenciesId.value;
        const dependentTaskId = dependentTaskSelect.value;
        
        if (!dependentTaskId) {
            alert('Please select a task');
            return;
        }
        
        const formData = new FormData();
        formData.append('task_id', taskId);
        formData.append('dependent_task_id', dependentTaskId);
        formData.append('<?= $this->csrf_token() ?>', '<?= $this->csrf_token() ?>');
        
        fetch(taskDependenciesForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new dependency to the list
                const newDependency = document.createElement('div');
                newDependency.className = 'task-dependency-item';
                newDependency.dataset.dependencyId = data.dependency.id;
                newDependency.innerHTML = `
                    <div class="task-description">${data.dependency.task_description}</div>
                    <button type="button" class="btn btn-link delete-dependency" 
                        data-dependency-id="${data.dependency.id}">
                        <i class='bx bx-trash'></i>
                    </button>
                `;
                taskDependenciesList.appendChild(newDependency);
                
                // Reset select
                dependentTaskSelect.value = '';
                
                // Update dependencies button state
                const button = document.querySelector(`.task-dependencies-button[data-task-id="${taskId}"]`);
                button.classList.add('has-dependencies');
            } else {
                alert('Failed to add dependency');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the dependency');
        });
    });

    // Handle dependency deletion
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-dependency')) {
            const button = e.target.closest('.delete-dependency');
            const dependencyId = button.dataset.dependencyId;
            const taskId = taskDependenciesId.value;
            
            if (confirm('Are you sure you want to remove this dependency?')) {
                fetch(`<?= $this->url("work-orders/{$workOrder['id']}/tasks/") ?>${taskId}/dependencies/${dependencyId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-Token': '<?= $this->csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.closest('.task-dependency-item').remove();
                        
                        // Update dependencies button state if no dependencies remain
                        if (taskDependenciesList.children.length === 0) {
                            const button = document.querySelector(`.task-dependencies-button[data-task-id="${taskId}"]`);
                            button.classList.remove('has-dependencies');
                        }
                    } else {
                        alert('Failed to remove dependency');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while removing the dependency');
                });
            }
        }
    });

    // Function to load task dependencies
    function loadTaskDependencies(taskId) {
        fetch(`<?= $this->url("work-orders/{$workOrder['id']}/tasks/") ?>${taskId}/dependencies`, {
            headers: {
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                taskDependenciesList.innerHTML = '';
                
                if (data.dependencies.length > 0) {
                    data.dependencies.forEach(dependency => {
                        const dependencyItem = document.createElement('div');
                        dependencyItem.className = 'task-dependency-item';
                        dependencyItem.dataset.dependencyId = dependency.id;
                        dependencyItem.innerHTML = `
                            <div class="task-description">${dependency.task_description}</div>
                            <button type="button" class="btn btn-link delete-dependency" 
                                data-dependency-id="${dependency.id}">
                                <i class='bx bx-trash'></i>
                            </button>
                        `;
                        taskDependenciesList.appendChild(dependencyItem);
                    });
                    
                    // Update dependencies button state
                    const button = document.querySelector(`.task-dependencies-button[data-task-id="${taskId}"]`);
                    button.classList.add('has-dependencies');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Update task dependencies button state when tasks are added
    document.addEventListener('taskAdded', function(e) {
        const taskId = e.detail.taskId;
        const button = document.querySelector(`.task-dependencies-button[data-task-id="${taskId}"]`);
        if (button) {
            button.classList.remove('has-dependencies');
        }
    });
});

// Add task deletion functionality
document.addEventListener('click', function(e) {
    if (e.target.closest('.task-delete-button')) {
        const button = e.target.closest('.task-delete-button');
        const taskId = button.dataset.taskId;
        const taskItem = document.getElementById(`task-${taskId}`);

        if (confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
            fetch(`<?= $this->url("work-orders/{$workOrder['id']}/tasks/") ?>${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-Token': '<?= $this->csrf_token() ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    taskItem.remove();
                    updateTaskProgress();
                    // Dispatch task deleted event
                    document.dispatchEvent(new Event('taskDeleted'));
                } else {
                    alert('Failed to delete task');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the task');
            });
        }
    }
});

// Add task status update functionality
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('task-status-select')) {
        const select = e.target;
        const taskId = select.dataset.taskId;
        const newStatus = select.value;

        fetch(`<?= $this->url("work-orders/{$workOrder['id']}/tasks/") ?>${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskItem = document.getElementById(`task-${taskId}`);
                const taskTitle = taskItem.querySelector('h6');
                
                // Update visual status
                if (newStatus === 'completed') {
                    taskTitle.classList.add('text-muted', 'text-decoration-line-through');
                } else {
                    taskTitle.classList.remove('text-muted', 'text-decoration-line-through');
                }
                
                updateTaskProgress();
            } else {
                alert('Failed to update task status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the task status');
        });
    }
});

// Add task priority update functionality
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('task-priority-select')) {
        const select = e.target;
        const taskId = select.dataset.taskId;
        const newPriority = select.value;

        fetch(`<?= $this->url("work-orders/{$workOrder['id']}/tasks/") ?>${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: JSON.stringify({ priority: newPriority })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskItem = document.getElementById(`task-${taskId}`);
                const priorityBadge = taskItem.querySelector('.priority-badge');
                
                // Update priority badge
                if (newPriority) {
                    const priorityColors = {
                        urgent: 'danger',
                        high: 'warning',
                        medium: 'info',
                        low: 'success'
                    };
                    
                    if (priorityBadge) {
                        priorityBadge.className = `badge bg-${priorityColors[newPriority]} priority-badge ms-2`;
                        priorityBadge.textContent = newPriority.charAt(0).toUpperCase() + newPriority.slice(1);
                    } else {
                        const newBadge = document.createElement('span');
                        newBadge.className = `badge bg-${priorityColors[newPriority]} priority-badge ms-2`;
                        newBadge.textContent = newPriority.charAt(0).toUpperCase() + newPriority.slice(1);
                        taskItem.querySelector('h6').appendChild(newBadge);
                    }
                } else if (priorityBadge) {
                    priorityBadge.remove();
                }
            } else {
                alert('Failed to update task priority');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the task priority');
        });
    }
});

// Add task due date update functionality
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('task-due-date-input')) {
        const input = e.target;
        const taskId = input.dataset.taskId;
        const newDueDate = input.value;

        fetch(`<?= $this->url("work-orders/{$workOrder['id']}/tasks/") ?>${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: JSON.stringify({ due_date: newDueDate })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskItem = document.getElementById(`task-${taskId}`);
                const dueDateSpan = taskItem.querySelector('.due-date-span');
                
                // Update due date display
                if (newDueDate) {
                    const formattedDate = new Date(newDueDate).toLocaleDateString();
                    if (dueDateSpan) {
                        dueDateSpan.innerHTML = `<i class='bx bx-calendar'></i> ${formattedDate}`;
                    } else {
                        const newSpan = document.createElement('span');
                        newSpan.className = 'due-date-span me-3';
                        newSpan.innerHTML = `<i class='bx bx-calendar'></i> ${formattedDate}`;
                        taskItem.querySelector('.task-meta').appendChild(newSpan);
                    }
                } else if (dueDateSpan) {
                    dueDateSpan.remove();
                }
            } else {
                alert('Failed to update task due date');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the task due date');
        });
    }
});

// Add task assignment update functionality
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('task-assignee-select')) {
        const select = e.target;
        const taskId = select.dataset.taskId;
        const newAssigneeId = select.value;

        fetch(`<?= $this->url("work-orders/{$workOrder['id']}/tasks/") ?>${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: JSON.stringify({ assigned_to: newAssigneeId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskItem = document.getElementById(`task-${taskId}`);
                const assigneeSpan = taskItem.querySelector('.assignee-span');
                
                // Update assignee display
                if (newAssigneeId) {
                    const selectedOption = select.options[select.selectedIndex];
                    const assigneeName = selectedOption.text;
                    
                    if (assigneeSpan) {
                        assigneeSpan.innerHTML = `<i class='bx bx-user'></i> ${assigneeName}`;
                    } else {
                        const newSpan = document.createElement('span');
                        newSpan.className = 'assignee-span me-3';
                        newSpan.innerHTML = `<i class='bx bx-user'></i> ${assigneeName}`;
                        taskItem.querySelector('.task-meta').appendChild(newSpan);
                    }
                } else if (assigneeSpan) {
                    assigneeSpan.remove();
                }
            } else {
                alert('Failed to update task assignment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the task assignment');
        });
    }
});

// Add task description update functionality
document.addEventListener('blur', function(e) {
    if (e.target.classList.contains('task-description-input')) {
        const input = e.target;
        const taskId = input.dataset.taskId;
        const newDescription = input.value;

        fetch(`<?= $this->url("work-orders/{$workOrder['id']}/tasks/") ?>${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $this->csrf_token() ?>'
            },
            body: JSON.stringify({ description: newDescription })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskItem = document.getElementById(`task-${taskId}`);
                const taskTitle = taskItem.querySelector('h6');
                taskTitle.textContent = newDescription;
            } else {
                alert('Failed to update task description');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the task description');
        });
    }
});

// Add task drag and drop functionality for reordering
let draggedTask = null;

document.addEventListener('dragstart', function(e) {
    if (e.target.classList.contains('task-item')) {
        draggedTask = e.target;
        e.target.classList.add('dragging');
    }
});

document.addEventListener('dragend', function(e) {
    if (e.target.classList.contains('task-item')) {
        e.target.classList.remove('dragging');
        draggedTask = null;
    }
});

document.addEventListener('dragover', function(e) {
    e.preventDefault();
    if (draggedTask) {
        const taskItems = document.querySelectorAll('.task-item:not(.dragging)');
        const afterElement = getDragAfterElement(taskItems, e.clientY);
        const tasksList = document.querySelector('.tasks-list');
        
        if (afterElement) {
            tasksList.insertBefore(draggedTask, afterElement);
        } else {
            tasksList.appendChild(draggedTask);
        }
    }
});

function getDragAfterElement(taskItems, y) {
    const closest = taskItems.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
    
    return closest;
}

// Add task search highlighting
function highlightSearchTerm(text, searchTerm) {
    if (!searchTerm) return text;
    const regex = new RegExp(`(${searchTerm})`, 'gi');
    return text.replace(regex, '<mark>$1</mark>');
}

// Update task search functionality
taskSearch.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const taskItems = document.querySelectorAll('.task-item');
    
    taskItems.forEach(task => {
        const description = task.querySelector('h6').textContent;
        const assignee = task.dataset.assignedTo;
        
        if (description.toLowerCase().includes(searchTerm) || 
            assignee.toLowerCase().includes(searchTerm)) {
            task.classList.remove('d-none');
            if (searchTerm) {
                const descriptionElement = task.querySelector('h6');
                descriptionElement.innerHTML = highlightSearchTerm(description, searchTerm);
            }
        } else {
            task.classList.add('d-none');
        }
    });
});

// Add task sorting functionality
taskSort.addEventListener('change', function() {
    const sortBy = this.value;
    const taskItems = Array.from(document.querySelectorAll('.task-item:not(.d-none)'));
    
    taskItems.sort((a, b) => {
        switch (sortBy) {
            case 'priority':
                const priorityOrder = { urgent: 0, high: 1, medium: 2, low: 3 };
                return priorityOrder[a.dataset.priority] - priorityOrder[b.dataset.priority];
            case 'due_date':
                return new Date(a.dataset.dueDate) - new Date(b.dataset.dueDate);
            case 'assigned':
                return a.dataset.assignedTo.localeCompare(b.dataset.assignedTo);
            case 'created':
                return new Date(b.dataset.created) - new Date(a.dataset.created);
            default:
                return 0;
        }
    });
    
    const tasksList = document.querySelector('.tasks-list');
    taskItems.forEach(task => tasksList.appendChild(task));
});

// Add task progress update animation
function updateTaskProgress() {
    const totalTasks = document.querySelectorAll('.task-item').length;
    const completedTasks = document.querySelectorAll('.task-item .task-checkbox:checked').length;
    const percentage = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;
    
    const progressBar = document.querySelector('.progress-bar');
    const progressPercentage = document.getElementById('taskProgressPercentage');
    
    // Animate progress bar
    progressBar.style.transition = 'width 0.5s ease-in-out';
    progressBar.style.width = `${percentage}%`;
    progressBar.setAttribute('aria-valuenow', percentage);
    
    // Update percentage text with animation
    progressPercentage.style.transition = 'all 0.3s ease-in-out';
    progressPercentage.textContent = `${percentage}% Complete`;
    
    // Update task count with animation
    const taskCount = document.querySelector('.task-count');
    if (taskCount) {
        taskCount.style.transition = 'all 0.3s ease-in-out';
        taskCount.textContent = `${completedTasks} of ${totalTasks} tasks completed`;
    }
}

// Add task completion animation
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('task-checkbox')) {
        const taskItem = e.target.closest('.task-item');
        const taskTitle = taskItem.querySelector('h6');
        
        if (e.target.checked) {
            taskItem.style.transition = 'all 0.3s ease-in-out';
            taskItem.style.opacity = '0.7';
            taskTitle.classList.add('text-muted', 'text-decoration-line-through');
            
            setTimeout(() => {
                taskItem.style.opacity = '1';
            }, 300);
        } else {
            taskItem.style.transition = 'all 0.3s ease-in-out';
            taskItem.style.opacity = '0.7';
            taskTitle.classList.remove('text-muted', 'text-decoration-line-through');
            
            setTimeout(() => {
                taskItem.style.opacity = '1';
            }, 300);
        }
    }
});

// Add task hover effects
document.querySelectorAll('.task-item').forEach(task => {
    task.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
    });
    
    task.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = 'none';
    });
});

// Add task keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + F to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        taskSearch.focus();
    }
    
    // Ctrl/Cmd + N to add new task
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        const addTaskButton = document.querySelector('[data-bs-target="#addTaskModal"]');
        if (addTaskButton) {
            addTaskButton.click();
        }
    }
});

// Add task tooltips
document.querySelectorAll('.task-actions .btn').forEach(button => {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = button.getAttribute('data-tooltip');
    button.appendChild(tooltip);
    
    button.addEventListener('mouseenter', function() {
        tooltip.style.display = 'block';
    });
    
    button.addEventListener('mouseleave', function() {
        tooltip.style.display = 'none';
    });
});

// Add task loading states
function setTaskLoading(taskId, isLoading) {
    const taskItem = document.getElementById(`task-${taskId}`);
    if (taskItem) {
        if (isLoading) {
            taskItem.classList.add('loading');
            taskItem.style.opacity = '0.7';
            taskItem.style.pointerEvents = 'none';
        } else {
            taskItem.classList.remove('loading');
            taskItem.style.opacity = '1';
            taskItem.style.pointerEvents = 'auto';
        }
    }
}

// Add task error handling
function handleTaskError(error, taskId) {
    console.error('Error:', error);
    const taskItem = document.getElementById(`task-${taskId}`);
    if (taskItem) {
        const errorMessage = document.createElement('div');
        errorMessage.className = 'alert alert-danger mt-2';
        errorMessage.textContent = 'An error occurred. Please try again.';
        taskItem.appendChild(errorMessage);
        
        setTimeout(() => {
            errorMessage.remove();
        }, 5000);
    }
}

// Add task success notifications
function showTaskSuccess(message) {
    const notification = document.createElement('div');
    notification.className = 'alert alert-success position-fixed top-0 end-0 m-3';
    notification.style.zIndex = '9999';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Add task validation
function validateTaskForm(form) {
    const description = form.querySelector('[name="description"]').value.trim();
    const dueDate = form.querySelector('[name="due_date"]').value;
    
    if (!description) {
        alert('Please enter a task description');
        return false;
    }
    
    if (dueDate) {
        const selectedDate = new Date(dueDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            alert('Due date cannot be in the past');
            return false;
        }
    }
    
    return true;
}

// Add task form submission validation
document.querySelector('.task-form')?.addEventListener('submit', function(e) {
    if (!validateTaskForm(this)) {
        e.preventDefault();
    }
});

// Add task modal close handling
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('hidden.bs.modal', function() {
        const form = this.querySelector('form');
        if (form) {
            form.reset();
        }
    });
});

// Add task keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Tab' && e.shiftKey) {
        const taskItems = document.querySelectorAll('.task-item:not(.d-none)');
        const currentIndex = Array.from(taskItems).indexOf(document.activeElement.closest('.task-item'));
        
        if (currentIndex > 0) {
            e.preventDefault();
            taskItems[currentIndex - 1].focus();
        }
    }
});

// Add task focus styles
document.querySelectorAll('.task-item').forEach(task => {
    task.setAttribute('tabindex', '0');
    
    task.addEventListener('focus', function() {
        this.style.outline = '2px solid #0d6efd';
        this.style.outlineOffset = '2px';
    });
    
    task.addEventListener('blur', function() {
        this.style.outline = 'none';
        this.style.outlineOffset = '0';
    });
});

// Add task accessibility attributes
document.querySelectorAll('.task-item').forEach(task => {
    task.setAttribute('role', 'listitem');
    task.setAttribute('aria-label', task.querySelector('h6').textContent);
    
    const checkbox = task.querySelector('.task-checkbox');
    if (checkbox) {
        checkbox.setAttribute('aria-label', `Mark ${task.querySelector('h6').textContent} as complete`);
    }
});

// Add task responsive behavior
function updateTaskResponsive() {
    const taskItems = document.querySelectorAll('.task-item');
    const isMobile = window.innerWidth < 768;
    
    taskItems.forEach(task => {
        const taskActions = task.querySelector('.task-actions');
        const taskMeta = task.querySelector('.task-meta');
        
        if (isMobile) {
            taskActions.classList.add('mt-2');
            taskMeta.classList.add('mt-2');
        } else {
            taskActions.classList.remove('mt-2');
            taskMeta.classList.remove('mt-2');
        }
    });
}

window.addEventListener('resize', updateTaskResponsive);
updateTaskResponsive();

// Add task performance optimization
const taskObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            taskObserver.unobserve(entry.target);
        }
    });
}, {
    threshold: 0.1
});

document.querySelectorAll('.task-item').forEach(task => {
    task.classList.add('fade-in');
    taskObserver.observe(task);
});

// Add task state persistence
function saveTaskState() {
    const taskStates = {};
    document.querySelectorAll('.task-item').forEach(task => {
        const taskId = task.id.split('-')[1];
        taskStates[taskId] = {
            completed: task.querySelector('.task-checkbox').checked,
            priority: task.dataset.priority,
            status: task.dataset.status
        };
    });
    
    localStorage.setItem('taskStates', JSON.stringify(taskStates));
}

function loadTaskState() {
    const taskStates = JSON.parse(localStorage.getItem('taskStates') || '{}');
    
    Object.entries(taskStates).forEach(([taskId, state]) => {
        const task = document.getElementById(`task-${taskId}`);
        if (task) {
            const checkbox = task.querySelector('.task-checkbox');
            if (checkbox) {
                checkbox.checked = state.completed;
            }
            
            task.dataset.priority = state.priority;
            task.dataset.status = state.state;
        }
    });
    
    updateTaskProgress();
}

// Save task state on changes
document.addEventListener('change', function(e) {
    if (e.target.closest('.task-item')) {
        saveTaskState();
    }
});

// Load task state on page load
document.addEventListener('DOMContentLoaded', loadTaskState);

// Add task export functionality
function exportTasks() {
    const tasks = Array.from(document.querySelectorAll('.task-item')).map(task => ({
        description: task.querySelector('h6').textContent,
        priority: task.dataset.priority,
        status: task.dataset.status,
        assignedTo: task.dataset.assignedTo,
        dueDate: task.dataset.dueDate,
        completed: task.querySelector('.task-checkbox').checked
    }));
    
    const csv = [
        ['Description', 'Priority', 'Status', 'Assigned To', 'Due Date', 'Completed'],
        ...tasks.map(task => [
            task.description,
            task.priority,
            task.status,
            task.assignedTo,
            task.dueDate,
            task.completed ? 'Yes' : 'No'
        ])
    ].map(row => row.join(',')).join('\n');
    
</script> 