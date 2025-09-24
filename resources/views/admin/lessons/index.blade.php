@extends('layouts.admin')

@section('title', 'Lessons for ' . $module->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-play-circle me-2"></i>
                        Lessons for: {{ $module->title }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.courses.modules.lessons.create', [$course, $module]) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Create New Lesson
                        </a>
                        <a href="{{ route('admin.courses.modules.index', $course) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Modules
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($lessons->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Content</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lessons as $lesson)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">{{ $lesson->order }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $lesson->title }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if($lesson->video_url)
                                                        <span class="badge bg-success">Video</span>
                                                    @endif
                                                    @if($lesson->attachment)
                                                        <span class="badge bg-info">Attachment</span>
                                                    @endif
                                                    @if($lesson->content)
                                                        <span class="badge bg-primary">Content</span>
                                                    @endif
                                                    @if(!$lesson->video_url && !$lesson->attachment && !$lesson->content)
                                                        <span class="badge bg-warning">Empty</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($lesson->content)
                                                    <small class="text-muted">{{ Str::limit(strip_tags($lesson->content), 50) }}</small>
                                                @else
                                                    <small class="text-muted">No content</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $lesson->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.courses.modules.lessons.edit', [$course, $module, $lesson]) }}" 
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="Edit Lesson">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($lesson->attachment)
                                                        <a href="{{ route('admin.courses.modules.lessons.download', [$course, $module, $lesson]) }}" 
                                                           class="btn btn-outline-info btn-sm"
                                                           title="Download Attachment">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('admin.courses.modules.lessons.destroy', [$course, $module, $lesson]) }}" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Are you sure you want to delete this lesson?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                title="Delete Lesson">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $lessons->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-play-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No lessons found</h5>
                            <p class="text-muted">Create lessons to add content to this module.</p>
                            <a href="{{ route('admin.courses.modules.lessons.create', [$course, $module]) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Create First Lesson
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

