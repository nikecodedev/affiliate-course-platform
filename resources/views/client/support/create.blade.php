@extends('layouts.client')

@section('title', 'Create Support Ticket')
@section('page-title', 'Create Support Ticket')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Create New Support Ticket
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

                <form method="POST" action="{{ route('client.support.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">Select a category</option>
                                <option value="account" {{ old('category') === 'account' ? 'selected' : '' }}>Account & Profile</option>
                                <option value="financial" {{ old('category') === 'financial' ? 'selected' : '' }}>Financial & Payments</option>
                                <option value="leads" {{ old('category') === 'leads' ? 'selected' : '' }}>Lead Management</option>
                                <option value="training" {{ old('category') === 'training' ? 'selected' : '' }}>Training & Courses</option>
                                <option value="technical" {{ old('category') === 'technical' ? 'selected' : '' }}>Technical Support</option>
                                <option value="billing" {{ old('category') === 'billing' ? 'selected' : '' }}>Billing & Invoices</option>
                                <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General Inquiry</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="">Select priority</option>
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low - General questions</option>
                                <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium - Need assistance</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High - Urgent issue</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" 
                               value="{{ old('subject') }}" placeholder="Brief description of your issue" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="6" class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Please provide detailed information about your issue..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Please include as much detail as possible to help us assist you quickly.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="attachments" class="form-label">Attachments</label>
                        <input type="file" name="attachments[]" id="attachments" class="form-control" multiple 
                               accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            You can attach screenshots, documents, or other files (max 5MB each, up to 3 files)
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-person-lines-fill me-2"></i>
                                Contact Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_email" class="form-label">Email</label>
                                    <input type="email" name="contact_email" id="contact_email" class="form-control" 
                                           value="{{ old('contact_email', auth('client')->user()->email) }}" readonly>
                                    <div class="form-text">This will be used for ticket updates</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact_phone" class="form-label">Phone (Optional)</label>
                                    <input type="tel" name="contact_phone" id="contact_phone" class="form-control" 
                                           value="{{ old('contact_phone', auth('client')->user()->phone) }}">
                                    <div class="form-text">For urgent issues, we may contact you by phone</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Response Preferences -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-gear me-2"></i>
                                Response Preferences
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="email_notifications" id="email_notifications" 
                                       value="1" {{ old('email_notifications', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_notifications">
                                    Email me when there are updates to this ticket
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="urgent_response" id="urgent_response" 
                                       value="1" {{ old('urgent_response') ? 'checked' : '' }}>
                                <label class="form-check-label" for="urgent_response">
                                    This is an urgent issue requiring immediate attention
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('client.support.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Support
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>
                            Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Tips -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    Tips for Getting Help Faster
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li><strong>Be specific:</strong> Include details about what you were trying to do when the issue occurred.</li>
                    <li><strong>Include steps:</strong> Describe the exact steps you took that led to the problem.</li>
                    <li><strong>Add screenshots:</strong> Visual evidence helps us understand the issue better.</li>
                    <li><strong>Check FAQ first:</strong> Your question might already be answered in our FAQ section.</li>
                    <li><strong>Use the right priority:</strong> High priority should only be used for urgent issues that affect your business.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-expand textarea
    document.getElementById('description').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // File upload validation
    document.getElementById('attachments').addEventListener('change', function() {
        const files = this.files;
        const maxFiles = 3;
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (files.length > maxFiles) {
            alert(`You can only upload up to ${maxFiles} files.`);
            this.value = '';
            return;
        }
        
        for (let file of files) {
            if (file.size > maxSize) {
                alert(`File "${file.name}" is too large. Maximum size is 5MB.`);
                this.value = '';
                return;
            }
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const subject = document.getElementById('subject').value.trim();
        const description = document.getElementById('description').value.trim();
        const category = document.getElementById('category').value;
        const priority = document.getElementById('priority').value;
        
        if (!subject || !description || !category || !priority) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }
        
        if (description.length < 20) {
            e.preventDefault();
            alert('Please provide a more detailed description (at least 20 characters).');
            return;
        }
    });
</script>
@endpush
@endsection
