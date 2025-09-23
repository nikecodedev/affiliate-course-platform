@extends('layouts.client')

@section('title', 'Withdrawals')
@section('page-title', 'Withdrawal Requests')

@section('content')
<div class="row">
    <!-- Withdrawal Statistics -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Available Balance
                        </div>
                        <div class="stat-number">R$ {{ number_format($client->available_balance, 2, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-wallet2" style="font-size: 2rem;"></i>
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
                            Total Withdrawn
                        </div>
                        <div class="stat-number">R$ {{ number_format($client->total_withdrawals, 2, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-arrow-up-circle" style="font-size: 2rem;"></i>
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
                            Pending Requests
                        </div>
                        <div class="stat-number">{{ $client->withdrawalRequests()->pending()->count() }}</div>
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
                            Processing Time
                        </div>
                        <div class="stat-number">1-3 Days</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-stopwatch" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Withdrawal Form -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Request Withdrawal
                </h5>
            </div>
            <div class="card-body">
                @if($client->available_balance > 0)
                    <form method="POST" action="{{ route('client.financial.withdrawals.store') }}" id="withdrawalForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount to Withdraw <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" 
                                       value="{{ old('amount') }}" step="0.01" min="10" max="{{ $client->available_balance }}" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Minimum: R$ 10.00 | Maximum: R$ {{ number_format($client->available_balance, 2, ',', '.') }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="">Select payment method</option>
                                <option value="pix" {{ old('payment_method') === 'pix' ? 'selected' : '' }}>PIX</option>
                                <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cryptocurrency" {{ old('payment_method') === 'cryptocurrency' ? 'selected' : '' }}>Cryptocurrency</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_details" class="form-label">Payment Details <span class="text-danger">*</span></label>
                            <textarea name="payment_details" id="payment_details" rows="3" class="form-control @error('payment_details') is-invalid @enderror" 
                                      placeholder="Enter your payment details (account number, PIX key, wallet address, etc.)" required>{{ old('payment_details') }}</textarea>
                            @error('payment_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cpf_password" class="form-label">CPF Password <span class="text-danger">*</span></label>
                            <input type="password" name="cpf_password" id="cpf_password" class="form-control @error('cpf_password') is-invalid @enderror" 
                                   placeholder="Last 4 digits of your CPF" required>
                            @error('cpf_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-shield-check me-1"></i>
                                Security verification using your CPF
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms_accepted" id="terms_accepted" required>
                                <label class="form-check-label" for="terms_accepted">
                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">withdrawal terms and conditions</a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send me-2"></i>
                            Submit Withdrawal Request
                        </button>
                    </form>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-wallet2 display-4 text-muted mb-3"></i>
                        <h5 class="text-muted">No Available Balance</h5>
                        <p class="text-muted">You need to have earnings available to make a withdrawal request.</p>
                        <a href="{{ route('client.dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Withdrawal Rules -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Withdrawal Rules
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Minimum Amount:</strong> R$ 10.00
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Processing Time:</strong> 1-3 business days
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Withdrawal Fee:</strong> See fee structure below
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Daily Limit:</strong> R$ 5,000.00
                    </li>
                    <li>
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Monthly Limit:</strong> R$ 50,000.00
                    </li>
                </ul>
            </div>
        </div>

        <!-- Fee Structure -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-calculator me-2"></i>
                    Fee Structure
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Amount Range</th>
                                <th>Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>R$ 10 - R$ 99</td>
                                <td class="text-success">R$ 5.00</td>
                            </tr>
                            <tr>
                                <td>R$ 100 - R$ 499</td>
                                <td class="text-warning">R$ 10.00</td>
                            </tr>
                            <tr>
                                <td>R$ 500+</td>
                                <td class="text-danger">R$ 15.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal History -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Withdrawal History
                    </h5>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="status-filter" id="all" autocomplete="off" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="all">All</label>

                        <input type="radio" class="btn-check" name="status-filter" id="pending" autocomplete="off">
                        <label class="btn btn-outline-warning btn-sm" for="pending">Pending</label>

                        <input type="radio" class="btn-check" name="status-filter" id="approved" autocomplete="off">
                        <label class="btn btn-outline-success btn-sm" for="approved">Approved</label>

                        <input type="radio" class="btn-check" name="status-filter" id="rejected" autocomplete="off">
                        <label class="btn btn-outline-danger btn-sm" for="rejected">Rejected</label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($withdrawals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Request #</th>
                                    <th>Amount</th>
                                    <th>Fee</th>
                                    <th>Net Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Requested</th>
                                    <th>Processed</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdrawals as $withdrawal)
                                <tr>
                                    <td>
                                        <code class="text-primary">#{{ $withdrawal->id }}</code>
                                    </td>
                                    <td>
                                        <span class="fw-bold">R$ {{ number_format($withdrawal->amount, 2, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">R$ {{ number_format($withdrawal->fee, 2, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">R$ {{ number_format($withdrawal->net_amount, 2, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($withdrawal->payment_method) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $withdrawal->status === 'pending' ? 'warning' : ($withdrawal->status === 'approved' ? 'success' : ($withdrawal->status === 'rejected' ? 'danger' : 'info')) }}">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $withdrawal->created_at->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $withdrawal->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($withdrawal->processed_at)
                                            <div class="d-flex flex-column">
                                                <span>{{ $withdrawal->processed_at->format('d/m/Y') }}</span>
                                                <small class="text-muted">{{ $withdrawal->processed_at->format('H:i') }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('client.financial.withdrawals.show', $withdrawal) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($withdrawal->status === 'rejected' && $withdrawal->rejection_reason)
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="showRejectionReason('{{ $withdrawal->rejection_reason }}')"
                                                    data-bs-toggle="tooltip" title="View Rejection Reason">
                                                <i class="bi bi-info-circle"></i>
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
                                Showing {{ $withdrawals->firstItem() }} to {{ $withdrawals->lastItem() }} 
                                of {{ $withdrawals->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $withdrawals->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-arrow-up-circle display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No withdrawal requests found</h4>
                        <p class="text-muted">You haven't made any withdrawal requests yet.</p>
                        @if($client->available_balance > 0)
                        <a href="{{ route('client.financial.withdrawals.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Make Your First Withdrawal
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdrawal Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Withdrawal Terms</h6>
                <ul>
                    <li>Withdrawals are processed within 1-3 business days</li>
                    <li>Minimum withdrawal amount is R$ 10.00</li>
                    <li>Withdrawal fees apply as per the fee structure</li>
                    <li>You must provide accurate payment details</li>
                    <li>Withdrawals are subject to verification</li>
                    <li>We reserve the right to reject withdrawals for security reasons</li>
                </ul>
                
                <h6 class="mt-4">Security</h6>
                <ul>
                    <li>Your CPF password is required for verification</li>
                    <li>All withdrawals are logged for security purposes</li>
                    <li>Suspicious activity may result in account review</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div class="modal fade" id="rejectionReasonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdrawal Rejection Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Reason for Rejection:</strong>
                </div>
                <p id="rejectionReasonText"></p>
                <p class="text-muted">
                    If you have questions about this rejection, please contact our support team.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('client.support.create') }}" class="btn btn-primary">Contact Support</a>
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
    function showRejectionReason(reason) {
        document.getElementById('rejectionReasonText').textContent = reason;
        const modal = new bootstrap.Modal(document.getElementById('rejectionReasonModal'));
        modal.show();
    }
    
    // Filter withdrawals by status
    document.querySelectorAll('input[name="status-filter"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const status = this.id;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const statusBadge = row.querySelector('.badge');
                const withdrawalStatus = statusBadge.textContent.toLowerCase().trim();
                
                if (status === 'all' || withdrawalStatus === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
    // Calculate fee dynamically
    document.getElementById('amount').addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        let fee = 0;
        
        if (amount >= 10 && amount < 100) {
            fee = 5.00;
        } else if (amount >= 100 && amount < 500) {
            fee = 10.00;
        } else if (amount >= 500) {
            fee = 15.00;
        }
        
        // You can display the fee calculation somewhere in the form if needed
        console.log(`Fee for R$ ${amount}: R$ ${fee}`);
    });
    
    // Form validation
    document.getElementById('withdrawalForm').addEventListener('submit', function(e) {
        const amount = parseFloat(document.getElementById('amount').value);
        const availableBalance = {{ $client->available_balance }};
        
        if (amount > availableBalance) {
            e.preventDefault();
            alert('Withdrawal amount cannot exceed your available balance.');
            return;
        }
        
        if (amount < 10) {
            e.preventDefault();
            alert('Minimum withdrawal amount is R$ 10.00.');
            return;
        }
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
@endsection
