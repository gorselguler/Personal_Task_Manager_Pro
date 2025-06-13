<?php require_once ROOT_PATH . '/views/layout/header.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Your Tasks</h2>
        <div class="btn-group" role="group">
            <a href="?controller=task&action=add" class="btn btn-success">Add New Task</a>
            <a href="?controller=task&action=search" class="btn btn-info">Search Tasks</a>
            <a href="?controller=task&action=report" class="btn btn-warning">View Reports</a>
            <a href="?controller=category&action=list" class="btn btn-secondary">Manage Categories</a>
            <a href="?action=logout" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="sort-links text-end mb-3">
        Sort by:
        <a href="?controller=task&action=list&sort_by=priority&sort_order=DESC" class="btn btn-outline-dark btn-sm me-1">Priority (High to Low)</a>
        <a href="?controller=task&action=list&sort_by=due_date&sort_order=ASC" class="btn btn-outline-dark btn-sm me-1">Due Date (Soonest)</a>
        <a href="?controller=task&action=list&sort_by=status&sort_order=ASC" class="btn btn-outline-dark btn-sm me-1">Status (To Do first)</a>
    </div>

    <?php if (!empty($tasks)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr class="<?php echo ($task['status'] === 'done') ? 'status-done' : ''; ?>">
                            <td><?php echo htmlspecialchars($task['title']); ?></td>
                            <td><?php echo htmlspecialchars($task['description'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($task['category_name'] ?? 'N/A'); ?></td>
                            <td class="priority-<?php echo htmlspecialchars($task['priority']); ?>">
                                <?php echo htmlspecialchars(ucfirst($task['priority'])); ?>
                            </td>
                            <td>
                                <?php
                                    if ($task['due_date'] === null) {
                                        echo 'N/A';
                                    } elseif (date('H:i:s', strtotime($task['due_date'])) === '00:00:00') {
                                        echo htmlspecialchars(date('Y-m-d', strtotime($task['due_date']))) . ' (All Day)';
                                    } else {
                                        echo htmlspecialchars(date('Y-m-d H:i', strtotime($task['due_date'])));
                                    }
                                ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $task['status']))); ?>
                                <div class="btn-group btn-group-sm ms-2" role="group">
                                    <?php if ($task['status'] !== 'done'): ?>
                                        <a href="?controller=task&action=setStatus&id=<?php echo $task['id']; ?>&status=done" class="status-btn done btn">Done</a>
                                    <?php endif; ?>
                                    <?php if ($task['status'] !== 'in_progress'): ?>
                                        <a href="?controller=task&action=setStatus&id=<?php echo $task['id']; ?>&status=in_progress" class="status-btn in_progress btn">In Progress</a>
                                    <?php endif; ?>
                                    <?php if ($task['status'] !== 'todo'): ?>
                                        <a href="?controller=task&action=setStatus&id=<?php echo $task['id']; ?>&status=todo" class="status-btn todo btn">To Do</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-nowrap">
                                <a href="?controller=task&action=edit&id=<?php echo htmlspecialchars($task['id']); ?>" class="btn btn-sm btn-info btn-action">Edit</a>
                                <a href="?controller=task&action=delete&id=<?php echo htmlspecialchars($task['id']); ?>" onclick="return confirm('Are you sure you want to delete this task?');" class="btn btn-sm btn-danger btn-action">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center" role="alert">
            No tasks found. Add one!
        </div>
    <?php endif; ?>

<?php require_once ROOT_PATH . '/views/layout/footer.php'; ?>