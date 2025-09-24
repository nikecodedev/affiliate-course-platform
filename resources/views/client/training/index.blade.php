@extends('layouts.client')

@section('title', 'Training Courses')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Training Courses
                    </h3>
                </div>

                <div class="card-body">
                    @if($courses->count() > 0)
                        <div class="row">
                            @foreach($courses as $course)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 course-card">
                                        @if($course->image)
                                            <img src="{{ asset('storage/' . $course->image) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $course->title }}"
                                                 style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                 style="height: 200px;">
                                                <i class="fas fa-graduation-cap fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $course->title }}</h5>
                                            
                                            @if($course->description)
                                                <p class="card-text text-muted">{{ Str::limit($course->description, 100) }}</p>
                                            @endif
                                            
                                            <div class="mt-auto">
                                                <!-- Progress Bar -->
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <small class="text-muted">Progress</small>
                                                        <small class="text-muted">{{ $course->progress }}%</small>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
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
                                                <div class="row text-center mb-3">
                                                    <div class="col-4">
                                                        <div class="border-end">
                                                            <strong class="text-primary">{{ $course->modules->count() }}</strong>
                                                            <br><small class="text-muted">Modules</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="border-end">
                                                            <strong class="text-success">{{ $course->modules->sum(function($module) { return $module->lessons->count(); }) }}</strong>
                                                            <br><small class="text-muted">Lessons</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <strong class="text-info">{{ $course->progress }}%</strong>
                                                        <br><small class="text-muted">Complete</small>
                                                    </div>
                                                </div>
                                                
                                                <!-- Action Button -->
                                                <a href="{{ route('training.course', $course) }}" 
                                                   class="btn btn-primary w-100">
                                                    @if($course->progress > 0)
                                                        <i class="fas fa-play me-1"></i>
                                                        Continue Learning
                                                    @else
                                                        <i class="fas fa-play me-1"></i>
                                                        Start Course
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No courses available</h5>
                            <p class="text-muted">Check back later for new training courses.</p>
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
.course-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}
</style>
@endsection

