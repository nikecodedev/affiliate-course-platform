@extends('layouts.admin')

@section('title', 'Create Bonus Configuration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus me-2"></i>
                        Create Bonus Configuration
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.bonus-configurations.global.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Configurations
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.bonus-configurations.global.store') }}" method="POST" id="bonusForm">
                    @csrf
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

                        <!-- Basic Configuration -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-cog me-2"></i>
                                    Basic Configuration
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Configuration Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="e.g., Unilevel Bonus Configuration" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="type" class="form-label">Bonus Type</label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Select Bonus Type</option>
                                        @foreach($bonusTypes as $key => $label)
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="2" 
                                              placeholder="Optional description for this configuration">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Configuration Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-sliders-h me-2"></i>
                                    Configuration Settings
                                </h5>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="max_depth" class="form-label">Max Depth</label>
                                    <input type="number" class="form-control @error('max_depth') is-invalid @enderror" 
                                           id="max_depth" name="max_depth" value="{{ old('max_depth', 10) }}" 
                                           min="1" max="20" required>
                                    @error('max_depth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="max_width" class="form-label">Max Width</label>
                                    <input type="number" class="form-control @error('max_width') is-invalid @enderror" 
                                           id="max_width" name="max_width" value="{{ old('max_width', 2) }}" 
                                           min="1" max="10" required>
                                    <small class="text-muted">Only for Forced Matrix</small>
                                    @error('max_width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="min_sale_amount" class="form-label">Min Sale Amount</label>
                                    <input type="number" class="form-control @error('min_sale_amount') is-invalid @enderror" 
                                           id="min_sale_amount" name="min_sale_amount" value="{{ old('min_sale_amount', 0) }}" 
                                           min="0" step="0.01" required>
                                    @error('min_sale_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label">Options</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_percentage" name="is_percentage" 
                                               value="1" {{ old('is_percentage') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_percentage">
                                            Use Percentage (Default)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="requires_active_invoice" name="requires_active_invoice" 
                                               value="1" {{ old('requires_active_invoice', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="requires_active_invoice">
                                            Requires Active Invoice
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bonus Levels -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-layer-group me-2"></i>
                                    Bonus Levels
                                </h5>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Configure the bonus amounts for each level. You can add up to 20 levels.
                                </div>
                            </div>
                            <div class="col-12">
                                <div id="levels-container">
                                    <!-- Levels will be added dynamically -->
                                </div>
                                <button type="button" class="btn btn-outline-primary" id="add-level-btn">
                                    <i class="fas fa-plus me-1"></i>
                                    Add Level
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.bonus-configurations.global.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Create Configuration
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
    let levelCount = 0;
    const levelsContainer = document.getElementById('levels-container');
    const addLevelBtn = document.getElementById('add-level-btn');
    const typeSelect = document.getElementById('type');

    // Add initial level
    addLevel();

    // Add level button click
    addLevelBtn.addEventListener('click', addLevel);

    // Type change handler
    typeSelect.addEventListener('change', function() {
        const maxWidthField = document.getElementById('max_width');
        if (this.value === 'forced_matrix') {
            maxWidthField.closest('.form-group').style.display = 'block';
        } else {
            maxWidthField.closest('.form-group').style.display = 'none';
        }
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
                        <div class="col-md-2">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control" name="levels[${levelCount}][amount]" 
                                   step="0.01" min="0" required placeholder="0.00">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select class="form-control" name="levels[${levelCount}][is_percentage]">
                                <option value="0">Fixed Amount</option>
                                <option value="1">Percentage</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="levels[${levelCount}][description]" 
                                   placeholder="Optional description">
                        </div>
                        <div class="col-md-2">
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
    }

    // Form submission validation
    document.getElementById('bonusForm').addEventListener('submit', function(e) {
        const levels = levelsContainer.querySelectorAll('.level-item');
        if (levels.length === 0) {
            e.preventDefault();
            alert('Please add at least one bonus level');
            return;
        }
    });
});
</script>
@endsection
