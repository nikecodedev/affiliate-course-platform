

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
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                            <i class="bi bi-funnel me-1"></i>Filters
                        </button>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-success" onclick="quickFilter('affiliates')">
                                <i class="bi bi-person-check me-1"></i>Affiliates
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="quickFilter('customers')">
                                <i class="bi bi-person me-1"></i>Customers
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="quickFilter('active')">
                                <i class="bi bi-check-circle me-1"></i>Active
                            </button>
                            <button type="button" class="btn btn-outline-warning" onclick="quickFilter('inactive')">
                                <i class="bi bi-x-circle me-1"></i>Inactive
                            </button>
                        </div>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
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

                <!-- Advanced Filters -->
                <div class="collapse" id="filterCollapse">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Advanced Filters</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" id="filterForm">
                                <div class="row g-3">
                                    <!-- Search -->
                                    <div class="col-md-6">
                                        <label for="search" class="form-label">Search</label>
                                        <input type="text" class="form-control" id="search" name="search" 
                                               value="<?php echo e(request('search')); ?>" 
                                               placeholder="Name, email, phone, or document">
                                    </div>
                                    
                                    <!-- User Type -->
                                    <div class="col-md-3">
                                        <label for="type" class="form-label">User Type</label>
                                        <select class="form-select" id="type" name="type">
                                            <option value="">All Types</option>
                                            <option value="affiliates" <?php echo e(request('type') == 'affiliates' ? 'selected' : ''); ?>>Affiliates</option>
                                            <option value="customers" <?php echo e(request('type') == 'customers' ? 'selected' : ''); ?>>Customers</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Status -->
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="">All Status</option>
                                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                                            <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Date Range -->
                                    <div class="col-md-3">
                                        <label for="date_from" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="date_from" name="date_from" 
                                               value="<?php echo e(request('date_from')); ?>">
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="date_to" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to" 
                                               value="<?php echo e(request('date_to')); ?>">
                                    </div>
                                    
                                    <!-- Country -->
                                    <div class="col-md-3">
                                        <label for="country" class="form-label">Country</label>
                                        <select class="form-select" id="country" name="country">
                                            <option value="">All Countries</option>
                                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($country); ?>" <?php echo e(request('country') == $country ? 'selected' : ''); ?>>
                                                    <?php echo e($country); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    
                                    <!-- City -->
                                    <div class="col-md-3">
                                        <label for="city" class="form-label">City</label>
                                        <select class="form-select" id="city" name="city">
                                            <option value="">All Cities</option>
                                            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($city); ?>" <?php echo e(request('city') == $city ? 'selected' : ''); ?>>
                                                    <?php echo e($city); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    
                                    <!-- Affiliate Code -->
                                    <div class="col-md-3">
                                        <label for="affiliate_code" class="form-label">Affiliate Code</label>
                                        <input type="text" class="form-control" id="affiliate_code" name="affiliate_code" 
                                               value="<?php echo e(request('affiliate_code')); ?>" 
                                               placeholder="Enter affiliate code">
                                    </div>
                                    
                                    <!-- Sort Options -->
                                    <div class="col-md-3">
                                        <label for="sort_by" class="form-label">Sort By</label>
                                        <select class="form-select" id="sort_by" name="sort_by">
                                            <option value="created_at" <?php echo e(request('sort_by') == 'created_at' ? 'selected' : ''); ?>>Join Date</option>
                                            <option value="name" <?php echo e(request('sort_by') == 'name' ? 'selected' : ''); ?>>Name</option>
                                            <option value="email" <?php echo e(request('sort_by') == 'email' ? 'selected' : ''); ?>>Email</option>
                                            <option value="is_active" <?php echo e(request('sort_by') == 'is_active' ? 'selected' : ''); ?>>Status</option>
                                            <option value="is_affiliate" <?php echo e(request('sort_by') == 'is_affiliate' ? 'selected' : ''); ?>>Type</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        <select class="form-select" id="sort_order" name="sort_order">
                                            <option value="desc" <?php echo e(request('sort_order') == 'desc' ? 'selected' : ''); ?>>Descending</option>
                                            <option value="asc" <?php echo e(request('sort_order') == 'asc' ? 'selected' : ''); ?>>Ascending</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Filter Buttons -->
                                    <div class="col-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-search me-1"></i>Apply Filters
                                            </button>
                                            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary">
                                                <i class="bi bi-x-circle me-1"></i>Clear Filters
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Active Filters Display -->
                <?php if(request()->hasAny(['search', 'type', 'status', 'date_from', 'date_to', 'country', 'city', 'affiliate_code'])): ?>
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Active Filters:</strong>
                                <?php if(request('search')): ?>
                                    <span class="badge bg-primary me-1">Search: <?php echo e(request('search')); ?></span>
                                <?php endif; ?>
                                <?php if(request('type')): ?>
                                    <span class="badge bg-success me-1">Type: <?php echo e(ucfirst(request('type'))); ?></span>
                                <?php endif; ?>
                                <?php if(request('status')): ?>
                                    <span class="badge bg-info me-1">Status: <?php echo e(ucfirst(request('status'))); ?></span>
                                <?php endif; ?>
                                <?php if(request('date_from') || request('date_to')): ?>
                                    <span class="badge bg-warning me-1">
                                        Date: <?php echo e(request('date_from') ?: 'Start'); ?> - <?php echo e(request('date_to') ?: 'End'); ?>

                                    </span>
                                <?php endif; ?>
                                <?php if(request('country')): ?>
                                    <span class="badge bg-secondary me-1">Country: <?php echo e(request('country')); ?></span>
                                <?php endif; ?>
                                <?php if(request('city')): ?>
                                    <span class="badge bg-secondary me-1">City: <?php echo e(request('city')); ?></span>
                                <?php endif; ?>
                                <?php if(request('affiliate_code')): ?>
                                    <span class="badge bg-dark me-1">Code: <?php echo e(request('affiliate_code')); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-x-circle me-1"></i>Clear All
                            </a>
                        </div>
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
                                            
                                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit User">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            
                                            <form method="POST" action="<?php echo e(route('admin.users.toggle.affiliate', $user)); ?>" 
                                                  style="display: inline-block;" 
                                                  onsubmit="return confirm('Are you sure you want to <?php echo e($user->is_affiliate ? 'remove affiliate status from' : 'make'); ?> this user?')">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-<?php echo e($user->is_affiliate ? 'danger' : 'success'); ?>" 
                                                        title="<?php echo e($user->is_affiliate ? 'Remove Affiliate' : 'Make Affiliate'); ?>">
                                                    <i class="bi bi-<?php echo e($user->is_affiliate ? 'person-x' : 'person-plus'); ?>"></i>
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" 
                                                  style="display: inline-block;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        title="Delete User">
                                                    <i class="bi bi-trash"></i>
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
// Auto-submit form when certain filters change
document.addEventListener('DOMContentLoaded', function() {
    const autoSubmitSelects = ['type', 'status', 'sort_by', 'sort_order'];
    
    autoSubmitSelects.forEach(selectId => {
        const select = document.getElementById(selectId);
        if (select) {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
    });
    
    // Auto-submit search after 500ms delay
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        });
    }
    
    // Date range validation
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');
    
    if (dateFrom && dateTo) {
        dateFrom.addEventListener('change', function() {
            if (dateTo.value && this.value > dateTo.value) {
                dateTo.value = this.value;
            }
        });
        
        dateTo.addEventListener('change', function() {
            if (dateFrom.value && this.value < dateFrom.value) {
                dateFrom.value = this.value;
            }
        });
    }
});

