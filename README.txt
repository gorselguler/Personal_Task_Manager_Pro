Personal Task Manager Pro - README

Project Overview:
This is a robust task management system built with pure PHP, following the MVC architectural pattern. It allows users to register, log in, manage tasks with categories, priorities, and deadlines, search tasks, generate reports, and sort/delete tasks.

Setup Instructions:

1.  Install XAMPP (or WAMP/MAMP):
    Ensure you have Apache and MySQL installed and running. XAMPP is recommended for ease of use.
    Download from: https://www.apachefriends.org/index.html

2.  Place Project Files:
    Extract the 'Task_Manager' project folder into your XAMPP's 'htdocs' directory.
    Example path: C:\xampp\htdocs\Task_Manager\

3.  Configure Apache (if necessary):
    Ensure Apache's mod_rewrite is enabled.
    Open C:\xampp\apache\conf\httpd.conf (or equivalent).
    Find and uncomment (remove '#') the line:
        LoadModule rewrite_module modules/mod_rewrite.so
    Find the <Directory "C:/xampp/htdocs"> block and change 'AllowOverride None' to 'AllowOverride All'.
    Restart Apache from the XAMPP Control Panel.

4.  Configure Database Connection:
    Open 'config.php' in the project root directory.
    Verify/update the database connection constants if necessary:
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'task_manager_pro');
        define('DB_USER', 'root');
        define('DB_PASS', ''); // Usually empty for XAMPP root user

5.  Create Database and Tables:
    Start your MySQL server from the XAMPP Control Panel.
    Open your web browser and go to: http://localhost/phpmyadmin/
    Click on 'Databases' and create a new database named 'task_manager_pro'.
    Select the 'task_manager_pro' database from the left sidebar.
    Go to the 'SQL' tab and paste the following SQL commands to create tables and test data:

    ```sql
    SET FOREIGN_KEY_CHECKS = 0;
    DROP TABLE IF EXISTS tasks;
    DROP TABLE IF EXISTS categories;
    DROP TABLE IF EXISTS users;

    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        created_at DATETIME DEFAULT NOW()
    );

    CREATE TABLE categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        name VARCHAR(50) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE (user_id, name)
    );

    CREATE TABLE tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        category_id INT NULL,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
        due_date DATETIME NULL,
        status ENUM('todo', 'in_progress', 'done') DEFAULT 'todo',
        created_at DATETIME DEFAULT NOW(),
        completed_at DATETIME NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
    );

    SET FOREIGN_KEY_CHECKS = 1;

    -- You can add test data here if you wish, e.g.:
    -- INSERT INTO users (username, password, email) VALUES
    -- ('testuser', '$2y$10$XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'test@example.com');
    -- (Replace XXXXX... with a real hash for 'password123' if you want)
    ```

Usage Guide:

1.  Access the Application:
    Open your web browser and navigate to:
    http://localhost/Task_Manager/public/

2.  Register:
    Click on "Register here" to create a new user account.
    Ensure username is 3-50 chars, email is valid, and password is min 6 chars.

3.  Login:
    Use your registered username and password to log in.

4.  Manage Categories:
    From the task list, click "Manage Categories" to add new categories.
    Category names must be 3-50 chars and unique per user.

5.  Manage Tasks (CRUD - Create, Read, Update, Delete):
    -   Add: Click "Add New Task". Fill in details. Due date can be all day (date only, time 00:00:00) or specific date/time.
    -   Read (List): All your tasks are displayed on the main page.
    -   Edit: Click "Edit" next to a task to update its details or status.
    -   Change Status: Click "Done", "In Progress", or "To Do" buttons next to a task to quickly change its status.
    -   Delete: Click "Delete" next to a task to remove it (confirmation required).

6.  Search Tasks:
    Click "Search Tasks". Filter tasks by category, priority, or status.

7.  View Reports:
    Click "View Reports". See summaries like tasks by status, overdue tasks, and category breakdown.

8.  Sort Tasks:
    On the task list page, use the "Sort by" links (Priority, Due Date, Status) to reorder your tasks.

9.  Logout:
    Click "Logout" to end your session.

Features:
- User Authentication (Registration, Login, Logout)
- Task Management (Add, View, Edit, Update Status, Delete)
- Category Management (Add, View)
- Task Search (by Category, Priority, Status)
- Task Reporting (Counts by Status, Overdue Tasks, Category Summary)
- Task Sorting (by Priority, Due Date, Status)
- Secure Password Hashing (password_hash())
- PDO Prepared Statements for Database Interactions
- Output Escaping (htmlspecialchars()) for XSS Prevention
- Robust Form Validation
- MVC Architectural Pattern
