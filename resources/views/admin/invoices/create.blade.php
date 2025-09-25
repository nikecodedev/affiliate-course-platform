@extends('layouts.admin')

@section('title', 'Create New Invoice')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-plus me-2"></i>
                        Create New Invoice
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Invoices
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.invoices.store') }}" method="POST">
                    @csrf
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
                                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                                                    {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
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
                                                               value="{{ old('amount') }}" 
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
                                                               value="{{ old('discount_amount', 0) }}" 
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
                                                               placeholder="0.00">
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
                                                      placeholder="Additional notes or comments...">{{ old('notes') }}</textarea>
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
                                            <span id="summary_amount">R$ 0,00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Discount:</span>
                                            <span id="summary_discount" class="text-success">-R$ 0,00</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <strong>Final Amount:</strong>
                                            <strong id="summary_final">R$ 0,00</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Next Steps -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bi bi-lightbulb me-2"></i>
                                            Next Steps
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ol class="small">
                                            <li>Create the invoice</li>
                                            <li>Customer receives invoice</li>
                                            <li>Customer makes payment</li>
                                            <li>Mark as paid when payment received</li>
                                            <li>Confirm invoice to finalize</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>
                                    Create Invoice
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
</script>
@endsection
