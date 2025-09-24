@extends('layouts.admin')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Settings Tabs -->
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('admin.settings.customization') }}" target="_blank">
                    <i class="bi bi-palette me-2"></i>Customization
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
                    <i class="bi bi-search me-2"></i>SEO
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tracking-tab" data-bs-toggle="tab" data-bs-target="#tracking" type="button" role="tab">
                    <i class="bi bi-graph-up me-2"></i>Tracking
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="recaptcha-tab" data-bs-toggle="tab" data-bs-target="#recaptcha" type="button" role="tab">
                    <i class="bi bi-shield-check me-2"></i>reCAPTCHA
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial" type="button" role="tab">
                    <i class="bi bi-currency-dollar me-2"></i>Financial
                </button>
            </li>
        </ul>

        <div class="tab-content" id="settingsTabsContent">
            <!-- SEO Settings -->
            <div class="tab-pane fade show active" id="seo" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">SEO Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="seoForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="site_title" class="form-label">Site Title</label>
                                        <input type="text" class="form-control" id="site_title" name="site_title" 
                                               value="{{ $groups['seo']->get('site_title')?->value ?? '' }}" 
                                               placeholder="Enter site title">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="site_description" class="form-label">Site Description</label>
                                        <textarea class="form-control" id="site_description" name="site_description" rows="3"
                                                  placeholder="Enter site description">{{ $groups['seo']->get('site_description')?->value ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="site_keywords" class="form-label">Site Keywords</label>
                                        <input type="text" class="form-control" id="site_keywords" name="site_keywords" 
                                               value="{{ $groups['seo']->get('site_keywords')?->value ?? '' }}" 
                                               placeholder="Enter keywords separated by commas">
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Save SEO Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tracking Settings -->
            <div class="tab-pane fade" id="tracking" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Tracking Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="trackingForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="google_analytics_id" class="form-label">Google Analytics ID</label>
                                        <input type="text" class="form-control" id="google_analytics_id" name="google_analytics_id" 
                                               value="{{ $groups['tracking']->get('google_analytics_id')?->value ?? '' }}" 
                                               placeholder="G-XXXXXXXXXX">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="google_tag_manager_id" class="form-label">Google Tag Manager ID</label>
                                        <input type="text" class="form-control" id="google_tag_manager_id" name="google_tag_manager_id" 
                                               value="{{ $groups['tracking']->get('google_tag_manager_id')?->value ?? '' }}" 
                                               placeholder="GTM-XXXXXXX">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="meta_pixel_id" class="form-label">Meta Pixel ID</label>
                                        <input type="text" class="form-control" id="meta_pixel_id" name="meta_pixel_id" 
                                               value="{{ $groups['tracking']->get('meta_pixel_id')?->value ?? '' }}" 
                                               placeholder="Enter Meta Pixel ID">
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Save Tracking Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- reCAPTCHA Settings -->
            <div class="tab-pane fade" id="recaptcha" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">reCAPTCHA Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="recaptchaForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="recaptcha_site_key" class="form-label">Site Key</label>
                                        <input type="text" class="form-control" id="recaptcha_site_key" name="recaptcha_site_key" 
                                               value="{{ $groups['recaptcha']->get('recaptcha_site_key')?->value ?? '' }}" 
                                               placeholder="Enter reCAPTCHA site key">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="recaptcha_secret_key" class="form-label">Secret Key</label>
                                        <input type="text" class="form-control" id="recaptcha_secret_key" name="recaptcha_secret_key" 
                                               value="{{ $groups['recaptcha']->get('recaptcha_secret_key')?->value ?? '' }}" 
                                               placeholder="Enter reCAPTCHA secret key">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="recaptcha_enabled" name="recaptcha_enabled" 
                                               {{ ($groups['recaptcha']->get('recaptcha_enabled')?->value ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="recaptcha_enabled">
                                            Enable reCAPTCHA
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Save reCAPTCHA Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Financial Settings -->
            <div class="tab-pane fade" id="financial" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Financial Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="financialForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="withdrawal_days" class="form-label">Withdrawal Days</label>
                                        <input type="text" class="form-control" id="withdrawal_days" name="withdrawal_days" 
                                               value="{{ $groups['financial']->get('withdrawal_days')?->value ?? '' }}" 
                                               placeholder="1,2,3,4,5">
                                        <small class="form-text text-muted">Comma-separated days of the week (1=Monday, 7=Sunday)</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="withdrawal_start_time" class="form-label">Start Time</label>
                                        <input type="time" class="form-control" id="withdrawal_start_time" name="withdrawal_start_time" 
                                               value="{{ $groups['financial']->get('withdrawal_start_time')?->value ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="withdrawal_end_time" class="form-label">End Time</label>
                                        <input type="time" class="form-control" id="withdrawal_end_time" name="withdrawal_end_time" 
                                               value="{{ $groups['financial']->get('withdrawal_end_time')?->value ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="withdrawal_fee_type" class="form-label">Fee Type</label>
                                        <select class="form-control" id="withdrawal_fee_type" name="withdrawal_fee_type">
                                            <option value="percentage" {{ ($groups['financial']->get('withdrawal_fee_type')?->value ?? '') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                            <option value="fixed" {{ ($groups['financial']->get('withdrawal_fee_type')?->value ?? '') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="withdrawal_fee_value" class="form-label">Fee Value</label>
                                        <input type="number" step="0.01" class="form-control" id="withdrawal_fee_value" name="withdrawal_fee_value" 
                                               value="{{ $groups['financial']->get('withdrawal_fee_value')?->value ?? '' }}" 
                                               placeholder="5.00">
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Save Financial Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Form submission handlers
document.getElementById('seoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    saveSettings('seo', this);
});

document.getElementById('trackingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    saveSettings('tracking', this);
});

document.getElementById('recaptchaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    saveSettings('recaptcha', this);
});

document.getElementById('financialForm').addEventListener('submit', function(e) {
    e.preventDefault();
    saveSettings('financial', this);
});

function saveSettings(group, form) {
    const formData = new FormData(form);
    
    fetch('{{ route("admin.settings.update") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Settings saved successfully!', 'success');
        } else {
            showAlert('Error saving settings: ' + (data.message || 'Unknown error'), 'danger');
        }
    })
    .catch(error => {
        showAlert('Error saving settings: ' + error.message, 'danger');
    });
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.tab-content');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endsection