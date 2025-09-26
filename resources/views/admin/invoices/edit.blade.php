@extends('layouts.admin')

@section('title', 'Edit Invoice - ' . $invoice->invoice_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Invoice - {{ $invoice->invoice_number }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Invoice
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.invoices.update', $invoice) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

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
                                                    <label for="user_id" class="form-label">
                                                        Customer <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select @error('user_id') is-invalid @enderror" 
                                                            id="user_id" name="user_id" required>
                                                        <option value="">Select Customer</option>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}" 
                                                                    {{ old('user_id', $invoice->user_id) == $user->id ? 'selected' : '' }}>
                                                                {{ $user->name }} ({{ $user->email }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('user_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="plan_id" class="form-label">
                                                        Plan/Product <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select @error('plan_id') is-invalid @enderror" 
                                                            id="plan_id" name="plan_id" required>
                                                        <option value="">Select Plan/Product</option>
                                                        @foreach($plans as $plan)
                                                            <option value="{{ $plan->id }}" 
                                                                    data-price="{{ $plan->sale_price }}"
                                                                    {{ old('plan_id', $invoice->plan_id) == $plan->id ? 'selected' : '' }}>
                                                                {{ $plan->title }} - R$ {{ number_format($plan->sale_price, 2, ',', '.') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('plan_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="amount" class="form-label">
                                                        Amount <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="number" 
                                                               class="form-control @error('amount') is-invalid @enderror" 
                                                               id="amount" 
                                                               name="amount" 
                                                               value="{{ old('amount', $invoice->amount) }}" 
                                                               step="0.01"
                                                               min="0"
                                                               placeholder="0.00"
                                                               required>
                                                    </div>
                                                    @error('amount')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="discount_amount" class="form-label">
                                                        Discount Amount
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="number" 
                                                               class="form-control @error('discount_amount') is-invalid @enderror" 
                                                               id="discount_amount" 
                                                               name="discount_amount" 
                                                               value="{{ old('discount_amount', $invoice->discount_amount) }}" 
                                                               step="0.01"
                                                               min="0"
                                                               placeholder="0.00">
                                                    </div>
                                                    @error('discount_amount')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Final Amount</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="text" 
                                                               class="form-control" 
                                                               id="final_amount" 
                                                               readonly
                                                               value="{{ number_format($invoice->final_amount, 2, '.', '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                      id="notes" 
                                                      name="notes" 
                                                      rows="3" 
                                                      placeholder="Additional notes or comments...">{{ old('notes', $invoice->notes) }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-calculator me-2"></i>
                                            Invoice Summary
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Amount:</span>
                                            <span id="summary_amount">R$ {{ number_format($invoice->amount, 2, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Discount:</span>
                                            <span id="summary_discount" class="text-success">-R$ {{ number_format($invoice->discount_amount, 2, ',', '.') }}</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <strong>Final Amount:</strong>
                                            <strong id="summary_final">R$ {{ number_format($invoice->final_amount, 2, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Status -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Current Status
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <span class="badge {{ $invoice->getStatusBadgeClass() }} fs-6">
                                                {{ $invoice->getStatusText() }}
                                            </span>
                                        </div>
                                        
                                        @if($invoice->paid_at)
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <strong>Paid:</strong> {{ $invoice->paid_at->format('M d, Y H:i') }}
                                                </small>
                                            </div>
                                        @endif

                                        @if($invoice->confirmed_at)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Confirmed:</strong> {{ $invoice->confirmed_at->format('M d, Y H:i') }}
                                                </small>
                                            </div>
                                        @endif

                                        @if($invoice->refunded_at)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Refunded:</strong> {{ $invoice->refunded_at->format('M d, Y H:i') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-gear me-2"></i>
                                            Quick Actions
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>
                                    Update Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const planSelect = document.getElementById('plan_id');
    const amountInput = document.getElementById('amount');
    const discountInput = document.getElementById('discount_amount');
    const finalAmountInput = document.getElementById('final_amount');
    
    const summaryAmount = document.getElementById('summary_amount');
    const summaryDiscount = document.getElementById('summary_discount');
    const summaryFinal = document.getElementById('summary_final');

    function updateCalculations() {
        const amount = parseFloat(amountInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const final = amount - discount;

        finalAmountInput.value = final.toFixed(2);
        
        // Update summary
        summaryAmount.textContent = 'R$ ' + amount.toLocaleString('pt-BR', {minimumFractionDigits: 2});
        summaryDiscount.textContent = '-R$ ' + discount.toLocaleString('pt-BR', {minimumFractionDigits: 2});
        summaryFinal.textContent = 'R$ ' + final.toLocaleString('pt-BR', {minimumFractionDigits: 2});
    }

    // Auto-fill amount when plan is selected
    planSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const price = parseFloat(selectedOption.dataset.price);
            amountInput.value = price.toFixed(2);
            updateCalculations();
        }
    });

    // Update calculations when amount or discount changes
    amountInput.addEventListener('input', updateCalculations);
    discountInput.addEventListener('input', updateCalculations);

    // Initial calculation
    updateCalculations();
});

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
