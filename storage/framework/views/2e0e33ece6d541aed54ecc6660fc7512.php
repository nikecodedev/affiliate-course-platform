<?php $__env->startSection('title', 'Invoice Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-receipt me-2"></i>
                        Invoice Management
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.invoices.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus me-1"></i>
                            Create Invoice
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('admin.invoices.index')); ?>" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?php echo e(request('search')); ?>" placeholder="Invoice #, User, Plan...">
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                                <option value="confirmed" <?php echo e(request('status') == 'confirmed' ? 'selected' : ''); ?>>Confirmed</option>
                                <option value="refunded" <?php echo e(request('status') == 'refunded' ? 'selected' : ''); ?>>Refunded</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="user_id" class="form-label">User</label>
                            <select class="form-select" id="user_id" name="user_id">
                                <option value="">All Users</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="plan_id" class="form-label">Plan</label>
                            <select class="form-select" id="plan_id" name="plan_id">
                                <option value="">All Plans</option>
                                <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($plan->id); ?>" <?php echo e(request('plan_id') == $plan->id ? 'selected' : ''); ?>>
                                        <?php echo e($plan->title); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="<?php echo e(request('date_from')); ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="<?php echo e(request('date_to')); ?>">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search me-1"></i>
                                Filter
                            </button>
                            <a href="<?php echo e(route('admin.invoices.index')); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Clear
                            </a>
                        </div>
                    </form>

                    <!-- Invoices Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>User</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($invoice->invoice_number); ?></strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo e($invoice->user->name); ?></strong><br>
                                                <small class="text-muted"><?php echo e($invoice->user->email); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo e($invoice->plan->title); ?></strong><br>
                                                <small class="text-muted"><?php echo e(ucfirst($invoice->plan->type)); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>R$ <?php echo e(number_format($invoice->final_amount, 2, ',', '.')); ?></strong>
                                                <?php if($invoice->discount_amount > 0): ?>
                                                    <br><small class="text-success">-R$ <?php echo e(number_format($invoice->discount_amount, 2, ',', '.')); ?> discount</small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo e($invoice->getStatusBadgeClass()); ?>">
                                                <?php echo e($invoice->getStatusText()); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($invoice->payment_method): ?>
                                                <span class="badge bg-info"><?php echo e(ucfirst(str_replace('_', ' ', $invoice->payment_method))); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?php echo e($invoice->created_at->format('M d, Y H:i')); ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" 
                                                   class="btn btn-outline-info btn-sm" 
                                                   title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.invoices.edit', $invoice)); ?>" 
                                                   class="btn btn-outline-primary btn-sm"
                                                   title="Edit Invoice">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                <?php if($invoice->canBePaid()): ?>
                                                    <button type="button" class="btn btn-outline-success btn-sm" 
                                                            onclick="markAsPaid(<?php echo e($invoice->id); ?>)" 
                                                            title="Mark as Paid">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <?php if($invoice->canBeConfirmed()): ?>
                                                    <form method="POST" action="<?php echo e(route('admin.invoices.mark-confirmed', $invoice)); ?>" 
                                                          style="display: inline-block;" 
                                                          onsubmit="return confirm('Are you sure you want to confirm this invoice? This action is irreversible.')">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-outline-success btn-sm" 
                                                                title="Confirm Invoice">
                                                            <i class="bi bi-check2-circle"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                
                                                <?php if($invoice->canBeRefunded()): ?>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                            onclick="markAsRefunded(<?php echo e($invoice->id); ?>)" 
                                                            title="Refund Invoice">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-receipt display-6 d-block mb-2"></i>
                                                <h5>No Invoices Found</h5>
                                                <p>There are no invoices to display at the moment.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($invoices->hasPages()): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo e($invoices->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Paid Modal -->
<div class="modal fade" id="markPaidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="markPaidForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Mark Invoice as Paid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="gateway">Payment Gateway</option>
                            <option value="manual">Manual Entry</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_reference" class="form-label">Payment Reference</label>
                        <input type="text" class="form-control" id="payment_reference" name="payment_reference" 
                               placeholder="Transaction ID, Reference Number, etc.">
                    </div>
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">Payment Proof</label>
                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" 
                               accept=".jpg,.jpeg,.png,.pdf">
                        <div class="form-text">Upload receipt or proof of payment (optional)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Mark as Paid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mark as Refunded Modal -->
<div class="modal fade" id="markRefundedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="markRefundedForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Refund Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action will refund the invoice and automatically reverse any bonuses/commissions paid for this sale.
                    </div>
                    <div class="mb-3">
                        <label for="refund_reason" class="form-label">Refund Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="refund_reason" name="refund_reason" rows="3" 
                                  placeholder="Please provide a reason for the refund..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Refund Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function markAsPaid(invoiceId) {
    const form = document.getElementById('markPaidForm');
    form.action = `/admin/invoices/${invoiceId}/mark-paid`;
    
    const modal = new bootstrap.Modal(document.getElementById('markPaidModal'));
    modal.show();
}

function markAsRefunded(invoiceId) {
    const form = document.getElementById('markRefundedForm');
    form.action = `/admin/invoices/${invoiceId}/mark-refunded`;
    
    const modal = new bootstrap.Modal(document.getElementById('markRefundedModal'));
    modal.show();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/invoices/index.blade.php ENDPATH**/ ?>