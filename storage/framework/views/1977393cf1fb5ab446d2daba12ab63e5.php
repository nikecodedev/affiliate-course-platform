

<?php $__env->startSection('title', 'User Details'); ?>
<?php $__env->startSection('page-title', 'User Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-person me-2"></i><?php echo e($user->name); ?></h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <div class="form-control-plaintext"><?php echo e($user->name); ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <div class="form-control-plaintext"><?php echo e($user->email); ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <div class="form-control-plaintext"><?php echo e($user->phone ?? '-'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <div class="form-control-plaintext">
                            <span class="badge <?php echo e($user->is_active ? 'bg-success' : 'bg-secondary'); ?>"><?php echo e($user->is_active ? 'Active' : 'Inactive'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h6 class="mb-0">Affiliate Stats</h6></div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col">
                        <div class="h4 mb-0"><?php echo e($stats['total_sales']); ?></div>
                        <small class="text-muted">Sales</small>
                    </div>
                    <div class="col">
                        <div class="h4 mb-0"><?php echo e(number_format($stats['total_revenue'], 2)); ?></div>
                        <small class="text-muted">Revenue</small>
                    </div>
                    <div class="col">
                        <div class="h4 mb-0"><?php echo e(number_format($stats['total_commission_earned'], 2)); ?></div>
                        <small class="text-muted">Commission Earned</small>
                    </div>
                    <div class="col">
                        <div class="h4 mb-0"><?php echo e(number_format($stats['total_commission_paid'], 2)); ?></div>
                        <small class="text-muted">Commission Paid</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Actions</h6></div>
            <div class="card-body">
                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-pencil me-1"></i>Edit User
                </a>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-1"></i>Back to Users
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/users/show.blade.php ENDPATH**/ ?>