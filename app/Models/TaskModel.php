<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['queue_num', 'description', 'date', 'status'];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'queue_num' => 'required|integer|greater_than[0]',
        'description' => 'required|min_length[3]|max_length[500]',
        'date' => 'required|valid_date',
        'status' => 'required|in_list[Done,Not done]'
    ];
    
    protected $validationMessages = [
        'description' => [
            'required' => 'Task description is required',
            'min_length' => 'Description must be at least 3 characters long',
            'max_length' => 'Description cannot exceed 500 characters'
        ],
        'date' => [
            'required' => 'Date is required',
            'valid_date' => 'Please provide a valid date'
        ],
        'queue_num' => [
            'required' => 'Queue number is required',
            'integer' => 'Queue number must be a number',
            'greater_than' => 'Queue number must be greater than 0'
        ]
    ];
    
    protected $skipValidation = false;
    
    // Reorder queue numbers sequentially
    public function reorderQueueNumbers()
    {
        $tasks = $this->orderBy('queue_num', 'ASC')->findAll();
        $counter = 1;
        
        foreach ($tasks as $task) {
            if ($task['queue_num'] != $counter) {
                $this->update($task['id'], ['queue_num' => $counter]);
            }
            $counter++;
        }
        
        return true;
    }
    
    // Get maximum queue number
    public function getMaxQueueNum()
    {
        $result = $this->selectMax('queue_num')->first();
        return $result['queue_num'] ?? 0;
    }
    
    // Get tasks by status
    public function getTasksByStatus($status)
    {
        return $this->where('status', $status)->orderBy('queue_num', 'ASC')->findAll();
    }
    
    // Get upcoming tasks (future dates)
    public function getUpcomingTasks($days = 7)
    {
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));
        return $this->where('date <=', $futureDate)
                    ->where('date >=', date('Y-m-d'))
                    ->where('status', 'Not done')
                    ->orderBy('date', 'ASC')
                    ->findAll();
    }
    
    // Search tasks
    public function searchTasks($keyword)
    {
        return $this->like('description', $keyword)
                    ->orderBy('queue_num', 'ASC')
                    ->findAll();
    }
    
    // Get overdue tasks
    public function getOverdueTasks()
    {
        return $this->where('date <', date('Y-m-d'))
                    ->where('status', 'Not done')
                    ->orderBy('date', 'ASC')
                    ->findAll();
    }
}