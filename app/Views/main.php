<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Do List' ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-card {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: none;
        }

        .card-header {
            background: #1a1a2e;
            color: white;
            padding: 25px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }

        .badge-done {
            background-color: #28a745;
            padding: 6px 15px;
            border-radius: 20px;
            white-space: nowrap;
            display: inline-block;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #333;
            padding: 6px 15px;
            border-radius: 20px;
            white-space: nowrap;
            display: inline-block;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: bold;
            color: #1a1a2e;
            margin: 10px 0;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 15px;
            padding: 35px;
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        .action-buttons .btn {
            margin: 0 3px;
            padding: 5px 12px;
        }

        .task-queue {
            font-weight: bold;
            color: #1a1a2e;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .table {
                font-size: 12px;
            }

            .action-buttons .btn {
                padding: 3px 8px;
                font-size: 11px;
            }

            .badge-done,
            .badge-pending {
                padding: 3px 10px;
                font-size: 11px;
            }
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <i class="fas fa-tasks fa-3x mb-3"></i>
            <h1 class="display-4 fw-bold">Do List</h1>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <i class="fas fa-clipboard-list fa-2x text-primary"></i>
                    <div class="stat-number"><?= $totalTasks ?? 0 ?></div>
                    <div class="text-muted">Total Tasks</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                    <div class="stat-number text-success"><?= $completedTasks ?? 0 ?></div>
                    <div class="text-muted">Completed</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                    <div class="stat-number text-warning"><?= $pendingTasks ?? 0 ?></div>
                    <div class="text-muted">Pending</div>
                </div>
            </div>
        </div>

        <!-- Tasks Table Card -->
        <div class="card main-card shadow">
            <div class="card-header">
                <h3 class="mb-0">
                    <i class="fas fa-list-check me-2"></i>My Tasks
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px">Queue #</th>
                                <th style="width: 50px"></th>
                                <th>Description</th>
                                <th style="width: 120px">Due Date</th>
                                <th style="width: 130px">Status</th>
                                <th style="width: 140px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($tasks) && count($tasks) > 0): ?>
                                <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td class="task-queue"><?= $task['queue_num'] ?></td>
                                        <td><i class="fas fa-tasks text-muted"></i></td>
                                        <td><?= esc($task['description']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($task['date'])) ?></td>
                                        <td>
                                            <?php if ($task['status'] == 'Done'): ?>
                                                <span class="badge-done">
                                                    <i class="fas fa-check-circle"></i> Done
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-pending">
                                                    <i class="fas fa-hourglass-half"></i> Not done
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="action-buttons">
                                            <button class="btn btn-sm btn-outline-success"
                                                onclick="toggleStatus(<?= $task['id'] ?>)"
                                                title="Toggle Status">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="editTask(<?= $task['id'] ?>, '<?= esc(addslashes($task['description'])) ?>', '<?= $task['status'] ?>')"
                                                title="Edit Task">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="deleteTask(<?= $task['id'] ?>)"
                                                title="Delete Task">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5>No tasks found</h5>
                                        <p class="text-muted">Click the "Add Task" button to create your first task.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light text-center">
                <div class="row">
                    <div class="col-md-6 text-md-start mb-2 mb-md-0">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Showing <?= count($tasks ?? []) ?> tasks
                        </small>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button class="btn btn-primary btn-sm" onclick="addTask()">
                            <i class="fas fa-plus"></i> Add New Task
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-center">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i>
                Copyright © 2026 LKT. All rights reserved.
            </small>
        </div>
    </div>




    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addTaskModalLabel">
                        <i class="fas fa-plus-circle"></i> Add New Task
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTaskForm">


                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description"
                                rows="3" placeholder="Enter task description..." required></textarea>
                            <div class="invalid-feedback">Please enter a task description.</div>
                        </div>

                        <div class="mb-3">
                            <label for="task_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="task_date" name="date">
                            <small class="text-muted">Leave empty to use today's date</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitNewTask()">
                        <i class="fas fa-save"></i> Add Task
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Task Modal -->


    <!-- Edit Task Modal-->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editTaskModalLabel">
                        <i class="fas fa-edit"></i> Edit Task
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTaskForm">
                        <input type="hidden" id="edit_task_id" name="task_id">

                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description *</label>
                            <textarea class="form-control" id="edit_description" name="description"
                                rows="3" placeholder="Enter task description..." required> </textarea>
                            <div class="invalid-feedback">Please enter a task description.</div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="Not done">⏳ Not done</option>
                                <option value="Done">✅ Done</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditTask()">
                        <i class="fas fa-save"></i> Update Task
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Task Modal-->


    <!-- Success/Error Toast Messages -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="toastMessage" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastBody"></div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let currentTaskId = null;

        function reloadPage() {
            location.reload();
        }
        // Placeholder functions for future implementation
        function toggleStatus(taskId) {
            alert('Toggle status for task ' + taskId + ' (to be implemented)');
        }


        // Function to open edit modal
        function editTask(taskId, description, status) {
            // Store task ID
            currentTaskId = taskId;

            // Set values in form
            document.getElementById('edit_task_id').value = taskId;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_status').value = status;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editTaskModal'));
            modal.show();
        }

        // Function to submit edit task
        function submitEditTask() {
            const taskId = document.getElementById('edit_task_id').value;
            const description = document.getElementById('edit_description').value.trim();
            const status = document.getElementById('edit_status').value;

            // Validate description
            if (!description) {
                showToast('Please enter a task description!', 'error');
                document.getElementById('edit_description').classList.add('is-invalid');
                return;
            }

            if (description.length < 3) {
                showToast('Description must be at least 3 characters long!', 'error');
                return;
            }

            // Disable submit button
            const submitBtn = event.target;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

            // Get CSRF token
            const csrfToken = document.querySelector('input[name="csrf_test_name"]')?.value || '';

            // Send update request
            fetch('/do_list/public/index.php/tasks/update/' + taskId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        description: description,
                        status: status,
                        csrf_test_name: csrfToken
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text.substring(0, 200));
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
                        modal.hide();

                        // Show success message
                        showToast(data.message, 'success');

                        // Reload page to show updated task
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message || 'Error updating task', 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Task';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred: ' + error.message, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Task';
                });
        }


        function deleteTask(taskId) {
            if (!confirm('Are you sure you want to delete this task?')) {
                return;
            }

            fetch('/do_list/public/tasks/delete/' + taskId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => {
                            reloadPage();
                        }, 1000);
                    } else {
                        showToast(data.message || 'Error deleting task', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                });


        }

        // Show modal when Add New Task button is clicked
        function addTask() {
            // Reset form
            document.getElementById('addTaskForm').reset();
            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('task_date').value = today;
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('addTaskModal'));
            modal.show();
        }

        // Submit new task via AJAX
        function submitNewTask() {
            const description = document.getElementById('description').value.trim();

            // Validate description
            if (!description) {
                showToast('Please enter a task description!', 'error');
                document.getElementById('description').classList.add('is-invalid');
                return;
            }

            // Get date (use today if empty)
            let date = document.getElementById('task_date').value;
            if (!date) {
                date = new Date().toISOString().split('T')[0];
            }

            // Disable submit button to prevent double submission
            const submitBtn = event.target;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';


            fetch('/do_list/public/tasks/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        description: description,
                        date: date,
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
                        modal.hide();

                        // Show success message
                        showToast(data.message, 'success');

                        // Reload page to show new task
                        setTimeout(() => {
                            reloadPage();
                        }, 1000);
                    } else {
                        showToast(data.message || 'Error adding task', 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save"></i> Add Task';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Add Task';
                });
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const toastEl = document.getElementById('toastMessage');
            const toastTitle = document.getElementById('toastTitle');
            const toastBody = document.getElementById('toastBody');

            if (type === 'success') {
                toastTitle.textContent = '✅ Success';
                toastTitle.style.color = 'green';
            } else {
                toastTitle.textContent = '❌ Error';
                toastTitle.style.color = 'red';
            }

            toastBody.textContent = message;

            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    </script>
</body>

</html>