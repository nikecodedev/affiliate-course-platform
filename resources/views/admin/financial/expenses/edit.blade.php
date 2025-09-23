@extends('layouts.admin')

@section('title', 'Edit Expense')
@section('page-title', 'Edit Expense')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil me-2"></i>Edit Expense: {{ $expense->title }}
                    </h5>
                    <a href="{{ route('admin.financial.expenses.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Expenses
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

                <form method="POST" action="{{ route('admin.financial.expenses.update', $expense) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Expense Title <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $expense->title) }}" 
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
                                          rows="4">{{ old('description', $expense->description) }}</textarea>
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
                                                   value="{{ old('amount', $expense->amount) }}" 
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
                                               value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" 
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
                                          rows="3">{{ old('notes', $expense->notes) }}</textarea>
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
                                        <option value="{{ $key }}" {{ old('category', $expense->category) === $key ? 'selected' : '' }}>
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
                                
                                @if($expense->receipt_path)
                                    <div class="mb-2">
                                        <label class="form-label">Current Receipt</label>
                                        <div class="border rounded p-2">
                                            @if(str_ends_with($expense->receipt_path, '.pdf'))
                                                <div class="text-center">
                                                    <i class="bi bi-file-earmark-pdf display-4 text-danger"></i>
                                                    <div class="mt-2">
                                                        <a href="{{ asset('storage/' . $expense->receipt_path) }}" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye me-1"></i>View Receipt
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <img src="{{ asset('storage/' . $expense->receipt_path) }}" 
                                                     class="img-fluid rounded" 
                                                     alt="Receipt"
                                                     style="max-height: 200px;">
                                                <div class="mt-2">
                                                    <a href="{{ asset('storage/' . $expense->receipt_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye me-1"></i>View Receipt
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <input type="file" 
                                       class="form-control @error('receipt') is-invalid @enderror" 
                                       id="receipt" 
                                       name="receipt" 
                                       accept=".jpg,.jpeg,.png,.pdf">
                                <div class="form-text">Upload new receipt to replace current one (max 2MB)</div>
                                @error('receipt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Receipt Preview -->
                            <div id="receipt-preview" class="mb-3" style="display: none;">
                                <label class="form-label">New Receipt Preview</label>
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

                            <!-- Expense Info -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-info-circle me-2"></i>Expense Information
                                    </h6>
                                    <div class="mb-2">
                                        <strong>Created:</strong>
                                        <div class="text-muted">{{ $expense->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Created By:</strong>
                                        <div class="text-muted">{{ $expense->creator->name ?? 'Admin' }}</div>
                                    </div>
                                    <div>
                                        <strong>Last Updated:</strong>
                                        <div class="text-muted">{{ $expense->updated_at->format('M d, Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('admin.financial.expenses.index') }}" class="btn btn-secondary me-2">
                                        <i class="bi bi-arrow-left me-2"></i>Back to Expenses
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="deleteExpense()">
                                        <i class="bi bi-trash me-2"></i>Delete Expense
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Update Expense
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>Delete Expense
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the expense <strong>"{{ $expense->title }}"</strong>?</p>
                <p class="text-danger">
                    <i class="bi bi-warning me-2"></i>
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.financial.expenses.destroy', $expense) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Delete Expense
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function deleteExpense() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
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
</script>
@endsection
