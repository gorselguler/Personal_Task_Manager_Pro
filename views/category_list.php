<?php require_once ROOT_PATH . '/views/layout/header.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="?controller=task&action=list" class="btn btn-secondary btn-sm">Back to Tasks</a>
        <h2 class="mb-0">Your Categories</h2>
        <a href="?action=logout" class="btn btn-warning btn-sm">Logout</a>
    </div>

    <?php if (!empty($categories)): ?>
        <ul class="list-group">
            <?php foreach ($categories as $category): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo htmlspecialchars($category['name']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info text-center" role="alert">
            No categories found. Add one!
        </div>
    <?php endif; ?>
    <div class="text-center mt-3">
        <a href="?controller=category&action=add" class="btn btn-primary">Add New Category</a>
    </div>

<?php require_once ROOT_PATH . '/views/layout/footer.php'; ?>