

<?php $__env->startSection('title', 'Bonus Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-gift me-2"></i>
                        Bonus Settings Configuration
                    </h3>
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

                    <div class="row">
                        <?php $__currentLoopData = $bonusTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $setting = $bonusSettings[$type];
                                $levels = $setting->getConfiguredLevels();
                                $levelCount = count($levels);
                            ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 <?php echo e($setting->is_active ? 'border-success' : 'border-secondary'); ?>">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-<?php echo e($type === 'direct_referral' ? 'handshake' : ($type === 'unilevel' ? 'sitemap' : 'th')); ?> me-2"></i>
                                            <?php echo e($name); ?>

                                        </h5>
                                        <form action="<?php echo e(route('admin.bonus.toggle', $type)); ?>" method="POST" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm <?php echo e($setting->is_active ? 'btn-success' : 'btn-secondary'); ?>">
                                                <?php echo e($setting->is_active ? 'Active' : 'Inactive'); ?>

                                            </button>
                                        </form>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong>Status:</strong>
                                            <span class="badge <?php echo e($setting->is_active ? 'bg-success' : 'bg-secondary'); ?>">
                                                <?php echo e($setting->is_active ? 'Active' : 'Inactive'); ?>

                                            </span>
                                        </div>

                                        <?php if($type === 'direct_referral'): ?>
                                            <div class="mb-2">
                                                <strong>Payment Mode:</strong>
                                                <span class="badge bg-info"><?php echo e($setting->getPaymentModeDisplayName()); ?></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Amount:</strong>
                                                <?php echo e($setting->getFormattedLevelConfig(1)); ?>

                                            </div>
                                        <?php elseif($type === 'unilevel'): ?>
                                            <div class="mb-2">
                                                <strong>Levels Configured:</strong>
                                                <span class="badge bg-primary"><?php echo e($levelCount); ?></span>
                                            </div>
                                            <?php if($levelCount > 0): ?>
                                                <div class="mb-2">
                                                    <strong>Levels:</strong>
                                                    <div class="mt-1">
                                                        <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <small class="d-block">
                                                                Level <?php echo e($level); ?>: <?php echo e($setting->getFormattedLevelConfig($level)); ?>

                                                            </small>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif($type === 'matrix'): ?>
                                            <div class="mb-2">
                                                <strong>Matrix Size:</strong>
                                                <span class="badge bg-warning"><?php echo e($setting->width ?? 'N/A'); ?>x<?php echo e($setting->depth ?? 'N/A'); ?></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Levels Configured:</strong>
                                                <span class="badge bg-primary"><?php echo e($levelCount); ?></span>
                                            </div>
                                            <?php if($levelCount > 0): ?>
                                                <div class="mb-2">
                                                    <strong>Levels:</strong>
                                                    <div class="mt-1">
                                                        <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <small class="d-block">
                                                                Level <?php echo e($level); ?>: <?php echo e($setting->getFormattedLevelConfig($level)); ?>

                                                            </small>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if($levelCount === 0 && $type !== 'direct_referral'): ?>
                                            <div class="alert alert-warning alert-sm">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                No levels configured
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer">
                                        <a href="<?php echo e(route('admin.bonus.edit', $type)); ?>" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-edit me-1"></i>
                                            Configure <?php echo e($name); ?>

                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Information Card -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle me-2"></i>
                                        How Bonus System Works
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6>Direct Referral</h6>
                                            <p class="small text-muted">
                                                Always paid when someone you referred makes a purchase, regardless of invoice status.
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Unilevel</h6>
                                            <p class="small text-muted">
                                                Paid to your downline network based on configured levels. Requires active invoice.
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Matrix</h6>
                                            <p class="small text-muted">
                                                Paid based on matrix structure with defined width and depth. Requires active invoice.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/bonus/index.blade.php ENDPATH**/ ?>