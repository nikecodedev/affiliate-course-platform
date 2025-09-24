@extends('layouts.admin')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Settings Tabs -->
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="customization-tab" data-bs-toggle="tab" data-bs-target="#customization" type="button" role="tab">
                    <i class="bi bi-palette me-2"></i>Customization
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
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
            <!-- Customization Settings -->
            <div class="tab-pane fade show active" id="customization" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-palette me-2"></i>
                            Appearance & Customization
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

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

                        <form action="{{ route('admin.customization.update') }}" method="POST" enctype="multipart/form-data" id="customizationForm">
                            @csrf
                            <input type="hidden" name="stay_on_page" value="1">
                            
                            <!-- Company Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-building me-2"></i>
                                        Company Information
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="company_name" class="form-label">Company Name</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" 
                                               value="{{ old('company_name', \App\Models\SystemCustomization::getCompanyName()) }}"
                                               placeholder="Enter company name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="company_email" class="form-label">Company Email</label>
                                        <input type="email" class="form-control" id="company_email" name="company_email" 
                                               value="{{ old('company_email', \App\Models\SystemCustomization::getCompanyEmail()) }}"
                                               placeholder="Enter company email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="company_phone" class="form-label">Company Phone</label>
                                        <input type="text" class="form-control" id="company_phone" name="company_phone" 
                                               value="{{ old('company_phone', \App\Models\SystemCustomization::getCompanyPhone()) }}"
                                               placeholder="Enter company phone">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="company_address" class="form-label">Company Address</label>
                                        <textarea class="form-control" id="company_address" name="company_address" rows="2"
                                                  placeholder="Enter company address">{{ old('company_address', \App\Models\SystemCustomization::getCompanyAddress()) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Brand Colors -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-paint-brush me-2"></i>
                                        Brand Colors
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="primary_color" class="form-label">Primary Color</label>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" 
                                                   value="{{ old('primary_color', \App\Models\SystemCustomization::getPrimaryColor()) }}">
                                            <input type="text" class="form-control" id="primary_color_text" 
                                                   value="{{ old('primary_color', \App\Models\SystemCustomization::getPrimaryColor()) }}"
                                                   placeholder="#007bff">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="secondary_color" class="form-label">Secondary Color</label>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color" id="secondary_color" name="secondary_color" 
                                                   value="{{ old('secondary_color', \App\Models\SystemCustomization::getSecondaryColor()) }}">
                                            <input type="text" class="form-control" id="secondary_color_text" 
                                                   value="{{ old('secondary_color', \App\Models\SystemCustomization::getSecondaryColor()) }}"
                                                   placeholder="#6c757d">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Brand Assets -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-images me-2"></i>
                                        Brand Assets
                                    </h6>
                                </div>
                                
                                <!-- Logo Upload -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="bi bi-image me-2"></i>
                                                Logo
                                            </h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <img id="logo-preview" src="{{ \App\Models\SystemCustomization::getLogoUrl() }}" 
                                                     alt="Logo Preview" class="img-fluid" style="max-height: 80px;">
                                            </div>
                                            <div class="form-group">
                                                <label for="logo" class="form-label">Upload Logo</label>
                                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*" onchange="previewImage(this, 'logo-preview')">
                                                <small class="text-muted">Recommended: 200x60px, PNG/JPG format</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Favicon Upload -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="bi bi-star me-2"></i>
                                                Favicon
                                            </h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <img id="favicon-preview" src="{{ \App\Models\SystemCustomization::getFaviconUrl() }}" 
                                                     alt="Favicon Preview" class="img-fluid" style="width: 32px; height: 32px;">
                                            </div>
                                            <div class="form-group">
                                                <label for="favicon" class="form-label">Upload Favicon</label>
                                                <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*" onchange="previewImage(this, 'favicon-preview')">
                                                <small class="text-muted">Recommended: 32x32px, ICO/PNG format</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Background Upload -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="bi bi-landscape me-2"></i>
                                                Background
                                            </h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <img id="background-preview" src="{{ \App\Models\SystemCustomization::getBackgroundUrl() ?? asset('images/default-bg.jpg') }}" 
                                                     alt="Background Preview" class="img-fluid" style="max-height: 80px;">
                                            </div>
                                            <div class="form-group">
                                                <label for="background" class="form-label">Upload Background</label>
                                                <input type="file" class="form-control" id="background" name="background" accept="image/*" onchange="previewImage(this, 'background-preview')">
                                                <small class="text-muted">Recommended: 1920x1080px, JPG/PNG format</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Live Preview -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-eye me-2"></i>
                                        Live Preview
                                    </h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Login Page Preview</h6>
                                                    <div class="border rounded p-3" style="background: linear-gradient(135deg, {{ \App\Models\SystemCustomization::getPrimaryColor() }} 0%, {{ \App\Models\SystemCustomization::getSecondaryColor() }} 100%); color: white; min-height: 150px;">
                                                        <div class="text-center">
                                                            <img id="preview-logo" src="{{ \App\Models\SystemCustomization::getLogoUrl() }}" alt="Logo" style="max-height: 30px; margin-bottom: 15px;">
                                                            <h5 id="preview-company">{{ \App\Models\SystemCustomization::getCompanyName() }}</h5>
                                                            <p class="mb-0">Welcome to your platform</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Header Preview</h6>
                                                    <div class="border rounded p-3">
                                                        <nav class="navbar navbar-expand-lg" style="background-color: {{ \App\Models\SystemCustomization::getPrimaryColor() }};">
                                                            <div class="container-fluid">
                                                                <img id="preview-header-logo" src="{{ \App\Models\SystemCustomization::getLogoUrl() }}" alt="Logo" style="max-height: 25px;">
                                                                <span class="navbar-brand text-white ms-2" id="preview-header-company">{{ \App\Models\SystemCustomization::getCompanyName() }}</span>
                                                            </div>
                                                        </nav>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-warning me-2" onclick="resetCustomization()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>
                                    Reset to Default
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>
                                    Save Customizations
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="tab-pane fade" id="seo" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">SEO Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="seoForm" action="{{ route('admin.settings.seo.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="seo_title" class="form-label">
                                            <i class="bi bi-heading me-1"></i>
                                            Site Title
                                            <span class="text-muted">(Max 60 characters)</span>
                                        </label>
                                        <input type="text" class="form-control" id="seo_title" name="seo_title" 
                                               value="{{ $groups['seo']->get('seo_title')?->value ?? '' }}" 
                                               placeholder="Enter site title"
                                               maxlength="60">
                                        <div class="form-text">
                                            <span id="title-count" class="text-muted">0/60 characters</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="seo_description" class="form-label">
                                            <i class="bi bi-align-left me-1"></i>
                                            Meta Description
                                            <span class="text-muted">(Max 160 characters)</span>
                                        </label>
                                        <textarea class="form-control" id="seo_description" name="seo_description" rows="3"
                                                  placeholder="Enter site description"
                                                  maxlength="160">{{ $groups['seo']->get('seo_description')?->value ?? '' }}</textarea>
                                        <div class="form-text">
                                            <span id="description-count" class="text-muted">0/160 characters</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="seo_keywords" class="form-label">
                                            <i class="bi bi-tags me-1"></i>
                                            Meta Keywords
                                            <span class="text-muted">(Max 255 characters)</span>
                                        </label>
                                        <input type="text" class="form-control" id="seo_keywords" name="seo_keywords" 
                                               value="{{ $groups['seo']->get('seo_keywords')?->value ?? '' }}" 
                                               placeholder="Enter keywords separated by commas (e.g., affiliate, marketing, courses)"
                                               maxlength="255">
                                        <div class="form-text">
                                            <span id="keywords-count" class="text-muted">0/255 characters</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Preview -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-eye me-2"></i>
                                        Search Engine Preview
                                    </h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="search-preview">
                                                <div class="search-result">
                                                    <h4 class="search-title" id="preview-title">
                                                        {{ $groups['seo']->get('seo_title')?->value ?? 'Affiliate & Course Platform' }}
                                                    </h4>
                                                    <div class="search-url text-success" id="preview-url">
                                                        {{ url('/') }}
                                                    </div>
                                                    <p class="search-description" id="preview-description">
                                                        {{ $groups['seo']->get('seo_description')?->value ?? 'Your description will appear here...' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
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
// Customization form functionality
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewChanges() {
    // Update preview elements with current form values
    const companyName = document.getElementById('company_name').value;
    const primaryColor = document.getElementById('primary_color').value;
    const secondaryColor = document.getElementById('secondary_color').value;
    const logoFile = document.getElementById('logo').files[0];
    const faviconFile = document.getElementById('favicon').files[0];
    const backgroundFile = document.getElementById('background').files[0];

    // Update company name in previews
    document.getElementById('preview-company').textContent = companyName || 'Your Platform';
    document.getElementById('preview-header-company').textContent = companyName || 'Your Platform';

    // Update colors in previews
    document.querySelector('.border.rounded.p-3').style.background = `linear-gradient(135deg, ${primaryColor || '#007bff'} 0%, ${secondaryColor || '#6c757d'} 100%)`;
    document.querySelector('.navbar').style.backgroundColor = primaryColor || '#007bff';

    // Update logo if new file is selected
    if (logoFile) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-logo').src = e.target.result;
            document.getElementById('preview-header-logo').src = e.target.result;
        };
        reader.readAsDataURL(logoFile);
    }

    // Update background if new file is selected
    if (backgroundFile) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.border.rounded.p-3').style.backgroundImage = `url(${e.target.result})`;
            document.querySelector('.border.rounded.p-3').style.backgroundSize = 'cover';
            document.querySelector('.border.rounded.p-3').style.backgroundPosition = 'center';
        };
        reader.readAsDataURL(backgroundFile);
    }
}

function resetCustomization() {
    if (confirm('Are you sure you want to reset all customizations to default values? This action cannot be undone.')) {
        // Create a form and submit it to the reset route
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.customization.reset") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

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

// Initialize customization form event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Color picker synchronization
    const primaryColor = document.getElementById('primary_color');
    const primaryColorText = document.getElementById('primary_color_text');
    const secondaryColor = document.getElementById('secondary_color');
    const secondaryColorText = document.getElementById('secondary_color_text');

    if (primaryColor && primaryColorText) {
        primaryColor.addEventListener('input', function() {
            primaryColorText.value = this.value;
            previewChanges();
        });
        primaryColorText.addEventListener('input', function() {
            if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                primaryColor.value = this.value;
                previewChanges();
            }
        });
    }

    if (secondaryColor && secondaryColorText) {
        secondaryColor.addEventListener('input', function() {
            secondaryColorText.value = this.value;
            previewChanges();
        });
        secondaryColorText.addEventListener('input', function() {
            if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                secondaryColor.value = this.value;
                previewChanges();
            }
        });
    }

    // Company name live preview
    const companyName = document.getElementById('company_name');
    if (companyName) {
        companyName.addEventListener('input', previewChanges);
    }

    // Customization form submission
    const customizationForm = document.getElementById('customizationForm');
    if (customizationForm) {
        customizationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Saving...';
            submitButton.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message || 'Customization saved successfully!', 'success');
                    
                    // Update preview elements with new values if images were uploaded
                    if (data.logo_url) {
                        document.getElementById('logo-preview').src = data.logo_url;
                        document.getElementById('preview-logo').src = data.logo_url;
                        document.getElementById('preview-header-logo').src = data.logo_url;
                    }
                    if (data.favicon_url) {
                        document.getElementById('favicon-preview').src = data.favicon_url;
                    }
                    if (data.background_url) {
                        document.getElementById('background-preview').src = data.background_url;
                    }
                    
                    // Update form fields with new values
                    if (data.company_name) {
                        document.getElementById('company_name').value = data.company_name;
                        document.getElementById('preview-company').textContent = data.company_name;
                        document.getElementById('preview-header-company').textContent = data.company_name;
                    }
                    if (data.company_email) {
                        document.getElementById('company_email').value = data.company_email;
                    }
                    if (data.company_phone) {
                        document.getElementById('company_phone').value = data.company_phone;
                    }
                    if (data.company_address) {
                        document.getElementById('company_address').value = data.company_address;
                    }
                    if (data.primary_color) {
                        document.getElementById('primary_color').value = data.primary_color;
                        document.getElementById('primary_color_text').value = data.primary_color;
                        // Update preview colors
                        const previewElement = document.querySelector('.border.rounded.p-3');
                        if (previewElement) {
                            previewElement.style.background = `linear-gradient(135deg, ${data.primary_color} 0%, ${data.secondary_color || '#6c757d'} 100%)`;
                        }
                        const navbarElement = document.querySelector('.navbar');
                        if (navbarElement) {
                            navbarElement.style.backgroundColor = data.primary_color;
                        }
                    }
                    if (data.secondary_color) {
                        document.getElementById('secondary_color').value = data.secondary_color;
                        document.getElementById('secondary_color_text').value = data.secondary_color;
                        // Update preview colors
                        const previewElement = document.querySelector('.border.rounded.p-3');
                        if (previewElement) {
                            previewElement.style.background = `linear-gradient(135deg, ${data.primary_color || '#007bff'} 0%, ${data.secondary_color} 100%)`;
                        }
                    }
                    
                    // Clear file inputs
                    document.getElementById('logo').value = '';
                    document.getElementById('favicon').value = '';
                    document.getElementById('background').value = '';
                } else {
                    showAlert(data.message || 'Error saving customization', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error saving customization: ' + error.message, 'danger');
            })
            .finally(() => {
                // Restore button state
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    }

    // SEO Form submission
    const seoForm = document.getElementById('seoForm');
    if (seoForm) {
        seoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Update button state
            submitButton.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Saving...';
            submitButton.disabled = true;
            
            fetch('{{ route("admin.settings.seo.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('SEO settings updated successfully!', 'success');
                } else {
                    showAlert(data.message || 'Error saving SEO settings', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error saving SEO settings: ' + error.message, 'danger');
            })
            .finally(() => {
                // Restore button state
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    }

    // SEO Character counters and live preview
    const titleInput = document.getElementById('seo_title');
    const descriptionInput = document.getElementById('seo_description');
    const keywordsInput = document.getElementById('seo_keywords');
    
    const titleCount = document.getElementById('title-count');
    const descriptionCount = document.getElementById('description-count');
    const keywordsCount = document.getElementById('keywords-count');

    // Update character counts
    function updateCount(input, counter, max) {
        const count = input.value.length;
        counter.textContent = `${count}/${max} characters`;
        
        // Add warning/danger classes
        counter.className = 'text-muted';
        if (count > max * 0.9) {
            counter.classList.add('text-danger');
        } else if (count > max * 0.8) {
            counter.classList.add('text-warning');
        }
    }

    // Live preview updates
    function updatePreview() {
        const title = titleInput.value || 'Affiliate & Course Platform';
        const description = descriptionInput.value || 'Your description will appear here...';
        
        document.getElementById('preview-title').textContent = title;
        document.getElementById('preview-description').textContent = description;
    }

    // Event listeners for SEO fields
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            updateCount(this, titleCount, 60);
            updatePreview();
        });
    }

    if (descriptionInput) {
        descriptionInput.addEventListener('input', function() {
            updateCount(this, descriptionCount, 160);
            updatePreview();
        });
    }

    if (keywordsInput) {
        keywordsInput.addEventListener('input', function() {
            updateCount(this, keywordsCount, 255);
        });
    }

    // Initialize counts
    if (titleInput && titleCount) updateCount(titleInput, titleCount, 60);
    if (descriptionInput && descriptionCount) updateCount(descriptionInput, descriptionCount, 160);
    if (keywordsInput && keywordsCount) updateCount(keywordsInput, keywordsCount, 255);
});
</script>

<style>
.search-preview {
    max-width: 600px;
}

.search-result {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 16px;
    background: #fff;
}

.search-title {
    color: #1a0dab;
    font-size: 18px;
    font-weight: 400;
    margin: 0 0 4px 0;
    line-height: 1.3;
}

.search-title:hover {
    text-decoration: underline;
    cursor: pointer;
}

.search-url {
    font-size: 14px;
    margin: 0 0 4px 0;
}

.search-description {
    color: #545454;
    font-size: 14px;
    line-height: 1.4;
    margin: 0;
}
</style>
@endsection