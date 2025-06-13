<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Task Manager Pro'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body { padding-top: 56px; background-color: #f8f9fa; }
        .navbar { margin-bottom: 20px; }
        .container { padding: 20px; border: 1px solid #dee2e6; border-radius: 0.25rem; background-color: #fff; margin-top: 20px; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        h2 { text-align: center; margin-bottom: 1.5rem; color: #343a40; }
        .form-label { font-weight: bold; }
        .errors { color: var(--bs-danger); margin-bottom: 1rem; }
        .alert { margin-bottom: 1rem; }
        .table { margin-top: 1rem; }
        .btn-action { margin-right: 5px; }

        .priority-low { color: var(--bs-success); font-weight: bold; }
        .priority-medium { color: var(--bs-warning); font-weight: bold; }
        .priority-high { color: var(--bs-danger); font-weight: bold; }
        
        .status-done { text-decoration: line-through; color: var(--bs-secondary); }

        .status-btn.done { background-color: var(--bs-success); border-color: var(--bs-success); color: white; }
        .status-btn.done:hover { background-color: #198754; border-color: #198754; }
        .status-btn.in_progress { background-color: var(--bs-warning); border-color: var(--bs-warning); color: #212529; }
        .status-btn.in_progress:hover { background-color: #ffcd39; border-color: #ffcd39; }
        .status-btn.todo { background-color: var(--bs-danger); border-color: var(--bs-danger); color: white; }
        .status-btn.todo:hover { background-color: #bb2d3b; border-color: #bb2d3b; }

        .all-day-checkbox { display: flex; align-items: center; margin-top: 5px;}
        .all-day-checkbox input[type="checkbox"] { width: auto; margin-right: 8px; }
        .sort-links a { margin-right: 10px; text-decoration: none; }
        .sort-links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="?controller=task&action=list">Task Manager Pro</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="?controller=task&action=list">Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?controller=task&action=add">Add Task</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?controller=category&action=list">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?controller=task&action=search">Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?controller=task&action=report">Reports</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="?action=logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">