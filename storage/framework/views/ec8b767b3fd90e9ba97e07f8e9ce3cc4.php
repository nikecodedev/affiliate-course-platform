

<?php $__env->startSection('title', 'Lessons for ' . $module->title); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-play-circle me-2"></i>
                        Lessons for: <?php echo e($module->title); ?>

                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.courses.modules.lessons.create', [$course, $module])); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Create New Lesson
                        </a>
                        <a href="<?php echo e(route('admin.courses.modules.index', $course)); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Modules
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

                    <?php if($lessons->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Content</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo e($lesson->order); ?></span>
                                            </td>
                                            <td>
                                                <strong><?php echo e($lesson->title); ?></strong>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <?php if($lesson->video_url): ?>
                                                        <span class="badge bg-success">Video</span>
                                                    <?php endif; ?>
                                                    <?php if($lesson->attachment): ?>
                                                        <span class="badge bg-info">Attachment</span>
                                                    <?php endif; ?>
                                                    <?php if($lesson->content): ?>
                                                        <span class="badge bg-primary">Content</span>
                                                    <?php endif; ?>
                                                    <?php if(!$lesson->video_url && !$lesson->attachment && !$lesson->content): ?>
                                                        <span class="badge bg-warning">Empty</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($lesson->content): ?>
                                                    <small class="text-muted"><?php echo e(Str::limit(strip_tags($lesson->content), 50)); ?></small>
                                                <?php else: ?>
                                                    <small class="text-muted">No content</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small><?php echo e($lesson->created_at->format('M d, Y')); ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('admin.courses.modules.lessons.edit', [$course, $module, $lesson])); ?>" 
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="Edit Lesson">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if($lesson->attachment): ?>
                                                        <a href="<?php echo e(route('admin.courses.modules.lessons.download', [$course, $module, $lesson])); ?>" 
                                                           class="btn btn-outline-info btn-sm"
                                                           title="Download Attachment">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <form action="<?php echo e(route('admin.courses.modules.lessons.destroy', [$course, $module, $lesson])); ?>" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Are you sure you want to delete this lesson?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                title="Delete Lesson">
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
                            <?php echo e($lessons->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-play-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No lessons found</h5>
                            <p class="text-muted">Create lessons to add content to this module.</p>
                            <a href="<?php echo e(route('admin.courses.modules.lessons.create', [$course, $module])); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Create First Lesson
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/lessons/index.blade.php ENDPATH**/ ?>