<?php

// Add these lines for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if we can bootstrap
try {
    // Rest of your existing code...
    require_once '../app/Config/Paths.php';
    // ... etc
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    throw $e;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Do List - Task Manager</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-card {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
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
            padding: 5px 12px;
            border-radius: 20px;
        }
        
        .badge-pending {
            background-color: #ffc107;
            color: #333;
            padding: 5px 12px;
            border-radius: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        
        .welcome-banner {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 15px;
            padding: 30px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Welcome Banner -->
        <div class="welcome-banner mb-4 text-center">
            <h1 class="display-4 fw-bold">
                <i class="fas fa-tasks me-3"></i>Do List
            </h1>
            <p class="lead mb-0">Organize your tasks, boost productivity, achieve more!</p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card shadow-sm">
                    <i class="fas fa-clipboard-list fa-2x text-primary mb-2"></i>
                    <div class="stat-number">12</div>
                    <div class="text-muted">Total Tasks</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card shadow-sm">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <div class="stat-number text-success">7</div>
                    <div class="text-muted">Completed</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card shadow-sm">
                    <i class="fas fa-hourglass-half fa-2x text-warning mb-2"></i>
                    <div class="stat-number text-warning">5</div>
                    <div class="text-muted">Pending</div>
                </div>
            </div>
        </div>
        
        <!-- Tasks Table Card -->
        <div class="card main-card shadow-lg">
            <div class="card-header">
                <h3 class="mb-0">
                    <i class="fas fa-list-check me-2"></i>My Tasks
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px">#</th>
                                <th style="width: 50px"></th>
                                <th>Description</th>
                                <th style="width: 120px">Date</th>
                                <th style="width: 120px">Status</th>
                                <th style="width: 100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Task 1 -->
                            <tr>
                                <td class="fw-bold">1</td>
                                <td><i class="fas fa-tasks text-muted"></i></td>
                                <td>Complete project documentation</td>
                                <td>2024-05-15</td>
                                <td><span class="badge-done"><i class="fas fa-check-circle"></i> Done</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success me-1" disabled>
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary me-1" disabled>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Task 2 -->
                            <tr>
                                <td class="fw-bold">2</td>
                                <td><i class="fas fa-tasks text-muted"></i></td>
                                <td>Review pull requests</td>
                                <td>2024-05-16</td>
                                <td><span class="badge-pending"><i class="fas fa-hourglass-half"></i> Not done</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success me-1" disabled>
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary me-1" disabled>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Task 3 -->
                            <tr>
                                <td class="fw-bold">3</td>
                                <td><i class="fas fa-tasks text-muted"></i></td>
                                <td>Update website content</td>
                                <td>2024-05-14</td>
                                <td><span class="badge-done"><i class="fas fa-check-circle"></i> Done</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success me-1" disabled>
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary me-1" disabled>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Task 4 -->
                            <tr>
                                <td class="fw-bold">4</td>
                                <td><i class="fas fa-tasks text-muted"></i></td>
                                <td>Fix bugs in login system</td>
                                <td>2024-05-17</td>
                                <td><span class="badge-pending"><i class="fas fa-hourglass-half"></i> Not done</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success me-1" disabled>
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary me-1" disabled>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Task 5 -->
                            <tr>
                                <td class="fw-bold">5</td>
                                <td><i class="fas fa-tasks text-muted"></i></td>
                                <td>Prepare monthly report</td>
                                <td>2024-05-18</td>
                                <td><span class="badge-pending"><i class="fas fa-hourglass-half"></i> Not done</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success me-1" disabled>
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary me-1" disabled>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Task 6 -->
                            <tr>
                                <td class="fw-bold">6</td>
                                <td><i class="fas fa-tasks text-muted"></i></td>
                                <td>Team meeting preparation</td>
                                <td>2024-05-19</td>
                                <td><span class="badge-pending"><i class="fas fa-hourglass-half"></i> Not done</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success me-1" disabled>
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary me-1" disabled>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Task 7 -->
                            <tr>
                                <td class="fw-bold">7</td>
                                <td><i class="fas fa-tasks text-muted"></i></td>
                                <td>Client feedback collection</td>
                                <td>2024-05-13</td>
                                <td><span class="badge-done"><i class="fas fa-check-circle"></i> Done</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success me-1" disabled>
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary me-1" disabled>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Task 8 -->
                            <tr>
                                <td class="fw-bold">8</td>
                                <td><i class="fas fa-tasks text-muted"></i></td>
                                <td>Deploy application to server</td>
                                <td>2024-05-20</td>
                                <td><span class="badge-pending"><i class="fas fa-hourglass-half"></i> Not done</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success me-1" disabled>
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary me-1" disabled>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light text-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    This is a demo UI with sample data. Buttons are disabled for preview.
                </small>
            </div>
        </div>
        
        <!-- Feature Highlights -->
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>Easy to Use</h5>
                        <p class="text-muted">Simple interface for managing your daily tasks</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-sync-alt fa-3x text-primary mb-3"></i>
                        <h5>One-Click Updates</h5>
                        <p class="text-muted">Toggle task status with a single click</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                        <h5>Track Progress</h5>
                        <p class="text-muted">Monitor your productivity and achievements</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>