<?php

namespace App\Controllers;

use App\Models\TaskModel;

class Tasks extends BaseController
{
    protected $taskModel;
    
    public function __construct()
    {
        $this->taskModel = new TaskModel();
        helper(['form', 'url', 'session']);
    }
    
    // Display all tasks
    public function index()
    {
        $data = [
            'title' => 'Do List - Task Manager',
            'tasks' => $this->taskModel->orderBy('queue_num', 'ASC')->findAll(),
            'totalTasks' => $this->taskModel->countAll(),
            'completedTasks' => $this->taskModel->where('status', 'Done')->countAllResults(),
            'pendingTasks' => $this->taskModel->where('status', 'Not done')->countAllResults()
        ];
        
        return view('tasks/index', $data);
    }
    
    // Show create task form
    public function create()
    {
        $data = [
            'title' => 'Create New Task'
        ];
        
        return view('tasks/create', $data);
    }
    
    // Store new task
    public function store()
    {
        // Validation rules
        $rules = [
            'description' => 'required|min_length[3]|max_length[500]',
            'date' => 'required|valid_date'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Get max queue number
        $maxQueue = $this->taskModel->getMaxQueueNum();
        $newQueueNum = $maxQueue + 1;
        
        $data = [
            'queue_num' => $newQueueNum,
            'description' => $this->request->getPost('description'),
            'date' => $this->request->getPost('date'),
            'status' => 'Not done'
        ];
        
        if ($this->taskModel->insert($data)) {
            session()->setFlashdata('success', 'Task created successfully!');
            return redirect()->to('/tasks');
        } else {
            session()->setFlashdata('error', 'Failed to create task. Please try again.');
            return redirect()->back()->withInput();
        }
    }
    
    // Edit task form
    public function edit($id = null)
    {
        if ($id === null) {
            return redirect()->to('/tasks');
        }
        
        $task = $this->taskModel->find($id);
        
        if (!$task) {
            session()->setFlashdata('error', 'Task not found.');
            return redirect()->to('/tasks');
        }
        
        $data = [
            'title' => 'Edit Task',
            'task' => $task
        ];
        
        return view('tasks/edit', $data);
    }
    
    // Update task
    public function update($id = null)
    {
        if ($id === null) {
            return redirect()->to('/tasks');
        }
        
        $task = $this->taskModel->find($id);
        
        if (!$task) {
            session()->setFlashdata('error', 'Task not found.');
            return redirect()->to('/tasks');
        }
        
        // Validation rules
        $rules = [
            'description' => 'required|min_length[3]|max_length[500]',
            'date' => 'required|valid_date',
            'queue_num' => 'required|integer|greater_than[0]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'description' => $this->request->getPost('description'),
            'date' => $this->request->getPost('date'),
            'queue_num' => $this->request->getPost('queue_num')
        ];
        
        if ($this->taskModel->update($id, $data)) {
            // Reorder all tasks to maintain sequential queue numbers
            $this->taskModel->reorderQueueNumbers();
            session()->setFlashdata('success', 'Task updated successfully!');
            return redirect()->to('/tasks');
        } else {
            session()->setFlashdata('error', 'Failed to update task.');
            return redirect()->back()->withInput();
        }
    }
    
    // Toggle task status (Done/Not done)
    public function toggle($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Task ID required']);
        }
        
        $task = $this->taskModel->find($id);
        
        if (!$task) {
            return $this->response->setJSON(['success' => false, 'message' => 'Task not found']);
        }
        
        $newStatus = $task['status'] === 'Done' ? 'Not done' : 'Done';
        
        if ($this->taskModel->update($id, ['status' => $newStatus])) {
            // Check if AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Task status updated!',
                    'new_status' => $newStatus,
                    'status_badge' => $newStatus === 'Done' ? 
                        '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Done</span>' : 
                        '<span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half"></i> Not done</span>'
                ]);
            }
            
            session()->setFlashdata('success', 'Task status updated!');
            return redirect()->to('/tasks');
        }
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update status']);
        }
        
        session()->setFlashdata('error', 'Failed to update task status.');
        return redirect()->to('/tasks');
    }
    
    // Delete task
    public function delete($id = null)
    {
        if ($id === null) {
            return redirect()->to('/tasks');
        }
        
        $task = $this->taskModel->find($id);
        
        if (!$task) {
            session()->setFlashdata('error', 'Task not found.');
            return redirect()->to('/tasks');
        }
        
        if ($this->taskModel->delete($id)) {
            // Reorder remaining tasks
            $this->taskModel->reorderQueueNumbers();
            session()->setFlashdata('success', 'Task deleted successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to delete task.');
        }
        
        return redirect()->to('/tasks');
    }
    
    // AJAX reorder tasks
    public function reorder()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        $order = $this->request->getJSON();
        
        if (!isset($order->order) || !is_array($order->order)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid order data']);
        }
        
        try {
            foreach ($order->order as $index => $taskId) {
                $this->taskModel->update($taskId, ['queue_num' => $index + 1]);
            }
            return $this->response->setJSON(['success' => true, 'message' => 'Order updated successfully']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update order']);
        }
    }
    
    // Get task statistics for dashboard
    public function stats()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }
        
        $data = [
            'total' => $this->taskModel->countAll(),
            'completed' => $this->taskModel->where('status', 'Done')->countAllResults(),
            'pending' => $this->taskModel->where('status', 'Not done')->countAllResults()
        ];
        
        return $this->response->setJSON(['success' => true, 'stats' => $data]);
    }
}