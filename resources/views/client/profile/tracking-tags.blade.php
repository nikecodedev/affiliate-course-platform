@extends('layouts.client')

@section('title', 'Tracking Tags')
@section('page-title', 'Tracking Tags')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-code-square me-2"></i>
                    Tracking Tags Configuration
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

                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('client.profile.update-tracking-tags') }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Facebook Pixel -->
                    <div class="mb-4">
                        <label for="facebook_pixel_id" class="form-label">
                            <i class="bi bi-facebook me-2"></i>
                            Facebook Pixel ID
                        </label>
                        <input type="text" class="form-control @error('facebook_pixel_id') is-invalid @enderror" 
                               id="facebook_pixel_id" name="facebook_pixel_id" 
                               value="{{ old('facebook_pixel_id', $client->facebook_pixel_id) }}"
                               placeholder="Enter your Facebook Pixel ID">
                        <div class="form-text">
                            Your Facebook Pixel ID for tracking conversions and events.
                        </div>
                        @error('facebook_pixel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Google Tag Manager -->
                    <div class="mb-4">
                        <label for="google_tag_manager_id" class="form-label">
                            <i class="bi bi-google me-2"></i>
                            Google Tag Manager ID
                        </label>
                        <input type="text" class="form-control @error('google_tag_manager_id') is-invalid @enderror" 
                               id="google_tag_manager_id" name="google_tag_manager_id" 
                               value="{{ old('google_tag_manager_id', $client->google_tag_manager_id) }}"
                               placeholder="Enter your Google Tag Manager ID">
                        <div class="form-text">
                            Your Google Tag Manager container ID (format: GTM-XXXXXXX).
                        </div>
                        @error('google_tag_manager_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Google Analytics -->
                    <div class="mb-4">
                        <label for="google_analytics_id" class="form-label">
                            <i class="bi bi-graph-up me-2"></i>
                            Google Analytics ID
                        </label>
                        <input type="text" class="form-control @error('google_analytics_id') is-invalid @enderror" 
                               id="google_analytics_id" name="google_analytics_id" 
                               value="{{ old('google_analytics_id', $client->google_analytics_id) }}"
                               placeholder="Enter your Google Analytics ID">
                        <div class="form-text">
                            Your Google Analytics measurement ID (format: G-XXXXXXXXXX).
                        </div>
                        @error('google_analytics_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Custom Tracking Code -->
                    <div class="mb-4">
                        <label for="custom_tracking_code" class="form-label">
                            <i class="bi bi-code-slash me-2"></i>
                            Custom Tracking Code
                        </label>
                        <textarea class="form-control @error('custom_tracking_code') is-invalid @enderror" 
                                  id="custom_tracking_code" name="custom_tracking_code" 
                                  rows="6" placeholder="Enter your custom tracking code here...">{{ old('custom_tracking_code', $client->custom_tracking_code) }}</textarea>
                        <div class="form-text">
                            Any custom tracking code or scripts you want to include (HTML/JavaScript).
                        </div>
                        @error('custom_tracking_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>
                            Update Tracking Tags
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Tracking Code Preview -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-eye me-2"></i>
                    Generated Tracking Code
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Your personalized tracking code based on the configured tags:
                </p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="generateTrackingCode()">
                        <i class="bi bi-arrow-clockwise me-2"></i>
                        Generate Code
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="downloadTrackingCode()">
                        <i class="bi bi-download me-2"></i>
                        Download Code
                    </button>
                </div>
            </div>
        </div>

        <!-- Help Information -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-question-circle me-2"></i>
                    How to Get Your IDs
                </h6>
            </div>
            <div class="card-body">
                <div class="accordion" id="helpAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#facebookHelp">
                                Facebook Pixel
                            </button>
                        </h2>
                        <div id="facebookHelp" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <small>1. Go to Facebook Business Manager<br>
                                2. Navigate to Events Manager<br>
                                3. Create a new Pixel<br>
                                4. Copy the Pixel ID</small>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gtmHelp">
                                Google Tag Manager
                            </button>
                        </h2>
                        <div id="gtmHelp" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <small>1. Go to Google Tag Manager<br>
                                2. Create a new container<br>
                                3. Copy the Container ID (GTM-XXXXXXX)</small>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gaHelp">
                                Google Analytics
                            </button>
                        </h2>
                        <div id="gaHelp" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <small>1. Go to Google Analytics<br>
                                2. Create a new property<br>
                                3. Copy the Measurement ID (G-XXXXXXXXXX)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Configuration -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Current Configuration
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Facebook Pixel:</strong>
                    <span class="badge {{ $client->facebook_pixel_id ? 'bg-success' : 'bg-secondary' }}">
                        {{ $client->facebook_pixel_id ? 'Configured' : 'Not Set' }}
                    </span>
                </div>
                <div class="mb-2">
                    <strong>Google Tag Manager:</strong>
                    <span class="badge {{ $client->google_tag_manager_id ? 'bg-success' : 'bg-secondary' }}">
                        {{ $client->google_tag_manager_id ? 'Configured' : 'Not Set' }}
                    </span>
                </div>
                <div class="mb-2">
                    <strong>Google Analytics:</strong>
                    <span class="badge {{ $client->google_analytics_id ? 'bg-success' : 'bg-secondary' }}">
                        {{ $client->google_analytics_id ? 'Configured' : 'Not Set' }}
                    </span>
                </div>
                <div class="mb-0">
                    <strong>Custom Code:</strong>
                    <span class="badge {{ $client->custom_tracking_code ? 'bg-success' : 'bg-secondary' }}">
                        {{ $client->custom_tracking_code ? 'Configured' : 'Not Set' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tracking Code Modal -->
<div class="modal fade" id="trackingCodeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-code-square me-2"></i>
                    Generated Tracking Code
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="trackingCodeContent" class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="copyTrackingCode()">
                    <i class="bi bi-clipboard me-2"></i>
                    Copy Code
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function generateTrackingCode() {
        // Show loading state
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generating...';
        btn.disabled = true;

        // Make AJAX request to generate tracking code
        fetch('{{ route("client.profile.generate-tracking-code") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('trackingCodeContent').textContent = data.tracking_code;
                new bootstrap.Modal(document.getElementById('trackingCodeModal')).show();
            } else {
                alert('Error generating tracking code');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error generating tracking code');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function downloadTrackingCode() {
        window.location.href = '{{ route("client.profile.download-tracking-code") }}';
    }

    function copyTrackingCode() {
        const code = document.getElementById('trackingCodeContent').textContent;
        navigator.clipboard.writeText(code).then(() => {
            // Show success message
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check me-2"></i>Copied!';
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-primary');
            }, 2000);
        });
    }

    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() !== '') {
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                }
            });
        });
    });
</script>
@endpush
@endsection
