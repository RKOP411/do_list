<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus-circle"></i> Create New Task
                </h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/tasks/store') ?>" method="post" id="taskForm">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">
                            <i class="fas fa-align-left"></i> Task Description *
                        </label>
                        <textarea class="form-control <?= session('errors.description') ? 'is-invalid' : '' ?>" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Enter your task description here..." 
                                  required><?= old('description') ?></textarea>
                        <div class="invalid-feedback">
                            <?= session('errors.description') ?? '' ?>
                        </div>
                        <small class="text-muted">Provide a clear and detailed description of the task.</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="date" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt"></i> Due Date *
                        </label>
                        <input type="date" class="form-control <?= session('errors.date') ? 'is-invalid' : '' ?>" 
                               id="date" name="date" value="<?= old('date', date('Y-m-d')) ?>" required>
                        <div class="invalid-feedback">
                            <?= session('errors.date') ?? '' ?>
                        </div>
                        <small class="text-muted">Select the date when this task should be completed.</small>
                    </div>
                    
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="quickAdd" checked>
                            <label class="form-check-label" for="quickAdd">
                                <i class="fas fa-bolt"></i> Quick add mode (stay on this page after saving)
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('/tasks') ?>" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-gradient btn-lg" id="submitBtn">
                            <i class="fas fa-save"></i> Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Tips Card -->
        <div class="card mt-4 shadow-sm">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-lightbulb text-warning"></i> Tips for effective task management:
                </h6>
                <ul class="mb-0">
                    <li>Be specific when describing your tasks</li>
                    <li>Set realistic due dates</li>
                    <li>Break large tasks into smaller ones</li>
                    <li>Review and update your task list regularly</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#taskForm').on('submit', function(e) {
        const quickAdd = $('#quickAdd').is(':checked');
        
        if (quickAdd) {
            e.preventDefault();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Clear form except date
                    $('#description').val('');
                    $('#date').val(new Date().toISOString().split('T')[0]);
                    
                    showMessage('Task created successfully! You can add another.', 'success');
                    
                    // Focus on description field
                    $('#description').focus();
                },
                error: function(xhr) {
                    if (xhr.status === 302) {
                        window.location.href = xhr.getResponseHeader('Location');
                    } else {
                        showMessage('Error creating task. Please check the form.', 'danger');
                    }
                }
            });
        }
    });
});

function showMessage(message, type) {
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
