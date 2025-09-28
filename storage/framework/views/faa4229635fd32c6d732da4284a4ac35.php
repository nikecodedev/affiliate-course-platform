

<?php $__env->startSection('title', 'Lead Details'); ?>
<?php $__env->startSection('page-title', 'Lead Details'); ?>

<?php $__env->startSection('page-actions'); ?>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
        <a href="<?php echo e(route('client.leads.edit', $lead)); ?>" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Edit Lead
        </a>
        <a href="<?php echo e(route('client.leads.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Leads
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <!-- Lead Information -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Lead Information
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-<?php echo e($lead->status_badge_color); ?> fs-6">
                            <?php echo e($lead->status_badge_text); ?>

                        </span>
                        <?php if($lead->contacted): ?>
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Contacted
                            </span>
                        <?php endif; ?>
                        <?php if($lead->converted): ?>
                            <span class="badge bg-success">
                                <i class="bi bi-trophy me-1"></i>Converted
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Full Name</label>
                            <p class="mb-0 fw-bold"><?php echo e($lead->name); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="mb-0">
                                <a href="mailto:<?php echo e($lead->email); ?>" class="text-decoration-none">
                                    <?php echo e($lead->email); ?>

                                </a>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone</label>
                            <p class="mb-0">
                                <?php if($lead->phone): ?>
                                    <a href="tel:<?php echo e($lead->phone); ?>" class="text-decoration-none">
                                        <?php echo e($lead->phone); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Not provided</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Source</label>
                            <p class="mb-0">
                                <span class="badge bg-light text-dark"><?php echo e($lead->source); ?></span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Created</label>
                            <p class="mb-0"><?php echo e($lead->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Last Updated</label>
                            <p class="mb-0"><?php echo e($lead->updated_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                </div>

                <?php if($lead->notes): ?>
                <div class="mb-3">
                    <label class="form-label text-muted">Notes</label>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0"><?php echo e($lead->notes); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($lead->contact_notes): ?>
                <div class="mb-3">
                    <label class="form-label text-muted">Contact Notes</label>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0"><?php echo e($lead->contact_notes); ?></p>
                        <?php if($lead->contacted_at): ?>
                            <small class="text-muted">Contacted on: <?php echo e($lead->contacted_at->format('d/m/Y H:i')); ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($lead->converted && $lead->conversion_value): ?>
                <div class="mb-3">
                    <label class="form-label text-muted">Conversion Value</label>
                    <p class="mb-0 fw-bold text-success">R$ <?php echo e(number_format($lead->conversion_value, 2, ',', '.')); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if(!$lead->contacted): ?>
                    <div class="col-md-6 mb-3">
                        <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="bi bi-telephone me-2"></i>
                            Mark as Contacted
                        </button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(!$lead->converted): ?>
                    <div class="col-md-6 mb-3">
                        <button type="button" class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#convertModal">
                            <i class="bi bi-trophy me-2"></i>
                            Mark as Converted
                        </button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="col-md-6 mb-3">
                        <a href="mailto:<?php echo e($lead->email); ?>" class="btn btn-outline-info w-100">
                            <i class="bi bi-envelope me-2"></i>
                            Send Email
                        </a>
                    </div>
                    
                    <?php if($lead->phone): ?>
                    <div class="col-md-6 mb-3">
                        <a href="tel:<?php echo e($lead->phone); ?>" class="btn btn-outline-warning w-100">
                            <i class="bi bi-telephone me-2"></i>
                            Call Now
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Lead Statistics -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Lead Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Days since creation</span>
                        <span class="fw-bold"><?php echo e($lead->created_at->diffInDays(now())); ?></span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Status</span>
                        <span class="badge bg-<?php echo e($lead->status_badge_color); ?>">
                            <?php echo e($lead->status_badge_text); ?>

                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Contacted</span>
                        <span class="badge bg-<?php echo e($lead->contacted ? 'success' : 'secondary'); ?>">
                            <?php echo e($lead->contacted ? 'Yes' : 'No'); ?>

                        </span>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex justify-content-between">
                        <span>Converted</span>
                        <span class="badge bg-<?php echo e($lead->converted ? 'success' : 'secondary'); ?>">
                            <?php echo e($lead->converted ? 'Yes' : 'No'); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Timeline
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Lead Created</h6>
                            <p class="text-muted mb-0"><?php echo e($lead->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                    
                    <?php if($lead->contacted_at): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Contacted</h6>
                            <p class="text-muted mb-0"><?php echo e($lead->contacted_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($lead->converted_at): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Converted</h6>
                            <p class="text-muted mb-0"><?php echo e($lead->converted_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Last Updated</h6>
                            <p class="text-muted mb-0"><?php echo e($lead->updated_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-telephone me-2"></i>
                    Mark as Contacted
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('client.leads.mark-contacted', $lead)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="contact_notes" class="form-label">Contact Notes *</label>
                        <textarea class="form-control <?php $__errorArgs = ['contact_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="contact_notes" name="contact_notes" rows="4" 
                                  placeholder="Describe how the contact was made and what was discussed..." required><?php echo e(old('contact_notes')); ?></textarea>
                        <?php $__errorArgs = ['contact_notes'];
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-2"></i>Mark as Contacted
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Convert Modal -->
<div class="modal fade" id="convertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-trophy me-2"></i>
                    Mark as Converted
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('client.leads.mark-converted', $lead)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="conversion_value" class="form-label">Conversion Value (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control <?php $__errorArgs = ['conversion_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="conversion_value" name="conversion_value" 
                                   value="<?php echo e(old('conversion_value')); ?>" 
                                   step="0.01" min="0" placeholder="0.00">
                            <?php $__errorArgs = ['conversion_value'];
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-trophy me-2"></i>Mark as Converted
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -24px;
    top: 17px;
    width: 2px;
    height: calc(100% + 3px);
    background: #dee2e6;
}

.timeline-content h6 {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.timeline-content p {
    font-size: 0.8rem;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\affiliate\resources\views/client/leads/show.blade.php ENDPATH**/ ?>