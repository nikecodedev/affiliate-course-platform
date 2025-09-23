@extends('layouts.admin')

@section('title', 'Sales Management')
@section('page-title', 'Sales Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-cart-check me-2"></i>Sales Management
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="filterSales('all')">
                            All Sales
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="filterSales('confirmed')">
                            Confirmed
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="filterSales('pending')">
                            Pending
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="filterSales('refunded')">
                            Refunded
                        </button>
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover" id="salesTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Commission</th>
                                <th>Affiliate</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#{{ $sale->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $sale->user->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $sale->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $sale->plan->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">${{ number_format($sale->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-warning">${{ number_format($sale->commission_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($sale->affiliate)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="bi bi-person-check text-white"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $sale->affiliate->name }}</div>
                                                    <small class="text-muted">{{ $sale->affiliate->affiliate_code }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Direct Sale</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($sale->status)
                                            @case('confirmed')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Confirmed
                                                </span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock me-1"></i>Pending
                                                </span>
                                                @break
                                            @case('refunded')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Refunded
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($sale->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            {{ $sale->sale_date->format('M d, Y') }}
                                            <br>
                                            <small>{{ $sale->sale_date->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewSale({{ $sale->id }})" 
                                                    title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            
                                            @if($sale->status === 'pending')
                                                <form method="POST" action="{{ route('admin.sales.confirm', $sale) }}" 
                                                      style="display: inline-block;" 
                                                      onsubmit="return confirm('Are you sure you want to confirm this sale?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-success" 
                                                            title="Confirm Sale">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($sale->status === 'confirmed')
                                                <form method="POST" action="{{ route('admin.sales.refund', $sale) }}" 
                                                      style="display: inline-block;" 
                                                      onsubmit="return confirm('Are you sure you want to refund this sale?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            title="Refund Sale">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-cart-x display-6 d-block mb-2"></i>
                                            <h5>No Sales Found</h5>
                                            <p>There are no sales to display at the moment.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($sales->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $sales->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Sale Details Modal -->
<div class="modal fade" id="saleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-cart-check me-2"></i>Sale Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="saleModalBody">
                <!-- Sale details will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function filterSales(status) {
    // Remove active class from all buttons
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    // Filter table rows
    const rows = document.querySelectorAll('#salesTable tbody tr');
    rows.forEach(row => {
        const statusBadge = row.querySelector('.badge');
        const rowStatus = statusBadge ? statusBadge.textContent.toLowerCase().trim() : '';
        
        if (status === 'all' || rowStatus.includes(status)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function viewSale(saleId) {
    // Here you would typically load sale details via AJAX
    // For now, we'll show a placeholder
    document.getElementById('saleModalBody').innerHTML = `
        <div class="text-center py-4">
            <i class="bi bi-hourglass-split display-4 text-muted mb-3"></i>
            <h5>Loading Sale Details...</h5>
            <p class="text-muted">Sale ID: ${saleId}</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('saleModal'));
    modal.show();
}

// Initialize DataTable if you want to add search and sorting
document.addEventListener('DOMContentLoaded', function() {
    // You can initialize DataTables here if needed
    // $('#salesTable').DataTable();
});
</script>
@endsection
