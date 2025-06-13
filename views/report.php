<?php require_once ROOT_PATH . '/views/layout/header.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="?controller=task&action=list" class="btn btn-secondary btn-sm">Back to Tasks</a>
        <h2 class="mb-0">Task Reports</h2>
        <a href="?action=logout" class="btn btn-warning btn-sm">Logout</a>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h3>Tasks by Status</h3>
        </div>
        <ul class="list-group list-group-flush">
            <?php
                $allStatuses = ['todo', 'in_progress', 'done'];
                foreach ($allStatuses as $status) {
                    $count = $taskCountsByStatus[$status] ?? 0;
                    echo '<li class="list-group-item"><strong>' . htmlspecialchars(ucfirst(str_replace('_', ' ', $status))) . ':</strong> ' . $count . '</li>';
                }
            ?>
        </ul>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h3>Overdue Tasks</h3>
        </div>
        <div class="card-body">
            <p class="mb-0">You have <strong><?php echo htmlspecialchars($overdueTaskCount); ?></strong> overdue task(s).</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h3>Category Summary</h3>
        </div>
        <?php if (!empty($categorySummary)): ?>
            <ul class="list-group list-group-flush">
                <?php foreach ($categorySummary as $summary): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($summary['category_name']); ?>:</strong>
                        <?php echo htmlspecialchars($summary['total_tasks']); ?> total,
                        <?php echo htmlspecialchars($summary['done_tasks']); ?> done
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="card-body">
                <p class="mb-0">No categories with tasks to report.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="text-center mt-3">
        <a href="?controller=task&action=list" class="btn btn-secondary">Back to Task List</a>
    </div>

<?php require_once ROOT_PATH . '/views/layout/footer.php'; ?>