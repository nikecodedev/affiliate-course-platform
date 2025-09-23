@extends('layouts.admin')

@section('title', 'Course Details')
@section('page-title', 'Course Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-book me-2"></i>{{ $course->title }}
                    </h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-outline-warning">
                            <i class="bi bi-pencil me-2"></i>Edit
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-4">
                            @if($course->image)
                                <img src="{{ asset('storage/' . $course->image) }}" 
                                     class="img-fluid rounded mb-3" 
                                     alt="{{ $course->title }}"
                                     style="max-height: 300px; width: 100%; object-fit: cover;">
                            @else
                                <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="bi bi-book display-4 text-muted"></i>
                                </div>
                            @endif
                            
                            <h4>{{ $course->title }}</h4>
                            
                            @if($course->description)
                                <div class="text-muted mb-3">
                                    {{ $course->description }}
                                </div>
                            @endif

                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="text-primary">
                                        <i class="bi bi-list-ol display-6"></i>
                                        <div class="fw-semibold">{{ $course->modules->count() }}</div>
                                        <small>Modules</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-success">
                                        <i class="bi bi-play-circle display-6"></i>
                                        <div class="fw-semibold">{{ $course->modules->sum('lessons_count') }}</div>
                                        <small>Lessons</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-info">
                                        <i class="bi bi-people display-6"></i>
                                        <div class="fw-semibold">0</div>
                                        <small>Students</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Course Modules -->
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-list me-2"></i>Course Modules
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="addModule()">
                                        <i class="bi bi-plus me-1"></i>Add Module
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @forelse($course->modules as $module)
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">{{ $module->title }}</h6>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="editModule({{ $module->id }})">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" 
                                                            onclick="addLesson({{ $module->id }})">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="deleteModule({{ $module->id }})">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if($module->description)
                                                <p class="text-muted mb-2">{{ $module->description }}</p>
                                            @endif
                                            
                                            <div class="lessons">
                                                @forelse($module->lessons as $lesson)
                                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-play-circle text-primary me-2"></i>
                                                            <span>{{ $lesson->title }}</span>
                                                            @if($lesson->is_free)
                                                                <span class="badge bg-success ms-2">Free</span>
                                                            @endif
                                                        </div>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <button type="button" class="btn btn-outline-primary" 
                                                                    onclick="editLesson({{ $lesson->id }})">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger" 
                                                                    onclick="deleteLesson({{ $lesson->id }})">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-muted text-center py-3">
                                                        <i class="bi bi-info-circle me-2"></i>
                                                        No lessons in this module yet.
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-list display-6 d-block mb-3"></i>
                                            <h5>No Modules Yet</h5>
                                            <p>Start building your course by adding modules and lessons.</p>
                                            <button type="button" class="btn btn-primary" onclick="addModule()">
                                                <i class="bi bi-plus-circle me-2"></i>Add First Module
                                            </button>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Course Info -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-info-circle me-2"></i>Course Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Status:</strong>
                                    @if($course->is_active)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Inactive
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Sort Order:</strong>
                                    <span class="badge bg-secondary">{{ $course->sort_order ?? 0 }}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Created:</strong>
                                    <div class="text-muted">{{ $course->created_at->format('M d, Y H:i') }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Last Updated:</strong>
                                    <div class="text-muted">{{ $course->updated_at->format('M d, Y H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-lightning me-2"></i>Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-primary" onclick="addModule()">
                                        <i class="bi bi-plus-circle me-2"></i>Add Module
                                    </button>
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-outline-warning">
                                        <i class="bi bi-pencil me-2"></i>Edit Course
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="deleteCourse({{ $course->id }})">
                                        <i class="bi bi-trash me-2"></i>Delete Course
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Module Modal -->
<div class="modal fade" id="moduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moduleModalTitle">
                    <i class="bi bi-list me-2"></i>Add Module
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="moduleForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="module_title" class="form-label">Module Title</label>
                        <input type="text" class="form-control" id="module_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="module_description" class="form-label">Description</label>
                        <textarea class="form-control" id="module_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="module_sort_order" class="form-label">Sort Order</label>
                        <input type="number" class="form-control" id="module_sort_order" name="sort_order" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Module</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function addModule() {
    document.getElementById('moduleModalTitle').innerHTML = '<i class="bi bi-list me-2"></i>Add Module';
    document.getElementById('moduleForm').action = '{{ route("admin.courses.modules.store", $course) }}';
    document.getElementById('moduleForm').innerHTML = `
        @csrf
        <div class="modal-body">
            <div class="mb-3">
                <label for="module_title" class="form-label">Module Title</label>
                <input type="text" class="form-control" id="module_title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="module_description" class="form-label">Description</label>
                <textarea class="form-control" id="module_description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="module_sort_order" class="form-label">Sort Order</label>
                <input type="number" class="form-control" id="module_sort_order" name="sort_order" value="0">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Module</button>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('moduleModal'));
    modal.show();
}

function editModule(moduleId) {
    // Here you would typically load module data via AJAX
    console.log('Edit module:', moduleId);
}

function addLesson(moduleId) {
    // Here you would typically show lesson creation form
    console.log('Add lesson to module:', moduleId);
}

function editLesson(lessonId) {
    // Here you would typically load lesson data via AJAX
    console.log('Edit lesson:', lessonId);
}

function deleteModule(moduleId) {
    if (confirm('Are you sure you want to delete this module? This will also delete all lessons in this module.')) {
        // Here you would typically make an AJAX request to delete the module
        console.log('Delete module:', moduleId);
    }
}

function deleteLesson(lessonId) {
    if (confirm('Are you sure you want to delete this lesson?')) {
        // Here you would typically make an AJAX request to delete the lesson
        console.log('Delete lesson:', lessonId);
    }
}

function deleteCourse(courseId) {
    if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
        // Here you would typically redirect to delete route or make AJAX request
        window.location.href = '{{ route("admin.courses.destroy", $course) }}';
    }
}
</script>
@endsection
