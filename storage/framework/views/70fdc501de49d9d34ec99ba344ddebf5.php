

<?php $__env->startSection('title', 'Plans Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-box me-2"></i>
                        Plans Management
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.plans.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Create New Plan
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

                    <?php if($plans->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Direct Bonus</th>
                                        <th>Commissions</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <?php if($plan->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $plan->image)); ?>" 
                                                         alt="<?php echo e($plan->title); ?>" 
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
                                                    <strong><?php echo e($plan->title); ?></strong>
                                                    <?php if($plan->course): ?>
                                                        <br><small class="text-muted">Course: <?php echo e($plan->course->title); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo e($plan->type === 'digital' ? 'info' : ($plan->type === 'physical' ? 'warning' : 'success')); ?>">
                                                    <?php echo e(ucfirst($plan->type)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>R$ <?php echo e(number_format($plan->sale_price, 2, ',', '.')); ?></strong>
                                                    <?php if($plan->cost_price): ?>
                                                        <br><small class="text-muted">Cost: R$ <?php echo e(number_format($plan->cost_price, 2, ',', '.')); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($plan->direct_bonus_enabled): ?>
                                                    <span class="badge bg-success">
                                                        <?php echo e($plan->getFormattedDirectReferralBonusAttribute()); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Disabled</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $commissionSummary = $plan->getCommissionSummary();
                                                ?>
                                                <?php if(!empty($commissionSummary)): ?>
                                                    <div class="small">
                                                        <?php $__currentLoopData = $commissionSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div>
                                                                <strong><?php echo e(ucfirst(str_replace('_', ' ', $type))); ?>:</strong> <?php echo e($value); ?>

                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">Not configured</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo e($plan->status ? 'bg-success' : 'bg-danger'); ?>">
                                                    <?php echo e($plan->status ? 'Active' : 'Inactive'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('admin.plans.edit', $plan)); ?>" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('admin.plans.destroy', $plan)); ?>" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
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
                            <?php echo e($plans->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No plans found</h5>
                            <p class="text-muted">Create your first plan to get started.</p>
                            <a href="<?php echo e(route('admin.plans.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Create New Plan
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/plans/index.blade.php ENDPATH**/ ?>