@extends('layouts.admin')

@section('title', 'Payment Gateways')
@section('page-title', 'Payment Gateway Management')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Gateways
                        </div>
                        <div class="stat-number">{{ $gateways->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-credit-card" style="font-size: 2rem;"></i>
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
                            Active Gateways
                        </div>
                        <div class="stat-number">{{ $gateways->where('is_active', true)->count() }}</div>
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
                            Gateway Types
                        </div>
                        <div class="stat-number">{{ count($types) }}</div>
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
                            Auto Approve
                        </div>
                        <div class="stat-number">{{ $gateways->where('auto_approve', true)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-lightning-charge" style="font-size: 2rem;"></i>
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
                <form method="GET" action="{{ route('admin.gateways.payment.index') }}">
                    <div class="mb-3">
                        <label for="type" class="form-label">Gateway Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">All Types</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
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

                    <div class="mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Name or code...">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.gateways.payment.index') }}" class="btn btn-outline-secondary">
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
                    <a href="{{ route('admin.gateways.payment.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>
                        New Gateway
                    </a>
                    <a href="{{ route('admin.gateways.payment.statistics') }}" class="btn btn-outline-info">
                        <i class="bi bi-graph-up me-2"></i>
                        View Statistics
                    </a>
                    <button type="button" class="btn btn-outline-secondary" onclick="exportGateways()">
                        <i class="bi bi-download me-2"></i>
                        Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Gateways List -->
    <div class="col-lg-9 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-credit-card me-2"></i>
                        Payment Gateways
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="exportGateways()">
                            <i class="bi bi-download me-2"></i>
                            Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($gateways->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Gateway</th>
                                    <th>Type</th>
                                    <th>Fees</th>
                                    <th>Settings</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gateways as $gateway)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $gateway->name }}</span>
                                            <small class="text-muted">{{ $gateway->code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $gateway->type === 'credit_card' ? 'primary' : ($gateway->type === 'pix' ? 'success' : ($gateway->type === 'cryptocurrency' ? 'warning' : 'info')) }}">
                                            {{ $types[$gateway->type] ?? ucfirst(str_replace('_', ' ', $gateway->type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fee-info">
                                            @if($gateway->fee_percentage > 0)
                                                <span class="badge bg-light text-dark">{{ $gateway->fee_percentage }}%</span>
                                            @endif
                                            @if($gateway->fee_fixed > 0)
                                                <span class="badge bg-light text-dark">R$ {{ number_format($gateway->fee_fixed, 2) }}</span>
                                            @endif
                                            @if($gateway->fee_percentage == 0 && $gateway->fee_fixed == 0)
                                                <span class="text-muted">No fees</span>
                                            @endif
                                            @if($gateway->fee_charged_to_customer)
                                                <br><small class="text-muted">Charged to customer</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="settings-info">
                                            <div class="mb-1">
                                                <small class="text-muted">Processing: {{ $gateway->processing_time_days }} day(s)</small>
                                            </div>
                                            @if($gateway->auto_approve)
                                                <span class="badge bg-success">Auto Approve</span>
                                            @else
                                                <span class="badge bg-warning">Manual</span>
                                            @endif
                                            @if($gateway->requires_webhook)
                                                <br><span class="badge bg-info">Webhook Required</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $gateway->is_active ? 'success' : 'secondary' }}">
                                            {{ $gateway->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.gateways.payment.show', $gateway) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.gateways.payment.edit', $gateway) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.gateways.payment.toggle', $gateway) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $gateway->is_active ? 'secondary' : 'success' }}" 
                                                        data-bs-toggle="tooltip" title="{{ $gateway->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi bi-{{ $gateway->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.gateways.payment.test', $gateway) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info" 
                                                        data-bs-toggle="tooltip" title="Test Connection">
                                                    <i class="bi bi-wifi"></i>
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
                                Showing {{ $gateways->firstItem() }} to {{ $gateways->lastItem() }} 
                                of {{ $gateways->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $gateways->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-credit-card display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No payment gateways found</h4>
                        <p class="text-muted">Create your first payment gateway to get started.</p>
                        <a href="{{ route('admin.gateways.payment.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Create Gateway
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

.fee-info .badge {
    font-size: 0.75rem;
    margin-right: 0.25rem;
}

.settings-info .badge {
    font-size: 0.75rem;
}
</style>

@push('scripts')
<script>
    function exportGateways() {
        // Implement export functionality
        window.location.href = '{{ route("admin.gateways.payment.export") }}';
    }
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
@endsection
