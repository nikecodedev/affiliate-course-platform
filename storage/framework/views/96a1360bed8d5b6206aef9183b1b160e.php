

<?php $__env->startSection('title', 'Modules for ' . $course->title); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-folder me-2"></i>
                        Modules for: <?php echo e($course->title); ?>

                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.courses.modules.create', $course)); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Create New Module
                        </a>
                        <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Courses
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

                    <?php if($modules->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Title</th>
                                        <th>Lessons</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo e($module->order); ?></span>
                                            </td>
                                            <td>
                                                <strong><?php echo e($module->title); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo e($module->lessons->count()); ?></span>
                                            </td>
                                            <td>
                                                <small><?php echo e($module->created_at->format('M d, Y')); ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('admin.courses.modules.lessons.index', [$course, $module])); ?>" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="Manage Lessons">
                                                        <i class="fas fa-list"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('admin.courses.modules.edit', [$course, $module])); ?>" 
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="Edit Module">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('admin.courses.modules.destroy', [$course, $module])); ?>" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Are you sure you want to delete this module?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                title="Delete Module">
                                                            <i class="fas fa-trash"></i>
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
                            <?php echo e($modules->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No modules found</h5>
                            <p class="text-muted">Create modules to organize your course content.</p>
                            <a href="<?php echo e(route('admin.courses.modules.create', $course)); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Create First Module
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/modules/index.blade.php ENDPATH**/ ?>