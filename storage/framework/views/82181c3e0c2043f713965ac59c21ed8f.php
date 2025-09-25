<?php $__env->startSection('title', 'Courses Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Courses Management
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Create New Course
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Success/Error Messages -->
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if($courses->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Modules</th>
                                        <th>Lessons</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <?php if($course->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $course->image)); ?>" 
                                                         alt="<?php echo e($course->title); ?>" 
                                                         class="img-thumbnail" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo e($course->title); ?></strong>
                                                    <?php if($course->description): ?>
                                                        <br><small class="text-muted"><?php echo e(Str::limit($course->description, 50)); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo e($course->modules->count()); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo e($course->modules->sum(function($module) { return $module->lessons->count(); })); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo e($course->status ? 'bg-success' : 'bg-danger'); ?>">
                                                    <?php echo e($course->status ? 'Active' : 'Inactive'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <small><?php echo e($course->created_at->format('M d, Y')); ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('admin.courses.show', $course)); ?>" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('admin.courses.edit', $course)); ?>" 
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="Edit Course">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('admin.courses.modules.index', $course)); ?>" 
                                                       class="btn btn-outline-success btn-sm"
                                                       title="Manage Modules">
                                                        <i class="bi bi-list-ul"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-<?php echo e($course->status ? 'warning' : 'success'); ?> btn-sm toggle-status"
                                                            data-course-id="<?php echo e($course->id); ?>"
                                                            title="<?php echo e($course->status ? 'Deactivate' : 'Activate'); ?> Course">
                                                        <i class="bi bi-<?php echo e($course->status ? 'pause-circle' : 'play-circle'); ?>"></i>
                                                    </button>
                                                    <form action="<?php echo e(route('admin.courses.destroy', $course)); ?>" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Are you sure you want to delete this course?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                title="Delete Course">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            <?php echo e($courses->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No courses found</h5>
                            <p class="text-muted">Create your first course to get started.</p>
                            <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Create New Course
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status functionality
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const courseId = this.getAttribute('data-course-id');
            const originalIcon = this.querySelector('i');
            const originalClass = this.className;
            
            // Show loading state
            this.disabled = true;
            originalIcon.className = 'fas fa-spinner fa-spin';
            
            fetch(`/admin/courses/${courseId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    if (data.status) {
                        this.className = originalClass.replace('outline-success', 'outline-warning');
                        this.title = 'Deactivate Course';
                        originalIcon.className = 'fas fa-pause';
                    } else {
                        this.className = originalClass.replace('outline-warning', 'outline-success');
                        this.title = 'Activate Course';
                        originalIcon.className = 'fas fa-play';
                    }
                    
                    // Update status badge in the same row
                    const row = this.closest('tr');
                    const statusBadge = row.querySelector('.badge');
                    if (data.status) {
                        statusBadge.className = 'badge bg-success';
                        statusBadge.textContent = 'Active';
                    } else {
                        statusBadge.className = 'badge bg-danger';
                        statusBadge.textContent = 'Inactive';
                    }
                    
                    // Show success message
                    showAlert('Course status updated successfully!', 'success');
                } else {
                    showAlert('Failed to update course status', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error updating course status', 'danger');
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
    
    // Alert function
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.card-body');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/courses/index.blade.php ENDPATH**/ ?>