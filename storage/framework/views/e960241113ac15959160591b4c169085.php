

<?php $__env->startSection('title', 'Edit ' . $bonusSetting->getTypeDisplayName() . ' Bonus'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Configure <?php echo e($bonusSetting->getTypeDisplayName()); ?> Bonus
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.bonus.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Bonus Settings
                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('admin.bonus.update', $bonusSetting->bonus_type)); ?>" method="POST" id="bonusForm">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Basic Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-cog me-2"></i>
                                    Basic Settings
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" <?php echo e($bonusSetting->is_active ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Activate <?php echo e($bonusSetting->getTypeDisplayName()); ?> Bonus</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <?php if($bonusSetting->bonus_type === 'direct_referral'): ?>
                            <!-- Direct Referral Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-handshake me-2"></i>
                                        Direct Referral Configuration
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="payment_mode" class="form-label">Payment Mode</label>
                                        <select class="form-control <?php $__errorArgs = ['payment_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                id="payment_mode" name="payment_mode" required>
                                            <option value="">Select Payment Mode</option>
                                            <?php $__currentLoopData = $paymentModes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($mode); ?>" <?php echo e(old('payment_mode', $bonusSetting->payment_mode) == $mode ? 'selected' : ''); ?>>
                                                    <?php echo e($label); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['payment_mode'];
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
                                    <div class="form-group mb-3">
                                        <label for="value" class="form-label">Bonus Value</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control <?php $__errorArgs = ['value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="value" name="value" 
                                                   value="<?php echo e(old('value', $bonusSetting->getLevelConfig(1)['value'] ?? '')); ?>" 
                                                   step="0.01" min="0" required>
                                            <span class="input-group-text" id="value-unit">R$</span>
                                        </div>
                                        <?php $__errorArgs = ['value'];
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

                        <?php elseif($bonusSetting->bonus_type === 'unilevel'): ?>
                            <!-- Unilevel Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-sitemap me-2"></i>
                                        Unilevel Configuration
                                    </h5>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Configure bonus amounts for each level in your downline network.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="levels-container">
                                        <?php
                                            $levels = $bonusSetting->getConfiguredLevels();
                                        ?>
                                        <?php if(count($levels) > 0): ?>
                                            <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="card mb-3 level-item" data-level="<?php echo e($level); ?>">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <label class="form-label">Level</label>
                                                                <input type="number" class="form-control" name="levels[<?php echo e($level); ?>][level]" 
                                                                       value="<?php echo e($level); ?>" min="1" max="20" required readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">Mode</label>
                                                                <select class="form-control" name="levels[<?php echo e($level); ?>][mode]" required>
                                                                    <option value="fixed" <?php echo e($config['mode'] === 'fixed' ? 'selected' : ''); ?>>Fixed Amount</option>
                                                                    <option value="percentage" <?php echo e($config['mode'] === 'percentage' ? 'selected' : ''); ?>>Percentage</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Value</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" name="levels[<?php echo e($level); ?>][value]" 
                                                                           value="<?php echo e($config['value']); ?>" step="0.01" min="0" required>
                                                                    <span class="input-group-text level-unit">R$</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">&nbsp;</label>
                                                                <button type="button" class="btn btn-outline-danger btn-block remove-level" 
                                                                        style="display: block; width: 100%;">
                                                                    <i class="fas fa-trash"></i> Remove
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" id="add-level-btn">
                                        <i class="fas fa-plus me-1"></i>
                                        Add Level
                                    </button>
                                </div>
                            </div>

                        <?php elseif($bonusSetting->bonus_type === 'matrix'): ?>
                            <!-- Matrix Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-th me-2"></i>
                                        Matrix Configuration
                                    </h5>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Configure matrix dimensions and bonus amounts for each level.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="width" class="form-label">Matrix Width</label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="width" name="width" 
                                               value="<?php echo e(old('width', $bonusSetting->width ?? 2)); ?>" 
                                               min="1" max="10" required>
                                        <div class="form-text">Number of positions per level</div>
                                        <?php $__errorArgs = ['width'];
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
                                    <div class="form-group mb-3">
                                        <label for="depth" class="form-label">Matrix Depth</label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['depth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="depth" name="depth" 
                                               value="<?php echo e(old('depth', $bonusSetting->depth ?? 5)); ?>" 
                                               min="1" max="20" required>
                                        <div class="form-text">Number of levels deep</div>
                                        <?php $__errorArgs = ['depth'];
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
                                <div class="col-12">
                                    <h6 class="mt-3 mb-3">Matrix Levels Configuration</h6>
                                    <div id="levels-container">
                                        <?php
                                            $levels = $bonusSetting->getConfiguredLevels();
                                        ?>
                                        <?php if(count($levels) > 0): ?>
                                            <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="card mb-3 level-item" data-level="<?php echo e($level); ?>">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <label class="form-label">Level</label>
                                                                <input type="number" class="form-control" name="levels[<?php echo e($level); ?>][level]" 
                                                                       value="<?php echo e($level); ?>" min="1" max="20" required readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">Mode</label>
                                                                <select class="form-control" name="levels[<?php echo e($level); ?>][mode]" required>
                                                                    <option value="fixed" <?php echo e($config['mode'] === 'fixed' ? 'selected' : ''); ?>>Fixed Amount</option>
                                                                    <option value="percentage" <?php echo e($config['mode'] === 'percentage' ? 'selected' : ''); ?>>Percentage</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Value</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" name="levels[<?php echo e($level); ?>][value]" 
                                                                           value="<?php echo e($config['value']); ?>" step="0.01" min="0" required>
                                                                    <span class="input-group-text level-unit">R$</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">&nbsp;</label>
                                                                <button type="button" class="btn btn-outline-danger btn-block remove-level" 
                                                                        style="display: block; width: 100%;">
                                                                    <i class="fas fa-trash"></i> Remove
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" id="add-level-btn">
                                        <i class="fas fa-plus me-1"></i>
                                        Add Level
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?php echo e(route('admin.bonus.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Save Configuration
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
    let levelCount = <?php echo e(count($bonusSetting->getConfiguredLevels())); ?>;
    const levelsContainer = document.getElementById('levels-container');
    const addLevelBtn = document.getElementById('add-level-btn');
    const paymentModeSelect = document.getElementById('payment_mode');
    const valueInput = document.getElementById('value');
    const valueUnit = document.getElementById('value-unit');

    // Add level button click
    if (addLevelBtn) {
        addLevelBtn.addEventListener('click', addLevel);
    }

    // Payment mode change for direct referral
    if (paymentModeSelect) {
        paymentModeSelect.addEventListener('change', function() {
            updateValueUnit();
        });
    }

    // Add remove event listeners to existing levels
    document.querySelectorAll('.remove-level').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.level-item').remove();
            levelCount--;
        });
    });

    function addLevel() {
        if (levelCount >= 20) {
            alert('Maximum 20 levels allowed');
            return;
        }

        levelCount++;
        const levelHtml = `
            <div class="card mb-3 level-item" data-level="${levelCount}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-1">
                            <label class="form-label">Level</label>
                            <input type="number" class="form-control" name="levels[${levelCount}][level]" 
                                   value="${levelCount}" min="1" max="20" required readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Mode</label>
                            <select class="form-control" name="levels[${levelCount}][mode]" required>
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Value</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="levels[${levelCount}][value]" 
                                       step="0.01" min="0" required>
                                <span class="input-group-text level-unit">R$</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger btn-block remove-level" 
                                    style="display: block; width: 100%;">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        levelsContainer.insertAdjacentHTML('beforeend', levelHtml);
        
        // Add remove event listener
        const removeBtn = levelsContainer.querySelector(`.level-item[data-level="${levelCount}"] .remove-level`);
        removeBtn.addEventListener('click', function() {
            this.closest('.level-item').remove();
            levelCount--;
        });

        // Add mode change listener
        const modeSelect = levelsContainer.querySelector(`.level-item[data-level="${levelCount}"] select[name*="[mode]"]`);
        modeSelect.addEventListener('change', function() {
            updateLevelUnit(this);
        });
    }

    function updateValueUnit() {
        if (paymentModeSelect && valueUnit) {
            if (paymentModeSelect.value === 'percentage') {
                valueUnit.textContent = '%';
            } else {
                valueUnit.textContent = 'R$';
            }
        }
    }

    function updateLevelUnit(selectElement) {
        const unitElement = selectElement.closest('.row').querySelector('.level-unit');
        if (unitElement) {
            if (selectElement.value === 'percentage') {
                unitElement.textContent = '%';
            } else {
                unitElement.textContent = 'R$';
            }
        }
    }

    // Add mode change listeners to existing levels
    document.querySelectorAll('select[name*="[mode]"]').forEach(select => {
        select.addEventListener('change', function() {
            updateLevelUnit(this);
        });
    });

    // Form submission validation
    document.getElementById('bonusForm').addEventListener('submit', function(e) {
        const levels = levelsContainer.querySelectorAll('.level-item');
        if (levels.length === 0 && '<?php echo e($bonusSetting->bonus_type); ?>' !== 'direct_referral') {
            e.preventDefault();
            alert('Please add at least one bonus level');
            return;
        }
    });

    // Initialize value unit
    updateValueUnit();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/bonus/edit.blade.php ENDPATH**/ ?>