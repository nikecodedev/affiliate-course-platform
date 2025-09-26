@extends('layouts.client')

@section('title', 'Bonus Overview')
@section('page-title', 'Bonus Overview')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Earned
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            R$ {{ number_format($statistics['total_earned'], 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Pending
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            R$ {{ number_format($statistics['total_pending'], 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Approved
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            R$ {{ number_format($statistics['total_approved'], 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            This Month
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            R$ {{ number_format($statistics['this_month'], 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-month" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Bonus Overview Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Bonus Trend (Last 12 Months)
                </h6>
            </div>
            <div class="card-body">
                <canvas id="bonusTrendChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightning-charge me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('client.bonus.payments') }}" class="btn btn-primary">
                        <i class="bi bi-list-ul me-2"></i>
                        View All Payments
                    </a>
                    <a href="{{ route('client.bonus.statistics') }}" class="btn btn-outline-info">
                        <i class="bi bi-graph-up me-2"></i>
                        Detailed Statistics
                    </a>
                    <a href="{{ route('client.network.index') }}" class="btn btn-outline-success">
                        <i class="bi bi-diagram-3 me-2"></i>
                        View Network
                    </a>
                    <a href="{{ route('client.bonus.export') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-download me-2"></i>
                        Export Data
                    </a>
                </div>
            </div>
        </div>

        <!-- Bonus Types Distribution -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>
                    Bonus Types
                </h6>
            </div>
            <div class="card-body">
                <canvas id="bonusTypesChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bonuses -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Recent Bonus Payments
                    </h6>
                    <a href="{{ route('client.bonus.payments') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-list me-1"></i>View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentBonuses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>From</th>
                                    <th>Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBonuses as $bonus)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $bonus->created_at->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $bonus->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $bonus->bonus_type === 'direct_referral' ? 'primary' : ($bonus->bonus_type === 'unilevel' ? 'info' : ($bonus->bonus_type === 'forced_matrix' ? 'warning' : 'success')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $bonus->bonus_type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            R$ {{ number_format($bonus->amount, 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $bonus->status === 'paid' ? 'success' : ($bonus->status === 'approved' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($bonus->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($bonus->fromClient)
                                            <span class="fw-bold">{{ $bonus->fromClient->name }}</span>
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($bonus->level)
                                            <span class="badge bg-light text-dark">Level {{ $bonus->level }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-gift display-4 text-muted"></i>
                        <h5 class="mt-3 text-muted">No bonus payments yet</h5>
                        <p class="text-muted">Start referring clients to earn bonuses!</p>
                        <a href="{{ route('client.network.index') }}" class="btn btn-primary">
                            <i class="bi bi-people me-2"></i>
                            View Network
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Bonus Trend Chart
    const bonusTrendCtx = document.getElementById('bonusTrendChart').getContext('2d');
    
    // Mock data for now - replace with actual data from controller
    const bonusTrendData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Bonus Earnings',
            data: [120, 190, 300, 500, 200, 300, 450, 320, 280, 400, 350, 420],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    };
    
    new Chart(bonusTrendCtx, {
        type: 'line',
        data: bonusTrendData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toFixed(2);
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Bonus Types Chart
    const bonusTypesCtx = document.getElementById('bonusTypesChart').getContext('2d');
    
    const bonusTypesData = {
        labels: ['Direct Referral', 'Unilevel', 'Matrix', 'Profit Sharing'],
        datasets: [{
            data: [30, 25, 20, 25],
            backgroundColor: [
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    };
    
    new Chart(bonusTypesCtx, {
        type: 'doughnut',
        data: bonusTypesData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush

<style>
.stat-card {
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}
</style>
@endsection
