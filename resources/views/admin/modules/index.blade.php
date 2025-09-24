@extends('layouts.admin')

@section('title', 'Modules for ' . $course->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-folder me-2"></i>
                        Modules for: {{ $course->title }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.courses.modules.create', $course) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Create New Module
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Courses
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

                    @if($modules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Title</th>
                                        <th>Lessons</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modules as $module)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">{{ $module->order }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $module->title }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $module->lessons->count() }}</span>
                                            </td>
                                            <td>
                                                <small>{{ $module->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.courses.modules.lessons.index', [$course, $module]) }}" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="Manage Lessons">
                                                        <i class="fas fa-list"></i>
                                                    </a>
                                                    <a href="{{ route('admin.courses.modules.edit', [$course, $module]) }}" 
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="Edit Module">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.courses.modules.destroy', [$course, $module]) }}" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Are you sure you want to delete this module?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                title="Delete Module">
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
                            {{ $modules->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No modules found</h5>
                            <p class="text-muted">Create modules to organize your course content.</p>
                            <a href="{{ route('admin.courses.modules.create', $course) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Create First Module
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

