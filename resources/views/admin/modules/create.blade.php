@extends('layouts.admin')

@section('title', 'Create New Module')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus me-2"></i>
                        Create New Module for: {{ $course->title }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.courses.modules.index', $course) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Modules
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.courses.modules.store', $course) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Module Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Module Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">
                                                Module Title <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('title') is-invalid @enderror" 
                                                   id="title" 
                                                   name="title" 
                                                   value="{{ old('title') }}" 
                                                   placeholder="Enter module title"
                                                   required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="order" class="form-label">Order</label>
                                            <input type="number" 
                                                   class="form-control @error('order') is-invalid @enderror" 
                                                   id="order" 
                                                   name="order" 
                                                   value="{{ old('order', $course->modules()->max('order') + 1) }}" 
                                                   min="1"
                                                   placeholder="Module order">
                                            <div class="form-text">Leave empty to add at the end</div>
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Course Information -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-graduation-cap me-2"></i>
                                            Course Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <h6>{{ $course->title }}</h6>
                                        @if($course->description)
                                            <p class="text-muted small">{{ Str::limit($course->description, 100) }}</p>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <strong>Current Modules:</strong> {{ $course->modules->count() }}<br>
                                                <strong>Total Lessons:</strong> {{ $course->modules->sum(function($module) { return $module->lessons->count(); }) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Next Steps -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            Next Steps
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ol class="small">
                                            <li>Create the module</li>
                                            <li>Add lessons to the module</li>
                                            <li>Organize lesson order</li>
                                            <li>Add video content and attachments</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.courses.modules.index', $course) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Create Module
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
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        
        if (!title) {
            e.preventDefault();
            showAlert('Please enter a module title.', 'danger');
            return;
        }
    });

    // Alert function
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.card-body');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>
@endsection

