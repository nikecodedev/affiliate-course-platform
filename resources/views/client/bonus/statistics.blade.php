@extends('layouts.client')

@section('title', 'Bonus Statistics')
@section('page-title', 'Bonus Statistics')

@section('page-actions')
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
        <a href="{{ route('client.bonus.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Bonus
        </a>
        <a href="{{ route('client.bonus.payments') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list me-2"></i>View Payments
        </a>
        <a href="{{ route('client.bonus.export') }}" class="btn btn-outline-success">
            <i class="bi bi-download me-2"></i>Export Data
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Overview Statistics -->
<div class="row mb-4">
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

<!-- Detailed Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Bonuses
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($statistics['total_bonuses']) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-gift text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Conversion Rate
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $statistics['conversion_rate'] }}%
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-graph-up text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Average Monthly
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ {{ number_format($statistics['average_monthly'], 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-bar-chart text-info" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            This Year
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ {{ number_format($statistics['this_year'], 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-year text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Monthly Earnings Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-graph-up me-2"></i>Monthly Earnings Trend
                </h6>
                <div class="dropdown no-arrow">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="periodDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-calendar me-1"></i>Period
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" onclick="updateChart('6_months')">Last 6 Months</a>
                        <a class="dropdown-item" href="#" onclick="updateChart('12_months')">Last 12 Months</a>
                        <a class="dropdown-item" href="#" onclick="updateChart('24_months')">Last 24 Months</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="monthlyEarningsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bonus Type Breakdown -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-pie-chart me-2"></i>Bonus Type Breakdown
                </h6>
            </div>
            <div class="card-body">
                @if($bonusTypeBreakdown->count() > 0)
                    @foreach($bonusTypeBreakdown as $breakdown)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-xs font-weight-bold text-uppercase">
                                    {{ ucfirst(str_replace('_', ' ', $breakdown['type'])) }}
                                </span>
                                <span class="text-xs font-weight-bold">
                                    R$ {{ number_format($breakdown['total'], 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: {{ $breakdown['percentage'] }}%"></div>
                            </div>
                            <small class="text-muted">{{ $breakdown['count'] }} bonuses</small>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-pie-chart display-4 text-muted"></i>
                        <p class="text-muted mt-2">No bonus data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Monthly Comparison -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-calendar-range me-2"></i>Monthly Earnings Comparison
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-end">Earnings</th>
                                <th class="text-end">Growth</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyEarnings as $index => $month)
                                @php
                                    $previousMonth = $index > 0 ? $monthlyEarnings[$index - 1]['total'] : 0;
                                    $growth = $previousMonth > 0 ? (($month['total'] - $previousMonth) / $previousMonth) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $month['month'] }}</td>
                                    <td class="text-end">
                                        <strong>R$ {{ number_format($month['total'], 2, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end">
                                        @if($growth > 0)
                                            <span class="text-success">
                                                <i class="bi bi-arrow-up me-1"></i>+{{ number_format($growth, 1) }}%
                                            </span>
                                        @elseif($growth < 0)
                                            <span class="text-danger">
                                                <i class="bi bi-arrow-down me-1"></i>{{ number_format($growth, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-muted">0%</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($month['total'] > 0)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">No Activity</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-card {
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.chart-area {
    position: relative;
    height: 300px;
    width: 100%;
}

.text-xs {
    font-size: 0.7rem;
}

.text-gray-800 {
    color: #5a5c69 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Earnings Chart
const ctx = document.getElementById('monthlyEarningsChart').getContext('2d');
let monthlyChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartData['labels']),
        datasets: [{
            label: 'Monthly Earnings',
            data: @json($chartData['data']),
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
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
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Earnings: R$ ' + context.parsed.y.toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                }
            }
        }
    }
});

// Update chart based on period selection
function updateChart(period) {
    fetch(`{{ route('client.bonus.chart-data') }}?period=${period}`)
        .then(response => response.json())
        .then(data => {
            monthlyChart.data.labels = data.labels;
            monthlyChart.data.datasets[0].data = data.data;
            monthlyChart.update();
        })
        .catch(error => {
            console.error('Error updating chart:', error);
        });
}

// Calculate percentages for bonus type breakdown
document.addEventListener('DOMContentLoaded', function() {
    const breakdowns = @json($bonusTypeBreakdown);
    const total = breakdowns.reduce((sum, item) => sum + item.total, 0);
    
    if (total > 0) {
        breakdowns.forEach(item => {
            item.percentage = (item.total / total) * 100;
        });
        
        // Update progress bars
        document.querySelectorAll('.progress-bar').forEach((bar, index) => {
            if (breakdowns[index]) {
                bar.style.width = breakdowns[index].percentage + '%';
            }
        });
    }
});
</script>
@endpush
