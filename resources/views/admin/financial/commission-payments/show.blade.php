@extends('layouts.admin')

@section('title', 'Commission Payment Details')
@section('page-title', 'Commission Payment Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-cash-coin me-2"></i>Commission Payment #{{ $commissionPayment->id }}
                    </h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.financial.commission-payments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Payments
                        </a>
                        @if($commissionPayment->status === 'pending')
                            <button type="button" class="btn btn-outline-success" onclick="markAsPaid()">
                                <i class="bi bi-check-circle me-2"></i>Mark as Paid
                            </button>
                        @endif
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
                        <!-- Payment Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-info-circle me-2"></i>Payment Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>Payment ID:</strong>
                                            <div class="text-muted">#{{ $commissionPayment->id }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Amount:</strong>
                                            <div class="text-success fw-bold fs-5">${{ number_format($commissionPayment->amount, 2) }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Payment Method:</strong>
                                            <div class="text-muted">
                                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $commissionPayment->payment_method)) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>Status:</strong>
                                            <div>
                                                @switch($commissionPayment->status)
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
                                                        <span class="badge bg-secondary">{{ ucfirst($commissionPayment->status) }}</span>
                                                @endswitch
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Payment Date:</strong>
                                            <div class="text-muted">
                                                @if($commissionPayment->payment_date)
                                                    {{ $commissionPayment->payment_date->format('M d, Y H:i') }}
                                                @else
                                                    <span class="text-muted">Not paid yet</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Created:</strong>
                                            <div class="text-muted">{{ $commissionPayment->created_at->format('M d, Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>

                                @if($commissionPayment->payment_reference)
                                    <div class="mb-3">
                                        <strong>Payment Reference:</strong>
                                        <div class="text-muted">
                                            <code>{{ $commissionPayment->payment_reference }}</code>
                                        </div>
                                    </div>
                                @endif

                                @if($commissionPayment->notes)
                                    <div class="mb-3">
                                        <strong>Notes:</strong>
                                        <div class="text-muted">{{ $commissionPayment->notes }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Affiliate Information -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-person-check me-2"></i>Affiliate Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-lg bg-success rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="bi bi-person-check text-white" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $commissionPayment->user->name ?? 'N/A' }}</h5>
                                        <div class="text-muted">{{ $commissionPayment->user->email ?? 'N/A' }}</div>
                                        @if($commissionPayment->user->affiliate_code)
                                            <div class="text-muted">
                                                <strong>Affiliate Code:</strong> 
                                                <span class="badge bg-warning">{{ $commissionPayment->user->affiliate_code }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($commissionPayment->user)
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded">
                                                <div class="text-success">
                                                    <i class="bi bi-graph-up display-6"></i>
                                                </div>
                                                <h5 class="mt-2">{{ $commissionPayment->user->affiliate_sales_count ?? 0 }}</h5>
                                                <p class="text-muted mb-0">Total Sales</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded">
                                                <div class="text-warning">
                                                    <i class="bi bi-cash-coin display-6"></i>
                                                </div>
                                                <h5 class="mt-2">${{ number_format($commissionPayment->user->total_commission_earned ?? 0, 2) }}</h5>
                                                <p class="text-muted mb-0">Total Earned</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded">
                                                <div class="text-info">
                                                    <i class="bi bi-arrow-up-circle display-6"></i>
                                                </div>
                                                <h5 class="mt-2">${{ number_format($commissionPayment->user->total_commission_paid ?? 0, 2) }}</h5>
                                                <p class="text-muted mb-0">Total Paid</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Payment Actions -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-lightning me-2"></i>Payment Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    @if($commissionPayment->status === 'pending')
                                        <button type="button" class="btn btn-success" onclick="markAsPaid()">
                                            <i class="bi bi-check-circle me-2"></i>Mark as Paid
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" onclick="markAsFailed()">
                                            <i class="bi bi-x-circle me-2"></i>Mark as Failed
                                        </button>
                                    @elseif($commissionPayment->status === 'paid')
                                        <div class="alert alert-success mb-0">
                                            <i class="bi bi-check-circle me-2"></i>
                                            Payment completed on {{ $commissionPayment->payment_date->format('M d, Y') }}
                                        </div>
                                    @elseif($commissionPayment->status === 'failed')
                                        <div class="alert alert-danger mb-0">
                                            <i class="bi bi-x-circle me-2"></i>
                                            Payment failed
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Payment History -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-clock-history me-2"></i>Payment History
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Payment Created</h6>
                                            <p class="timeline-text text-muted">{{ $commissionPayment->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($commissionPayment->payment_date)
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">Payment Completed</h6>
                                                <p class="timeline-text text-muted">{{ $commissionPayment->payment_date->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            <form method="POST" action="{{ route('admin.financial.commission-payments.mark-paid', $commissionPayment) }}">
                @csrf
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Mark as Paid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mark as Failed Modal -->
<div class="modal fade" id="markFailedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-x-circle me-2"></i>Mark Payment as Failed
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.financial.commission-payments.mark-failed', $commissionPayment) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="failure_reason" class="form-label">Failure Reason</label>
                        <textarea class="form-control" id="failure_reason" name="failure_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i>Mark as Failed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    font-size: 12px;
    margin-bottom: 0;
}
</style>
@endsection

@section('scripts')
<script>
function markAsPaid() {
    const modal = new bootstrap.Modal(document.getElementById('markPaidModal'));
    modal.show();
}

function markAsFailed() {
    const modal = new bootstrap.Modal(document.getElementById('markFailedModal'));
    modal.show();
}
</script>
@endsection
