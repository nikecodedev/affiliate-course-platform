@extends('layouts.admin')

@section('title', 'Expenses Management')
@section('page-title', 'Expenses Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>Expenses Management
                    </h5>
                    <a href="{{ route('admin.financial.expenses.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Expense
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
                        <label for="category_filter" class="form-label">Category</label>
                        <select class="form-select" id="category_filter" onchange="applyFilters()">
                            <option value="">All Categories</option>
                            @foreach($categories as $key => $category)
                                <option value="{{ $key }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" onchange="applyFilters()">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" onchange="applyFilters()">
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
                                <th>Title</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Created By</th>
                                <th>Receipt</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#{{ $expense->id }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $expense->title }}</div>
                                        @if($expense->description)
                                            <small class="text-muted">{{ Str::limit($expense->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $categories[$expense->category] ?? $expense->category }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-danger">${{ number_format($expense->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            {{ $expense->expense_date->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $expense->creator->name ?? 'Admin' }}</div>
                                                <small class="text-muted">{{ $expense->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($expense->receipt_path)
                                            <a href="{{ asset('storage/' . $expense->receipt_path) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.financial.expenses.edit', $expense) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.financial.expenses.destroy', $expense) }}" 
                                                  style="display: inline-block;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this expense?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-receipt display-6 d-block mb-2"></i>
                                            <h5>No Expenses Found</h5>
                                            <p>Start tracking your business expenses.</p>
                                            <a href="{{ route('admin.financial.expenses.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>Add First Expense
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($expenses->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function applyFilters() {
    const category = document.getElementById('category_filter').value;
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    
    // Here you would typically make an AJAX request to filter the data
    // For now, we'll just show a loading state
    console.log('Applying filters:', { category, dateFrom, dateTo });
}

function clearFilters() {
    document.getElementById('category_filter').value = '';
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    applyFilters();
}
</script>
@endsection
