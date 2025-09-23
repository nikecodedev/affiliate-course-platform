@extends('layouts.admin')

@section('title', 'Financial Management')
@section('page-title', 'Financial Management')

@section('content')
<div class="row">
    <!-- Financial Overview Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h4>
                        <p class="mb-0">Total Revenue</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-dollar display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">${{ number_format($stats['total_commissions'] ?? 0, 2) }}</h4>
                        <p class="mb-0">Total Commissions</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-graph-up display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">${{ number_format($stats['pending_withdrawals'] ?? 0, 2) }}</h4>
                        <p class="mb-0">Pending Withdrawals</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">${{ number_format($stats['total_expenses'] ?? 0, 2) }}</h4>
                        <p class="mb-0">Total Expenses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-receipt display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.financial.withdrawals') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-up-circle me-2"></i>Manage Withdrawals
                    </a>
                    <a href="{{ route('admin.financial.expenses.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-plus-circle me-2"></i>Add Expense
                    </a>
                    <a href="{{ route('admin.financial.reports') }}" class="btn btn-outline-info">
                        <i class="bi bi-graph-up me-2"></i>View Reports
                    </a>
                    <a href="{{ route('admin.financial.commission-payments.index') }}" class="btn btn-outline-warning">
                        <i class="bi bi-cash-coin me-2"></i>Commission Payments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>Recent Transactions
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_transactions as $transaction)
                                <tr>
                                    <td>
                                        @switch($transaction->type)
                                            @case('sale')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-cart-check me-1"></i>Sale
                                                </span>
                                                @break
                                            @case('commission')
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-graph-up me-1"></i>Commission
                                                </span>
                                                @break
                                            @case('withdrawal')
                                                <span class="badge bg-info">
                                                    <i class="bi bi-arrow-up-circle me-1"></i>Withdrawal
                                                </span>
                                                @break
                                            @case('expense')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-receipt me-1"></i>Expense
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($transaction->type) }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $transaction->description ?? 'N/A' }}</td>
                                    <td>
                                        <span class="fw-semibold {{ $transaction->amount >= 0 ? 'text-success' : 'text-danger' }}">
                                            ${{ number_format(abs($transaction->amount), 2) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @switch($transaction->status ?? 'completed')
                                            @case('completed')
                                                <span class="badge bg-success">Completed</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">Failed</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($transaction->status ?? 'N/A') }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                            <p class="mb-0">No recent transactions</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Financial Chart -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Financial Overview
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="updateChart('week')">
                            Week
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm active" onclick="updateChart('month')">
                            Month
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="updateChart('year')">
                            Year
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="financialChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Financial Chart
const ctx = document.getElementById('financialChart').getContext('2d');
let financialChart;

function initChart() {
    financialChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue',
                data: [12000, 19000, 15000, 25000, 22000, 30000],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1
            }, {
                label: 'Commissions',
                data: [2400, 3800, 3000, 5000, 4400, 6000],
                borderColor: 'rgb(255, 205, 86)',
                backgroundColor: 'rgba(255, 205, 86, 0.1)',
                tension: 0.1
            }, {
                label: 'Expenses',
                data: [2000, 3000, 2500, 4000, 3500, 4500],
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

function updateChart(period) {
    // Remove active class from all buttons
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    // Here you would typically fetch new data via AJAX
    console.log('Updating chart for period:', period);
    
    // For demo purposes, we'll just show a loading state
    financialChart.data.datasets.forEach(dataset => {
        dataset.data = dataset.data.map(() => Math.random() * 30000);
    });
    financialChart.update();
}

// Initialize chart when page loads
document.addEventListener('DOMContentLoaded', function() {
    initChart();
});
</script>
@endsection
