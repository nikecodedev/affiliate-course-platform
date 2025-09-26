@extends('layouts.admin')

@section('title', 'Invoice Details - ' . $invoice->invoice_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-receipt me-2"></i>
                        Invoice Details - {{ $invoice->invoice_number }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Invoices
                        </a>
                        <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>
                            Edit Invoice
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Invoice Information -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Invoice Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Invoice Number</label>
                                                <p class="form-control-plaintext">{{ $invoice->invoice_number }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Status</label>
                                                <p class="form-control-plaintext">
                                                    <span class="badge {{ $invoice->getStatusBadgeClass() }}">
                                                        {{ $invoice->getStatusText() }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Customer</label>
                                                <p class="form-control-plaintext">
                                                    <strong>{{ $invoice->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $invoice->user->email }}</small>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Plan/Product</label>
                                                <p class="form-control-plaintext">
                                                    <strong>{{ $invoice->plan->title }}</strong><br>
                                                    <small class="text-muted">{{ ucfirst($invoice->plan->type) }}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Amount</label>
                                                <p class="form-control-plaintext">
                                                    <strong>R$ {{ number_format($invoice->amount, 2, ',', '.') }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Discount</label>
                                                <p class="form-control-plaintext">
                                                    <span class="text-success">-R$ {{ number_format($invoice->discount_amount, 2, ',', '.') }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Final Amount</label>
                                                <p class="form-control-plaintext">
                                                    <strong class="text-primary">R$ {{ number_format($invoice->final_amount, 2, ',', '.') }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Cost Price</label>
                                                <p class="form-control-plaintext">
                                                    @if($invoice->cost_price)
                                                        R$ {{ number_format($invoice->cost_price, 2, ',', '.') }}
                                                    @else
                                                        <span class="text-muted">Not set</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($invoice->notes)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Notes</label>
                                            <p class="form-control-plaintext">{{ $invoice->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Payment Information -->
                            @if($invoice->status !== 'pending')
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-credit-card me-2"></i>
                                            Payment Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Payment Method</label>
                                                    <p class="form-control-plaintext">
                                                        @if($invoice->payment_method)
                                                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $invoice->payment_method)) }}</span>
                                                        @else
                                                            <span class="text-muted">Not specified</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Payment Reference</label>
                                                    <p class="form-control-plaintext">
                                                        @if($invoice->payment_reference)
                                                            <code>{{ $invoice->payment_reference }}</code>
                                                        @else
                                                            <span class="text-muted">Not provided</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        @if($invoice->paid_at)
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Paid At</label>
                                                <p class="form-control-plaintext">{{ $invoice->paid_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        @endif

                                        @if($invoice->payment_proof_url)
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Payment Proof</label>
                                                <p class="form-control-plaintext">
                                                    <a href="{{ route('admin.invoices.download-payment-proof', $invoice) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-download me-1"></i>
                                                        Download Proof
                                                    </a>
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Admin Actions -->
                            @if($invoice->confirmed_at)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-check-circle me-2"></i>
                                            Confirmation Details
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Confirmed At</label>
                                                    <p class="form-control-plaintext">{{ $invoice->confirmed_at->format('M d, Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Confirmed By</label>
                                                    <p class="form-control-plaintext">
                                                        @if($invoice->confirmedBy)
                                                            {{ $invoice->confirmedBy->name }}
                                                        @else
                                                            <span class="text-muted">Unknown</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($invoice->refunded_at)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-arrow-counterclockwise me-2"></i>
                                            Refund Details
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Refunded At</label>
                                                    <p class="form-control-plaintext">{{ $invoice->refunded_at->format('M d, Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Refunded By</label>
                                                    <p class="form-control-plaintext">
                                                        @if($invoice->refundedBy)
                                                            {{ $invoice->refundedBy->name }}
                                                        @else
                                                            <span class="text-muted">Unknown</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Actions Sidebar -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-gear me-2"></i>
                                        Actions
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($invoice->canBePaid())
                                        <button type="button" class="btn btn-success w-100 mb-2" 
                                                onclick="markAsPaid({{ $invoice->id }})">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Mark as Paid
                                        </button>
                                    @endif

                                    @if($invoice->canBeConfirmed())
                                        <form method="POST" action="{{ route('admin.invoices.mark-confirmed', $invoice) }}" 
                                              onsubmit="return confirm('Are you sure you want to confirm this invoice? This action is irreversible.')">
                                            @csrf
                                            <button type="submit" class="btn btn-success w-100 mb-2">
                                                <i class="bi bi-check2-circle me-1"></i>
                                                Confirm Invoice
                                            </button>
                                        </form>
                                    @endif

                                    @if($invoice->canBeRefunded())
                                        <button type="button" class="btn btn-danger w-100 mb-2" 
                                                onclick="markAsRefunded({{ $invoice->id }})">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                                            Refund Invoice
                                        </button>
                                    @endif

                                    <hr>

                                    <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-outline-primary w-100 mb-2">
                                        <i class="bi bi-pencil me-1"></i>
                                        Edit Invoice
                                    </a>

                                    <a href="{{ route('admin.users.show', $invoice->user) }}" class="btn btn-outline-info w-100 mb-2">
                                        <i class="bi bi-person me-1"></i>
                                        View Customer
                                    </a>

                                    <a href="{{ route('admin.plans.show', $invoice->plan) }}" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-box me-1"></i>
                                        View Plan
                                    </a>
                                </div>
                            </div>

                            <!-- Invoice Timeline -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-clock-history me-2"></i>
                                        Timeline
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">Invoice Created</h6>
                                                <p class="timeline-text">{{ $invoice->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>

                                        @if($invoice->paid_at)
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-info"></div>
                                                <div class="timeline-content">
                                                    <h6 class="timeline-title">Payment Received</h6>
                                                    <p class="timeline-text">{{ $invoice->paid_at->format('M d, Y H:i') }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($invoice->confirmed_at)
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-success"></div>
                                                <div class="timeline-content">
                                                    <h6 class="timeline-title">Invoice Confirmed</h6>
                                                    <p class="timeline-text">{{ $invoice->confirmed_at->format('M d, Y H:i') }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($invoice->refunded_at)
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-danger"></div>
                                                <div class="timeline-content">
                                                    <h6 class="timeline-title">Invoice Refunded</h6>
                                                    <p class="timeline-text">{{ $invoice->refunded_at->format('M d, Y H:i') }}</p>
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
</div>

<!-- Mark as Paid Modal -->
<div class="modal fade" id="markPaidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="markPaidForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Mark Invoice as Paid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="gateway">Payment Gateway</option>
                            <option value="manual">Manual Entry</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_reference" class="form-label">Payment Reference</label>
                        <input type="text" class="form-control" id="payment_reference" name="payment_reference" 
                               placeholder="Transaction ID, Reference Number, etc.">
                    </div>
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">Payment Proof</label>
                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" 
                               accept=".jpg,.jpeg,.png,.pdf">
                        <div class="form-text">Upload receipt or proof of payment (optional)</div>
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

<!-- Mark as Refunded Modal -->
<div class="modal fade" id="markRefundedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="markRefundedForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Refund Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action will refund the invoice and automatically reverse any bonuses/commissions paid for this sale.
                    </div>
                    <div class="mb-3">
                        <label for="refund_reason" class="form-label">Refund Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="refund_reason" name="refund_reason" rows="3" 
                                  placeholder="Please provide a reason for the refund..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Refund Invoice
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
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 2px;
}

.timeline-text {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0;
}
</style>
@endsection

@section('scripts')
<script>
function markAsPaid(invoiceId) {
    const form = document.getElementById('markPaidForm');
    form.action = `/admin/invoices/${invoiceId}/mark-paid`;
    
    const modal = new bootstrap.Modal(document.getElementById('markPaidModal'));
    modal.show();
}

function markAsRefunded(invoiceId) {
    const form = document.getElementById('markRefundedForm');
    form.action = `/admin/invoices/${invoiceId}/mark-refunded`;
    
    const modal = new bootstrap.Modal(document.getElementById('markRefundedModal'));
    modal.show();
}
</script>
@endsection
