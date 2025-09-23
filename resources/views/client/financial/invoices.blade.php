@extends('layouts.client')

@section('title', 'Invoices')
@section('page-title', 'Invoices & Billing')

@section('content')
<div class="row">
    <!-- Invoice Statistics -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Invoices
                        </div>
                        <div class="stat-number">{{ $client->invoices()->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-receipt" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Active Invoices
                        </div>
                        <div class="stat-number">{{ $client->invoices()->active()->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Pending Payment
                        </div>
                        <div class="stat-number">{{ $client->invoices()->where('status', 'pending')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Paid
                        </div>
                        <div class="stat-number">R$ {{ number_format($client->invoices()->where('status', 'paid')->sum('amount'), 2, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-charge me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="bi bi-credit-card me-2"></i>
                        Make Payment
                    </button>
                    <button class="btn btn-outline-primary" onclick="downloadAllInvoices()">
                        <i class="bi bi-download me-2"></i>
                        Download All Invoices
                    </button>
                    <a href="{{ route('client.financial.transactions') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-list-ul me-2"></i>
                        View Transactions
                    </a>
                    <a href="{{ route('client.support.create') }}" class="btn btn-outline-info">
                        <i class="bi bi-question-circle me-2"></i>
                        Billing Support
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-credit-card me-2"></i>
                    Accepted Payment Methods
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-2">
                        <i class="bi bi-credit-card-2-front text-primary" style="font-size: 2rem;"></i>
                        <div class="small">Credit Card</div>
                    </div>
                    <div class="col-6 mb-2">
                        <i class="bi bi-bank text-success" style="font-size: 2rem;"></i>
                        <div class="small">Bank Transfer</div>
                    </div>
                    <div class="col-6 mb-2">
                        <i class="bi bi-phone text-info" style="font-size: 2rem;"></i>
                        <div class="small">PIX</div>
                    </div>
                    <div class="col-6 mb-2">
                        <i class="bi bi-currency-bitcoin text-warning" style="font-size: 2rem;"></i>
                        <div class="small">Cryptocurrency</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices List -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>
                        Invoice History
                    </h5>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="status-filter" id="all" autocomplete="off" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="all">All</label>

                        <input type="radio" class="btn-check" name="status-filter" id="active" autocomplete="off">
                        <label class="btn btn-outline-success btn-sm" for="active">Active</label>

                        <input type="radio" class="btn-check" name="status-filter" id="pending" autocomplete="off">
                        <label class="btn btn-outline-warning btn-sm" for="pending">Pending</label>

                        <input type="radio" class="btn-check" name="status-filter" id="expired" autocomplete="off">
                        <label class="btn btn-outline-danger btn-sm" for="expired">Expired</label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Expires</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                <tr>
                                    <td>
                                        <code class="text-primary">#{{ $invoice->id }}</code>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $invoice->plan->name ?? 'No Plan' }}</span>
                                            <small class="text-muted">{{ $invoice->plan->description ?? '' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">R$ {{ number_format($invoice->amount, 2, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->status === 'active' ? 'success' : ($invoice->status === 'pending' ? 'warning' : ($invoice->status === 'paid' ? 'info' : 'danger')) }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $invoice->created_at->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $invoice->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $invoice->expires_at ? $invoice->expires_at->format('d/m/Y') : 'N/A' }}</span>
                                            <small class="text-muted">{{ $invoice->expires_at ? $invoice->expires_at->format('H:i') : '' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('client.financial.invoice.show', $invoice) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" title="View Invoice">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="downloadInvoice({{ $invoice->id }})"
                                                    data-bs-toggle="tooltip" title="Download PDF">
                                                <i class="bi bi-download"></i>
                                            </button>
                                            @if($invoice->status === 'pending')
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    onclick="uploadReceipt({{ $invoice->id }})"
                                                    data-bs-toggle="tooltip" title="Upload Receipt">
                                                <i class="bi bi-upload"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} 
                                of {{ $invoices->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $invoices->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-receipt display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No invoices found</h4>
                        <p class="text-muted">You don't have any invoices yet.</p>
                        <a href="{{ route('client.financial.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Financial
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Make Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="invoice_id" class="form-label">Select Invoice</label>
                            <select name="invoice_id" id="invoice_id" class="form-select" required>
                                <option value="">Choose an invoice</option>
                                @foreach($client->invoices()->where('status', 'pending')->get() as $invoice)
                                <option value="{{ $invoice->id }}">
                                    Invoice #{{ $invoice->id }} - R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="">Select payment method</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="pix">PIX</option>
                                <option value="cryptocurrency">Cryptocurrency</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="payment_notes" class="form-label">Payment Notes (Optional)</label>
                        <textarea name="payment_notes" id="payment_notes" rows="3" class="form-control" 
                                  placeholder="Add any additional information about your payment..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitPayment()">
                    <i class="bi bi-credit-card me-2"></i>
                    Process Payment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Receipt Modal -->
<div class="modal fade" id="uploadReceiptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Payment Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="receiptForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="receipt_file" class="form-label">Receipt File</label>
                        <input type="file" name="receipt" id="receipt_file" class="form-control" 
                               accept=".jpg,.jpeg,.png,.pdf" required>
                        <div class="form-text">
                            Accepted formats: JPG, PNG, PDF (max 5MB)
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="payment_notes" class="form-label">Payment Details</label>
                        <textarea name="payment_notes" id="payment_notes" rows="3" class="form-control" 
                                  placeholder="Include payment method, transaction ID, date, etc."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitReceipt()">
                    <i class="bi bi-upload me-2"></i>
                    Upload Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card .card-body {
    padding: 1.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
}
</style>

@push('scripts')
<script>
    function downloadInvoice(invoiceId) {
        // Simulate invoice download
        window.open(`/client/financial/invoices/${invoiceId}/download`, '_blank');
    }
    
    function downloadAllInvoices() {
        // Simulate bulk download
        window.open('/client/financial/invoices/download-all', '_blank');
    }
    
    function uploadReceipt(invoiceId) {
        const modal = new bootstrap.Modal(document.getElementById('uploadReceiptModal'));
        const form = document.getElementById('receiptForm');
        
        form.action = `/client/financial/invoices/${invoiceId}/receipt`;
        modal.show();
    }
    
    function submitReceipt() {
        const form = document.getElementById('receiptForm');
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Receipt uploaded successfully!');
                location.reload();
            } else {
                alert('Error uploading receipt: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error uploading receipt');
            console.error('Error:', error);
        });
    }
    
    function submitPayment() {
        const form = document.getElementById('paymentForm');
        const formData = new FormData(form);
        
        // Simulate payment processing
        alert('Payment processing... This is a demo.');
        bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
    }
    
    // Filter invoices by status
    document.querySelectorAll('input[name="status-filter"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const status = this.id;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const statusBadge = row.querySelector('.badge');
                const invoiceStatus = statusBadge.textContent.toLowerCase().trim();
                
                if (status === 'all' || invoiceStatus === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
@endsection
