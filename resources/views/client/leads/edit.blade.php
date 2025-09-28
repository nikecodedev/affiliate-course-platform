@extends('layouts.client')

@section('title', 'Edit Lead')
@section('page-title', 'Edit Lead')

@section('page-actions')
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
        <a href="{{ route('client.leads.show', $lead) }}" class="btn btn-outline-primary">
            <i class="bi bi-eye me-2"></i>View Lead
        </a>
        <a href="{{ route('client.leads.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Leads
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pencil me-2"></i>Edit Lead Information
                </h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('client.leads.update', $lead) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $lead->name) }}" 
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
                                       value="{{ old('email', $lead->email) }}" 
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
                                <label for="phone" class="form-label">Telefone</label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $lead->phone) }}" 
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
                                    <option value="website" {{ old('source', $lead->source) === 'website' ? 'selected' : '' }}>Website</option>
                                    <option value="facebook" {{ old('source', $lead->source) === 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="instagram" {{ old('source', $lead->source) === 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="google" {{ old('source', $lead->source) === 'google' ? 'selected' : '' }}>Google Ads</option>
                                    <option value="indicacao" {{ old('source', $lead->source) === 'indicacao' ? 'selected' : '' }}>Referral</option>
                                    <option value="evento" {{ old('source', $lead->source) === 'evento' ? 'selected' : '' }}>Event</option>
                                    <option value="outro" {{ old('source', $lead->source) === 'outro' ? 'selected' : '' }}>Other</option>
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
                                    <option value="active" {{ old('status', $lead->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="pending" {{ old('status', $lead->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="inactive" {{ old('status', $lead->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                  placeholder="Add notes about this lead...">{{ old('notes', $lead->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contact_notes" class="form-label">Contact Notes</label>
                        <textarea class="form-control @error('contact_notes') is-invalid @enderror" 
                                  id="contact_notes" 
                                  name="contact_notes" 
                                  rows="3" 
                                  placeholder="Add notes about contact with this lead...">{{ old('contact_notes', $lead->contact_notes) }}</textarea>
                        @error('contact_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('client.leads.show', $lead) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Lead
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Lead Status -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Current Status
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Status</span>
                        <span class="badge bg-{{ $lead->status_badge_color }}">
                            {{ $lead->status_badge_text }}
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Contacted</span>
                        <span class="badge bg-{{ $lead->contacted ? 'success' : 'secondary' }}">
                            {{ $lead->contacted ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Converted</span>
                        <span class="badge bg-{{ $lead->converted ? 'success' : 'secondary' }}">
                            {{ $lead->converted ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
                @if($lead->conversion_value)
                <div class="mb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Conversion Value</span>
                        <span class="fw-bold text-success">R$ {{ number_format($lead->conversion_value, 2, ',', '.') }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Lead Timeline -->
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
                            <p class="text-muted mb-0">{{ $lead->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($lead->contacted_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Contacted</h6>
                            <p class="text-muted mb-0">{{ $lead->contacted_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($lead->converted_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Converted</h6>
                            <p class="text-muted mb-0">{{ $lead->converted_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Last Updated</h6>
                            <p class="text-muted mb-0">{{ $lead->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
@endpush

@push('scripts')
<script>
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

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
    });
    
    // Initialize custom source field visibility
    const sourceSelect = document.getElementById('source');
    const customSourceField = document.getElementById('custom_source');
    if (sourceSelect.value === 'outro') {
        customSourceField.style.display = 'block';
        customSourceField.required = true;
    }
});

// Auto-focus on name field
document.getElementById('name').focus();
</script>
@endpush
