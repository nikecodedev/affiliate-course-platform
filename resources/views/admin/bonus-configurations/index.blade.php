@extends('layouts.admin')

@section('title', 'Bonus Configurations')
@section('page-title', 'Bonus Configurations Management')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Configurations
                        </div>
                        <div class="stat-number">{{ $configurations->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-gear" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Active Configurations
                        </div>
                        <div class="stat-number">{{ $configurations->where('is_active', true)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Bonus Types
                        </div>
                        <div class="stat-number">{{ count($bonusTypes) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-diagram-3" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Plans Covered
                        </div>
                        <div class="stat-number">{{ $plans->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-layers" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Filters -->
    <div class="col-lg-3 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-funnel me-2"></i>
                    Filters
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.bonus.configurations.index') }}">
                    <div class="mb-3">
                        <label for="plan_id" class="form-label">Plan</label>
                        <select name="plan_id" id="plan_id" class="form-select">
                            <option value="">All Plans</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="bonus_type" class="form-label">Bonus Type</label>
                        <select name="bonus_type" id="bonus_type" class="form-select">
                            <option value="">All Types</option>
                            @foreach($bonusTypes as $key => $label)
                                <option value="{{ $key }}" {{ request('bonus_type') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.bonus.configurations.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightning-charge me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.bonus.configurations.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>
                        New Configuration
                    </a>
                    <a href="{{ route('admin.bonus.configurations.statistics') }}" class="btn btn-outline-info">
                        <i class="bi bi-graph-up me-2"></i>
                        View Statistics
                    </a>
                    <a href="{{ route('admin.bonus.payments.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-currency-dollar me-2"></i>
                        View Payments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Configurations List -->
    <div class="col-lg-9 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-gear me-2"></i>
                        Bonus Configurations
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="exportConfigurations()">
                            <i class="bi bi-download me-2"></i>
                            Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($configurations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>Bonus Type</th>
                                    <th>Configuration</th>
                                    <th>Eligibility</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($configurations as $config)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $config->plan->title }}</span>
                                            <small class="text-muted">R$ {{ number_format($config->plan->sale_price, 2, ',', '.') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $config->bonus_type === 'direct_referral' ? 'primary' : ($config->bonus_type === 'unilevel' ? 'info' : ($config->bonus_type === 'forced_matrix' ? 'warning' : 'success')) }}">
                                            {{ $bonusTypes[$config->bonus_type] ?? ucfirst(str_replace('_', ' ', $config->bonus_type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="configuration-details">
                                            @switch($config->bonus_type)
                                                @case('direct_referral')
                                                    @if($config->direct_referral_percentage)
                                                        <span class="badge bg-light text-dark">{{ $config->direct_referral_percentage }}%</span>
                                                    @endif
                                                    @if($config->direct_referral_fixed)
                                                        <span class="badge bg-light text-dark">R$ {{ number_format($config->direct_referral_fixed, 2) }}</span>
                                                    @endif
                                                    @break
                                                @case('unilevel')
                                                    @if($config->unilevel_percentages)
                                                        <small class="text-muted">{{ count($config->unilevel_percentages) }} levels</small>
                                                        <br>
                                                        @foreach(array_slice($config->unilevel_percentages, 0, 3) as $percentage)
                                                            <span class="badge bg-light text-dark me-1">{{ $percentage }}%</span>
                                                        @endforeach
                                                        @if(count($config->unilevel_percentages) > 3)
                                                            <span class="text-muted">...</span>
                                                        @endif
                                                    @endif
                                                    @break
                                                @case('forced_matrix')
                                                    <small class="text-muted">{{ $config->matrix_width }}x{{ $config->matrix_depth }} matrix</small>
                                                    <br>
                                                    @if($config->matrix_percentages)
                                                        @foreach(array_slice($config->matrix_percentages, 0, 3) as $percentage)
                                                            <span class="badge bg-light text-dark me-1">{{ $percentage }}%</span>
                                                        @endforeach
                                                        @if(count($config->matrix_percentages) > 3)
                                                            <span class="text-muted">...</span>
                                                        @endif
                                                    @endif
                                                    @break
                                                @case('profit_sharing')
                                                    <span class="badge bg-light text-dark">{{ $config->profit_sharing_percentage }}%</span>
                                                    <br>
                                                    <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $config->profit_sharing_basis)) }}</small>
                                                    @break
                                            @endswitch
                                        </div>
                                    </td>
                                    <td>
                                        <div class="eligibility-info">
                                            @if($config->requires_active_invoice)
                                                <span class="badge bg-warning">Active Invoice Required</span>
                                            @else
                                                <span class="badge bg-success">Always Eligible</span>
                                            @endif
                                            @if($config->minimum_volume > 0)
                                                <br>
                                                <small class="text-muted">Min: R$ {{ number_format($config->minimum_volume, 2) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $config->is_active ? 'success' : 'secondary' }}">
                                            {{ $config->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $config->created_at->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $config->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.bonus.configurations.show', $config) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.bonus.configurations.edit', $config) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.bonus.configurations.toggle', $config) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $config->is_active ? 'secondary' : 'success' }}" 
                                                        data-bs-toggle="tooltip" title="{{ $config->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi bi-{{ $config->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.bonus.configurations.duplicate', $config) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info" 
                                                        data-bs-toggle="tooltip" title="Duplicate">
                                                    <i class="bi bi-files"></i>
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
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $configurations->firstItem() }} to {{ $configurations->lastItem() }} 
                                of {{ $configurations->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $configurations->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-gear display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No bonus configurations found</h4>
                        <p class="text-muted">Create your first bonus configuration to get started.</p>
                        <a href="{{ route('admin.bonus.configurations.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Create Configuration
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card .card-body {
    padding: 1.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
}

.configuration-details .badge {
    font-size: 0.75rem;
}
</style>

@push('scripts')
<script>
    function exportConfigurations() {
        // Implement export functionality
        alert('Export functionality will be implemented');
    }
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
@endsection
