

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
                            <?php echo session('error'); ?>

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
                                                    <a href="<?php echo e(route('admin.plans.show', $plan)); ?>" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('admin.plans.edit', $plan)); ?>" 
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="Edit Plan">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-<?php echo e($plan->status ? 'warning' : 'success'); ?> btn-sm toggle-status"
                                                            data-plan-id="<?php echo e($plan->id); ?>"
                                                            title="<?php echo e($plan->status ? 'Deactivate' : 'Activate'); ?> Plan">
                                                        <i class="bi bi-<?php echo e($plan->status ? 'pause-circle' : 'play-circle'); ?>"></i>
                                                    </button>
                                                    
                                                    <!-- Delete Options -->
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-outline-danger btn-sm dropdown-toggle" 
                                                                data-bs-toggle="dropdown" title="Delete Options">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="#" 
                                                                   onclick="deletePlan(<?php echo e($plan->id); ?>, '<?php echo e($plan->title); ?>')">
                                                                    <i class="bi bi-trash me-2"></i>Delete Plan
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-warning" href="#" 
                                                                   onclick="confirmDelete(<?php echo e($plan->id); ?>, '<?php echo e($plan->title); ?>')">
                                                                    <i class="bi bi-exclamation-triangle me-2"></i>Delete with Options
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="#" 
                                                                   onclick="forceDelete(<?php echo e($plan->id); ?>, '<?php echo e($plan->title); ?>')">
                                                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Force Delete All
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
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

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status functionality
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const planId = this.getAttribute('data-plan-id');
            const originalIcon = this.querySelector('i');
            const originalClass = this.className;
            
            // Show loading state
            this.disabled = true;
            originalIcon.className = 'fas fa-spinner fa-spin';
            
            fetch(`/admin/plans/${planId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    if (data.status) {
                        this.className = originalClass.replace('outline-success', 'outline-warning');
                        this.title = 'Deactivate Plan';
                        originalIcon.className = 'fas fa-pause';
                    } else {
                        this.className = originalClass.replace('outline-warning', 'outline-success');
                        this.title = 'Activate Plan';
                        originalIcon.className = 'fas fa-play';
                    }
                    
                    // Update status badge in the same row
                    const row = this.closest('tr');
                    const statusBadge = row.querySelector('.badge');
                    if (data.status) {
                        statusBadge.className = 'badge bg-success';
                        statusBadge.textContent = 'Active';
                    } else {
                        statusBadge.className = 'badge bg-danger';
                        statusBadge.textContent = 'Inactive';
                    }
                    
                    // Show success message
                    showAlert('Plan status updated successfully!', 'success');
                } else {
                    showAlert('Failed to update plan status', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error updating plan status', 'danger');
            })
            .finally(() => {
                this.disabled = false;
            });
        });
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

// Delete functions
function deletePlan(planId, planTitle) {
    if (confirm(`Are you sure you want to delete the plan "${planTitle}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/plans/${planId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmDelete(planId, planTitle) {
    window.location.href = `/admin/plans/${planId}/confirm-delete`;
}

function forceDelete(planId, planTitle) {
    if (confirm(`⚠️ WARNING: This will permanently delete the plan "${planTitle}" and ALL related records (invoices, sales, products). This action cannot be undone!\n\nAre you absolutely sure you want to continue?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/plans/${planId}/force-delete`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/plans/index.blade.php ENDPATH**/ ?>