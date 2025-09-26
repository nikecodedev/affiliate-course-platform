@extends('layouts.client')

@section('title', 'Financial')
@section('page-title', 'Financial Overview')

@section('content')
<div class="row">
    <!-- Financial Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Earnings
                        </div>
                        <div class="stat-number">R$ {{ number_format($client->total_earnings ?? 0, 2, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
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
                            Available Balance
                        </div>
                        <div class="stat-number">R$ {{ number_format($client->available_balance ?? 0, 2, ',', '.') }}</div>
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
                            Total Withdrawals
                        </div>
                        <div class="stat-number">R$ {{ number_format($client->total_withdrawals ?? 0, 2, ',', '.') }}</div>
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
                            Active Plans
                        </div>
                        <div class="stat-number">{{ $client->activeInvoices()->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-box" style="font-size: 2rem;"></i>
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
                    <a href="{{ route('client.financial.withdrawals.create') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-up-circle me-2"></i>
                        Request Withdrawal
                    </a>
                    <a href="{{ route('client.financial.transactions') }}" class="btn btn-outline-primary">
                        <i class="bi bi-list-ul me-2"></i>
                        View Transactions
                    </a>
                    <a href="{{ route('client.financial.invoices') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-receipt me-2"></i>
                        View Invoices
                    </a>
                    <a href="{{ route('client.financial.statements') }}" class="btn btn-outline-info">
                        <i class="bi bi-download me-2"></i>
                        Download Statement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Recent Transactions
                    </h5>
                    <a href="{{ route('client.financial.transactions') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-list me-1"></i>View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>
                                    <td class="text-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                                        {{ $transaction->type === 'credit' ? '+' : '-' }}R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-receipt display-4 text-muted"></i>
                        <h5 class="mt-3 text-muted">No transactions found</h5>
                        <p class="text-muted">Your transaction history will appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Active Invoices -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>
                        Active Invoices
                    </h5>
                    <a href="{{ route('client.financial.invoices') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-receipt me-1"></i>View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($activeInvoices->count() > 0)
                    @foreach($activeInvoices as $invoice)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <h6 class="mb-1">{{ $invoice->plan->name ?? 'Plan' }}</h6>
                            <small class="text-muted">
                                Expires: {{ $invoice->expires_at->format('d/m/Y') }}
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-receipt display-4 text-muted"></i>
                        <h5 class="mt-3 text-muted">No active invoices</h5>
                        <p class="text-muted">You don't have any active subscriptions.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pending Withdrawals -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-hourglass-split me-2"></i>
                        Pending Withdrawals
                    </h5>
                    <a href="{{ route('client.financial.withdrawals') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-up-circle me-1"></i>View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($pendingWithdrawals->count() > 0)
                    @foreach($pendingWithdrawals as $withdrawal)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <h6 class="mb-1">R$ {{ number_format($withdrawal->amount, 2, ',', '.') }}</h6>
                            <small class="text-muted">
                                Requested: {{ $withdrawal->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-warning">Pending</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-hourglass-split display-4 text-muted"></i>
                        <h5 class="mt-3 text-muted">No pending withdrawals</h5>
                        <p class="text-muted">Your withdrawal requests will appear here.</p>
                    </div>
                @endif
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
@endsection
