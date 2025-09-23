@extends('layouts.admin')

@section('title', 'Commission Payments')
@section('page-title', 'Commission Payments')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-cash-coin me-2"></i>Commission Payments
                    </h5>
                    <a href="{{ route('admin.financial.commission-payments.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Make Payment
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

                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="status_filter" class="form-label">Status</label>
                        <select class="form-select" id="status_filter" onchange="applyFilters()">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="method_filter" class="form-label">Payment Method</label>
                        <select class="form-select" id="method_filter" onchange="applyFilters()">
                            <option value="">All Methods</option>
                            <option value="pix">PIX</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                <i class="bi bi-x-circle me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Affiliate</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Payment Date</th>
                                <th>Reference</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commissionPayments as $payment)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#{{ $payment->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person-check text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $payment->user->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $payment->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">${{ number_format($payment->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                    </td>
                                    <td>
                                        @switch($payment->status)
                                            @case('pending')
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock me-1"></i>Pending
                                                </span>
                                                @break
                                            @case('paid')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Paid
                                                </span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Failed
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($payment->payment_date)
                                            <div class="text-muted">
                                                {{ $payment->payment_date->format('M d, Y') }}
                                                <br>
                                                <small>{{ $payment->payment_date->format('H:i') }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->payment_reference)
                                            <code>{{ $payment->payment_reference }}</code>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.financial.commission-payments.show', $payment) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @if($payment->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="markAsPaid({{ $payment->id }})" 
                                                        title="Mark as Paid">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-cash-coin display-6 d-block mb-2"></i>
                                            <h5>No Commission Payments</h5>
                                            <p>No commission payments have been made yet.</p>
                                            <a href="{{ route('admin.financial.commission-payments.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>Make First Payment
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($commissionPayments->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $commissionPayments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Mark as Paid Modal -->
<div class="modal fade" id="markPaidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle me-2"></i>Mark Payment as Paid
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="markPaidForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_reference" class="form-label">Payment Reference</label>
                        <input type="text" class="form-control" id="payment_reference" name="payment_reference" required>
                        <div class="form-text">Transaction ID or reference number</div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Mark as Paid</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function applyFilters() {
    const status = document.getElementById('status_filter').value;
    const method = document.getElementById('method_filter').value;
    
    // Here you would typically make an AJAX request to filter the data
    console.log('Applying filters:', { status, method });
}

function clearFilters() {
    document.getElementById('status_filter').value = '';
    document.getElementById('method_filter').value = '';
    applyFilters();
}

function markAsPaid(paymentId) {
    document.getElementById('markPaidForm').action = `/admin/financial/commission-payments/${paymentId}/mark-paid`;
    
    const modal = new bootstrap.Modal(document.getElementById('markPaidModal'));
    modal.show();
}
</script>
@endsection
