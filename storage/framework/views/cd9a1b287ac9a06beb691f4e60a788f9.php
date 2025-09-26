<?php $__env->startSection('title', 'Edit Invoice - ' . $invoice->invoice_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Invoice - <?php echo e($invoice->invoice_number); ?>

                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Invoice
                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('admin.invoices.update', $invoice)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <!-- Invoice Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Invoice Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="user_id" class="form-label">
                                                        Customer <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                            id="user_id" name="user_id" required>
                                                        <option value="">Select Customer</option>
                                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($user->id); ?>" 
                                                                    <?php echo e(old('user_id', $invoice->user_id) == $user->id ? 'selected' : ''); ?>>
                                                                <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="plan_id" class="form-label">
                                                        Plan/Product <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select <?php $__errorArgs = ['plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                            id="plan_id" name="plan_id" required>
                                                        <option value="">Select Plan/Product</option>
                                                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($plan->id); ?>" 
                                                                    data-price="<?php echo e($plan->sale_price); ?>"
                                                                    <?php echo e(old('plan_id', $invoice->plan_id) == $plan->id ? 'selected' : ''); ?>>
                                                                <?php echo e($plan->title); ?> - R$ <?php echo e(number_format($plan->sale_price, 2, ',', '.')); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    <?php $__errorArgs = ['plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="amount" class="form-label">
                                                        Amount <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="number" 
                                                               class="form-control <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                               id="amount" 
                                                               name="amount" 
                                                               value="<?php echo e(old('amount', $invoice->amount)); ?>" 
                                                               step="0.01"
                                                               min="0"
                                                               placeholder="0.00"
                                                               required>
                                                    </div>
                                                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="discount_amount" class="form-label">
                                                        Discount Amount
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="number" 
                                                               class="form-control <?php $__errorArgs = ['discount_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                               id="discount_amount" 
                                                               name="discount_amount" 
                                                               value="<?php echo e(old('discount_amount', $invoice->discount_amount)); ?>" 
                                                               step="0.01"
                                                               min="0"
                                                               placeholder="0.00">
                                                    </div>
                                                    <?php $__errorArgs = ['discount_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Final Amount</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="text" 
                                                               class="form-control" 
                                                               id="final_amount" 
                                                               readonly
                                                               value="<?php echo e(number_format($invoice->final_amount, 2, '.', '')); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                      id="notes" 
                                                      name="notes" 
                                                      rows="3" 
                                                      placeholder="Additional notes or comments..."><?php echo e(old('notes', $invoice->notes)); ?></textarea>
                                            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-calculator me-2"></i>
                                            Invoice Summary
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Amount:</span>
                                            <span id="summary_amount">R$ <?php echo e(number_format($invoice->amount, 2, ',', '.')); ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Discount:</span>
                                            <span id="summary_discount" class="text-success">-R$ <?php echo e(number_format($invoice->discount_amount, 2, ',', '.')); ?></span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <strong>Final Amount:</strong>
                                            <strong id="summary_final">R$ <?php echo e(number_format($invoice->final_amount, 2, ',', '.')); ?></strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Status -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Current Status
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <span class="badge <?php echo e($invoice->getStatusBadgeClass()); ?> fs-6">
                                                <?php echo e($invoice->getStatusText()); ?>

                                            </span>
                                        </div>
                                        
                                        <?php if($invoice->paid_at): ?>
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <strong>Paid:</strong> <?php echo e($invoice->paid_at->format('M d, Y H:i')); ?>

                                                </small>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($invoice->confirmed_at): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Confirmed:</strong> <?php echo e($invoice->confirmed_at->format('M d, Y H:i')); ?>

                                                </small>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($invoice->refunded_at): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Refunded:</strong> <?php echo e($invoice->refunded_at->format('M d, Y H:i')); ?>

                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-gear me-2"></i>
                                            Quick Actions
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if($invoice->canBePaid()): ?>
                                            <button type="button" class="btn btn-success w-100 mb-2" 
                                                    onclick="markAsPaid(<?php echo e($invoice->id); ?>)">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Mark as Paid
                                            </button>
                                        <?php endif; ?>

                                        <?php if($invoice->canBeConfirmed()): ?>
                                            <form method="POST" action="<?php echo e(route('admin.invoices.mark-confirmed', $invoice)); ?>" 
                                                  onsubmit="return confirm('Are you sure you want to confirm this invoice? This action is irreversible.')">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-success w-100 mb-2">
                                                    <i class="bi bi-check2-circle me-1"></i>
                                                    Confirm Invoice
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if($invoice->canBeRefunded()): ?>
                                            <button type="button" class="btn btn-danger w-100 mb-2" 
                                                    onclick="markAsRefunded(<?php echo e($invoice->id); ?>)">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                Refund Invoice
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>
                                    Update Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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
document.addEventListener('DOMContentLoaded', function() {
    const planSelect = document.getElementById('plan_id');
    const amountInput = document.getElementById('amount');
    const discountInput = document.getElementById('discount_amount');
    const finalAmountInput = document.getElementById('final_amount');
    
    const summaryAmount = document.getElementById('summary_amount');
    const summaryDiscount = document.getElementById('summary_discount');
    const summaryFinal = document.getElementById('summary_final');

    function updateCalculations() {
        const amount = parseFloat(amountInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const final = amount - discount;

        finalAmountInput.value = final.toFixed(2);
        
        // Update summary
        summaryAmount.textContent = 'R$ ' + amount.toLocaleString('pt-BR', {minimumFractionDigits: 2});
        summaryDiscount.textContent = '-R$ ' + discount.toLocaleString('pt-BR', {minimumFractionDigits: 2});
        summaryFinal.textContent = 'R$ ' + final.toLocaleString('pt-BR', {minimumFractionDigits: 2});
    }

    // Auto-fill amount when plan is selected
    planSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const price = parseFloat(selectedOption.dataset.price);
            amountInput.value = price.toFixed(2);
            updateCalculations();
        }
    });

    // Update calculations when amount or discount changes
    amountInput.addEventListener('input', updateCalculations);
    discountInput.addEventListener('input', updateCalculations);

    // Initial calculation
    updateCalculations();
});

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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/invoices/edit.blade.php ENDPATH**/ ?>