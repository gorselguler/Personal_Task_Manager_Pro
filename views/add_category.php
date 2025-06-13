<?php require_once ROOT_PATH . '/views/layout/header.php'; ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Add New Category</h2>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="?controller=category&action=add" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required minlength="3" maxlength="50">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Add Category</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="?controller=category&action=list" class="btn btn-secondary btn-sm">Back to Categories</a>
            </div>
        </div>
    </div>

<?php require_once ROOT_PATH . '/views/layout/footer.php'; ?>