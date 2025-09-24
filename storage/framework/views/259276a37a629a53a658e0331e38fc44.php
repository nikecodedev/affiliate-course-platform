

<?php $__env->startSection('title', 'Plan Details: ' . $plan->title); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye me-2"></i>
                        Plan Details: <?php echo e($plan->title); ?>

                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.plans.edit', $plan)); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Edit Plan
                        </a>
                        <a href="<?php echo e(route('admin.plans.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Plans
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Plan Image -->
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <?php if($plan->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $plan->image)); ?>" 
                                         alt="<?php echo e($plan->title); ?>" 
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

                        <!-- Plan Information -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Basic Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Title:</strong></td>
                                            <td><?php echo e($plan->title); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Type:</strong></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($plan->type === 'digital' ? 'info' : ($plan->type === 'physical' ? 'warning' : 'success')); ?>">
                                                    <?php echo e(ucfirst($plan->type)); ?>

                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge <?php echo e($plan->status ? 'bg-success' : 'bg-danger'); ?>">
                                                    <?php echo e($plan->status ? 'Active' : 'Inactive'); ?>

                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Course:</strong></td>
                                            <td>
                                                <?php if($plan->course): ?>
                                                    <?php echo e($plan->course->title); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">No course assigned</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>External URL:</strong></td>
                                            <td>
                                                <?php if($plan->external_url): ?>
                                                    <a href="<?php echo e($plan->external_url); ?>" target="_blank" class="text-decoration-none">
                                                        <?php echo e($plan->external_url); ?> <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">No external URL</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Pricing</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Sale Price:</strong></td>
                                            <td><span class="h5 text-success">R$ <?php echo e(number_format($plan->sale_price, 2, ',', '.')); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cost Price:</strong></td>
                                            <td>
                                                <?php if($plan->cost_price): ?>
                                                    R$ <?php echo e(number_format($plan->cost_price, 2, ',', '.')); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">Not set</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Profit Margin:</strong></td>
                                            <td>
                                                <?php if($plan->cost_price): ?>
                                                    <?php
                                                        $margin = (($plan->sale_price - $plan->cost_price) / $plan->sale_price) * 100;
                                                    ?>
                                                    <span class="text-<?php echo e($margin > 0 ? 'success' : 'danger'); ?>">
                                                        <?php echo e(number_format($margin, 1)); ?>%
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <?php if($plan->description): ?>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="text-primary mb-3">Description</h5>
                                        <p class="text-muted"><?php echo e($plan->description); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Commission Configuration -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-percentage me-2"></i>
                                Commission Configuration
                            </h5>
                        </div>

                        <!-- Direct Referral Bonus -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-handshake me-2"></i>
                                        Direct Referral Bonus
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php if($plan->direct_bonus_enabled): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="fw-bold"><?php echo e($plan->getFormattedDirectReferralBonusAttribute()); ?></span>
                                            <span class="ms-2 badge bg-info"><?php echo e(ucfirst($plan->direct_bonus_mode)); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times-circle text-danger me-2"></i>
                                            <span class="text-muted">Disabled</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Profit Sharing -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-share-alt me-2"></i>
                                        Profit Sharing
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php if($plan->commission_profit_sharing): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-percentage text-primary me-2"></i>
                                            <span class="fw-bold"><?php echo e($plan->commission_profit_sharing); ?>%</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times-circle text-danger me-2"></i>
                                            <span class="text-muted">Not configured</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Unilevel Commission -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-sitemap me-2"></i>
                                        Unilevel Commission
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php
                                        $unilevelConfig = $plan->getUnilevelConfig();
                                    ?>
                                    <?php if(!empty($unilevelConfig)): ?>
                                        <div class="row">
                                            <?php $__currentLoopData = $unilevelConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-6 mb-2">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Level <?php echo e($level); ?>:</span>
                                                        <span class="fw-bold">
                                                            <?php if($config['mode'] === 'percentage'): ?>
                                                                <?php echo e($config['value']); ?>%
                                                            <?php else: ?>
                                                                R$ <?php echo e(number_format($config['value'], 2, ',', '.')); ?>

                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times-circle text-danger me-2"></i>
                                            <span class="text-muted">Not configured</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Matrix Commission -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-th me-2"></i>
                                        Matrix Commission
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php
                                        $matrixConfig = $plan->getMatrixConfig();
                                    ?>
                                    <?php if(!empty($matrixConfig['levels'])): ?>
                                        <div class="mb-2">
                                            <strong>Matrix Size:</strong> 
                                            <span class="badge bg-warning"><?php echo e($matrixConfig['width']); ?>x<?php echo e($matrixConfig['depth']); ?></span>
                                        </div>
                                        <div class="row">
                                            <?php $__currentLoopData = $matrixConfig['levels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-6 mb-2">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Level <?php echo e($level); ?>:</span>
                                                        <span class="fw-bold"><?php echo e($value); ?>%</span>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times-circle text-danger me-2"></i>
                                            <span class="text-muted">Not configured</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i>
                                Plan Statistics
                            </h5>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-shopping-cart fa-2x text-primary mb-2"></i>
                                    <h5 class="card-title"><?php echo e($plan->invoices()->count()); ?></h5>
                                    <p class="card-text text-muted">Total Sales</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                                    <h5 class="card-title">R$ <?php echo e(number_format($plan->invoices()->sum('amount'), 2, ',', '.')); ?></h5>
                                    <p class="card-text text-muted">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-calendar fa-2x text-info mb-2"></i>
                                    <h5 class="card-title"><?php echo e($plan->created_at->format('M d, Y')); ?></h5>
                                    <p class="card-text text-muted">Created</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                    <h5 class="card-title"><?php echo e($plan->updated_at->diffForHumans()); ?></h5>
                                    <p class="card-text text-muted">Last Updated</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?php echo e(route('admin.plans.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Back to Plans
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?php echo e(route('admin.plans.edit', $plan)); ?>" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>
                                Edit Plan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/plans/show.blade.php ENDPATH**/ ?>