

<?php $__env->startSection('title', 'Withdrawals Management'); ?>
<?php $__env->startSection('page-title', 'Withdrawals Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-arrow-up-circle me-2"></i>Withdrawals Management
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="filterWithdrawals('all')">
                            All Withdrawals
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="filterWithdrawals('pending')">
                            Pending
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="filterWithdrawals('completed')">
                            Completed
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="filterWithdrawals('rejected')">
                            Rejected
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

                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="status_filter" class="form-label">Status</label>
                        <select class="form-select" id="status_filter" onchange="applyFilters()">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" onchange="applyFilters()">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" onchange="applyFilters()">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                <i class="bi bi-x-circle me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="withdrawalsTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Net Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Request Date</th>
                                <th>Processed Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#<?php echo e($withdrawal->id); ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?php echo e($withdrawal->user->name ?? 'N/A'); ?></div>
                                                <small class="text-muted"><?php echo e($withdrawal->user->email ?? 'N/A'); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-primary">$<?php echo e(number_format($withdrawal->amount, 2)); ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">$<?php echo e(number_format($withdrawal->net_amount ?? $withdrawal->amount, 2)); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo e(ucfirst(str_replace('_', ' ', $withdrawal->payment_method))); ?></span>
                                    </td>
                                    <td>
                                        <?php switch($withdrawal->status):
                                            case ('pending'): ?>
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock me-1"></i>Pending
                                                </span>
                                                <?php break; ?>
                                            <?php case ('completed'): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Completed
                                                </span>
                                                <?php break; ?>
                                            <?php case ('rejected'): ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Rejected
                                                </span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge bg-secondary"><?php echo e(ucfirst($withdrawal->status)); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            <?php echo e($withdrawal->request_date->format('M d, Y')); ?>

                                            <br>
                                            <small><?php echo e($withdrawal->request_date->format('H:i')); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($withdrawal->processed_date): ?>
                                            <div class="text-muted">
                                                <?php echo e($withdrawal->processed_date->format('M d, Y')); ?>

                                                <br>
                                                <small><?php echo e($withdrawal->processed_date->format('H:i')); ?></small>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewWithdrawal(<?php echo e($withdrawal->id); ?>)" 
                                                    title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            
                                            <?php if($withdrawal->status === 'pending'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="processWithdrawal(<?php echo e($withdrawal->id); ?>, 'completed')" 
                                                        title="Approve">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="processWithdrawal(<?php echo e($withdrawal->id); ?>, 'rejected')" 
                                                        title="Reject">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-arrow-up-circle display-6 d-block mb-2"></i>
                                            <h5>No Withdrawals Found</h5>
                                            <p>There are no withdrawal requests at the moment.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($withdrawals->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($withdrawals->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Details Modal -->
<div class="modal fade" id="withdrawalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-arrow-up-circle me-2"></i>Withdrawal Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="withdrawalModalBody">
                <!-- Withdrawal details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Process Withdrawal Modal -->
<div class="modal fade" id="processModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="processModalTitle">
                    <i class="bi bi-check-circle me-2"></i>Process Withdrawal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="processForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="process_status" class="form-label">Status</label>
                        <select class="form-select" id="process_status" name="status" required>
                            <option value="completed">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_reference" class="form-label">Payment Reference</label>
                        <input type="text" class="form-control" id="payment_reference" name="payment_reference">
                        <div class="form-text">Transaction ID or reference number</div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Process Withdrawal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function filterWithdrawals(status) {
    // Remove active class from all buttons
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    // Filter table rows
    const rows = document.querySelectorAll('#withdrawalsTable tbody tr');
    rows.forEach(row => {
        const statusBadge = row.querySelector('.badge');
        const rowStatus = statusBadge ? statusBadge.textContent.toLowerCase().trim() : '';
        
        if (status === 'all' || rowStatus.includes(status)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function applyFilters() {
    const status = document.getElementById('status_filter').value;
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    
    // Here you would typically make an AJAX request to filter the data
    // For now, we'll just show a loading state
    console.log('Applying filters:', { status, dateFrom, dateTo });
}

function clearFilters() {
    document.getElementById('status_filter').value = '';
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    applyFilters();
}

function viewWithdrawal(withdrawalId) {
    // Here you would typically load withdrawal details via AJAX
    document.getElementById('withdrawalModalBody').innerHTML = `
        <div class="text-center py-4">
            <i class="bi bi-hourglass-split display-4 text-muted mb-3"></i>
            <h5>Loading Withdrawal Details...</h5>
            <p class="text-muted">Withdrawal ID: ${withdrawalId}</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('withdrawalModal'));
    modal.show();
}

function processWithdrawal(withdrawalId, status) {
    document.getElementById('processModalTitle').innerHTML = `
        <i class="bi bi-${status === 'completed' ? 'check-circle' : 'x-circle'} me-2"></i>
        ${status === 'completed' ? 'Approve' : 'Reject'} Withdrawal
    `;
    
    document.getElementById('processForm').action = `/admin/financial/withdrawals/${withdrawalId}/process`;
    document.getElementById('process_status').value = status;
    
    const modal = new bootstrap.Modal(document.getElementById('processModal'));
    modal.show();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/financial/withdrawals.blade.php ENDPATH**/ ?>