@extends('layouts.admin')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Settings Tabs -->
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab">
                    <i class="bi bi-palette me-2"></i>Appearance
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
            <!-- Appearance Settings -->
            <div class="tab-pane fade show active" id="appearance" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Appearance Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="appearanceForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Logo Upload -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Main Logo</label>
                                    <div class="logo-upload-container">
                                        <div class="current-logo mb-3" id="currentLogo">
                                            @if($groups['appearance']->get('logo_path')?->value)
                                                <img src="{{ asset('storage/' . $groups['appearance']->get('logo_path')->value) }}" 
                                                     alt="Current Logo" class="img-fluid" style="max-height: 100px;">
                                            @else
                                                <div class="no-logo-placeholder bg-light border rounded p-4 text-center">
                                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                    <p class="text-muted mt-2">No logo uploaded</p>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="file" class="form-control" id="logo" accept="image/*">
                                        <small class="form-text text-muted">Recommended: 200x60px, PNG or JPG format</small>
                                    </div>
                                </div>

                                <!-- Dark Logo Upload -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Dark Logo (Optional)</label>
                                    <div class="logo-upload-container">
                                        <div class="current-logo mb-3" id="currentDarkLogo">
                                            @if($groups['appearance']->get('logo_dark_path')?->value)
                                                <img src="{{ asset('storage/' . $groups['appearance']->get('logo_dark_path')->value) }}" 
                                                     alt="Current Dark Logo" class="img-fluid" style="max-height: 100px;">
                                            @else
                                                <div class="no-logo-placeholder bg-dark border rounded p-4 text-center">
                                                    <i class="bi bi-image text-light" style="font-size: 2rem;"></i>
                                                    <p class="text-light mt-2">No dark logo uploaded</p>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="file" class="form-control" id="logoDark" accept="image/*">
                                        <small class="form-text text-muted">For dark themes, same size as main logo</small>
                                    </div>
                                </div>

                                <!-- Favicon Upload -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Favicon</label>
                                    <div class="favicon-upload-container">
                                        <div class="current-favicon mb-3" id="currentFavicon">
                                            @if($groups['appearance']->get('favicon_path')?->value)
                                                <img src="{{ asset('storage/' . $groups['appearance']->get('favicon_path')->value) }}" 
                                                     alt="Current Favicon" style="width: 32px; height: 32px;">
                                            @else
                                                <div class="no-favicon-placeholder bg-light border rounded p-2 text-center" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-star text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="file" class="form-control" id="favicon" accept="image/*">
                                        <small class="form-text text-muted">Recommended: 32x32px, ICO or PNG format</small>
                                    </div>
                                </div>

                                <!-- Background Image Upload -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Background Image (Optional)</label>
                                    <div class="background-upload-container">
                                        <div class="current-background mb-3" id="currentBackground">
                                            @if($groups['appearance']->get('background_path')?->value)
                                                <img src="{{ asset('storage/' . $groups['appearance']->get('background_path')->value) }}" 
                                                     alt="Current Background" class="img-fluid" style="max-height: 100px; border-radius: 0.5rem;">
                                            @else
                                                <div class="no-background-placeholder bg-light border rounded p-4 text-center">
                                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                    <p class="text-muted mt-2">No background uploaded</p>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="file" class="form-control" id="background" accept="image/*">
                                        <small class="form-text text-muted">For login page background, JPG or PNG format</small>
                                    </div>
                                </div>
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
                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="site_title" class="form-label">Site Title</label>
                                    <input type="text" class="form-control" id="site_title" name="settings[site_title]" 
                                           value="{{ $groups['seo']->get('site_title')?->value ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="site_keywords" class="form-label">Keywords</label>
                                    <input type="text" class="form-control" id="site_keywords" name="settings[site_keywords]" 
                                           value="{{ $groups['seo']->get('site_keywords')?->value ?? '' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="site_description" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="site_description" name="settings[site_description]" rows="3">{{ $groups['seo']->get('site_description')?->value ?? '' }}</textarea>
                                <small class="form-text text-muted">Recommended: 150-160 characters</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Save SEO Settings
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tracking Settings -->
            <div class="tab-pane fade" id="tracking" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Tracking Codes</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="google_analytics_id" class="form-label">Google Analytics ID</label>
                                    <input type="text" class="form-control" id="google_analytics_id" name="google_analytics_id" 
                                           value="{{ $groups['tracking']->get('google_analytics_id')?->value ?? '' }}" 
                                           placeholder="GA-XXXXXXXXX-X">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="google_tag_manager_id" class="form-label">Google Tag Manager ID</label>
                                    <input type="text" class="form-control" id="google_tag_manager_id" name="google_tag_manager_id" 
                                           value="{{ $groups['tracking']->get('google_tag_manager_id')?->value ?? '' }}" 
                                           placeholder="GTM-XXXXXXX">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="meta_pixel_id" class="form-label">Meta Pixel ID</label>
                                    <input type="text" class="form-control" id="meta_pixel_id" name="meta_pixel_id" 
                                           value="{{ $groups['tracking']->get('meta_pixel_id')?->value ?? '' }}" 
                                           placeholder="123456789012345">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="google_analytics_code" class="form-label">Google Analytics Code</label>
                                <textarea class="form-control" id="google_analytics_code" name="google_analytics_code" rows="4" 
                                          placeholder="<!-- Google Analytics Code -->">{{ $groups['tracking']->get('google_analytics_code')?->value ?? '' }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="google_tag_manager_code" class="form-label">Google Tag Manager Code</label>
                                <textarea class="form-control" id="google_tag_manager_code" name="google_tag_manager_code" rows="4" 
                                          placeholder="<!-- Google Tag Manager Code -->">{{ $groups['tracking']->get('google_tag_manager_code')?->value ?? '' }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="meta_pixel_code" class="form-label">Meta Pixel Code</label>
                                <textarea class="form-control" id="meta_pixel_code" name="meta_pixel_code" rows="4" 
                                          placeholder="<!-- Meta Pixel Code -->">{{ $groups['tracking']->get('meta_pixel_code')?->value ?? '' }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Save Tracking Settings
                            </button>
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
                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="recaptcha_enabled" name="recaptcha_enabled" 
                                           {{ $groups['recaptcha']->get('recaptcha_enabled')?->value ? 'checked' : '' }}>
                                    <label class="form-check-label" for="recaptcha_enabled">
                                        Enable reCAPTCHA
                                    </label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="recaptcha_site_key" class="form-label">Site Key</label>
                                    <input type="text" class="form-control" id="recaptcha_site_key" name="recaptcha_site_key" 
                                           value="{{ $groups['recaptcha']->get('recaptcha_site_key')?->value ?? '' }}" 
                                           placeholder="6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="recaptcha_secret_key" class="form-label">Secret Key</label>
                                    <input type="password" class="form-control" id="recaptcha_secret_key" name="recaptcha_secret_key" 
                                           value="{{ $groups['recaptcha']->get('recaptcha_secret_key')?->value ?? '' }}" 
                                           placeholder="6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX">
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>How to get reCAPTCHA keys:</strong>
                                <ol class="mb-0 mt-2">
                                    <li>Go to <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA Admin</a></li>
                                    <li>Create a new site or select existing one</li>
                                    <li>Choose reCAPTCHA v2 "I'm not a robot" Checkbox</li>
                                    <li>Add your domain and get the keys</li>
                                </ol>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Save reCAPTCHA Settings
                            </button>
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
                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="withdrawal_days" class="form-label">Withdrawal Days</label>
                                    <input type="text" class="form-control" id="withdrawal_days" name="withdrawal_days" 
                                           value="{{ $groups['financial']->get('withdrawal_days')?->value ?? '1,2,3,4,5' }}" 
                                           placeholder="1,2,3,4,5">
                                    <small class="form-text text-muted">Comma-separated days (1=Monday, 7=Sunday)</small>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="withdrawal_start_time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="withdrawal_start_time" name="withdrawal_start_time" 
                                           value="{{ $groups['financial']->get('withdrawal_start_time')?->value ?? '09:00' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="withdrawal_end_time" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="withdrawal_end_time" name="withdrawal_end_time" 
                                           value="{{ $groups['financial']->get('withdrawal_end_time')?->value ?? '18:00' }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="withdrawal_fee_type" class="form-label">Fee Type</label>
                                    <select class="form-select" id="withdrawal_fee_type" name="withdrawal_fee_type">
                                        <option value="percentage" {{ ($groups['financial']->get('withdrawal_fee_type')?->value ?? 'percentage') === 'percentage' ? 'selected' : '' }}>
                                            Percentage
                                        </option>
                                        <option value="fixed" {{ ($groups['financial']->get('withdrawal_fee_type')?->value) === 'fixed' ? 'selected' : '' }}>
                                            Fixed Amount
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="withdrawal_fee_value" class="form-label">Fee Value</label>
                                    <input type="number" step="0.01" class="form-control" id="withdrawal_fee_value" name="withdrawal_fee_value" 
                                           value="{{ $groups['financial']->get('withdrawal_fee_value')?->value ?? '5.00' }}">
                                    <small class="form-text text-muted">
                                        <span id="feeHelp">Percentage value (e.g., 5 for 5%)</span>
                                    </small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Save Financial Settings
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // File upload handlers
    $('#logo, #logoDark, #favicon, #background').on('change', function() {
        const file = this.files[0];
        const type = this.id;
        
        if (file) {
            uploadFile(file, type);
        }
    });

    // Fee type change handler
    $('#withdrawal_fee_type').on('change', function() {
        const type = $(this).val();
        const helpText = $('#feeHelp');
        
        if (type === 'percentage') {
            helpText.text('Percentage value (e.g., 5 for 5%)');
        } else {
            helpText.text('Fixed amount in currency');
        }
    });

    function uploadFile(file, type) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        // Show loading
        const container = $(`#current${type.charAt(0).toUpperCase() + type.slice(1)}`);
        container.html('<div class="text-center"><i class="bi bi-hourglass-split text-primary"></i><br>Uploading...</div>');

        $.ajax({
            url: `/admin/settings/upload-${type === 'logoDark' ? 'logo' : type}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    if (type === 'logo' || type === 'logoDark') {
                        container.html(`<img src="${response.url}" alt="Current Logo" class="img-fluid" style="max-height: 100px;">`);
                    } else if (type === 'favicon') {
                        container.html(`<img src="${response.url}" alt="Current Favicon" style="width: 32px; height: 32px;">`);
                    } else if (type === 'background') {
                        container.html(`<img src="${response.url}" alt="Current Background" class="img-fluid" style="max-height: 100px; border-radius: 0.5rem;">`);
                    }
                } else {
                    container.html('<div class="alert alert-danger">Upload failed</div>');
                }
            },
            error: function(xhr) {
                container.html('<div class="alert alert-danger">Upload failed: ' + xhr.responseJSON.message + '</div>');
            }
        });
    }
});
</script>
@endpush

