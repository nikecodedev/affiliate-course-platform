@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-person me-2"></i>{{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <div class="form-control-plaintext">{{ $user->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <div class="form-control-plaintext">{{ $user->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <div class="form-control-plaintext">{{ $user->phone ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <div class="form-control-plaintext">
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h6 class="mb-0">Affiliate Stats</h6></div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col">
                        <div class="h4 mb-0">{{ $stats['total_sales'] }}</div>
                        <small class="text-muted">Sales</small>
                    </div>
                    <div class="col">
                        <div class="h4 mb-0">{{ number_format($stats['total_revenue'], 2) }}</div>
                        <small class="text-muted">Revenue</small>
                    </div>
                    <div class="col">
                        <div class="h4 mb-0">{{ number_format($stats['total_commission_earned'], 2) }}</div>
                        <small class="text-muted">Commission Earned</small>
                    </div>
                    <div class="col">
                        <div class="h4 mb-0">{{ number_format($stats['total_commission_paid'], 2) }}</div>
                        <small class="text-muted">Commission Paid</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Actions</h6></div>
            <div class="card-body">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-pencil me-1"></i>Edit User
                </a>
                
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')" 
                      class="mb-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash me-1"></i>Delete User
                    </button>
                </form>
                
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-1"></i>Back to Users
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

