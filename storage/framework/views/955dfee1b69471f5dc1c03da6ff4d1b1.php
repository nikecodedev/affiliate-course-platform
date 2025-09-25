

<?php $__env->startSection('title', 'Create New Invoice'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-plus me-2"></i>
                        Create New Invoice
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.invoices.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Invoices
                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('admin.invoices.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
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
                                                                    <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
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
                                                                    <?php echo e(old('plan_id') == $plan->id ? 'selected' : ''); ?>>
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
                                                               value="<?php echo e(old('amount')); ?>" 
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
                                                               value="<?php echo e(old('discount_amount', 0)); ?>" 
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
                                                               placeholder="0.00">
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
                                                      placeholder="Additional notes or comments..."><?php echo e(old('notes')); ?></textarea>
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
                                            <span id="summary_amount">R$ 0,00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Discount:</span>
                                            <span id="summary_discount" class="text-success">-R$ 0,00</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <strong>Final Amount:</strong>
                                            <strong id="summary_final">R$ 0,00</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Next Steps -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-lightbulb me-2"></i>
                                            Next Steps
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ol class="small">
                                            <li>Create the invoice</li>
                                            <li>Customer receives invoice</li>
                                            <li>Customer makes payment</li>
                                            <li>Mark as paid when payment received</li>
                                            <li>Confirm invoice to finalize</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?php echo e(route('admin.invoices.index')); ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>
                                    Create Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/invoices/create.blade.php ENDPATH**/ ?>