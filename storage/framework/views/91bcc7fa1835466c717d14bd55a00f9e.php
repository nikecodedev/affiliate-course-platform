

<?php $__env->startSection('title', 'Create Support Ticket'); ?>
<?php $__env->startSection('page-title', 'Create Support Ticket'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Create New Support Ticket
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

                <form method="POST" action="<?php echo e(route('client.support.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-select <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Select a category</option>
                                <option value="account" <?php echo e(old('category') === 'account' ? 'selected' : ''); ?>>Account & Profile</option>
                                <option value="financial" <?php echo e(old('category') === 'financial' ? 'selected' : ''); ?>>Financial & Payments</option>
                                <option value="leads" <?php echo e(old('category') === 'leads' ? 'selected' : ''); ?>>Lead Management</option>
                                <option value="training" <?php echo e(old('category') === 'training' ? 'selected' : ''); ?>>Training & Courses</option>
                                <option value="technical" <?php echo e(old('category') === 'technical' ? 'selected' : ''); ?>>Technical Support</option>
                                <option value="billing" <?php echo e(old('category') === 'billing' ? 'selected' : ''); ?>>Billing & Invoices</option>
                                <option value="general" <?php echo e(old('category') === 'general' ? 'selected' : ''); ?>>General Inquiry</option>
                            </select>
                            <?php $__errorArgs = ['category'];
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

                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select name="priority" id="priority" class="form-select <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Select priority</option>
                                <option value="low" <?php echo e(old('priority') === 'low' ? 'selected' : ''); ?>>Low - General questions</option>
                                <option value="medium" <?php echo e(old('priority') === 'medium' ? 'selected' : ''); ?>>Medium - Need assistance</option>
                                <option value="high" <?php echo e(old('priority') === 'high' ? 'selected' : ''); ?>>High - Urgent issue</option>
                            </select>
                            <?php $__errorArgs = ['priority'];
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

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="subject" class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('subject')); ?>" placeholder="Brief description of your issue" required>
                        <?php $__errorArgs = ['subject'];
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
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="6" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  placeholder="Please provide detailed information about your issue..." required><?php echo e(old('description')); ?></textarea>
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
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Please include as much detail as possible to help us assist you quickly.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="attachments" class="form-label">Attachments</label>
                        <input type="file" name="attachments[]" id="attachments" class="form-control" multiple 
                               accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            You can attach screenshots, documents, or other files (max 5MB each, up to 3 files)
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-person-lines-fill me-2"></i>
                                Contact Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_email" class="form-label">Email</label>
                                    <input type="email" name="contact_email" id="contact_email" class="form-control" 
                                           value="<?php echo e(old('contact_email', auth('client')->user()->email)); ?>" readonly>
                                    <div class="form-text">This will be used for ticket updates</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact_phone" class="form-label">Phone (Optional)</label>
                                    <input type="tel" name="contact_phone" id="contact_phone" class="form-control" 
                                           value="<?php echo e(old('contact_phone', auth('client')->user()->phone)); ?>">
                                    <div class="form-text">For urgent issues, we may contact you by phone</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Response Preferences -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-gear me-2"></i>
                                Response Preferences
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="email_notifications" id="email_notifications" 
                                       value="1" <?php echo e(old('email_notifications', true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="email_notifications">
                                    Email me when there are updates to this ticket
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="urgent_response" id="urgent_response" 
                                       value="1" <?php echo e(old('urgent_response') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="urgent_response">
                                    This is an urgent issue requiring immediate attention
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo e(route('client.support.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Support
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>
                            Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Tips -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    Tips for Getting Help Faster
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li><strong>Be specific:</strong> Include details about what you were trying to do when the issue occurred.</li>
                    <li><strong>Include steps:</strong> Describe the exact steps you took that led to the problem.</li>
                    <li><strong>Add screenshots:</strong> Visual evidence helps us understand the issue better.</li>
                    <li><strong>Check FAQ first:</strong> Your question might already be answered in our FAQ section.</li>
                    <li><strong>Use the right priority:</strong> High priority should only be used for urgent issues that affect your business.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto-expand textarea
    document.getElementById('description').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // File upload validation
    document.getElementById('attachments').addEventListener('change', function() {
        const files = this.files;
        const maxFiles = 3;
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (files.length > maxFiles) {
            alert(`You can only upload up to ${maxFiles} files.`);
            this.value = '';
            return;
        }
        
        for (let file of files) {
            if (file.size > maxSize) {
                alert(`File "${file.name}" is too large. Maximum size is 5MB.`);
                this.value = '';
                return;
            }
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const subject = document.getElementById('subject').value.trim();
        const description = document.getElementById('description').value.trim();
        const category = document.getElementById('category').value;
        const priority = document.getElementById('priority').value;
        
        if (!subject || !description || !category || !priority) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }
        
        if (description.length < 20) {
            e.preventDefault();
            alert('Please provide a more detailed description (at least 20 characters).');
            return;
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\affiliate\resources\views/client/support/create.blade.php ENDPATH**/ ?>