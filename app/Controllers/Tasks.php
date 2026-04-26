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
}