

<?php $__env->startSection('title', 'Edit Plan: ' . $plan->title); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Edit Plan: <?php echo e($plan->title); ?>

                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.plans.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Plans
                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('admin.plans.update', $plan)); ?>" method="POST" enctype="multipart/form-data" id="planForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
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

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Basic Information
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="title" name="title" value="<?php echo e(old('title', $plan->title)); ?>" required>
                                    <?php $__errorArgs = ['title'];
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
                                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="physical" <?php echo e(old('type', $plan->type) == 'physical' ? 'selected' : ''); ?>>Physical</option>
                                        <option value="digital" <?php echo e(old('type', $plan->type) == 'digital' ? 'selected' : ''); ?>>Digital</option>
                                        <option value="service" <?php echo e(old('type', $plan->type) == 'service' ? 'selected' : ''); ?>>Service</option>
                                    </select>
                                    <?php $__errorArgs = ['type'];
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
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="description" name="description" rows="3"><?php echo e(old('description', $plan->description)); ?></textarea>
                                    <?php $__errorArgs = ['description'];
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
                                    <label for="image" class="form-label">Image</label>
                                    <?php if($plan->image): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo e(asset('storage/' . $plan->image)); ?>" 
                                                 alt="<?php echo e($plan->title); ?>" 
                                                 class="img-thumbnail" 
                                                 style="width: 100px; height: 75px; object-fit: cover;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="image" name="image" accept="image/*">
                                    <div class="form-text">Recommended size: 400x300px</div>
                                    <?php $__errorArgs = ['image'];
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
                                    <label for="external_product_url" class="form-label">External Product URL</label>
                                    <input type="url" class="form-control <?php $__errorArgs = ['external_product_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="external_product_url" name="external_product_url" 
                                           value="<?php echo e(old('external_product_url', $plan->external_product_url)); ?>" 
                                           placeholder="https://downloads.example.com/product.zip">
                                    <?php $__errorArgs = ['external_product_url'];
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
                                    <label for="course_ids" class="form-label">Courses (Multi-select)</label>
                                    <select multiple class="form-control <?php $__errorArgs = ['course_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="course_ids" name="course_ids[]">
                                        <?php
                                            $selectedCourses = collect(old('course_ids', $plan->courses->pluck('id')->toArray()));
                                        ?>
                                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($course->id); ?>" <?php echo e($selectedCourses->contains($course->id) ? 'selected' : ''); ?>>
                                                <?php echo e($course->title); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['course_ids'];
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

                        <!-- Pricing -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-dollar-sign me-2"></i>
                                    Pricing
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sale_price" class="form-label">Sale Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control <?php $__errorArgs = ['sale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="sale_price" name="sale_price" 
                                               value="<?php echo e(old('sale_price', $plan->sale_price)); ?>" 
                                               step="0.01" min="0" required>
                                    </div>
                                    <?php $__errorArgs = ['sale_price'];
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
                                    <label for="cost_price" class="form-label">Cost Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control <?php $__errorArgs = ['cost_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="cost_price" name="cost_price" 
                                               value="<?php echo e(old('cost_price', $plan->cost_price)); ?>" 
                                               step="0.01" min="0">
                                    </div>
                                    <?php $__errorArgs = ['cost_price'];
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

                        <!-- Direct Referral Bonus -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-handshake me-2"></i>
                                    Direct Referral Bonus
                                </h5>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="direct_bonus_enabled" 
                                           name="direct_bonus_enabled" value="1" <?php echo e(old('direct_bonus_enabled', $plan->direct_bonus_enabled) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="direct_bonus_enabled">
                                        <strong>Enable Direct Referral Bonus</strong>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6" id="direct-bonus-mode" style="display: <?php echo e(old('direct_bonus_enabled', $plan->direct_bonus_enabled) ? 'block' : 'none'); ?>;">
                                <div class="form-group mb-3">
                                    <label for="direct_bonus_mode" class="form-label">Payment Mode</label>
                                    <select class="form-control <?php $__errorArgs = ['direct_bonus_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="direct_bonus_mode" name="direct_bonus_mode">
                                        <option value="">Select Mode</option>
                                        <option value="fixed" <?php echo e(old('direct_bonus_mode', $plan->direct_bonus_mode) == 'fixed' ? 'selected' : ''); ?>>Fixed Amount</option>
                                        <option value="percentage" <?php echo e(old('direct_bonus_mode', $plan->direct_bonus_mode) == 'percentage' ? 'selected' : ''); ?>>Percentage</option>
                                    </select>
                                    <?php $__errorArgs = ['direct_bonus_mode'];
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
                            <div class="col-md-6" id="direct-bonus-value" style="display: <?php echo e(old('direct_bonus_enabled', $plan->direct_bonus_enabled) ? 'block' : 'none'); ?>;">
                                <div class="form-group mb-3">
                                    <label for="direct_bonus_value" class="form-label">Bonus Value</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php $__errorArgs = ['direct_bonus_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="direct_bonus_value" name="direct_bonus_value" 
                                               value="<?php echo e(old('direct_bonus_value', $plan->direct_bonus_value)); ?>" 
                                               step="0.01" min="0">
                                        <span class="input-group-text" id="direct-bonus-unit">R$</span>
                                    </div>
                                    <?php $__errorArgs = ['direct_bonus_value'];
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

                        <!-- Unilevel Commission -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-sitemap me-2"></i>
                                    Unilevel Commission
                                </h5>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Configure commission levels for unilevel network structure.
                                </div>
                            </div>
                            <div class="col-12">
                                <div id="unilevel-levels-container">
                                    <?php
                                        $unilevelConfig = $plan->getUnilevelConfig();
                                    ?>
                                    <?php if(!empty($unilevelConfig)): ?>
                                        <?php $__currentLoopData = $unilevelConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="card mb-3 unilevel-level" data-level="<?php echo e($level); ?>">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <label class="form-label">Level</label>
                                                            <input type="number" class="form-control" name="commission_unilevel[<?php echo e($level); ?>][level]" 
                                                                   value="<?php echo e($level); ?>" min="1" max="20" required readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Mode</label>
                                                            <select class="form-control" name="commission_unilevel[<?php echo e($level); ?>][mode]" required>
                                                                <option value="fixed" <?php echo e($config['mode'] === 'fixed' ? 'selected' : ''); ?>>Fixed Amount</option>
                                                                <option value="percentage" <?php echo e($config['mode'] === 'percentage' ? 'selected' : ''); ?>>Percentage</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Value</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="commission_unilevel[<?php echo e($level); ?>][value]" 
                                                                       value="<?php echo e($config['value']); ?>" step="0.01" min="0" required>
                                                                <span class="input-group-text unilevel-unit">R$</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">&nbsp;</label>
                                                            <button type="button" class="btn btn-outline-danger btn-block remove-unilevel-level" 
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
                                <button type="button" class="btn btn-outline-primary" id="add-unilevel-level">
                                    <i class="fas fa-plus me-1"></i>
                                    Add Unilevel Level
                                </button>
                            </div>
                        </div>

                        <!-- Matrix Commission -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-th me-2"></i>
                                    Matrix Commission
                                </h5>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Configure matrix structure and commission levels.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="matrix_width" class="form-label">Matrix Width</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['commission_matrix.width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="matrix_width" name="commission_matrix[width]" 
                                           value="<?php echo e(old('commission_matrix.width', $plan->getMatrixConfig()['width'] ?? 2)); ?>" 
                                           min="1" max="10">
                                    <?php $__errorArgs = ['commission_matrix.width'];
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
                                    <label for="matrix_depth" class="form-label">Matrix Depth</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['commission_matrix.depth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="matrix_depth" name="commission_matrix[depth]" 
                                           value="<?php echo e(old('commission_matrix.depth', $plan->getMatrixConfig()['depth'] ?? 5)); ?>" 
                                           min="1" max="20">
                                    <?php $__errorArgs = ['commission_matrix.depth'];
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
                                <div id="matrix-levels-container">
                                    <?php
                                        $matrixConfig = $plan->getMatrixConfig();
                                    ?>
                                    <?php if(!empty($matrixConfig['levels'])): ?>
                                        <?php $__currentLoopData = $matrixConfig['levels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="card mb-3 matrix-level" data-level="<?php echo e($level); ?>">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <label class="form-label">Level</label>
                                                            <input type="number" class="form-control" name="commission_matrix[levels][<?php echo e($level); ?>][level]" 
                                                                   value="<?php echo e($level); ?>" min="1" max="20" required readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Commission (%)</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="commission_matrix[levels][<?php echo e($level); ?>][value]" 
                                                                       value="<?php echo e($value); ?>" step="0.01" min="0" max="100" required>
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">&nbsp;</label>
                                                            <button type="button" class="btn btn-outline-danger btn-block remove-matrix-level" 
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
                                <button type="button" class="btn btn-outline-primary" id="add-matrix-level">
                                    <i class="fas fa-plus me-1"></i>
                                    Add Matrix Level
                                </button>
                            </div>
                        </div>

                        <!-- Additional Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-cog me-2"></i>
                                    Additional Settings
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="commission_profit_sharing" class="form-label">Profit Sharing (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php $__errorArgs = ['commission_profit_sharing'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="commission_profit_sharing" name="commission_profit_sharing" 
                                               value="<?php echo e(old('commission_profit_sharing', $plan->commission_profit_sharing)); ?>" 
                                               step="0.01" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <?php $__errorArgs = ['commission_profit_sharing'];
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
                                    <label for="external_url" class="form-label">External URL</label>
                                    <input type="url" class="form-control <?php $__errorArgs = ['external_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="external_url" name="external_url" 
                                           value="<?php echo e(old('external_url', $plan->external_url)); ?>" 
                                           placeholder="https://example.com">
                                    <?php $__errorArgs = ['external_url'];
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
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" 
                                           name="status" value="1" <?php echo e(old('status', $plan->status) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="status">
                                        <strong>Active Plan</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?php echo e(route('admin.plans.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Update Plan
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
    let unilevelLevelCount = <?php echo e(count($plan->getUnilevelConfig())); ?>;
    let matrixLevelCount = <?php echo e(count($plan->getMatrixConfig()['levels'] ?? [])); ?>;
    
    const directBonusEnabled = document.getElementById('direct_bonus_enabled');
    const directBonusMode = document.getElementById('direct_bonus_mode');
    const directBonusValue = document.getElementById('direct_bonus_value');
    const directBonusModeDiv = document.getElementById('direct-bonus-mode');
    const directBonusValueDiv = document.getElementById('direct-bonus-value');
    const directBonusUnit = document.getElementById('direct-bonus-unit');
    
    const unilevelContainer = document.getElementById('unilevel-levels-container');
    const addUnilevelBtn = document.getElementById('add-unilevel-level');
    
    const matrixContainer = document.getElementById('matrix-levels-container');
    const addMatrixBtn = document.getElementById('add-matrix-level');

    // Direct bonus toggle
    directBonusEnabled.addEventListener('change', function() {
        if (this.checked) {
            directBonusModeDiv.style.display = 'block';
            directBonusValueDiv.style.display = 'block';
        } else {
            directBonusModeDiv.style.display = 'none';
            directBonusValueDiv.style.display = 'none';
        }
    });

    // Direct bonus mode change
    directBonusMode.addEventListener('change', function() {
        if (this.value === 'percentage') {
            directBonusUnit.textContent = '%';
        } else {
            directBonusUnit.textContent = 'R$';
        }
    });

    // Add unilevel level
    addUnilevelBtn.addEventListener('click', function() {
        unilevelLevelCount++;
        const levelHtml = `
            <div class="card mb-3 unilevel-level" data-level="${unilevelLevelCount}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-1">
                            <label class="form-label">Level</label>
                            <input type="number" class="form-control" name="commission_unilevel[${unilevelLevelCount}][level]" 
                                   value="${unilevelLevelCount}" min="1" max="20" required readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Mode</label>
                            <select class="form-control" name="commission_unilevel[${unilevelLevelCount}][mode]" required>
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Value</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="commission_unilevel[${unilevelLevelCount}][value]" 
                                       step="0.01" min="0" required>
                                <span class="input-group-text unilevel-unit">R$</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger btn-block remove-unilevel-level" 
                                    style="display: block; width: 100%;">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        unilevelContainer.insertAdjacentHTML('beforeend', levelHtml);
        
        // Add remove event listener
        const removeBtn = unilevelContainer.querySelector(`.unilevel-level[data-level="${unilevelLevelCount}"] .remove-unilevel-level`);
        removeBtn.addEventListener('click', function() {
            this.closest('.unilevel-level').remove();
        });

        // Add mode change listener
        const modeSelect = unilevelContainer.querySelector(`.unilevel-level[data-level="${unilevelLevelCount}"] select[name*="[mode]"]`);
        modeSelect.addEventListener('change', function() {
            const unitElement = this.closest('.row').querySelector('.unilevel-unit');
            if (this.value === 'percentage') {
                unitElement.textContent = '%';
            } else {
                unitElement.textContent = 'R$';
            }
        });
    });

    // Add matrix level
    addMatrixBtn.addEventListener('click', function() {
        matrixLevelCount++;
        const levelHtml = `
            <div class="card mb-3 matrix-level" data-level="${matrixLevelCount}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-1">
                            <label class="form-label">Level</label>
                            <input type="number" class="form-control" name="commission_matrix[levels][${matrixLevelCount}][level]" 
                                   value="${matrixLevelCount}" min="1" max="20" required readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Commission (%)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="commission_matrix[levels][${matrixLevelCount}][value]" 
                                       step="0.01" min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger btn-block remove-matrix-level" 
                                    style="display: block; width: 100%;">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        matrixContainer.insertAdjacentHTML('beforeend', levelHtml);
        
        // Add remove event listener
        const removeBtn = matrixContainer.querySelector(`.matrix-level[data-level="${matrixLevelCount}"] .remove-matrix-level`);
        removeBtn.addEventListener('click', function() {
            this.closest('.matrix-level').remove();
        });
    });

    // Add remove event listeners to existing levels
    document.querySelectorAll('.remove-unilevel-level').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.unilevel-level').remove();
        });
    });

    document.querySelectorAll('.remove-matrix-level').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.matrix-level').remove();
        });
    });

    // Add mode change listeners to existing unilevel levels
    document.querySelectorAll('.unilevel-level select[name*="[mode]"]').forEach(select => {
        select.addEventListener('change', function() {
            const unitElement = this.closest('.row').querySelector('.unilevel-unit');
            if (this.value === 'percentage') {
                unitElement.textContent = '%';
            } else {
                unitElement.textContent = 'R$';
            }
        });
    });

    // Initialize direct bonus visibility and unit
    if (directBonusEnabled.checked) {
        directBonusModeDiv.style.display = 'block';
        directBonusValueDiv.style.display = 'block';
    }

    if (directBonusMode.value === 'percentage') {
        directBonusUnit.textContent = '%';
    } else {
        directBonusUnit.textContent = 'R$';
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/plans/edit.blade.php ENDPATH**/ ?>