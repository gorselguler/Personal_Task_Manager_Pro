<?php require_once ROOT_PATH . '/views/layout/header.php'; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">Add New Task</h2>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="?controller=task&action=add" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title:</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required minlength="3" maxlength="100">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" maxlength="1000"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category:</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">-- Select Category (Optional) --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['id']); ?>"
                                <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="priority" class="form-label">Priority:</label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="low" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'low') ? 'selected' : ''; ?>>Low</option>
                        <option value="medium" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="high" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'high') ? 'selected' : ''; ?>>High</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Due Date:</label>
                    <div class="row">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <input type="date" class="form-control" id="due_date_date" name="due_date_date" value="<?php echo htmlspecialchars($_POST['due_date_date'] ?? date('Y-m-d')); ?>">
                        </div>
                        <div class="col-md-6">
                            <input type="time" class="form-control" id="due_date_time" name="due_date_time" value="<?php echo htmlspecialchars($_POST['due_date_time'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-check mt-2 all-day-checkbox">
                        <input class="form-check-input" type="checkbox" id="is_all_day" name="is_all_day" <?php echo isset($_POST['is_all_day']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_all_day">All Day</label>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success">Add Task</button>
                    <a href="?controller=task&action=list" class="btn btn-secondary">Back to Tasks</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const dueDateInput = document.getElementById('due_date_date');
        const dueTimeInput = document.getElementById('due_date_time');
        const isAllDayCheckbox = document.getElementById('is_all_day');

        function toggleDateTimeInputs() {
            if (isAllDayCheckbox.checked) {
                dueTimeInput.disabled = true;
                dueTimeInput.value = '';
            } else {
                dueTimeInput.disabled = false;
            }
        }

        isAllDayCheckbox.addEventListener('change', toggleDateTimeInputs);
        toggleDateTimeInputs();
    </script>

<?php require_once ROOT_PATH . '/views/layout/footer.php'; ?>