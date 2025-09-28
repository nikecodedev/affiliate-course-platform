@extends('layouts.admin')

@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Report Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Report Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.financial.reports') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="period" class="form-label">Period</label>
                        <select name="period" id="period" class="form-select">
                            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Last Week</option>
                            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Last Month</option>
                            <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>Last Quarter</option>
                            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Last Year</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="format" class="form-label">Export Format</label>
                        <select name="format" id="format" class="form-select">
                            <option value="view">View Only</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                            <a href="{{ route('admin.financial.reports') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Revenue Report -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Revenue Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-success">R$ {{ number_format($reports['revenue']['total'], 2, ',', '.') }}</h3>
                                    <p class="text-muted mb-0">Total Revenue</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-info">R$ {{ number_format($reports['revenue']['average_daily'], 2, ',', '.') }}</h3>
                                    <p class="text-muted mb-0">Average Daily</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Commission Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-warning">R$ {{ number_format($reports['commissions']['total'], 2, ',', '.') }}</h3>
                                    <p class="text-muted mb-0">Total Commissions</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-danger">R$ {{ number_format($reports['commissions']['pending'], 2, ',', '.') }}</h3>
                                    <p class="text-muted mb-0">Pending</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Revenue Trend</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Commission Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="commissionChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top Sales Performers</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Sales</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports['top_performers']['top_sales'] as $performer)
                                    <tr>
                                        <td>{{ $performer->name }}</td>
                                        <td>{{ $performer->sales_count }}</td>
                                        <td>R$ {{ number_format($performer->sales_sum_amount, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top Earners</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Earnings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports['top_performers']['top_earners'] as $earner)
                                    <tr>
                                        <td>{{ $earner->name }}</td>
                                        <td>R$ {{ number_format($earner->bonus_payments_sum_amount, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conversion Rates -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Conversion Rates</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-primary">{{ number_format($reports['conversion_rates']['lead_conversion_rate'], 1) }}%</h3>
                                    <p class="text-muted mb-0">Lead Conversion</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-success">{{ number_format($reports['conversion_rates']['sales_conversion_rate'], 1) }}%</h3>
                                    <p class="text-muted mb-0">Sales Conversion</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Total Leads: {{ $reports['conversion_rates']['total_leads'] }}</small><br>
                                <small class="text-muted">Converted: {{ $reports['conversion_rates']['converted_leads'] }}</small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Total Users: {{ $reports['conversion_rates']['total_users'] }}</small><br>
                                <small class="text-muted">Sales: {{ $reports['conversion_rates']['total_sales'] }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Matrix Performance</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-info">{{ $reports['matrix_performance']['stats']->total_users }}</h3>
                                    <p class="text-muted mb-0">Total Users</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-success">{{ $reports['matrix_performance']['stats']->active_users }}</h3>
                                    <p class="text-muted mb-0">Active Users</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Average Level: {{ number_format($reports['matrix_performance']['stats']->average_level, 1) }}</small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Max Level: {{ $reports['matrix_performance']['stats']->max_level }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Tables -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Commission by Type</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports['commissions']['by_type'] as $type)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $type->bonus_type)) }}</td>
                                        <td>R$ {{ number_format($type->total, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Expenses by Category</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports['expenses']['by_category'] as $category)
                                    <tr>
                                        <td>{{ ucfirst($category->category) }}</td>
                                        <td>R$ {{ number_format($category->total, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($reports['revenue']['daily']->pluck('date')) !!},
        datasets: [{
            label: 'Daily Revenue',
            data: {!! json_encode($reports['revenue']['daily']->pluck('revenue')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Commission Chart
const commissionCtx = document.getElementById('commissionChart').getContext('2d');
const commissionChart = new Chart(commissionCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($reports['commissions']['by_type']->pluck('bonus_type')) !!},
        datasets: [{
            data: {!! json_encode($reports['commissions']['by_type']->pluck('total')) !!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 205, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endsection