function viewUser(userId) {
    // Load user details via AJAX
    document.getElementById('userModalBody').innerHTML = `
        <div class="text-center py-4">
            <i class="bi bi-hourglass-split display-4 text-muted mb-3"></i>
            <h5>Loading User Details...</h5>
            <p class="text-muted">User ID: ${userId}</p>
        </div>
    `;
    
    // Fetch user details
    fetch(`/admin/users/${userId}`)
        .then(response => response.text())
        .then(html => {
            // Extract the user details from the response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const userDetails = doc.querySelector('.card-body');
            
            if (userDetails) {
                document.getElementById('userModalBody').innerHTML = userDetails.outerHTML;
            } else {
                document.getElementById('userModalBody').innerHTML = `
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-triangle display-4 text-warning mb-3"></i>
                        <h5>Error Loading User Details</h5>
                        <p class="text-muted">Unable to load user information.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading user details:', error);
            document.getElementById('userModalBody').innerHTML = `
                <div class="text-center py-4">
                    <i class="bi bi-exclamation-triangle display-4 text-danger mb-3"></i>
                    <h5>Error Loading User Details</h5>
                    <p class="text-muted">There was an error loading the user information.</p>
                </div>
            `;
        });
    
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}

// Quick filter buttons for common searches
function quickFilter(type) {
    const form = document.getElementById('filterForm');
    
    // Clear existing filters
    form.reset();
    
    // Set the specific filter
    if (type === 'affiliates') {
        document.getElementById('type').value = 'affiliates';
    } else if (type === 'customers') {
        document.getElementById('type').value = 'customers';
    } else if (type === 'active') {
        document.getElementById('status').value = 'active';
    } else if (type === 'inactive') {
        document.getElementById('status').value = 'inactive';
    }
    
    form.submit();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/users/index.blade.php ENDPATH**/ ?>