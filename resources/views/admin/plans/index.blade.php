@extends('layouts.admin')

@section('title', 'Plans Management')
@section('page-title', 'Plans Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-box me-2"></i>Plans Management
                    </h5>
                    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create New Plan
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

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Commission Rate</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plans as $plan)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#{{ $plan->id }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $plan->name }}</div>
                                        <small class="text-muted">{{ Str::limit($plan->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">${{ number_format($plan->price, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-warning">{{ $plan->commission_rate }}%</span>
                                    </td>
                                    <td>
                                        @if($plan->is_active)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            {{ $plan->created_at->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.plans.show', $plan) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.plans.edit', $plan) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.plans.toggle.status', $plan) }}" 
                                                  style="display: inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $plan->is_active ? 'danger' : 'success' }}" 
                                                        title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi bi-{{ $plan->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-box display-6 d-block mb-2"></i>
                                            <h5>No Plans Found</h5>
                                            <p>Create your first plan to get started.</p>
                                            <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>Create Plan
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($plans->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $plans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection