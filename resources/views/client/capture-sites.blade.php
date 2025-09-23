@extends('layouts.client')

@section('title', 'Capture Sites')
@section('page-title', 'Capture Sites')

@section('content')
<div class="row">
    <!-- Capture Sites Overview -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-link-45deg me-2"></i>
                    Your Capture Sites
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Capture sites are landing pages designed to convert visitors into leads. Use these links in your marketing campaigns 
                    to track conversions and manage your leads effectively.
                </p>
                
                <!-- Main Capture Site -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-star-fill me-2"></i>
                                    Primary Capture Site
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="mainCaptureUrl" 
                                               value="{{ route('client.capture.main', ['code' => auth('client')->user()->id]) }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('mainCaptureUrl')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Visits:</strong> {{ $stats['main_visits'] ?? 0 }}
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Conversions:</strong> {{ $stats['main_conversions'] ?? 0 }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="bi bi-eye display-4 text-primary mb-3"></i>
                                <h5>Preview Site</h5>
                                <p class="text-muted">See how your capture site looks to visitors</p>
                                <a href="{{ route('client.capture.main', ['code' => auth('client')->user()->id]) }}" 
                                   target="_blank" class="btn btn-primary">
                                    <i class="bi bi-eye me-2"></i>
                                    Preview
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Capture Sites -->
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="card border-secondary">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-layers me-2"></i>
                                    Secondary Capture Site
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="secondaryCaptureUrl" 
                                               value="{{ route('client.capture.secondary', ['code' => auth('client')->user()->id]) }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('secondaryCaptureUrl')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Visits:</strong> {{ $stats['secondary_visits'] ?? 0 }}
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Conversions:</strong> {{ $stats['secondary_conversions'] ?? 0 }}
                                        </small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('client.capture.secondary', ['code' => auth('client')->user()->id]) }}" 
                                       target="_blank" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-eye me-2"></i>
                                        Preview
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-phone me-2"></i>
                                    Mobile Capture Site
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="mobileCaptureUrl" 
                                               value="{{ route('client.capture.main', ['code' => auth('client')->user()->id, 'mobile' => 1]) }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('mobileCaptureUrl')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Visits:</strong> {{ $stats['mobile_visits'] ?? 0 }}
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Conversions:</strong> {{ $stats['mobile_conversions'] ?? 0 }}
                                        </small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('client.capture.main', ['code' => auth('client')->user()->id, 'mobile' => 1]) }}" 
                                       target="_blank" class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-phone me-2"></i>
                                        Preview Mobile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Customization Options -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-palette me-2"></i>
                    Customization Options
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Colors & Branding</h6>
                            <small class="text-muted">Customize colors and add your branding</small>
                        </div>
                        <span class="badge bg-success">Available</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Custom Headlines</h6>
                            <small class="text-muted">Add your own headlines and descriptions</small>
                        </div>
                        <span class="badge bg-success">Available</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Lead Magnets</h6>
                            <small class="text-muted">Add downloadable content to increase conversions</small>
                        </div>
                        <span class="badge bg-warning">Premium</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">A/B Testing</h6>
                            <small class="text-muted">Test different versions to optimize conversions</small>
                        </div>
                        <span class="badge bg-warning">Premium</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Integration Guide -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plug me-2"></i>
                    Integration Guide
                </h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="integrationAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#integration1">
                                Social Media Integration
                            </button>
                        </h2>
                        <div id="integration1" class="accordion-collapse collapse show" data-bs-parent="#integrationAccordion">
                            <div class="accordion-body">
                                <p>Use these links in your social media posts:</p>
                                <ul>
                                    <li><strong>Facebook:</strong> Post the capture site URL directly</li>
                                    <li><strong>Instagram:</strong> Add to your bio or story links</li>
                                    <li><strong>Twitter:</strong> Include in tweets with relevant hashtags</li>
                                    <li><strong>LinkedIn:</strong> Share in posts or add to your profile</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#integration2">
                                Email Marketing
                            </button>
                        </h2>
                        <div id="integration2" class="accordion-collapse collapse" data-bs-parent="#integrationAccordion">
                            <div class="accordion-body">
                                <p>Include capture sites in your email campaigns:</p>
                                <ul>
                                    <li><strong>Newsletters:</strong> Add as featured links</li>
                                    <li><strong>CTAs:</strong> Use as call-to-action buttons</li>
                                    <li><strong>Signatures:</strong> Add to email signatures</li>
                                    <li><strong>Follow-ups:</strong> Include in automated sequences</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#integration3">
                                Website Integration
                            </button>
                        </h2>
                        <div id="integration3" class="accordion-collapse collapse" data-bs-parent="#integrationAccordion">
                            <div class="accordion-body">
                                <p>Integrate with your existing website:</p>
                                <ul>
                                    <li><strong>Pop-ups:</strong> Redirect to capture sites</li>
                                    <li><strong>Banners:</strong> Link from homepage banners</li>
                                    <li><strong>Sidebar:</strong> Add as sidebar widgets</li>
                                    <li><strong>Footer:</strong> Include in footer links</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics & Performance -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Analytics & Performance
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="border-end">
                            <h4 class="text-primary">{{ $stats['total_visits'] ?? 0 }}</h4>
                            <small class="text-muted">Total Visits</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border-end">
                            <h4 class="text-success">{{ $stats['total_conversions'] ?? 0 }}</h4>
                            <small class="text-muted">Total Conversions</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border-end">
                            <h4 class="text-warning">{{ number_format($stats['conversion_rate'] ?? 0, 1) }}%</h4>
                            <small class="text-muted">Conversion Rate</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4 class="text-info">{{ $stats['total_leads'] ?? 0 }}</h4>
                        <small class="text-muted">Total Leads Generated</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        element.setSelectionRange(0, 99999); // For mobile devices
        
        try {
            document.execCommand('copy');
            
            // Show success message
            const button = element.nextElementSibling;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check"></i>';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');
            
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        } catch (err) {
            alert('Failed to copy URL. Please copy manually.');
        }
    }
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
@endsection
