

<?php $__env->startSection('title', 'Create New Lesson'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus me-2"></i>
                        Create New Lesson for: <?php echo e($module->title); ?>

                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.courses.modules.lessons.index', [$course, $module])); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Lessons
                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('admin.courses.modules.lessons.store', [$course, $module])); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
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
                            <!-- Lesson Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Lesson Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">
                                                Lesson Title <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="title" 
                                                   name="title" 
                                                   value="<?php echo e(old('title')); ?>" 
                                                   placeholder="Enter lesson title"
                                                   required>
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

                                        <div class="mb-3">
                                            <label for="order" class="form-label">Order</label>
                                            <input type="number" 
                                                   class="form-control <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="order" 
                                                   name="order" 
                                                   value="<?php echo e(old('order', $module->lessons()->max('order') + 1)); ?>" 
                                                   min="1"
                                                   placeholder="Lesson order">
                                            <div class="form-text">Leave empty to add at the end</div>
                                            <?php $__errorArgs = ['order'];
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

                                        <div class="mb-3">
                                            <label for="content" class="form-label">Lesson Content</label>
                                            <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                      id="content" 
                                                      name="content" 
                                                      rows="6" 
                                                      placeholder="Enter lesson content (supports basic HTML)"><?php echo e(old('content')); ?></textarea>
                                            <div class="form-text">You can use basic HTML tags for formatting</div>
                                            <?php $__errorArgs = ['content'];
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

                                        <div class="mb-3">
                                            <label for="video_url" class="form-label">Video URL</label>
                                            <input type="url" 
                                                   class="form-control <?php $__errorArgs = ['video_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="video_url" 
                                                   name="video_url" 
                                                   value="<?php echo e(old('video_url')); ?>" 
                                                   placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/...">
                                            <div class="form-text">Supports YouTube and Vimeo URLs</div>
                                            <?php $__errorArgs = ['video_url'];
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

                                        <div class="mb-3">
                                            <label for="attachment" class="form-label">Lesson Attachment</label>
                                            <input type="file" 
                                                   class="form-control <?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="attachment" 
                                                   name="attachment" 
                                                   accept=".pdf,.doc,.docx,.txt,.zip,.rar">
                                            <div class="form-text">Upload PDF, DOC, DOCX, TXT, ZIP, or RAR files (Max 10MB)</div>
                                            <?php $__errorArgs = ['attachment'];
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

                            <!-- Module Information -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-folder me-2"></i>
                                            Module Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <h6><?php echo e($module->title); ?></h6>
                                        <p class="text-muted small">From: <?php echo e($course->title); ?></p>
                                        
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <strong>Current Lessons:</strong> <?php echo e($module->lessons->count()); ?><br>
                                                <strong>Module Order:</strong> <?php echo e($module->order); ?>

                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Video Preview -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-video me-2"></i>
                                            Video Preview
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="videoPreview" class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 150px; border: 2px dashed #dee2e6;">
                                            <div class="text-center">
                                                <i class="fas fa-video fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0 small">No video URL entered</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tips -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            Tips
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="small mb-0">
                                            <li>Add a descriptive title</li>
                                            <li>Include video content when possible</li>
                                            <li>Provide downloadable resources</li>
                                            <li>Use clear, engaging content</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?php echo e(route('admin.courses.modules.lessons.index', [$course, $module])); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Create Lesson
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
    // Video preview functionality
    const videoInput = document.getElementById('video_url');
    const videoPreview = document.getElementById('videoPreview');

    videoInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url) {
            // Check if it's a YouTube URL
            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                const videoId = extractYouTubeId(url);
                if (videoId) {
                    videoPreview.innerHTML = `
                        <div class="text-center">
                            <img src="https://img.youtube.com/vi/${videoId}/mqdefault.jpg" 
                                 alt="YouTube Preview" 
                                 class="img-fluid rounded" 
                                 style="max-height: 120px;">
                            <p class="text-muted mb-0 small mt-2">YouTube Video Preview</p>
                        </div>
                    `;
                } else {
                    showVideoError();
                }
            }
            // Check if it's a Vimeo URL
            else if (url.includes('vimeo.com')) {
                videoPreview.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-video fa-2x text-primary mb-2"></i>
                        <p class="text-muted mb-0 small">Vimeo Video</p>
                    </div>
                `;
            }
            // Other video URLs
            else {
                videoPreview.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-video fa-2x text-info mb-2"></i>
                        <p class="text-muted mb-0 small">Video URL</p>
                    </div>
                `;
            }
        } else {
            videoPreview.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-video fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0 small">No video URL entered</p>
                </div>
            `;
        }
    });

    function extractYouTubeId(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    function showVideoError() {
        videoPreview.innerHTML = `
            <div class="text-center">
                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                <p class="text-muted mb-0 small">Invalid video URL</p>
            </div>
        `;
    }

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        
        if (!title) {
            e.preventDefault();
            showAlert('Please enter a lesson title.', 'danger');
            return;
        }
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
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/lessons/create.blade.php ENDPATH**/ ?>