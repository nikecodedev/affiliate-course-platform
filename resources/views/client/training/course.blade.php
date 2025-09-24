@extends('layouts.client')

@section('title', $course->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Course Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($course->image)
                                <img src="{{ asset('storage/' . $course->image) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $course->title }}"
                                     style="max-height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                     style="height: 250px;">
                                    <i class="fas fa-graduation-cap fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h2 class="mb-3">{{ $course->title }}</h2>
                            
                            @if($course->description)
                                <p class="text-muted mb-4">{{ $course->description }}</p>
                            @endif
                            
                            <!-- Overall Progress -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Course Progress</h6>
                                    <span class="badge bg-primary">{{ $course->progress }}%</span>
                                </div>
                                <div class="progress" style="height: 12px;">
                                    <div class="progress-bar bg-primary" 
                                         role="progressbar" 
                                         style="width: {{ $course->progress }}%"
                                         aria-valuenow="{{ $course->progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Course Stats -->
                            <div class="row text-center">
                                <div class="col-3">
                                    <div class="border-end">
                                        <h5 class="text-primary mb-1">{{ $course->modules->count() }}</h5>
                                        <small class="text-muted">Modules</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="border-end">
                                        <h5 class="text-success mb-1">{{ $course->modules->sum(function($module) { return $module->lessons->count(); }) }}</h5>
                                        <small class="text-muted">Lessons</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="border-end">
                                        <h5 class="text-info mb-1">{{ $course->modules->sum(function($module) { return $module->getCompletedLessonsCountForUser(auth()->id()); }) }}</h5>
                                        <small class="text-muted">Completed</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <h5 class="text-warning mb-1">{{ $course->modules->sum(function($module) { return $module->lessons->count(); }) - $course->modules->sum(function($module) { return $module->getCompletedLessonsCountForUser(auth()->id()); }) }}</h5>
                                    <small class="text-muted">Remaining</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modules -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Course Modules
                    </h4>
                </div>
                <div class="card-body">
                    @if($course->modules->count() > 0)
                        <div class="accordion" id="modulesAccordion">
                            @foreach($course->modules as $module)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="module{{ $module->id }}">
                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapse{{ $module->id }}" 
                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                                aria-controls="collapse{{ $module->id }}">
                                            <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-folder me-3 text-primary"></i>
                                                    <span>{{ $module->title }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <div class="progress" style="width: 60px; height: 6px;">
                                                            <div class="progress-bar bg-success" 
                                                                 style="width: {{ $module->progress }}%">
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">{{ $module->progress }}%</small>
                                                    </div>
                                                    <span class="badge bg-primary">{{ $module->lessons->count() }} lessons</span>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $module->id }}" 
                                         class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                                         aria-labelledby="module{{ $module->id }}" 
                                         data-bs-parent="#modulesAccordion">
                                        <div class="accordion-body">
                                            @if($module->lessons->count() > 0)
                                                <div class="list-group">
                                                    @foreach($module->lessons as $lesson)
                                                        @php
                                                            $isCompleted = $lesson->isCompletedByUser(auth()->id());
                                                        @endphp
                                                        <div class="list-group-item d-flex justify-content-between align-items-center {{ $isCompleted ? 'bg-light' : '' }}">
                                                            <div class="d-flex align-items-center">
                                                                @if($isCompleted)
                                                                    <i class="fas fa-check-circle text-success me-3"></i>
                                                                @else
                                                                    <i class="fas fa-play-circle text-primary me-3"></i>
                                                                @endif
                                                                <div>
                                                                    <strong>{{ $lesson->title }}</strong>
                                                                    @if($lesson->video_url)
                                                                        <span class="badge bg-success ms-2">Video</span>
                                                                    @endif
                                                                    @if($lesson->attachment)
                                                                        <span class="badge bg-info ms-2">Attachment</span>
                                                                    @endif
                                                                    <br>
                                                                    <small class="text-muted">{{ $lesson->getDurationAttribute() }}</small>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <a href="{{ route('training.lesson', [$course, $module, $lesson]) }}" 
                                                                   class="btn btn-{{ $isCompleted ? 'outline-success' : 'primary' }} btn-sm">
                                                                    @if($isCompleted)
                                                                        <i class="fas fa-redo me-1"></i>
                                                                        Review
                                                                    @else
                                                                        <i class="fas fa-play me-1"></i>
                                                                        Start
                                                                    @endif
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-3">
                                                    <i class="fas fa-book-open fa-2x text-muted mb-2"></i>
                                                    <p class="text-muted mb-0">No lessons in this module yet</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No modules available</h5>
                            <p class="text-muted">This course doesn't have any modules yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.list-group-item {
    border-left: none;
    border-right: none;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}
</style>
@endsection

