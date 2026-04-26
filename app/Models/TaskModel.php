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
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Get all tasks ordered by queue number
    public function getAllTasks()
    {
        return $this->orderBy('queue_num', 'ASC')->findAll();
    }
    
    // Get tasks statistics
    public function getStats()
    {
        $total = $this->countAll();
        $completed = $this->where('status', 'Done')->countAllResults();
        $pending = $this->where('status', 'Not done')->countAllResults();
        
        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending
        ];
    }
}