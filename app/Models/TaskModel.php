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

    protected $useTimestamps = false;

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

    // Get maximum queue number
    public function getMaxQueueNum()
    {
        $result = $this->selectMax('queue_num')->first();
        return $result['queue_num'] ?? 0;
    }

    // Reorder queue numbers after deletion
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
    }
}
