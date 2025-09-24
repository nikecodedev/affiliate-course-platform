@extends('layouts.admin')

@section('title', 'Global Bonus Configurations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs me-2"></i>
                        Global Bonus Configurations
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.bonus-configurations.global.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Add Configuration
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

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($configurations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Max Depth</th>
                                        <th>Max Width</th>
                                        <th>Levels</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($configurations as $configuration)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $configuration->name }}</strong>
                                                    @if($configuration->description)
                                                        <br><small class="text-muted">{{ $configuration->description }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst(str_replace('_', ' ', $configuration->type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.bonus-configurations.global.toggle', $configuration) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $configuration->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                        {{ $configuration->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $configuration->max_depth }}</span>
                                            </td>
                                            <td>
                                                @if($configuration->type === 'forced_matrix')
                                                    <span class="badge bg-warning">{{ $configuration->max_width }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $configuration->bonusLevels->count() }} levels</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.bonus-configurations.global.show', $configuration) }}" 
                                                       class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.bonus-configurations.global.edit', $configuration) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.bonus-configurations.global.destroy', $configuration) }}" 
                                                          method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('Are you sure you want to delete this configuration?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
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
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Bonus Configurations Found</h5>
                            <p class="text-muted">Create your first bonus configuration to get started.</p>
                            <a href="{{ route('admin.bonus-configurations.global.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Create Configuration
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
