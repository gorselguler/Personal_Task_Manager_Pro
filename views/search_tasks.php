<?php require_once ROOT_PATH . '/views/layout/header.php'; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">Search Tasks</h2>
            <form action="?controller=task&action=search" method="GET">
                <input type="hidden" name="controller" value="task">
                <input type="hidden" name="action" value="search">
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category:</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">-- All Categories --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['id']); ?>"
                                <?php echo (isset($_GET['category_id']) && $_GET['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="priority" class="form-label">Priority:</label>
                    <select class="form-select" id="priority" name="priority">
                        <option value="">-- All Priorities --</option>
                        <option value="low" <?php echo (isset($_GET['priority']) && $_GET['priority'] == 'low') ? 'selected' : ''; ?>>Low</option>
                        <option value="medium" <?php echo (isset($_GET['priority']) && $_GET['priority'] == 'medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="high" <?php echo (isset($_GET['priority']) && $_GET['priority'] == 'high') ? 'selected' : ''; ?>>High</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">-- All Statuses --</option>
                        <option value="todo" <?php echo (isset($_GET['status']) && $_GET['status'] == 'todo') ? 'selected' : ''; ?>>To Do</option>
                        <option value="in_progress" <?php echo (isset($_GET['status']) && $_GET['status'] == 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                        <option value="done" <?php echo (isset($_GET['status']) && $_GET['status'] == 'done') ? 'selected' : ''; ?>>Done</option>
                    </select>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="?controller=task&action=list" class="btn btn-secondary">Back to All Tasks</a>
                </div>
            </form>

            <?php if (!empty($tasks)): ?>
                <h3 class="mt-4">Search Results</h3>
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
                                    <td><?php htmlspecialchars($task['category_name'] ?? 'N/A'); ?></td>
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
                                    <td><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $task['status']))); ?></td>
                                    <td class="text-nowrap">
                                        <a href="?controller=task&action=edit&id=<?php echo htmlspecialchars($task['id']); ?>" class="btn btn-sm btn-info btn-action">Edit</a>
                                        <a href="?controller=task&action=delete&id=<?php echo htmlspecialchars($task['id']); ?>" onclick="return confirm('Are you sure you want to delete this task?');" class="btn btn-sm btn-danger btn-action">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['category_id']) || isset($_GET['priority']) || isset($_GET['status']))): ?>
                <div class="alert alert-info text-center mt-4" role="alert">
                    No tasks found matching your search criteria.
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php require_once ROOT_PATH . '/views/layout/footer.php'; ?>