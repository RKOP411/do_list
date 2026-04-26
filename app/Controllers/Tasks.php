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
}
