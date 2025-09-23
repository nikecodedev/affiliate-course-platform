@extends('layouts.admin')

@section('title', 'Make Commission Payment')
@section('page-title', 'Make Commission Payment')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Make Commission Payment
                </h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.financial.commission-payments.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Select Affiliate <span class="text-danger">*</span></label>
                                <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="user_id" 
                                        name="user_id" 
                                        required 
                                        onchange="loadAffiliateInfo()">
                                    <option value="">Select an affiliate</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" 
                                                data-pending="{{ $user->pending_commission ?? 0 }}"
                                                data-total="{{ $user->total_commission_earned ?? 0 }}"
                                                data-paid="{{ $user->total_commission_paid ?? 0 }}">
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control @error('amount') is-invalid @enderror" 
                                                   id="amount" 
                                                   name="amount" 
                                                   value="{{ old('amount') }}" 
                                                   step="0.01" 
                                                   min="0" 
                                                   required>
                                        </div>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-select @error('payment_method') is-invalid @enderror" 
                                                id="payment_method" 
                                                name="payment_method" 
                                                required>
                                            <option value="">Select Method</option>
                                            <option value="pix" {{ old('payment_method') === 'pix' ? 'selected' : '' }}>PIX</option>
                                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="payment_reference" class="form-label">Payment Reference</label>
                                <input type="text" 
                                       class="form-control @error('payment_reference') is-invalid @enderror" 
                                       id="payment_reference" 
                                       name="payment_reference" 
                                       value="{{ old('payment_reference') }}">
                                <div class="form-text">Transaction ID or reference number (optional)</div>
                                @error('payment_reference')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="4">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Affiliate Information Card -->
                            <div class="card" id="affiliate-info" style="display: none;">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-person-check me-2"></i>Affiliate Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Pending Commission:</strong>
                                        <div class="text-warning fw-bold" id="pending-commission">$0.00</div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Total Earned:</strong>
                                        <div class="text-success fw-bold" id="total-earned">$0.00</div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Total Paid:</strong>
                                        <div class="text-info fw-bold" id="total-paid">$0.00</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Amount Buttons -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-lightning me-2"></i>Quick Amount
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="btn-group w-100 mb-2" role="group">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(25)">$25</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(50)">$50</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(100)">$100</button>
                                    </div>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(250)">$250</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(500)">$500</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setFullPending()">Full</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Guidelines -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-info-circle me-2"></i>Payment Guidelines
                                    </h6>
                                    <ul class="mb-0 small">
                                        <li>Verify affiliate's payment details</li>
                                        <li>Ensure sufficient pending commission</li>
                                        <li>Record payment reference</li>
                                        <li>Keep receipts for accounting</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.financial.commission-payments.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Payments
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-cash-coin me-2"></i>Make Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function loadAffiliateInfo() {
    const select = document.getElementById('user_id');
    const option = select.options[select.selectedIndex];
    const affiliateInfo = document.getElementById('affiliate-info');
    
    if (select.value) {
        const pending = parseFloat(option.dataset.pending) || 0;
        const total = parseFloat(option.dataset.total) || 0;
        const paid = parseFloat(option.dataset.paid) || 0;
        
        document.getElementById('pending-commission').textContent = '$' + pending.toFixed(2);
        document.getElementById('total-earned').textContent = '$' + total.toFixed(2);
        document.getElementById('total-paid').textContent = '$' + paid.toFixed(2);
        
        affiliateInfo.style.display = 'block';
    } else {
        affiliateInfo.style.display = 'none';
    }
}

function setAmount(amount) {
    document.getElementById('amount').value = amount;
}

function setFullPending() {
    const select = document.getElementById('user_id');
    const option = select.options[select.selectedIndex];
    
    if (select.value) {
        const pending = parseFloat(option.dataset.pending) || 0;
        document.getElementById('amount').value = pending;
    }
}

// Validate amount against pending commission
document.getElementById('amount').addEventListener('input', function() {
    const select = document.getElementById('user_id');
    const option = select.options[select.selectedIndex];
    
    if (select.value) {
        const amount = parseFloat(this.value) || 0;
        const pending = parseFloat(option.dataset.pending) || 0;
        
        if (amount > pending) {
            this.classList.add('is-invalid');
            if (!document.querySelector('#amount-error')) {
                const errorDiv = document.createElement('div');
                errorDiv.id = 'amount-error';
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'Amount cannot exceed pending commission ($' + pending.toFixed(2) + ')';
                this.parentNode.appendChild(errorDiv);
            }
        } else {
            this.classList.remove('is-invalid');
            const errorDiv = document.querySelector('#amount-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
    }
});
</script>
@endsection
