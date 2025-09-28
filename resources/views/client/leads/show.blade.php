@extends('layouts.client')

@section('title', 'Lead Details')
@section('page-title', 'Lead Details')

@section('page-actions')
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
        <a href="{{ route('client.leads.edit', $lead) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Edit Lead
        </a>
        <a href="{{ route('client.leads.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Leads
        </a>
    </div>
</div>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

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
                        <span class="badge bg-{{ $lead->status_badge_color }} fs-6">
                            {{ $lead->status_badge_text }}
                        </span>
                        @if($lead->contacted)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Contacted
                            </span>
                        @endif
                        @if($lead->converted)
                            <span class="badge bg-success">
                                <i class="bi bi-trophy me-1"></i>Converted
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Full Name</label>
                            <p class="mb-0 fw-bold">{{ $lead->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $lead->email }}" class="text-decoration-none">
                                    {{ $lead->email }}
                                </a>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone</label>
                            <p class="mb-0">
                                @if($lead->phone)
                                    <a href="tel:{{ $lead->phone }}" class="text-decoration-none">
                                        {{ $lead->phone }}
                                    </a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Source</label>
                            <p class="mb-0">
                                <span class="badge bg-light text-dark">{{ $lead->source }}</span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Created</label>
                            <p class="mb-0">{{ $lead->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Last Updated</label>
                            <p class="mb-0">{{ $lead->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                @if($lead->notes)
                <div class="mb-3">
                    <label class="form-label text-muted">Notes</label>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $lead->notes }}</p>
                    </div>
                </div>
                @endif

                @if($lead->contact_notes)
                <div class="mb-3">
                    <label class="form-label text-muted">Contact Notes</label>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $lead->contact_notes }}</p>
                        @if($lead->contacted_at)
                            <small class="text-muted">Contacted on: {{ $lead->contacted_at->format('d/m/Y H:i') }}</small>
                        @endif
                    </div>
                </div>
                @endif

                @if($lead->converted && $lead->conversion_value)
                <div class="mb-3">
                    <label class="form-label text-muted">Conversion Value</label>
                    <p class="mb-0 fw-bold text-success">R$ {{ number_format($lead->conversion_value, 2, ',', '.') }}</p>
                </div>
                @endif
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
                    @if(!$lead->contacted)
                    <div class="col-md-6 mb-3">
                        <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="bi bi-telephone me-2"></i>
                            Mark as Contacted
                        </button>
                    </div>
                    @endif
                    
                    @if(!$lead->converted)
                    <div class="col-md-6 mb-3">
                        <button type="button" class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#convertModal">
                            <i class="bi bi-trophy me-2"></i>
                            Mark as Converted
                        </button>
                    </div>
                    @endif
                    
                    <div class="col-md-6 mb-3">
                        <a href="mailto:{{ $lead->email }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-envelope me-2"></i>
                            Send Email
                        </a>
                    </div>
                    
                    @if($lead->phone)
                    <div class="col-md-6 mb-3">
                        <a href="tel:{{ $lead->phone }}" class="btn btn-outline-warning w-100">
                            <i class="bi bi-telephone me-2"></i>
                            Call Now
                        </a>
                    </div>
                    @endif
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
                        <span class="fw-bold">{{ $lead->created_at->diffInDays(now()) }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Status</span>
                        <span class="badge bg-{{ $lead->status_badge_color }}">
                            {{ $lead->status_badge_text }}
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Contacted</span>
                        <span class="badge bg-{{ $lead->contacted ? 'success' : 'secondary' }}">
                            {{ $lead->contacted ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex justify-content-between">
                        <span>Converted</span>
                        <span class="badge bg-{{ $lead->converted ? 'success' : 'secondary' }}">
                            {{ $lead->converted ? 'Yes' : 'No' }}
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
            <form method="POST" action="{{ route('client.leads.mark-contacted', $lead) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="contact_notes" class="form-label">Contact Notes *</label>
                        <textarea class="form-control @error('contact_notes') is-invalid @enderror" 
                                  id="contact_notes" name="contact_notes" rows="4" 
                                  placeholder="Describe how the contact was made and what was discussed..." required>{{ old('contact_notes') }}</textarea>
                        @error('contact_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
            <form method="POST" action="{{ route('client.leads.mark-converted', $lead) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="conversion_value" class="form-label">Conversion Value (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control @error('conversion_value') is-invalid @enderror" 
                                   id="conversion_value" name="conversion_value" 
                                   value="{{ old('conversion_value') }}" 
                                   step="0.01" min="0" placeholder="0.00">
                            @error('conversion_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
