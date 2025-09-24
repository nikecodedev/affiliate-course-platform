@extends('layouts.admin')

@section('title', 'System Customization')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-palette me-2"></i>
                        System Customization
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-warning btn-sm" onclick="resetCustomization()">
                            <i class="fas fa-undo me-1"></i>
                            Reset to Default
                        </button>
                    </div>
                </div>

                <form action="{{ route('admin.customization.update') }}" method="POST" enctype="multipart/form-data" id="customizationForm">
                    @csrf
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

                        <!-- Company Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-building me-2"></i>
                                    Company Information
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" 
                                           value="{{ old('company_name', $customizations['company_name']->value ?? 'Your Platform') }}"
                                           placeholder="Enter company name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_email" class="form-label">Company Email</label>
                                    <input type="email" class="form-control" id="company_email" name="company_email" 
                                           value="{{ old('company_email', $customizations['company_email']->value ?? 'contact@yourplatform.com') }}"
                                           placeholder="Enter company email">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="company_phone" class="form-label">Company Phone</label>
                                    <input type="text" class="form-control" id="company_phone" name="company_phone" 
                                           value="{{ old('company_phone', $customizations['company_phone']->value ?? '+1 (555) 123-4567') }}"
                                           placeholder="Enter company phone">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="company_address" class="form-label">Company Address</label>
                                    <textarea class="form-control" id="company_address" name="company_address" rows="2"
                                              placeholder="Enter company address">{{ old('company_address', $customizations['company_address']->value ?? '123 Business St, City, State 12345') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Brand Colors -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-paint-brush me-2"></i>
                                    Brand Colors
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="primary_color" class="form-label">Primary Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" 
                                               value="{{ old('primary_color', $customizations['primary_color']->value ?? '#007bff') }}">
                                        <input type="text" class="form-control" id="primary_color_text" 
                                               value="{{ old('primary_color', $customizations['primary_color']->value ?? '#007bff') }}"
                                               placeholder="#007bff">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="secondary_color" class="form-label">Secondary Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="secondary_color" name="secondary_color" 
                                               value="{{ old('secondary_color', $customizations['secondary_color']->value ?? '#6c757d') }}">
                                        <input type="text" class="form-control" id="secondary_color_text" 
                                               value="{{ old('secondary_color', $customizations['secondary_color']->value ?? '#6c757d') }}"
                                               placeholder="#6c757d">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Brand Assets -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-images me-2"></i>
                                    Brand Assets
                                </h5>
                            </div>
                            
                            <!-- Logo Upload -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-image me-2"></i>
                                            Logo
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img id="logo-preview" src="{{ \App\Models\SystemCustomization::getLogoUrl() }}" 
                                                 alt="Logo Preview" class="img-fluid" style="max-height: 100px;">
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
                                            <i class="fas fa-star me-2"></i>
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
                                            <i class="fas fa-landscape me-2"></i>
                                            Background
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img id="background-preview" src="{{ \App\Models\SystemCustomization::getBackgroundUrl() ?? asset('images/default-bg.jpg') }}" 
                                                 alt="Background Preview" class="img-fluid" style="max-height: 100px;">
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
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-eye me-2"></i>
                                    Live Preview
                                </h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Login Page Preview</h6>
                                                <div class="border rounded p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; min-height: 200px;">
                                                    <div class="text-center">
                                                        <img id="preview-logo" src="{{ \App\Models\SystemCustomization::getLogoUrl() }}" alt="Logo" style="max-height: 40px; margin-bottom: 20px;">
                                                        <h4 id="preview-company">{{ old('company_name', $customizations['company_name']->value ?? 'Your Platform') }}</h4>
                                                        <p class="mb-0">Welcome to your platform</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Header Preview</h6>
                                                <div class="border rounded p-3">
                                                    <nav class="navbar navbar-expand-lg" style="background-color: {{ old('primary_color', $customizations['primary_color']->value ?? '#007bff') }};">
                                                        <div class="container-fluid">
                                                            <img id="preview-header-logo" src="{{ \App\Models\SystemCustomization::getLogoUrl() }}" alt="Logo" style="max-height: 30px;">
                                                            <span class="navbar-brand text-white ms-2" id="preview-header-company">{{ old('company_name', $customizations['company_name']->value ?? 'Your Platform') }}</span>
                                                        </div>
                                                    </nav>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-info" onclick="previewChanges()">
                                    <i class="fas fa-eye me-1"></i>
                                    Preview Changes
                                </button>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Save Customizations
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Customizations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reset all customizations to default values? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This will delete all uploaded images and reset all settings to their default values.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.customization.reset') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Reset to Default
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
    const logoFile = document.getElementById('logo').files[0];
    const faviconFile = document.getElementById('favicon').files[0];
    const backgroundFile = document.getElementById('background').files[0];

    // Update company name in previews
    document.getElementById('preview-company').textContent = companyName || 'Your Platform';
    document.getElementById('preview-header-company').textContent = companyName || 'Your Platform';

    // Update primary color in header preview
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
    const modal = new bootstrap.Modal(document.getElementById('resetModal'));
    modal.show();
}

// Sync color picker with text input
document.getElementById('primary_color').addEventListener('input', function() {
    document.getElementById('primary_color_text').value = this.value;
});

document.getElementById('primary_color_text').addEventListener('input', function() {
    if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
        document.getElementById('primary_color').value = this.value;
    }
});

document.getElementById('secondary_color').addEventListener('input', function() {
    document.getElementById('secondary_color_text').value = this.value;
});

document.getElementById('secondary_color_text').addEventListener('input', function() {
    if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
        document.getElementById('secondary_color').value = this.value;
    }
});

// Auto-preview on input change
document.addEventListener('DOMContentLoaded', function() {
    const inputs = ['company_name', 'primary_color', 'secondary_color'];
    inputs.forEach(inputId => {
        document.getElementById(inputId).addEventListener('input', previewChanges);
    });
});
</script>
@endsection
