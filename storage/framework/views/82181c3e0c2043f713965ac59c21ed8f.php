

<?php $__env->startSection('title', 'Courses Management'); ?>
<?php $__env->startSection('page-title', 'Courses Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-book me-2"></i>Courses Management
                    </h5>
                    <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create New Course
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <?php if($course->image_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $course->image_path)); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo e($course->title); ?>"
                                         style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="bi bi-book display-4 text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo e($course->title); ?></h5>
                                    <p class="card-text text-muted flex-grow-1">
                                        <?php echo e(Str::limit($course->description, 100)); ?>

                                    </p>
                                    
                                    <div class="mb-3">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="text-primary">
                                                    <i class="bi bi-list-ol"></i>
                                                    <div class="small"><?php echo e($course->modules_count ?? 0); ?> Modules</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-success">
                                                    <i class="bi bi-play-circle"></i>
                                                    <div class="small"><?php echo e($course->lessons_count ?? 0); ?> Lessons</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-info">
                                                    <i class="bi bi-people"></i>
                                                    <div class="small"><?php echo e($course->students_count ?? 0); ?> Students</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-auto">
                                        <?php if($course->is_active): ?>
                                            <span class="badge bg-success mb-2">
                                                <i class="bi bi-check-circle me-1"></i>Active
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger mb-2">
                                                <i class="bi bi-x-circle me-1"></i>Inactive
                                            </span>
                                        <?php endif; ?>
                                        
                                        <div class="btn-group w-100" role="group">
                                            <a href="<?php echo e(route('admin.courses.show', $course)); ?>" 
                                               class="btn btn-outline-primary btn-sm" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.courses.edit', $course)); ?>" 
                                               class="btn btn-outline-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                    onclick="manageModules(<?php echo e($course->id); ?>)" title="Manage Modules">
                                                <i class="bi bi-list"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-book display-6 d-block mb-3"></i>
                                    <h4>No Courses Found</h4>
                                    <p>Create your first course to start building your educational content.</p>
                                    <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>Create Course
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if($courses->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($courses->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Course Modules Modal -->
<div class="modal fade" id="modulesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-list me-2"></i>Course Modules
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modulesModalBody">
                <!-- Modules will be loaded here -->
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function manageModules(courseId) {
    // Here you would typically load course modules via AJAX
    document.getElementById('modulesModalBody').innerHTML = `
        <div class="text-center py-4">
            <i class="bi bi-hourglass-split display-4 text-muted mb-3"></i>
            <h5>Loading Course Modules...</h5>
            <p class="text-muted">Course ID: ${courseId}</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('modulesModal'));
    modal.show();
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/courses/index.blade.php ENDPATH**/ ?>