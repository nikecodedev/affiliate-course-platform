@extends('layouts.admin')

@section('title', 'Add Expense')
@section('page-title', 'Add New Expense')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Add New Expense
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

                <form method="POST" action="{{ route('admin.financial.expenses.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Expense Title <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
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
                                        <label for="expense_date" class="form-label">Expense Date <span class="text-danger">*</span></label>
                                        <input type="date" 
                                               class="form-control @error('expense_date') is-invalid @enderror" 
                                               id="expense_date" 
                                               name="expense_date" 
                                               value="{{ old('expense_date', date('Y-m-d')) }}" 
                                               required>
                                        @error('expense_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" 
                                        name="category" 
                                        required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="receipt" class="form-label">Receipt</label>
                                <input type="file" 
                                       class="form-control @error('receipt') is-invalid @enderror" 
                                       id="receipt" 
                                       name="receipt" 
                                       accept=".jpg,.jpeg,.png,.pdf">
                                <div class="form-text">Upload receipt image or PDF (max 2MB)</div>
                                @error('receipt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Receipt Preview -->
                            <div id="receipt-preview" class="mb-3" style="display: none;">
                                <label class="form-label">Receipt Preview</label>
                                <div class="border rounded p-2">
                                    <img id="receipt-image" class="img-fluid" style="max-height: 200px; display: none;">
                                    <div id="receipt-file" class="text-center" style="display: none;">
                                        <i class="bi bi-file-earmark-pdf display-4 text-danger"></i>
                                        <div class="mt-2">
                                            <span id="receipt-filename" class="fw-semibold"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Amount Buttons -->
                            <div class="mb-3">
                                <label class="form-label">Quick Amount</label>
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(10)">
                                        <i class="bi bi-currency-dollar me-1"></i>$10
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(25)">
                                        <i class="bi bi-currency-dollar me-1"></i>$25
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(50)">
                                        <i class="bi bi-currency-dollar me-1"></i>$50
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(100)">
                                        <i class="bi bi-currency-dollar me-1"></i>$100
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.financial.expenses.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Expenses
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Add Expense
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
function setAmount(amount) {
    document.getElementById('amount').value = amount;
}

// Receipt preview functionality
document.getElementById('receipt').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('receipt-preview');
    const image = document.getElementById('receipt-image');
    const fileDiv = document.getElementById('receipt-file');
    const filename = document.getElementById('receipt-filename');
    
    if (file) {
        preview.style.display = 'block';
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
                image.style.display = 'block';
                fileDiv.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            filename.textContent = file.name;
            image.style.display = 'none';
            fileDiv.style.display = 'block';
        }
    } else {
        preview.style.display = 'none';
    }
});

// Auto-fill today's date if not set
document.addEventListener('DOMContentLoaded', function() {
    const expenseDate = document.getElementById('expense_date');
    if (!expenseDate.value) {
        expenseDate.value = new Date().toISOString().split('T')[0];
    }
});
</script>
@endsection
