<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Do List' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .card {
            border-radius: 15px;
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .task-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border-left: 4px solid #667eea;
        }
        
        .task-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .task-card.done {
            background: #f8f9fa;
            border-left-color: #28a745;
            opacity: 0.8;
        }
        
        .task-card.done .task-description {
            text-decoration: line-through;
            color: #6c757d;
        }
        
        .queue-number {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
            margin-right: 15px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .btn-action {
            margin: 0 3px;
            border-radius: 20px;
            padding: 5px 15px;
        }
        
        .drag-handle {
            cursor: move;
            color: #6c757d;
            margin-right: 10px;
        }
        
        .drag-handle:hover {
            color: #667eea;
        }
        
        .alert-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideIn 0.5s ease;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #764ba2, #667eea);
            color: white;
            transform: translateY(-2px);
        }
        
        .search-box {
            border-radius: 25px;
            padding: 10px 20px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-btn {
            border-radius: 25px;
            padding: 8px 20px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .filter-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        @media (max-width: 768px) {
            .stat-number {
                font-size: 1.5rem;
            }
            
            .btn-action {
                padding: 3px 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/tasks') ?>">
                <i class="fas fa-tasks"></i> Do List
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('/tasks') ? 'active' : '' ?>" href="<?= base_url('/tasks') ?>">
                            <i class="fas fa-list"></i> All Tasks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('/tasks/create') ? 'active' : '' ?>" href="<?= base_url('/tasks/create') ?>">
                            <i class="fas fa-plus"></i> Add Task
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mt-4">
        <?= view('partials/messages') ?>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="text-center text-white mt-5 py-3">
        <div class="container">
            <small>&copy; <?= date('Y') ?> Do List - Task Manager. All rights reserved.</small>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert-floating').fadeOut('slow');
            }, 5000);
        });
        
        // Function to show temporary message
        function showMessage(message, type = 'success') {
            const alert = $(`
                <div class="alert alert-${type} alert-floating alert-dismissible fade show" role="alert">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> 
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            $('body').append(alert);
            setTimeout(() => alert.fadeOut('slow'), 3000);
        }
    </script>
</body>
</html>