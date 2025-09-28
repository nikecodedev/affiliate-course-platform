

<?php $__env->startSection('title', 'Tracking Tags'); ?>
<?php $__env->startSection('page-title', 'Tracking Tags'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-code-square me-2"></i>
                    Tracking Tags Configuration
                </h5>
            </div>
            <div class="card-body">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('client.profile.update-tracking-tags')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <!-- Facebook Pixel -->
                    <div class="mb-4">
                        <label for="facebook_pixel_id" class="form-label">
                            <i class="bi bi-facebook me-2"></i>
                            Facebook Pixel ID
                        </label>
                        <input type="text" class="form-control <?php $__errorArgs = ['facebook_pixel_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="facebook_pixel_id" name="facebook_pixel_id" 
                               value="<?php echo e(old('facebook_pixel_id', $client->facebook_pixel_id)); ?>"
                               placeholder="Enter your Facebook Pixel ID">
                        <div class="form-text">
                            Your Facebook Pixel ID for tracking conversions and events.
                        </div>
                        <?php $__errorArgs = ['facebook_pixel_id'];
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

                    <!-- Google Tag Manager -->
                    <div class="mb-4">
                        <label for="google_tag_manager_id" class="form-label">
                            <i class="bi bi-google me-2"></i>
                            Google Tag Manager ID
                        </label>
                        <input type="text" class="form-control <?php $__errorArgs = ['google_tag_manager_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="google_tag_manager_id" name="google_tag_manager_id" 
                               value="<?php echo e(old('google_tag_manager_id', $client->google_tag_manager_id)); ?>"
                               placeholder="Enter your Google Tag Manager ID">
                        <div class="form-text">
                            Your Google Tag Manager container ID (format: GTM-XXXXXXX).
                        </div>
                        <?php $__errorArgs = ['google_tag_manager_id'];
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

                    <!-- Google Analytics -->
                    <div class="mb-4">
                        <label for="google_analytics_id" class="form-label">
                            <i class="bi bi-graph-up me-2"></i>
                            Google Analytics ID
                        </label>
                        <input type="text" class="form-control <?php $__errorArgs = ['google_analytics_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="google_analytics_id" name="google_analytics_id" 
                               value="<?php echo e(old('google_analytics_id', $client->google_analytics_id)); ?>"
                               placeholder="Enter your Google Analytics ID">
                        <div class="form-text">
                            Your Google Analytics measurement ID (format: G-XXXXXXXXXX).
                        </div>
                        <?php $__errorArgs = ['google_analytics_id'];
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

                    <!-- Custom Tracking Code -->
                    <div class="mb-4">
                        <label for="custom_tracking_code" class="form-label">
                            <i class="bi bi-code-slash me-2"></i>
                            Custom Tracking Code
                        </label>
                        <textarea class="form-control <?php $__errorArgs = ['custom_tracking_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="custom_tracking_code" name="custom_tracking_code" 
                                  rows="6" placeholder="Enter your custom tracking code here..."><?php echo e(old('custom_tracking_code', $client->custom_tracking_code)); ?></textarea>
                        <div class="form-text">
                            Any custom tracking code or scripts you want to include (HTML/JavaScript).
                        </div>
                        <?php $__errorArgs = ['custom_tracking_code'];
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

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>
                            Update Tracking Tags
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Tracking Code Preview -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-eye me-2"></i>
                    Generated Tracking Code
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Your personalized tracking code based on the configured tags:
                </p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="generateTrackingCode()">
                        <i class="bi bi-arrow-clockwise me-2"></i>
                        Generate Code
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="downloadTrackingCode()">
                        <i class="bi bi-download me-2"></i>
                        Download Code
                    </button>
                </div>
            </div>
        </div>

        <!-- Help Information -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-question-circle me-2"></i>
                    How to Get Your IDs
                </h6>
            </div>
            <div class="card-body">
                <div class="accordion" id="helpAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#facebookHelp">
                                Facebook Pixel
                            </button>
                        </h2>
                        <div id="facebookHelp" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <small>1. Go to Facebook Business Manager<br>
                                2. Navigate to Events Manager<br>
                                3. Create a new Pixel<br>
                                4. Copy the Pixel ID</small>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gtmHelp">
                                Google Tag Manager
                            </button>
                        </h2>
                        <div id="gtmHelp" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <small>1. Go to Google Tag Manager<br>
                                2. Create a new container<br>
                                3. Copy the Container ID (GTM-XXXXXXX)</small>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gaHelp">
                                Google Analytics
                            </button>
                        </h2>
                        <div id="gaHelp" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <small>1. Go to Google Analytics<br>
                                2. Create a new property<br>
                                3. Copy the Measurement ID (G-XXXXXXXXXX)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Configuration -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Current Configuration
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Facebook Pixel:</strong>
                    <span class="badge <?php echo e($client->facebook_pixel_id ? 'bg-success' : 'bg-secondary'); ?>">
                        <?php echo e($client->facebook_pixel_id ? 'Configured' : 'Not Set'); ?>

                    </span>
                </div>
                <div class="mb-2">
                    <strong>Google Tag Manager:</strong>
                    <span class="badge <?php echo e($client->google_tag_manager_id ? 'bg-success' : 'bg-secondary'); ?>">
                        <?php echo e($client->google_tag_manager_id ? 'Configured' : 'Not Set'); ?>

                    </span>
                </div>
                <div class="mb-2">
                    <strong>Google Analytics:</strong>
                    <span class="badge <?php echo e($client->google_analytics_id ? 'bg-success' : 'bg-secondary'); ?>">
                        <?php echo e($client->google_analytics_id ? 'Configured' : 'Not Set'); ?>

                    </span>
                </div>
                <div class="mb-0">
                    <strong>Custom Code:</strong>
                    <span class="badge <?php echo e($client->custom_tracking_code ? 'bg-success' : 'bg-secondary'); ?>">
                        <?php echo e($client->custom_tracking_code ? 'Configured' : 'Not Set'); ?>

                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tracking Code Modal -->
<div class="modal fade" id="trackingCodeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-code-square me-2"></i>
                    Generated Tracking Code
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="trackingCodeContent" class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="copyTrackingCode()">
                    <i class="bi bi-clipboard me-2"></i>
                    Copy Code
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function generateTrackingCode() {
        // Show loading state
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generating...';
        btn.disabled = true;

        // Make AJAX request to generate tracking code
        fetch('<?php echo e(route("client.profile.generate-tracking-code")); ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('trackingCodeContent').textContent = data.tracking_code;
                new bootstrap.Modal(document.getElementById('trackingCodeModal')).show();
            } else {
                alert('Error generating tracking code');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error generating tracking code');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function downloadTrackingCode() {
        window.location.href = '<?php echo e(route("client.profile.download-tracking-code")); ?>';
    }

    function copyTrackingCode() {
        const code = document.getElementById('trackingCodeContent').textContent;
        navigator.clipboard.writeText(code).then(() => {
            // Show success message
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check me-2"></i>Copied!';
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-primary');
            }, 2000);
        });
    }

    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() !== '') {
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\affiliate\resources\views/client/profile/tracking-tags.blade.php ENDPATH**/ ?>