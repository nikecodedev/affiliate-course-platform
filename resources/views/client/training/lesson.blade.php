@extends('layouts.client')

@section('title', $lesson->title . ' - ' . $course->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('training.index') }}">Training</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('training.course', $course) }}">{{ $course->title }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $lesson->title }}</li>
                </ol>
            </nav>

            <!-- Lesson Header -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">{{ $lesson->title }}</h3>
                            <p class="text-muted mb-0">{{ $module->title }}</p>
                        </div>
                        <div>
                            @if($isCompleted)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check me-1"></i>
                                    Completed
                                </span>
                            @else
                                <button class="btn btn-success" id="completeLessonBtn">
                                    <i class="fas fa-check me-1"></i>
                                    Mark as Complete
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Lesson Content -->
                <div class="col-lg-8">
                    <!-- Video Section -->
                    @if($lesson->video_url)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-play-circle me-2"></i>
                                    Video Lesson
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="ratio ratio-16x9">
                                    <iframe src="{{ $lesson->video_embed_url }}" 
                                            title="{{ $lesson->title }}"
                                            allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Content Section -->
                    @if($lesson->content)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-text me-2"></i>
                                    Lesson Content
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="lesson-content">
                                    {!! nl2br(e($lesson->content)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Attachment Section -->
                    @if($lesson->attachment)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-paperclip me-2"></i>
                                    Lesson Attachment
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file fa-2x text-primary me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $lesson->attachment_name }}</h6>
                                        <small class="text-muted">Click to download</small>
                                    </div>
                                    <a href="{{ route('training.download', [$course, $module, $lesson]) }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-download me-1"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Navigation -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($previousLesson)
                                        <a href="{{ route('training.lesson', [$course, $module, $previousLesson]) }}" 
                                           class="btn btn-outline-primary w-100">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            Previous: {{ $previousLesson->title }}
                                        </a>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($nextLesson)
                                        <a href="{{ route('training.lesson', [$course, $module, $nextLesson]) }}" 
                                           class="btn btn-primary w-100">
                                            Next: {{ $nextLesson->title }}
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Course Progress -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>
                                Course Progress
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Overall Progress</span>
                                    <span class="badge bg-primary">{{ $course->progress }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" 
                                         style="width: {{ $course->progress }}%">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Module Progress</span>
                                    <span class="badge bg-success">{{ $module->progress }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" 
                                         style="width: {{ $module->progress }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Module Lessons -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>
                                Module Lessons
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($module->lessons as $moduleLesson)
                                    @php
                                        $isModuleLessonCompleted = $moduleLesson->isCompletedByUser(auth()->id());
                                        $isCurrentLesson = $moduleLesson->id === $lesson->id;
                                    @endphp
                                    <a href="{{ route('training.lesson', [$course, $module, $moduleLesson]) }}" 
                                       class="list-group-item list-group-item-action {{ $isCurrentLesson ? 'active' : '' }}">
                                        <div class="d-flex align-items-center">
                                            @if($isModuleLessonCompleted)
                                                <i class="fas fa-check-circle text-success me-3"></i>
                                            @else
                                                <i class="fas fa-play-circle text-primary me-3"></i>
                                            @endif
                                            <div class="flex-grow-1">
                                                <strong>{{ $moduleLesson->title }}</strong>
                                                @if($isCurrentLesson)
                                                    <br><small>Current Lesson</small>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const completeBtn = document.getElementById('completeLessonBtn');
    
    if (completeBtn) {
        completeBtn.addEventListener('click', function() {
            const originalText = this.innerHTML;
            const originalClass = this.className;
            
            // Show loading state
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Completing...';
            this.className = 'btn btn-secondary';
            
            fetch('{{ route("training.complete", [$course, $module, $lesson]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button to completed state
                    this.innerHTML = '<i class="fas fa-check me-1"></i>Completed';
                    this.className = 'btn btn-success';
                    this.disabled = true;
                    
                    // Update progress bars
                    updateProgressBars(data.module_progress, data.course_progress);
                    
                    // Show success message
                    showAlert('Lesson completed successfully!', 'success');
                } else {
                    // Restore original state
                    this.innerHTML = originalText;
                    this.className = originalClass;
                    this.disabled = false;
                    
                    showAlert(data.message || 'Failed to complete lesson', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Restore original state
                this.innerHTML = originalText;
                this.className = originalClass;
                this.disabled = false;
                
                showAlert('Error completing lesson', 'danger');
            });
        });
    }
    
    function updateProgressBars(moduleProgress, courseProgress) {
        // Update module progress
        const moduleProgressBar = document.querySelector('.progress-bar.bg-success');
        if (moduleProgressBar) {
            moduleProgressBar.style.width = moduleProgress + '%';
        }
        
        // Update course progress
        const courseProgressBar = document.querySelector('.progress-bar.bg-primary');
        if (courseProgressBar) {
            courseProgressBar.style.width = courseProgress + '%';
        }
        
        // Update progress badges
        const moduleBadge = document.querySelector('.badge.bg-success');
        if (moduleBadge) {
            moduleBadge.textContent = moduleProgress + '%';
        }
        
        const courseBadge = document.querySelector('.badge.bg-primary');
        if (courseBadge) {
            courseBadge.textContent = courseProgress + '%';
        }
    }
    
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
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

@section('styles')
<style>
.lesson-content {
    line-height: 1.6;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

.list-group-item.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.ratio {
    border-radius: 8px;
    overflow: hidden;
}
</style>
@endsection

