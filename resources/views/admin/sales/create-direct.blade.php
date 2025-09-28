@extends('layouts.admin')

@section('title', 'Create Direct Sale')
@section('page-title', 'Create Direct Sale')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Direct Sale Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.sales.direct.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                    <option value="">Select Customer</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                <label for="plan_id" class="form-label">Plan <span class="text-danger">*</span></label>
                                <select name="plan_id" id="plan_id" class="form-select @error('plan_id') is-invalid @enderror" required>
                                    <option value="">Select Plan</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" 
                                           step="0.01" min="0" value="{{ old('amount') }}" required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="pix" {{ old('payment_method') == 'pix' ? 'selected' : '' }}>PIX</option>
                                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="debit_card" {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                    <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_reference" class="form-label">Payment Reference</label>
                                <input type="text" name="payment_reference" id="payment_reference" class="form-control @error('payment_reference') is-invalid @enderror" 
                                       value="{{ old('payment_reference') }}" placeholder="Transaction ID, Receipt Number, etc.">
                                @error('payment_reference')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="auto_confirm" class="form-label">Auto Confirm</label>
                                <div class="form-check">
                                    <input type="checkbox" name="auto_confirm" id="auto_confirm" class="form-check-input" 
                                           value="1" {{ old('auto_confirm') ? 'checked' : '' }}>
                                    <label for="auto_confirm" class="form-check-label">
                                        Automatically confirm this sale and process bonuses
                                    </label>
                                </div>
                                <small class="text-muted">If checked, the sale will be immediately confirmed and all bonuses will be processed.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                  rows="3" placeholder="Additional notes about this sale...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.sales.direct') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Direct Sale</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Help Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Help & Information</h5>
            </div>
            <div class="card-body">
                <h6>Direct Sales Process</h6>
                <ol class="small">
                    <li>Select the customer and plan</li>
                    <li>Enter the sale amount</li>
                    <li>Choose payment method</li>
                    <li>Optionally auto-confirm the sale</li>
                </ol>

                <h6 class="mt-3">Auto Confirm</h6>
                <p class="small text-muted">
                    When enabled, the sale will be immediately confirmed and:
                </p>
                <ul class="small text-muted">
                    <li>Invoice will be marked as paid</li>
                    <li>User will be positioned in matrix</li>
                    <li>All bonuses will be processed</li>
                </ul>

                <h6 class="mt-3">Payment Methods</h6>
                <ul class="small text-muted">
                    <li><strong>Cash:</strong> Physical cash payment</li>
                    <li><strong>Bank Transfer:</strong> Bank to bank transfer</li>
                    <li><strong>PIX:</strong> Instant payment system</li>
                    <li><strong>Credit/Debit Card:</strong> Card payments</li>
                </ul>
            </div>
        </div>

        <!-- Plan Information -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Selected Plan Info</h5>
            </div>
            <div class="card-body" id="plan-info">
                <p class="text-muted">Select a plan to see details</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const planSelect = document.getElementById('plan_id');
    const amountInput = document.getElementById('amount');
    const planInfo = document.getElementById('plan-info');
    
    // Plan data (you might want to load this via AJAX)
    const plans = @json($plans->keyBy('id'));
    
    planSelect.addEventListener('change', function() {
        const planId = this.value;
        if (planId && plans[planId]) {
            const plan = plans[planId];
            
            // Update amount input
            amountInput.value = plan.sale_price;
            
            // Update plan info
            planInfo.innerHTML = `
                <h6>${plan.title}</h6>
                <p class="small text-muted">${plan.description}</p>
                <div class="row">
                    <div class="col-6">
                        <strong>Sale Price:</strong><br>
                        <span class="text-success">R$ ${parseFloat(plan.sale_price).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</span>
                    </div>
                    <div class="col-6">
                        <strong>Type:</strong><br>
                        <span class="text-info">${plan.type}</span>
                    </div>
                </div>
            `;
        } else {
            amountInput.value = '';
            planInfo.innerHTML = '<p class="text-muted">Select a plan to see details</p>';
        }
    });
});
</script>
@endsection
