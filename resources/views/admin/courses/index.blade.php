@extends('layouts.admin')

@section('title', 'Courses Management')
@section('page-title', 'Courses Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-book me-2"></i>Courses Management
                    </h5>
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create New Course
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    @forelse($courses as $course)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                @if($course->image_path)
                                    <img src="{{ asset('storage/' . $course->image_path) }}" 
                                         class="card-img-top" 
                                         alt="{{ $course->title }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="bi bi-book display-4 text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $course->title }}</h5>
                                    <p class="card-text text-muted flex-grow-1">
                                        {{ Str::limit($course->description, 100) }}
                                    </p>
                                    
                                    <div class="mb-3">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="text-primary">
                                                    <i class="bi bi-list-ol"></i>
                                                    <div class="small">{{ $course->modules_count ?? 0 }} Modules</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-success">
                                                    <i class="bi bi-play-circle"></i>
                                                    <div class="small">{{ $course->lessons_count ?? 0 }} Lessons</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-info">
                                                    <i class="bi bi-people"></i>
                                                    <div class="small">{{ $course->students_count ?? 0 }} Students</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-auto">
                                        @if($course->is_active)
                                            <span class="badge bg-success mb-2">
                                                <i class="bi bi-check-circle me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger mb-2">
                                                <i class="bi bi-x-circle me-1"></i>Inactive
                                            </span>
                                        @endif
                                        
                                        <div class="btn-group w-100" role="group">
                                            <a href="{{ route('admin.courses.show', $course) }}" 
                                               class="btn btn-outline-primary btn-sm" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.courses.edit', $course) }}" 
                                               class="btn btn-outline-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                    onclick="manageModules({{ $course->id }})" title="Manage Modules">
                                                <i class="bi bi-list"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-book display-6 d-block mb-3"></i>
                                    <h4>No Courses Found</h4>
                                    <p>Create your first course to start building your educational content.</p>
                                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>Create Course
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($courses->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $courses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Course Modules Modal -->
<div class="modal fade" id="modulesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-list me-2"></i>Course Modules
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modulesModalBody">
                <!-- Modules will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function manageModules(courseId) {
    // Here you would typically load course modules via AJAX
    document.getElementById('modulesModalBody').innerHTML = `
        <div class="text-center py-4">
            <i class="bi bi-hourglass-split display-4 text-muted mb-3"></i>
            <h5>Loading Course Modules...</h5>
            <p class="text-muted">Course ID: ${courseId}</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('modulesModal'));
    modal.show();
}
</script>
@endsection