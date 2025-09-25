@extends('layouts.admin')

@section('title', 'Edit Plan: ' . $plan->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Edit Plan: {{ $plan->title }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Plans
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.plans.update', $plan) }}" method="POST" enctype="multipart/form-data" id="planForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

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
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $plan->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="physical" {{ old('type', $plan->type) == 'physical' ? 'selected' : '' }}>Physical</option>
                                        <option value="digital" {{ old('type', $plan->type) == 'digital' ? 'selected' : '' }}>Digital</option>
                                        <option value="service" {{ old('type', $plan->type) == 'service' ? 'selected' : '' }}>Service</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $plan->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    @if($plan->image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $plan->image) }}" 
                                                 alt="{{ $plan->title }}" 
                                                 class="img-thumbnail" 
                                                 style="width: 100px; height: 75px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <div class="form-text">Recommended size: 400x300px</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="external_product_url" class="form-label">External Product URL</label>
                                    <input type="url" class="form-control @error('external_product_url') is-invalid @enderror" 
                                           id="external_product_url" name="external_product_url" 
                                           value="{{ old('external_product_url', $plan->external_product_url) }}" 
                                           placeholder="https://downloads.example.com/product.zip">
                                    @error('external_product_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="course_ids" class="form-label">Courses (Multi-select)</label>
                                    <select multiple class="form-control @error('course_ids') is-invalid @enderror" 
                                            id="course_ids" name="course_ids[]">
                                        @php
                                            $selectedCourses = collect(old('course_ids', $plan->courses->pluck('id')->toArray()));
                                        @endphp
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ $selectedCourses->contains($course->id) ? 'selected' : '' }}>
                                                {{ $course->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror" 
                                               id="sale_price" name="sale_price" 
                                               value="{{ old('sale_price', $plan->sale_price) }}" 
                                               step="0.01" min="0" required>
                                    </div>
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="cost_price" class="form-label">Cost Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control @error('cost_price') is-invalid @enderror" 
                                               id="cost_price" name="cost_price" 
                                               value="{{ old('cost_price', $plan->cost_price) }}" 
                                               step="0.01" min="0">
                                    </div>
                                    @error('cost_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                           name="direct_bonus_enabled" value="1" {{ old('direct_bonus_enabled', $plan->direct_bonus_enabled) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="direct_bonus_enabled">
                                        <strong>Enable Direct Referral Bonus</strong>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6" id="direct-bonus-mode" style="display: {{ old('direct_bonus_enabled', $plan->direct_bonus_enabled) ? 'block' : 'none' }};">
                                <div class="form-group mb-3">
                                    <label for="direct_bonus_mode" class="form-label">Payment Mode</label>
                                    <select class="form-control @error('direct_bonus_mode') is-invalid @enderror" 
                                            id="direct_bonus_mode" name="direct_bonus_mode">
                                        <option value="">Select Mode</option>
                                        <option value="fixed" {{ old('direct_bonus_mode', $plan->direct_bonus_mode) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                        <option value="percentage" {{ old('direct_bonus_mode', $plan->direct_bonus_mode) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    </select>
                                    @error('direct_bonus_mode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6" id="direct-bonus-value" style="display: {{ old('direct_bonus_enabled', $plan->direct_bonus_enabled) ? 'block' : 'none' }};">
                                <div class="form-group mb-3">
                                    <label for="direct_bonus_value" class="form-label">Bonus Value</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('direct_bonus_value') is-invalid @enderror" 
                                               id="direct_bonus_value" name="direct_bonus_value" 
                                               value="{{ old('direct_bonus_value', $plan->direct_bonus_value) }}" 
                                               step="0.01" min="0">
                                        <span class="input-group-text" id="direct-bonus-unit">R$</span>
                                    </div>
                                    @error('direct_bonus_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                    @php
                                        $unilevelConfig = $plan->getUnilevelConfig();
                                    @endphp
                                    @if(!empty($unilevelConfig))
                                        @foreach($unilevelConfig as $level => $config)
                                            <div class="card mb-3 unilevel-level" data-level="{{ $level }}">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <label class="form-label">Level</label>
                                                            <input type="number" class="form-control" name="commission_unilevel[{{ $level }}][level]" 
                                                                   value="{{ $level }}" min="1" max="20" required readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Mode</label>
                                                            <select class="form-control" name="commission_unilevel[{{ $level }}][mode]" required>
                                                                <option value="fixed" {{ $config['mode'] === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                                                <option value="percentage" {{ $config['mode'] === 'percentage' ? 'selected' : '' }}>Percentage</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Value</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="commission_unilevel[{{ $level }}][value]" 
                                                                       value="{{ $config['value'] }}" step="0.01" min="0" required>
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
                                        @endforeach
                                    @endif
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
                                    <input type="number" class="form-control @error('commission_matrix.width') is-invalid @enderror" 
                                           id="matrix_width" name="commission_matrix[width]" 
                                           value="{{ old('commission_matrix.width', $plan->getMatrixConfig()['width'] ?? 2) }}" 
                                           min="1" max="10">
                                    @error('commission_matrix.width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="matrix_depth" class="form-label">Matrix Depth</label>
                                    <input type="number" class="form-control @error('commission_matrix.depth') is-invalid @enderror" 
                                           id="matrix_depth" name="commission_matrix[depth]" 
                                           value="{{ old('commission_matrix.depth', $plan->getMatrixConfig()['depth'] ?? 5) }}" 
                                           min="1" max="20">
                                    @error('commission_matrix.depth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div id="matrix-levels-container">
                                    @php
                                        $matrixConfig = $plan->getMatrixConfig();
                                    @endphp
                                    @if(!empty($matrixConfig['levels']))
                                        @foreach($matrixConfig['levels'] as $level => $value)
                                            <div class="card mb-3 matrix-level" data-level="{{ $level }}">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <label class="form-label">Level</label>
                                                            <input type="number" class="form-control" name="commission_matrix[levels][{{ $level }}][level]" 
                                                                   value="{{ $level }}" min="1" max="20" required readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Commission (%)</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="commission_matrix[levels][{{ $level }}][value]" 
                                                                       value="{{ $value }}" step="0.01" min="0" max="100" required>
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
                                        @endforeach
                                    @endif
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
                                        <input type="number" class="form-control @error('commission_profit_sharing') is-invalid @enderror" 
                                               id="commission_profit_sharing" name="commission_profit_sharing" 
                                               value="{{ old('commission_profit_sharing', $plan->commission_profit_sharing) }}" 
                                               step="0.01" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('commission_profit_sharing')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="external_url" class="form-label">External URL</label>
                                    <input type="url" class="form-control @error('external_url') is-invalid @enderror" 
                                           id="external_url" name="external_url" 
                                           value="{{ old('external_url', $plan->external_url) }}" 
                                           placeholder="https://example.com">
                                    @error('external_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" 
                                           name="status" value="1" {{ old('status', $plan->status) ? 'checked' : '' }}>
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
                                <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let unilevelLevelCount = {{ count($plan->getUnilevelConfig()) }};
    let matrixLevelCount = {{ count($plan->getMatrixConfig()['levels'] ?? []) }};
    
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
@endsection
