

<?php $__env->startSection('title', 'Plans Management'); ?>
<?php $__env->startSection('page-title', 'Plans Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-box me-2"></i>Plans Management
                    </h5>
                    <a href="<?php echo e(route('admin.plans.create')); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create New Plan
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

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Commission Rate</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#<?php echo e($plan->id); ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($plan->name); ?></div>
                                        <small class="text-muted"><?php echo e(Str::limit($plan->description, 50)); ?></small>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">$<?php echo e(number_format($plan->price, 2)); ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-warning"><?php echo e($plan->commission_rate); ?>%</span>
                                    </td>
                                    <td>
                                        <?php if($plan->is_active): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Active
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Inactive
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            <?php echo e($plan->created_at->format('M d, Y')); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.plans.show', $plan)); ?>" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.plans.edit', $plan)); ?>" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="<?php echo e(route('admin.plans.toggle.status', $plan)); ?>" 
                                                  style="display: inline-block;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-<?php echo e($plan->is_active ? 'danger' : 'success'); ?>" 
                                                        title="<?php echo e($plan->is_active ? 'Deactivate' : 'Activate'); ?>">
                                                    <i class="bi bi-<?php echo e($plan->is_active ? 'pause' : 'play'); ?>"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-box display-6 d-block mb-2"></i>
                                            <h5>No Plans Found</h5>
                                            <p>Create your first plan to get started.</p>
                                            <a href="<?php echo e(route('admin.plans.create')); ?>" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>Create Plan
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($plans->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($plans->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/plans/index.blade.php ENDPATH**/ ?>