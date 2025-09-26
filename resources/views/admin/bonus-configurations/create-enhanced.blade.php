@extends('layouts.admin')

@section('title', 'Create Enhanced Bonus Configuration')
@section('page-title', 'Create Enhanced Bonus Configuration')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Enhanced Bonus Configuration
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.bonus.configurations.store') }}" id="bonusConfigForm">
                    @csrf
                    
                    <!-- Basic Configuration -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="plan_id" class="form-label">Plan <span class="text-danger">*</span></label>
                            <select class="form-select @error('plan_id') is-invalid @enderror" id="plan_id" name="plan_id" required>
                                <option value="">Select Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bonus_type" class="form-label">Bonus Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('bonus_type') is-invalid @enderror" id="bonus_type" name="bonus_type" required>
                                <option value="">Select Bonus Type</option>
                                <option value="unilevel" {{ old('bonus_type') == 'unilevel' ? 'selected' : '' }}>Unilevel</option>
                                <option value="forced_matrix" {{ old('bonus_type') == 'forced_matrix' ? 'selected' : '' }}>Forced Matrix</option>
                                <option value="profit_sharing" {{ old('bonus_type') == 'profit_sharing' ? 'selected' : '' }}>Profit Sharing</option>
                            </select>
                            @error('bonus_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Direct Referral Configuration -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Direct Referral Configuration</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="direct_referral_enabled" 
                                               name="direct_referral_enabled" value="1" {{ old('direct_referral_enabled') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="direct_referral_enabled">
                                            Enable Direct Referral Bonus
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="direct_referral_payment_type" class="form-label">Payment Type</label>
                                    <select class="form-select" id="direct_referral_payment_type" name="direct_referral_payment_type">
                                        <option value="percentage" {{ old('direct_referral_payment_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        <option value="fixed_amount" {{ old('direct_referral_payment_type') == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3" id="direct_referral_percentage_field">
                                    <label for="direct_referral_percentage" class="form-label">Percentage (%)</label>
                                    <input type="number" class="form-control" id="direct_referral_percentage" 
                                           name="direct_referral_percentage" value="{{ old('direct_referral_percentage') }}" 
                                           step="0.01" min="0" max="100">
                                </div>

                                <div class="col-md-3 mb-3" id="direct_referral_fixed_field" style="display: none;">
                                    <label for="direct_referral_fixed" class="form-label">Fixed Amount (R$)</label>
                                    <input type="number" class="form-control" id="direct_referral_fixed" 
                                           name="direct_referral_fixed" value="{{ old('direct_referral_fixed') }}" 
                                           step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unilevel Configuration -->
                    <div class="card mt-4" id="unilevel_config" style="display: none;">
                        <div class="card-header">
                            <h6 class="mb-0">Unilevel Configuration</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="unilevel_payment_type" class="form-label">Payment Type</label>
                                    <select class="form-select" id="unilevel_payment_type" name="unilevel_payment_type">
                                        <option value="percentage" {{ old('unilevel_payment_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        <option value="fixed_amount" {{ old('unilevel_payment_type') == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="unilevel_max_depth" class="form-label">Maximum Depth</label>
                                    <input type="number" class="form-control" id="unilevel_max_depth" 
                                           name="unilevel_max_depth" value="{{ old('unilevel_max_depth', 10) }}" 
                                           min="1" max="20">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <button type="button" class="btn btn-success" id="add_unilevel_level">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        Add Level
                                    </button>
                                </div>
                            </div>

                            <div id="unilevel_levels">
                                <!-- Dynamic levels will be added here -->
                            </div>
                        </div>
                    </div>

                    <!-- Forced Matrix Configuration -->
                    <div class="card mt-4" id="matrix_config" style="display: none;">
                        <div class="card-header">
                            <h6 class="mb-0">Forced Matrix Configuration</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="matrix_width" class="form-label">Matrix Width</label>
                                    <input type="number" class="form-control" id="matrix_width" 
                                           name="matrix_width" value="{{ old('matrix_width', 2) }}" 
                                           min="2" max="10">
                                    <div class="form-text">Number of positions per level (2 = binary, 3 = ternary, etc.)</div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="matrix_depth" class="form-label">Matrix Depth</label>
                                    <input type="number" class="form-control" id="matrix_depth" 
                                           name="matrix_depth" value="{{ old('matrix_depth', 10) }}" 
                                           min="1" max="20">
                                    <div class="form-text">Number of levels deep</div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="matrix_payment_type" class="form-label">Payment Type</label>
                                    <select class="form-select" id="matrix_payment_type" name="matrix_payment_type">
                                        <option value="percentage" {{ old('matrix_payment_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        <option value="fixed_amount" {{ old('matrix_payment_type') == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <button type="button" class="btn btn-success" id="add_matrix_level">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        Add Level
                                    </button>
                                </div>
                            </div>

                            <div id="matrix_levels">
                                <!-- Dynamic levels will be added here -->
                            </div>
                        </div>
                    </div>

                    <!-- Profit Sharing Configuration -->
                    <div class="card mt-4" id="profit_sharing_config" style="display: none;">
                        <div class="card-header">
                            <h6 class="mb-0">Profit Sharing Configuration</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="profit_sharing_percentage" class="form-label">Percentage (%)</label>
                                    <input type="number" class="form-control" id="profit_sharing_percentage" 
                                           name="profit_sharing_percentage" value="{{ old('profit_sharing_percentage') }}" 
                                           step="0.01" min="0" max="100">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="profit_sharing_basis" class="form-label">Basis</label>
                                    <select class="form-select" id="profit_sharing_basis" name="profit_sharing_basis">
                                        <option value="total_volume" {{ old('profit_sharing_basis') == 'total_volume' ? 'selected' : '' }}>Total Volume</option>
                                        <option value="personal_volume" {{ old('profit_sharing_basis') == 'personal_volume' ? 'selected' : '' }}>Personal Volume</option>
                                        <option value="group_volume" {{ old('profit_sharing_basis') == 'group_volume' ? 'selected' : '' }}>Group Volume</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Eligibility and Limits -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Eligibility and Limits</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="requires_active_invoice" 
                                               name="requires_active_invoice" value="1" {{ old('requires_active_invoice', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="requires_active_invoice">
                                            Requires Active Invoice
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="minimum_volume" class="form-label">Minimum Volume (R$)</label>
                                    <input type="number" class="form-control" id="minimum_volume" 
                                           name="minimum_volume" value="{{ old('minimum_volume', 0) }}" 
                                           step="0.01" min="0">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="maximum_bonus_per_period" class="form-label">Maximum Bonus per Period (R$)</label>
                                    <input type="number" class="form-control" id="maximum_bonus_per_period" 
                                           name="maximum_bonus_per_period" value="{{ old('maximum_bonus_per_period') }}" 
                                           step="0.01" min="0">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="period" class="form-label">Period</label>
                                    <select class="form-select" id="period" name="period">
                                        <option value="daily" {{ old('period') == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ old('period') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ old('period') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" 
                                               name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.bonus.configurations.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Configurations
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Create Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let unilevelLevelCount = 0;
let matrixLevelCount = 0;

// Show/hide configuration sections based on bonus type
document.getElementById('bonus_type').addEventListener('change', function() {
    const bonusType = this.value;
    
    // Hide all config sections
    document.getElementById('unilevel_config').style.display = 'none';
    document.getElementById('matrix_config').style.display = 'none';
    document.getElementById('profit_sharing_config').style.display = 'none';
    
    // Show relevant section
    if (bonusType === 'unilevel') {
        document.getElementById('unilevel_config').style.display = 'block';
    } else if (bonusType === 'forced_matrix') {
        document.getElementById('matrix_config').style.display = 'block';
    } else if (bonusType === 'profit_sharing') {
        document.getElementById('profit_sharing_config').style.display = 'block';
    }
});

// Direct referral payment type toggle
document.getElementById('direct_referral_payment_type').addEventListener('change', function() {
    const paymentType = this.value;
    const percentageField = document.getElementById('direct_referral_percentage_field');
    const fixedField = document.getElementById('direct_referral_fixed_field');
    
    if (paymentType === 'percentage') {
        percentageField.style.display = 'block';
        fixedField.style.display = 'none';
    } else {
        percentageField.style.display = 'none';
        fixedField.style.display = 'block';
    }
});

// Add unilevel level
document.getElementById('add_unilevel_level').addEventListener('click', function() {
    unilevelLevelCount++;
    const maxDepth = document.getElementById('unilevel_max_depth').value;
    
    if (unilevelLevelCount > maxDepth) {
        alert('Maximum depth reached');
        return;
    }
    
    const levelsContainer = document.getElementById('unilevel_levels');
    const levelDiv = document.createElement('div');
    levelDiv.className = 'row mb-3 level-row';
    levelDiv.innerHTML = `
        <div class="col-md-2">
            <label class="form-label">Level ${unilevelLevelCount}</label>
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control" name="unilevel_levels[${unilevelLevelCount}][value]" 
                   placeholder="Enter value" step="0.01" min="0" required>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="unilevel_levels[${unilevelLevelCount}][description]" 
                   placeholder="Description (optional)">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm remove-level">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    
    levelsContainer.appendChild(levelDiv);
});

// Add matrix level
document.getElementById('add_matrix_level').addEventListener('click', function() {
    matrixLevelCount++;
    const maxDepth = document.getElementById('matrix_depth').value;
    
    if (matrixLevelCount > maxDepth) {
        alert('Maximum depth reached');
        matrixLevelCount--; // Decrement since we didn't actually add a level
        return;
    }
    
    const levelsContainer = document.getElementById('matrix_levels');
    const levelDiv = document.createElement('div');
    levelDiv.className = 'row mb-3 level-row';
    levelDiv.innerHTML = `
        <div class="col-md-2">
            <label class="form-label">Level ${matrixLevelCount}</label>
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control" name="matrix_levels[${matrixLevelCount}][value]" 
                   placeholder="Enter value" step="0.01" min="0" required>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="matrix_levels[${matrixLevelCount}][description]" 
                   placeholder="Description (optional)">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm remove-level" title="Remove Level">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    
    levelsContainer.appendChild(levelDiv);
    
    // Add animation
    levelDiv.style.opacity = '0';
    levelDiv.style.transform = 'translateY(-20px)';
    setTimeout(() => {
        levelDiv.style.transition = 'all 0.3s ease';
        levelDiv.style.opacity = '1';
        levelDiv.style.transform = 'translateY(0)';
    }, 10);
});

// Remove level functionality with improved event delegation
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-level') || e.target.closest('.remove-level')) {
        const removeBtn = e.target.classList.contains('remove-level') ? e.target : e.target.closest('.remove-level');
        const levelRow = removeBtn.closest('.level-row');
        
        if (levelRow) {
            // Add removal animation
            levelRow.style.transition = 'all 0.3s ease';
            levelRow.style.opacity = '0';
            levelRow.style.transform = 'translateX(-100%)';
            
            setTimeout(() => {
                levelRow.remove();
                // Update level labels after removal
                updateMatrixLevelLabels();
            }, 300);
        }
    }
});

// Function to update level labels after removal
function updateMatrixLevelLabels() {
    const levelRows = document.querySelectorAll('#matrix_levels .level-row');
    levelRows.forEach((row, index) => {
        const label = row.querySelector('label');
        if (label) {
            label.textContent = `Level ${index + 1}`;
        }
        
        // Update input names to maintain sequential numbering
        const valueInput = row.querySelector('input[name*="[value]"]');
        const descInput = row.querySelector('input[name*="[description]"]');
        
        if (valueInput) {
            valueInput.name = `matrix_levels[${index + 1}][value]`;
        }
        if (descInput) {
            descInput.name = `matrix_levels[${index + 1}][description]`;
        }
    });
    
    // Update the counter to match actual levels
    matrixLevelCount = levelRows.length;
}

// Form submission
document.getElementById('bonusConfigForm').addEventListener('submit', function(e) {
    // Validate that at least one level is configured for unilevel/matrix
    const bonusType = document.getElementById('bonus_type').value;
    const unilevelLevels = document.querySelectorAll('#unilevel_levels .level-row').length;
    const matrixLevels = document.querySelectorAll('#matrix_levels .level-row').length;
    
    if ((bonusType === 'unilevel' && unilevelLevels === 0) || 
        (bonusType === 'forced_matrix' && matrixLevels === 0)) {
        e.preventDefault();
        alert('Please add at least one level for the selected bonus type.');
    }
});
</script>
@endpush
@endsection
