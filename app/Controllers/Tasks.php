<?php


namespace App\Controllers;

use App\Models\TaskModel;

class Tasks extends BaseController
{
    protected $taskModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
    }

    public function index()
    {
        // Get all tasks ordered by queue_num
        $tasks = $this->taskModel->getAllTasks();

        // Get statistics
        $stats = $this->taskModel->getStats();

        $data = [
            'title' => 'Do List - Task Manager',
            'tasks' => $tasks,
            'totalTasks' => $stats['total'],
            'completedTasks' => $stats['completed'],
            'pendingTasks' => $stats['pending']
        ];

        return view('main', $data);
    }

    // Return tasks partial for AJAX refresh
    public function getTasks()
    {
        $tasks = $this->taskModel->getAllTasks();
        $stats = $this->taskModel->getStats();

        // If AJAX request, return partial HTML
        if ($this->request->isAJAX()) {
            $html = '';
            if (!empty($tasks)) {
                foreach ($tasks as $task) {
                    $statusBadge = $task['status'] == 'Done' 
                        ? '<span class="badge-done"><i class="fas fa-check-circle"></i> Done</span>'
                        : '<span class="badge-pending"><i class="fas fa-hourglass-half"></i> Not done</span>';
                    
                    $html .= '<tr>';
                    $html .= '<td class="task-queue">' . $task['queue_num'] . '</td>';
                    $html .= '<td><i class="fas fa-tasks text-muted"></i></td>';
                    $html .= '<td>' . esc($task['description']) . '</td>';
                    $html .= '<td>' . date('d/m/Y', strtotime($task['date'])) . '</td>';
                    $html .= '<td>' . $statusBadge . '</td>';
                    $html .= '<td class="action-buttons">';
                    $html .= '<button class="btn btn-sm btn-outline-success" onclick="toggleStatus(' . $task['id'] . ', \'' . $task['status'] . '\')" title="Toggle Status"><i class="fas fa-check"></i></button>';
                    $html .= '<button class="btn btn-sm btn-outline-primary" onclick="editTask(' . $task['id'] . ', \'' . esc(addslashes($task['description'])) . '\', \'' . $task['status'] . '\')" title="Edit Task"><i class="fas fa-edit"></i></button>';
                    $html .= '<button class="btn btn-sm btn-outline-danger" onclick="deleteTask(' . $task['id'] . ')" title="Delete Task"><i class="fas fa-trash"></i></button>';
                    $html .= '</td></tr>';
                }
            } else {
                $html .= '<tr><td colspan="6" class="text-center py-5"><i class="fas fa-inbox fa-3x text-muted mb-3"></i><h5>No tasks found</h5><p class="text-muted">Click the "Add Task" button to create your first task.</p></td></tr>';
            }

            return $this->response->setJSON([
                'success' => true,
                'html' => $html,
                'totalTasks' => $stats['total'],
                'completedTasks' => $stats['completed'],
                'pendingTasks' => $stats['pending']
            ]);
        }

        return $this->index();
    }

    public function store()
    {
        // Check if AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $jsonData = $this->request->getJSON();

        $description = $jsonData->description ?? $this->request->getPost('description');
        $date = $jsonData->date ?? $this->request->getPost('date');

        // Validation
        $errors = [];
        if (empty($description) || strlen($description) < 3) {
            $errors[] = 'Description must be at least 3 characters long.';
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(' ', $errors)
            ]);
        }

        // Set date to today if not provided
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $maxQueue = $this->taskModel->getMaxQueueNum();
        $newQueueNum = $maxQueue + 1;

        // Prepare data for insertion
        $data = [
            'queue_num' => $newQueueNum,
            'description' => trim($description),
            'date' => $date,
            'status' => 'Not done'
        ];

        error_log('Inserting task: ' . print_r($data, true));

        // Insert into database
        if ($this->taskModel->insert($data)) {
            $newId = $this->taskModel->getInsertID();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Task added successfully!',
                'task_id' => $newId,
                'queue_num' => $newQueueNum
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add task. Please try again.'
            ]);
        }
    }
    // Method to get max queue number (add to model if not exists)
    public function getMaxQueue()
    {
        $max = $this->taskModel->getMaxQueueNum();
        return $this->response->setJSON(['max_queue' => $max]);
    }

    // Delete a task
    public function delete($id = null)
    {
        // Check if AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task ID is required'
            ]);
        }

        // Check if task exists
        $task = $this->taskModel->find($id);
        if (!$task) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task not found'
            ]);
        }

        // Delete the task
        if ($this->taskModel->delete($id)) {
            // Reorder queue numbers after deletion
            $this->taskModel->reorderQueueNumbers();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Task deleted successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete task. Please try again.'
            ]);
        }
    }

    // Update a task
    public function update($id = null)
    {
        // Check if AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $jsonData = $this->request->getJSON();

        $description = $jsonData->description ?? $this->request->getPost('description');
        $status = $jsonData->status ?? $this->request->getPost('status');

        // Validation
        $errors = [];
        if (empty($description) || strlen($description) < 3) {
            $errors[] = 'Description must be at least 3 characters long.';
        }

        if (!in_array($status, ['Done', 'Not done'])) {
            $errors[] = 'Invalid status value.';
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(' ', $errors)
            ]);
        }

        // Check if task exists
        $task = $this->taskModel->find($id);
        if (!$task) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task not found'
            ]);
        }

        // Prepare data for update
        $data = [
            'description' => trim($description),
            'status' => $status
        ];

        // Update the task
        if ($this->taskModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Task updated successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update task. Please try again.'
            ]);
        }
    }

    public function toggleStatus($id = null)
    {
        // Check if AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task ID is required'
            ]);
        }

        // Check if task exists
        $task = $this->taskModel->find($id);
        if (!$task) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Task not found'
            ]);
        }

        // Toggle status
        $newStatus = ($task['status'] === 'Done') ? 'Not done' : 'Done';

        // Update the task
        if ($this->taskModel->update($id, ['status' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Task status updated successfully!',
                'new_status' => $newStatus
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update task status. Please try again.'
            ]);
        }
    }
}
