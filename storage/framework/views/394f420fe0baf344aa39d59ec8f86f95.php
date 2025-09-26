

<?php $__env->startSection('title', 'Create New Module'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus me-2"></i>
                        Create New Module for: <?php echo e($course->title); ?>

                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.courses.modules.index', $course)); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Modules
                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('admin.courses.modules.store', $course)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <!-- Module Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Module Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">
                                                Module Title <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="title" 
                                                   name="title" 
                                                   value="<?php echo e(old('title')); ?>" 
                                                   placeholder="Enter module title"
                                                   required>
                                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="mb-3">
                                            <label for="order" class="form-label">Order</label>
                                            <input type="number" 
                                                   class="form-control <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="order" 
                                                   name="order" 
                                                   value="<?php echo e(old('order', $course->modules()->max('order') + 1)); ?>" 
                                                   min="1"
                                                   placeholder="Module order">
                                            <div class="form-text">Leave empty to add at the end</div>
                                            <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Course Information -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-graduation-cap me-2"></i>
                                            Course Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <h6><?php echo e($course->title); ?></h6>
                                        <?php if($course->description): ?>
                                            <p class="text-muted small"><?php echo e(Str::limit($course->description, 100)); ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <strong>Current Modules:</strong> <?php echo e($course->modules->count()); ?><br>
                                                <strong>Total Lessons:</strong> <?php echo e($course->modules->sum(function($module) { return $module->lessons->count(); })); ?>

                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Next Steps -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            Next Steps
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ol class="small">
                                            <li>Create the module</li>
                                            <li>Add lessons to the module</li>
                                            <li>Organize lesson order</li>
                                            <li>Add video content and attachments</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?php echo e(route('admin.courses.modules.index', $course)); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Create Module
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        
        if (!title) {
            e.preventDefault();
            showAlert('Please enter a module title.', 'danger');
            return;
        }
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


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/modules/create.blade.php ENDPATH**/ ?>