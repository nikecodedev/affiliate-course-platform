<?php $__env->startSection('title', 'Course Details: ' . $course->title); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Course Details: <?php echo e($course->title); ?>

                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.courses.edit', $course)); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Edit Course
                        </a>
                        <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Courses
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Course Image -->
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <?php if($course->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $course->image)); ?>" 
                                         alt="<?php echo e($course->title); ?>" 
                                         class="img-fluid rounded" 
                                         style="max-height: 300px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                         style="height: 300px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Course Information -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Basic Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Title:</strong></td>
                                            <td><?php echo e($course->title); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge <?php echo e($course->status ? 'bg-success' : 'bg-danger'); ?>">
                                                    <?php echo e($course->status ? 'Active' : 'Inactive'); ?>

                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Modules:</strong></td>
                                            <td>
                                                <span class="badge bg-info"><?php echo e($course->modules->count()); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Lessons:</strong></td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo e($course->modules->sum(function($module) { return $module->lessons->count(); })); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td><?php echo e($course->created_at->format('M d, Y H:i')); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Updated:</strong></td>
                                            <td><?php echo e($course->updated_at->format('M d, Y H:i')); ?></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Description</h5>
                                    <?php if($course->description): ?>
                                        <p class="text-muted"><?php echo e($course->description); ?></p>
                                    <?php else: ?>
                                        <p class="text-muted fst-italic">No description provided</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modules and Lessons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-list me-2"></i>
                                Modules and Lessons
                            </h5>
                        </div>

                        <?php if($course->modules->count() > 0): ?>
                            <div class="col-12">
                                <div class="accordion" id="modulesAccordion">
                                    <?php $__currentLoopData = $course->modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="module<?php echo e($module->id); ?>">
                                                <button class="accordion-button <?php echo e($loop->first ? '' : 'collapsed'); ?>" 
                                                        type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse<?php echo e($module->id); ?>" 
                                                        aria-expanded="<?php echo e($loop->first ? 'true' : 'false'); ?>" 
                                                        aria-controls="collapse<?php echo e($module->id); ?>">
                                                    <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                        <span>
                                                            <i class="fas fa-folder me-2"></i>
                                                            <?php echo e($module->title); ?>

                                                        </span>
                                                        <span class="badge bg-primary"><?php echo e($module->lessons->count()); ?> lessons</span>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse<?php echo e($module->id); ?>" 
                                                 class="accordion-collapse collapse <?php echo e($loop->first ? 'show' : ''); ?>" 
                                                 aria-labelledby="module<?php echo e($module->id); ?>" 
                                                 data-bs-parent="#modulesAccordion">
                                                <div class="accordion-body">
                                                    <?php if($module->lessons->count() > 0): ?>
                                                        <div class="list-group">
                                                            <?php $__currentLoopData = $module->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <i class="fas fa-play-circle me-2 text-primary"></i>
                                                                        <strong><?php echo e($lesson->title); ?></strong>
                                                                        <?php if($lesson->video_url): ?>
                                                                            <span class="badge bg-success ms-2">Video</span>
                                                                        <?php endif; ?>
                                                                        <?php if($lesson->attachment): ?>
                                                                            <span class="badge bg-info ms-2">Attachment</span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div>
                                                                        <a href="<?php echo e(route('admin.courses.modules.lessons.edit', [$course, $module, $lesson])); ?>" 
                                                                           class="btn btn-outline-primary btn-sm me-1">
                                                                            <i class="fas fa-edit"></i>
                                                                        </a>
                                                                        <form action="<?php echo e(route('admin.courses.modules.lessons.destroy', [$course, $module, $lesson])); ?>" 
                                                                              method="POST" 
                                                                              style="display: inline;"
                                                                              onsubmit="return confirm('Are you sure you want to delete this lesson?')">
                                                                            <?php echo csrf_field(); ?>
                                                                            <?php echo method_field('DELETE'); ?>
                                                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="text-center py-3">
                                                            <i class="fas fa-book-open fa-2x text-muted mb-2"></i>
                                                            <p class="text-muted mb-0">No lessons in this module</p>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="mt-3">
                                                        <a href="<?php echo e(route('admin.courses.modules.lessons.create', [$course, $module])); ?>" 
                                                           class="btn btn-success btn-sm">
                                                            <i class="fas fa-plus me-1"></i>
                                                            Add Lesson
                                                        </a>
                                                        <a href="<?php echo e(route('admin.courses.modules.edit', [$course, $module])); ?>" 
                                                           class="btn btn-outline-primary btn-sm ms-2">
                                                            <i class="fas fa-edit me-1"></i>
                                                            Edit Module
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No modules found</h5>
                                    <p class="text-muted">Create modules to organize your course content.</p>
                                    <a href="<?php echo e(route('admin.courses.modules.create', $course)); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>
                                        Create First Module
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Back to Courses
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?php echo e(route('admin.courses.modules.create', $course)); ?>" class="btn btn-success me-2">
                                <i class="fas fa-plus me-1"></i>
                                Add Module
                            </a>
                            <a href="<?php echo e(route('admin.courses.edit', $course)); ?>" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>
                                Edit Course
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/courses/show.blade.php ENDPATH**/ ?>