Personal Task Manager Pro
Project Overview
This is a robust Personal Task Manager Pro web application developed using pure PHP, MySQL with PDO for secure database access, and a clear Model-View-Controller (MVC) architectural pattern. It's designed to help users effectively manage their daily tasks, offering a comprehensive set of features from user authentication to advanced reporting and task management functionalities.

The project demonstrates complex database interactions, advanced routing, detailed validation, and a strong focus on security and maintainability. Its user interface is enhanced with Bootstrap 5 for a modern and responsive design.

Features
This application provides the following key functionalities:

User Authentication:

Secure user Registration with password hashing (password_hash()).
Login and Logout functionality using PHP sessions ($_SESSION).
Robust input validation for username (3-50 chars), email (valid format), and password (min 6 chars).
![Login](Login.png)

Task Management (CRUD):

Create: Add new tasks with title, description, category, priority (low/medium/high), and due date. Due dates can be specific or marked as "All Day."
Read (List): View all tasks with full details, including categorization and status indicators.
Update (Edit): Modify existing tasks including title, description, category, priority, due date, and status.
Status Management: Quickly change task status to "To Do," "In Progress," or "Done" directly from the list. completed_at timestamp is recorded upon completion.
Delete: Remove tasks securely, requiring user ownership confirmation.

![Task_List](Add_Task.png)

![Task_List](Main_Screen.png)

Category Management:

Add and list custom categories for task organization. Category names are unique per user.

Task Search & Filtering:

Filter tasks by Category, Priority, and Status. (Initial search by term functionality is simplified to focus on filter options).
Task Reporting:

![Categories](Categories.png)

Generate comprehensive reports:
Tasks by Status: Counts of tasks in "To Do," "In Progress," and "Done" states.
Overdue Tasks: Count of tasks past their due date and not yet completed.
Category Summary: Breakdown of tasks per category, showing total and completed tasks.

Task Sorting:

Sort tasks on the main list by Priority, Due Date, or Status.

Security & Validation:

All database queries utilize PDO prepared statements to prevent SQL injection.
All user inputs and outputs are properly escaped using htmlspecialchars() to prevent XSS attacks.
Strict server-side validation is implemented across all forms to ensure data integrity and user-friendly error messages.
MVC Architecture:

Clear separation of concerns into models (database logic), views (HTML templates), and controllers (business logic and routing).
Responsive UI:

Integrated Bootstrap 5 for a modern, clean, and responsive user interface across various devices.
Technologies Used
Backend: PHP (Pure PHP, PDO)
Database: MySQL
Frontend: HTML, CSS, JavaScript, Bootstrap 5
Development Tools: XAMPP (Apache, MySQL), VS Code, Git, GitHub


