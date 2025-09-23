

<?php $__env->startSection('title', 'Users Management'); ?>
<?php $__env->startSection('page-title', 'Users Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>Users Management
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="filterUsers('all')">
                            All Users
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="filterUsers('affiliates')">
                            Affiliates
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="filterUsers('customers')">
                            Customers
                        </button>
                    </div>
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
                    <table class="table table-hover" id="usersTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Affiliate Code</th>
                                <th>Sales</th>
                                <th>Commission</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#<?php echo e($user->id); ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?php echo e($user->name); ?></div>
                                                <small class="text-muted"><?php echo e($user->phone ?? 'No phone'); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted"><?php echo e($user->email); ?></span>
                                    </td>
                                    <td>
                                        <?php if($user->is_affiliate): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-person-check me-1"></i>Affiliate
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-info">
                                                <i class="bi bi-person me-1"></i>Customer
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($user->affiliate_code): ?>
                                            <span class="badge bg-warning"><?php echo e($user->affiliate_code); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="fw-semibold text-success"><?php echo e($user->affiliate_sales_count ?? 0); ?></div>
                                            <small class="text-muted">Sales</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="fw-semibold text-warning">$<?php echo e(number_format($user->total_commission_earned ?? 0, 2)); ?></div>
                                            <small class="text-muted">Earned</small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($user->is_active): ?>
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
                                            <?php echo e($user->created_at->format('M d, Y')); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewUser(<?php echo e($user->id); ?>)" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            
                                            <form method="POST" action="<?php echo e(route('admin.users.toggle.affiliate', $user)); ?>" 
                                                  style="display: inline-block;" 
                                                  onsubmit="return confirm('Are you sure you want to <?php echo e($user->is_affiliate ? 'remove affiliate status from' : 'make'); ?> this user?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-<?php echo e($user->is_affiliate ? 'danger' : 'success'); ?>" 
                                                        title="<?php echo e($user->is_affiliate ? 'Remove Affiliate' : 'Make Affiliate'); ?>">
                                                    <i class="bi bi-<?php echo e($user->is_affiliate ? 'person-x' : 'person-plus'); ?>"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-people display-6 d-block mb-2"></i>
                                            <h5>No Users Found</h5>
                                            <p>There are no users registered yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($users->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($users->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person me-2"></i>User Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userModalBody">
                <!-- User details will be loaded here -->
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function filterUsers(type) {
    // Remove active class from all buttons
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    // Filter table rows
    const rows = document.querySelectorAll('#usersTable tbody tr');
    rows.forEach(row => {
        const typeBadge = row.querySelector('.badge');
        const rowType = typeBadge ? typeBadge.textContent.toLowerCase().trim() : '';
        
        if (type === 'all' || 
            (type === 'affiliates' && rowType.includes('affiliate')) ||
            (type === 'customers' && rowType.includes('customer'))) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function viewUser(userId) {
    // Here you would typically load user details via AJAX
    document.getElementById('userModalBody').innerHTML = `
        <div class="text-center py-4">
            <i class="bi bi-hourglass-split display-4 text-muted mb-3"></i>
            <h5>Loading User Details...</h5>
            <p class="text-muted">User ID: ${userId}</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/users/index.blade.php ENDPATH**/ ?>