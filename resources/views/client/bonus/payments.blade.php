@extends('layouts.client')

@section('title', 'Bonus Payments')
@section('page-title', 'Bonus Payments')

@section('content')
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
                <form method="GET" action="{{ route('client.bonus.payments') }}">
                    <div class="mb-3">
                        <label for="bonus_type" class="form-label">Bonus Type</label>
                        <select name="bonus_type" id="bonus_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="direct_referral" {{ request('bonus_type') == 'direct_referral' ? 'selected' : '' }}>
                                Direct Referral
                            </option>
                            <option value="unilevel" {{ request('bonus_type') == 'unilevel' ? 'selected' : '' }}>
                                Unilevel
                            </option>
                            <option value="forced_matrix" {{ request('bonus_type') == 'forced_matrix' ? 'selected' : '' }}>
                                Forced Matrix
                            </option>
                            <option value="profit_sharing" {{ request('bonus_type') == 'profit_sharing' ? 'selected' : '' }}>
                                Profit Sharing
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('client.bonus.payments') }}" class="btn btn-outline-secondary">
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
                    <a href="{{ route('client.bonus.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-graph-up me-2"></i>
                        Overview
                    </a>
                    <a href="{{ route('client.bonus.export') }}" class="btn btn-outline-success">
                        <i class="bi bi-download me-2"></i>
                        Export Data
                    </a>
                    <a href="{{ route('client.network.index') }}" class="btn btn-outline-info">
                        <i class="bi bi-diagram-3 me-2"></i>
                        View Network
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bonus Payments List -->
    <div class="col-lg-9 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-gift me-2"></i>
                        Bonus Payments
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="exportPayments()">
                            <i class="bi bi-download me-2"></i>
                            Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($bonusPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>From Client</th>
                                    <th>Level</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bonusPayments as $payment)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $payment->created_at->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payment->bonus_type === 'direct_referral' ? 'primary' : ($payment->bonus_type === 'unilevel' ? 'info' : ($payment->bonus_type === 'forced_matrix' ? 'warning' : 'success')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $payment->bonus_type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            R$ {{ number_format($payment->amount, 2, ',', '.') }}
                                        </span>
                                        @if($payment->percentage)
                                            <br>
                                            <small class="text-muted">{{ $payment->percentage }}%</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'approved' ? 'warning' : ($payment->status === 'pending' ? 'secondary' : 'danger')) }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($payment->fromClient)
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">{{ $payment->fromClient->name }}</span>
                                                <small class="text-muted">{{ $payment->fromClient->email }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->level)
                                            <span class="badge bg-light text-dark">Level {{ $payment->level }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#paymentModal{{ $payment->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
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
                                Showing {{ $bonusPayments->firstItem() }} to {{ $bonusPayments->lastItem() }} 
                                of {{ $bonusPayments->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $bonusPayments->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-gift display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No bonus payments found</h4>
                        <p class="text-muted">Start referring clients to earn bonuses!</p>
                        <a href="{{ route('client.network.index') }}" class="btn btn-primary">
                            <i class="bi bi-people me-2"></i>
                            View Network
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Payment Detail Modals -->
@foreach($bonusPayments as $payment)
<div class="modal fade" id="paymentModal{{ $payment->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bonus Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Date:</strong><br>
                        {{ $payment->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Type:</strong><br>
                        <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $payment->bonus_type)) }}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Amount:</strong><br>
                        <span class="h5 text-success">R$ {{ number_format($payment->amount, 2, ',', '.') }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong><br>
                        <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'approved' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>
                @if($payment->fromClient)
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>From Client:</strong><br>
                        {{ $payment->fromClient->name }}<br>
                        <small class="text-muted">{{ $payment->fromClient->email }}</small>
                    </div>
                    @if($payment->level)
                    <div class="col-md-6">
                        <strong>Level:</strong><br>
                        <span class="badge bg-light text-dark">Level {{ $payment->level }}</span>
                    </div>
                    @endif
                </div>
                @endif
                @if($payment->base_amount)
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Base Amount:</strong><br>
                        R$ {{ number_format($payment->base_amount, 2, ',', '.') }}
                    </div>
                    @if($payment->percentage)
                    <div class="col-md-6">
                        <strong>Percentage:</strong><br>
                        {{ $payment->percentage }}%
                    </div>
                    @endif
                </div>
                @endif
                @if($payment->notes)
                <hr>
                <div class="row">
                    <div class="col-12">
                        <strong>Notes:</strong><br>
                        {{ $payment->notes }}
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    function exportPayments() {
        // Get current filter parameters
        const params = new URLSearchParams(window.location.search);
        const exportUrl = '{{ route("client.bonus.export") }}?' + params.toString();
        window.location.href = exportUrl;
    }
</script>
@endpush
@endsection
