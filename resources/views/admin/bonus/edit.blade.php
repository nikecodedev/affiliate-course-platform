@extends('layouts.admin')

@section('title', 'Edit ' . $bonusSetting->getTypeDisplayName() . ' Bonus')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Configure {{ $bonusSetting->getTypeDisplayName() }} Bonus
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.bonus.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Bonus Settings
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.bonus.update', $bonusSetting->bonus_type) }}" method="POST" id="bonusForm">
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
                                           value="1" {{ $bonusSetting->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Activate {{ $bonusSetting->getTypeDisplayName() }} Bonus</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                        @if($bonusSetting->bonus_type === 'direct_referral')
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
                                        <select class="form-control @error('payment_mode') is-invalid @enderror" 
                                                id="payment_mode" name="payment_mode" required>
                                            <option value="">Select Payment Mode</option>
                                            @foreach($paymentModes as $mode => $label)
                                                <option value="{{ $mode }}" {{ old('payment_mode', $bonusSetting->payment_mode) == $mode ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('payment_mode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="value" class="form-label">Bonus Value</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                                   id="value" name="value" 
                                                   value="{{ old('value', $bonusSetting->getLevelConfig(1)['value'] ?? '') }}" 
                                                   step="0.01" min="0" required>
                                            <span class="input-group-text" id="value-unit">R$</span>
                                        </div>
                                        @error('value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        @elseif($bonusSetting->bonus_type === 'unilevel')
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
                                        @php
                                            $levels = $bonusSetting->getConfiguredLevels();
                                        @endphp
                                        @if(count($levels) > 0)
                                            @foreach($levels as $level => $config)
                                                <div class="card mb-3 level-item" data-level="{{ $level }}">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <label class="form-label">Level</label>
                                                                <input type="number" class="form-control" name="levels[{{ $level }}][level]" 
                                                                       value="{{ $level }}" min="1" max="20" required readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">Mode</label>
                                                                <select class="form-control" name="levels[{{ $level }}][mode]" required>
                                                                    <option value="fixed" {{ $config['mode'] === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                                                    <option value="percentage" {{ $config['mode'] === 'percentage' ? 'selected' : '' }}>Percentage</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Value</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" name="levels[{{ $level }}][value]" 
                                                                           value="{{ $config['value'] }}" step="0.01" min="0" required>
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
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" id="add-level-btn">
                                        <i class="fas fa-plus me-1"></i>
                                        Add Level
                                    </button>
                                </div>
                            </div>

                        @elseif($bonusSetting->bonus_type === 'matrix')
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
                                        <input type="number" class="form-control @error('width') is-invalid @enderror" 
                                               id="width" name="width" 
                                               value="{{ old('width', $bonusSetting->width ?? 2) }}" 
                                               min="1" max="10" required>
                                        <div class="form-text">Number of positions per level</div>
                                        @error('width')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="depth" class="form-label">Matrix Depth</label>
                                        <input type="number" class="form-control @error('depth') is-invalid @enderror" 
                                               id="depth" name="depth" 
                                               value="{{ old('depth', $bonusSetting->depth ?? 5) }}" 
                                               min="1" max="20" required>
                                        <div class="form-text">Number of levels deep</div>
                                        @error('depth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h6 class="mt-3 mb-3">Matrix Levels Configuration</h6>
                                    <div id="levels-container">
                                        @php
                                            $levels = $bonusSetting->getConfiguredLevels();
                                        @endphp
                                        @if(count($levels) > 0)
                                            @foreach($levels as $level => $config)
                                                <div class="card mb-3 level-item" data-level="{{ $level }}">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <label class="form-label">Level</label>
                                                                <input type="number" class="form-control" name="levels[{{ $level }}][level]" 
                                                                       value="{{ $level }}" min="1" max="20" required readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">Mode</label>
                                                                <select class="form-control" name="levels[{{ $level }}][mode]" required>
                                                                    <option value="fixed" {{ $config['mode'] === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                                                    <option value="percentage" {{ $config['mode'] === 'percentage' ? 'selected' : '' }}>Percentage</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Value</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" name="levels[{{ $level }}][value]" 
                                                                           value="{{ $config['value'] }}" step="0.01" min="0" required>
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
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" id="add-level-btn">
                                        <i class="fas fa-plus me-1"></i>
                                        Add Level
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.bonus.index') }}" class="btn btn-secondary">
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let levelCount = {{ count($bonusSetting->getConfiguredLevels()) }};
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
        if (levels.length === 0 && '{{ $bonusSetting->bonus_type }}' !== 'direct_referral') {
            e.preventDefault();
            alert('Please add at least one bonus level');
            return;
        }
    });

    // Initialize value unit
    updateValueUnit();
});
</script>
@endsection
