

<?php $__env->startSection('title', 'SEO Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-search me-2"></i>
                        SEO Settings
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Settings
                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('admin.seo.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

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

                        <!-- SEO Settings -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-globe me-2"></i>
                                    Search Engine Optimization
                                </h5>
                            </div>
                            
                            <!-- Site Title -->
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="seo_title" class="form-label">
                                        <i class="fas fa-heading me-1"></i>
                                        Site Title
                                        <span class="text-muted">(Max 60 characters)</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['seo_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="seo_title" 
                                           name="seo_title" 
                                           value="<?php echo e(old('seo_title', $seoSettings->get('seo_title')?->value ?? '')); ?>"
                                           placeholder="Enter your site title"
                                           maxlength="60">
                                    <div class="form-text">
                                        This will appear in the browser tab and search results. 
                                        <span id="title-count" class="text-muted">0/60 characters</span>
                                    </div>
                                    <?php $__errorArgs = ['seo_title'];
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

                            <!-- Meta Description -->
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="seo_description" class="form-label">
                                        <i class="fas fa-align-left me-1"></i>
                                        Meta Description
                                        <span class="text-muted">(Max 160 characters)</span>
                                    </label>
                                    <textarea class="form-control <?php $__errorArgs = ['seo_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="seo_description" 
                                              name="seo_description" 
                                              rows="3"
                                              maxlength="160"
                                              placeholder="Enter a brief description of your website"><?php echo e(old('seo_description', $seoSettings->get('seo_description')?->value ?? '')); ?></textarea>
                                    <div class="form-text">
                                        This description will appear in search engine results. 
                                        <span id="description-count" class="text-muted">0/160 characters</span>
                                    </div>
                                    <?php $__errorArgs = ['seo_description'];
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

                            <!-- Meta Keywords -->
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="seo_keywords" class="form-label">
                                        <i class="fas fa-tags me-1"></i>
                                        Meta Keywords
                                        <span class="text-muted">(Max 255 characters)</span>
                                    </label>
                                    <textarea class="form-control <?php $__errorArgs = ['seo_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="seo_keywords" 
                                              name="seo_keywords" 
                                              rows="2"
                                              maxlength="255"
                                              placeholder="Enter keywords separated by commas (e.g., affiliate, marketing, courses)"><?php echo e(old('seo_keywords', $seoSettings->get('seo_keywords')?->value ?? '')); ?></textarea>
                                    <div class="form-text">
                                        Keywords help search engines understand your content. 
                                        <span id="keywords-count" class="text-muted">0/255 characters</span>
                                    </div>
                                    <?php $__errorArgs = ['seo_keywords'];
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

                        <!-- SEO Preview -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-eye me-2"></i>
                                    Search Engine Preview
                                </h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="search-preview">
                                            <div class="search-result">
                                                <h4 class="search-title" id="preview-title">
                                                    <?php echo e($seoSettings->get('seo_title')?->value ?? 'Affiliate & Course Platform'); ?>

                                                </h4>
                                                <div class="search-url text-success" id="preview-url">
                                                    <?php echo e(url('/')); ?>

                                                </div>
                                                <p class="search-description" id="preview-description">
                                                    <?php echo e($seoSettings->get('seo_description')?->value ?? 'Your description will appear here...'); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-warning" onclick="resetForm()">
                                    <i class="fas fa-undo me-1"></i>
                                    Reset Form
                                </button>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Save SEO Settings
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

<?php $__env->startSection('styles'); ?>
<style>
.search-preview {
    max-width: 600px;
}

.search-result {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 16px;
    background: #fff;
}

.search-title {
    color: #1a0dab;
    font-size: 18px;
    font-weight: 400;
    margin: 0 0 4px 0;
    line-height: 1.3;
}

.search-title:hover {
    text-decoration: underline;
    cursor: pointer;
}

.search-url {
    font-size: 14px;
    margin: 0 0 4px 0;
}

.search-description {
    color: #545454;
    font-size: 14px;
    line-height: 1.4;
    margin: 0;
}

.character-count {
    font-size: 12px;
}

.character-count.warning {
    color: #ff9800;
}

.character-count.danger {
    color: #f44336;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counters
    const titleInput = document.getElementById('seo_title');
    const descriptionInput = document.getElementById('seo_description');
    const keywordsInput = document.getElementById('seo_keywords');
    
    const titleCount = document.getElementById('title-count');
    const descriptionCount = document.getElementById('description-count');
    const keywordsCount = document.getElementById('keywords-count');

    // Update character counts
    function updateCount(input, counter, max) {
        const count = input.value.length;
        counter.textContent = `${count}/${max} characters`;
        
        // Add warning/danger classes
        counter.className = 'character-count';
        if (count > max * 0.9) {
            counter.classList.add('danger');
        } else if (count > max * 0.8) {
            counter.classList.add('warning');
        }
    }

    // Live preview updates
    function updatePreview() {
        const title = titleInput.value || 'Affiliate & Course Platform';
        const description = descriptionInput.value || 'Your description will appear here...';
        
        document.getElementById('preview-title').textContent = title;
        document.getElementById('preview-description').textContent = description;
    }

    // Event listeners
    titleInput.addEventListener('input', function() {
        updateCount(this, titleCount, 60);
        updatePreview();
    });

    descriptionInput.addEventListener('input', function() {
        updateCount(this, descriptionCount, 160);
        updatePreview();
    });

    keywordsInput.addEventListener('input', function() {
        updateCount(this, keywordsCount, 255);
    });

    // Initialize counts
    updateCount(titleInput, titleCount, 60);
    updateCount(descriptionInput, descriptionCount, 160);
    updateCount(keywordsInput, keywordsCount, 255);
});

function resetForm() {
    if (confirm('Are you sure you want to reset the form? All changes will be lost.')) {
        document.getElementById('seo_title').value = '';
        document.getElementById('seo_description').value = '';
        document.getElementById('seo_keywords').value = '';
        
        // Trigger input events to update counts and preview
        document.getElementById('seo_title').dispatchEvent(new Event('input'));
        document.getElementById('seo_description').dispatchEvent(new Event('input'));
        document.getElementById('seo_keywords').dispatchEvent(new Event('input'));
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/seo/index.blade.php ENDPATH**/ ?>