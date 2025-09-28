@extends('layouts.client')

@section('title', 'New Lead')
@section('page-title', 'Add New Lead')

@section('page-actions')
<a href="{{ route('client.leads.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back to Leads
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Informações do Lead
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('client.leads.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}" 
                                       placeholder="(11) 99999-9999">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="source" class="form-label">Source <span class="text-danger">*</span></label>
                                <select class="form-select @error('source') is-invalid @enderror" 
                                        id="source" 
                                        name="source" 
                                        required>
                                    <option value="">Select source</option>
                                    <option value="website" {{ old('source') === 'website' ? 'selected' : '' }}>Website</option>
                                    <option value="facebook" {{ old('source') === 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="instagram" {{ old('source') === 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="google" {{ old('source') === 'google' ? 'selected' : '' }}>Google Ads</option>
                                    <option value="indicacao" {{ old('source') === 'indicacao' ? 'selected' : '' }}>Referral</option>
                                    <option value="evento" {{ old('source') === 'evento' ? 'selected' : '' }}>Event</option>
                                    <option value="outro" {{ old('source') === 'outro' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="">Select status</option>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="custom_source" class="form-label">Custom Source</label>
                                <input type="text" 
                                       class="form-control @error('custom_source') is-invalid @enderror" 
                                       id="custom_source" 
                                       name="custom_source" 
                                       value="{{ old('custom_source') }}" 
                                       placeholder="Enter if you selected 'Other'"
                                       style="display: none;">
                                @error('custom_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="4" 
                                  placeholder="Add notes about this lead...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Custom Fields Section -->
                    <div class="mb-3">
                        <label class="form-label">Custom Fields</label>
                        <div id="customFields">
                            <!-- Custom fields will be added here dynamically -->
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCustomField()">
                            <i class="bi bi-plus me-1"></i>Add Field
                        </button>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('client.leads.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Save Lead
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Tips -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-lightbulb me-2"></i>Quick Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Full name</strong> makes identification easier
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Valid email</strong> is required for contact
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Source</strong> helps identify effective channels
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Notes</strong> are important for tracking
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Use <strong>custom fields</strong> for specific information
                    </li>
                </ul>
            </div>
        </div>

        <!-- Lead Sources Stats -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>Most Used Sources
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span>Website</span>
                        <span class="fw-bold">45%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" style="width: 45%"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span>Facebook</span>
                        <span class="fw-bold">30%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 30%"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span>Instagram</span>
                        <span class="fw-bold">15%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 15%"></div>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex justify-content-between">
                        <span>Outros</span>
                        <span class="fw-bold">10%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: 10%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let customFieldCount = 0;

// Show/hide custom source field
document.getElementById('source').addEventListener('change', function() {
    const customSourceField = document.getElementById('custom_source');
    if (this.value === 'outro') {
        customSourceField.style.display = 'block';
        customSourceField.required = true;
    } else {
        customSourceField.style.display = 'none';
        customSourceField.required = false;
        customSourceField.value = '';
    }
});

// Phone mask
document.getElementById('phone').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 0) {
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        } else {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        }
    }
    this.value = value;
});

// Add custom field
function addCustomField() {
    customFieldCount++;
    const container = document.getElementById('customFields');
    const fieldHtml = `
        <div class="row mb-2" id="customField${customFieldCount}">
            <div class="col-md-5">
                <input type="text" class="form-control form-control-sm" 
                       name="custom_field_names[]" placeholder="Nome do campo">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" 
                       name="custom_field_values[]" placeholder="Valor">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm" 
                        onclick="removeCustomField(${customFieldCount})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', fieldHtml);
}

// Remove custom field
function removeCustomField(fieldId) {
    document.getElementById(`customField${fieldId}`).remove();
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Salvando...';
    });
});

// Auto-focus on name field
document.getElementById('name').focus();
</script>
@endsection
