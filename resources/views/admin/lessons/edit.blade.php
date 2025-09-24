@extends('layouts.admin')

@section('title', 'Edit Lesson: ' . $lesson->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Edit Lesson: {{ $lesson->title }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.courses.modules.lessons.index', [$course, $module]) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Lessons
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.courses.modules.lessons.update', [$course, $module, $lesson]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                            <!-- Lesson Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Lesson Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">
                                                Lesson Title <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('title') is-invalid @enderror" 
                                                   id="title" 
                                                   name="title" 
                                                   value="{{ old('title', $lesson->title) }}" 
                                                   placeholder="Enter lesson title"
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
                                                   value="{{ old('order', $lesson->order) }}" 
                                                   min="1"
                                                   placeholder="Lesson order">
                                            <div class="form-text">Lower numbers appear first</div>
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="content" class="form-label">Lesson Content</label>
                                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                                      id="content" 
                                                      name="content" 
                                                      rows="6" 
                                                      placeholder="Enter lesson content (supports basic HTML)">{{ old('content', $lesson->content) }}</textarea>
                                            <div class="form-text">You can use basic HTML tags for formatting</div>
                                            @error('content')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="video_url" class="form-label">Video URL</label>
                                            <input type="url" 
                                                   class="form-control @error('video_url') is-invalid @enderror" 
                                                   id="video_url" 
                                                   name="video_url" 
                                                   value="{{ old('video_url', $lesson->video_url) }}" 
                                                   placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/...">
                                            <div class="form-text">Supports YouTube and Vimeo URLs</div>
                                            @error('video_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="attachment" class="form-label">Lesson Attachment</label>
                                            <input type="file" 
                                                   class="form-control @error('attachment') is-invalid @enderror" 
                                                   id="attachment" 
                                                   name="attachment" 
                                                   accept=".pdf,.doc,.docx,.txt,.zip,.rar">
                                            <div class="form-text">Upload a new file to replace the current one (PDF, DOC, DOCX, TXT, ZIP, RAR - Max 10MB)</div>
                                            @error('attachment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lesson Details -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-chart-bar me-2"></i>
                                            Lesson Details
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <h5 class="text-primary">{{ $lesson->order }}</h5>
                                                <small class="text-muted">Order</small>
                                            </div>
                                            <div class="col-6">
                                                <h5 class="text-success">{{ $lesson->created_at->format('M d') }}</h5>
                                                <small class="text-muted">Created</small>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <strong>Updated:</strong> {{ $lesson->updated_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Attachment -->
                                @if($lesson->attachment)
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-paperclip me-2"></i>
                                                Current Attachment
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file fa-2x text-primary me-3"></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $lesson->attachment_name }}</h6>
                                                    <small class="text-muted">Current file</small>
                                                </div>
                                                <a href="{{ route('admin.courses.modules.lessons.download', [$course, $module, $lesson]) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Video Preview -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-video me-2"></i>
                                            Video Preview
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="videoPreview" class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 150px; border: 2px dashed #dee2e6;">
                                            @if($lesson->video_url)
                                                @if(strpos($lesson->video_url, 'youtube.com') !== false || strpos($lesson->video_url, 'youtu.be') !== false)
                                                    @php
                                                        $videoId = null;
                                                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $lesson->video_url, $matches)) {
                                                            $videoId = $matches[1];
                                                        }
                                                    @endphp
                                                    @if($videoId)
                                                        <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg" 
                                                             alt="YouTube Preview" 
                                                             class="img-fluid rounded" 
                                                             style="max-height: 120px;">
                                                    @else
                                                        <div class="text-center">
                                                            <i class="fas fa-video fa-2x text-primary mb-2"></i>
                                                            <p class="text-muted mb-0 small">YouTube Video</p>
                                                        </div>
                                                    @endif
                                                @elseif(strpos($lesson->video_url, 'vimeo.com') !== false)
                                                    <div class="text-center">
                                                        <i class="fas fa-video fa-2x text-primary mb-2"></i>
                                                        <p class="text-muted mb-0 small">Vimeo Video</p>
                                                    </div>
                                                @else
                                                    <div class="text-center">
                                                        <i class="fas fa-video fa-2x text-info mb-2"></i>
                                                        <p class="text-muted mb-0 small">Video URL</p>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-center">
                                                    <i class="fas fa-video fa-2x text-muted mb-2"></i>
                                                    <p class="text-muted mb-0 small">No video URL</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Module Information -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-folder me-2"></i>
                                            Module Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <h6>{{ $module->title }}</h6>
                                        <p class="text-muted small">From: {{ $course->title }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.courses.modules.lessons.index', [$course, $module]) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Update Lesson
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
    // Video preview functionality
    const videoInput = document.getElementById('video_url');
    const videoPreview = document.getElementById('videoPreview');

    videoInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url) {
            // Check if it's a YouTube URL
            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                const videoId = extractYouTubeId(url);
                if (videoId) {
                    videoPreview.innerHTML = `
                        <div class="text-center">
                            <img src="https://img.youtube.com/vi/${videoId}/mqdefault.jpg" 
                                 alt="YouTube Preview" 
                                 class="img-fluid rounded" 
                                 style="max-height: 120px;">
                            <p class="text-muted mb-0 small mt-2">YouTube Video Preview</p>
                        </div>
                    `;
                } else {
                    showVideoError();
                }
            }
            // Check if it's a Vimeo URL
            else if (url.includes('vimeo.com')) {
                videoPreview.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-video fa-2x text-primary mb-2"></i>
                        <p class="text-muted mb-0 small">Vimeo Video</p>
                    </div>
                `;
            }
            // Other video URLs
            else {
                videoPreview.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-video fa-2x text-info mb-2"></i>
                        <p class="text-muted mb-0 small">Video URL</p>
                    </div>
                `;
            }
        } else {
            videoPreview.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-video fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0 small">No video URL</p>
                </div>
            `;
        }
    });

    function extractYouTubeId(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    function showVideoError() {
        videoPreview.innerHTML = `
            <div class="text-center">
                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                <p class="text-muted mb-0 small">Invalid video URL</p>
            </div>
        `;
    }

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        
        if (!title) {
            e.preventDefault();
            showAlert('Please enter a lesson title.', 'danger');
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

