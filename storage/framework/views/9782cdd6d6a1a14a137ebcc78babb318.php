

<?php $__env->startSection('title', 'Financial Reports'); ?>
<?php $__env->startSection('page-title', 'Financial Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Period Selection -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-calendar me-2"></i>Report Period
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="period" class="form-label">Period</label>
                        <select class="form-select" id="period" onchange="updateReport()">
                            <option value="week" <?php echo e($period === 'week' ? 'selected' : ''); ?>>Last Week</option>
                            <option value="month" <?php echo e($period === 'month' ? 'selected' : ''); ?>>Last Month</option>
                            <option value="quarter" <?php echo e($period === 'quarter' ? 'selected' : ''); ?>>Last Quarter</option>
                            <option value="year" <?php echo e($period === 'year' ? 'selected' : ''); ?>>Last Year</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date Range</label>
                        <div class="text-muted">
                            <?php echo e($startDate->format('M d, Y')); ?> - <?php echo e($endDate->format('M d, Y')); ?>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-primary" onclick="exportReport()">
                                <i class="bi bi-download me-2"></i>Export Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">$<?php echo e(number_format($reports['revenue'], 2)); ?></h4>
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
                        <h4 class="mb-0">$<?php echo e(number_format($reports['commissions'], 2)); ?></h4>
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
                        <h4 class="mb-0">$<?php echo e(number_format($reports['expenses'], 2)); ?></h4>
                        <p class="mb-0">Total Expenses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-receipt display-6"></i>
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
                        <h4 class="mb-0">$<?php echo e(number_format($reports['profit'], 2)); ?></h4>
                        <p class="mb-0">Net Profit</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calculator display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Breakdown -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Expenses by Category
                </h5>
            </div>
            <div class="card-body">
                <canvas id="expenseChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Affiliates -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-trophy me-2"></i>Top Affiliates
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Sales</th>
                                <th>Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $topAffiliates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $affiliate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($affiliate->name); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo e($affiliate->sales_count); ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">$<?php echo e(number_format($affiliate->total_commission, 2)); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        No affiliate data available
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Chart -->
    <div class="col-12 mb-4">
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

    <!-- Detailed Reports -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Detailed Reports
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <div class="text-primary">
                                <i class="bi bi-arrow-up-circle display-4"></i>
                            </div>
                            <h5 class="mt-2">$<?php echo e(number_format($reports['withdrawals'], 2)); ?></h5>
                            <p class="text-muted mb-0">Withdrawals</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <div class="text-warning">
                                <i class="bi bi-cash-coin display-4"></i>
                            </div>
                            <h5 class="mt-2">$<?php echo e(number_format($reports['commission_payments'], 2)); ?></h5>
                            <p class="text-muted mb-0">Commission Payments</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <div class="text-success">
                                <i class="bi bi-graph-up display-4"></i>
                            </div>
                            <h5 class="mt-2"><?php echo e($topAffiliates->count()); ?></h5>
                            <p class="text-muted mb-0">Active Affiliates</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <div class="text-info">
                                <i class="bi bi-percent display-4"></i>
                            </div>
                            <h5 class="mt-2"><?php echo e($reports['revenue'] > 0 ? number_format(($reports['commissions'] / $reports['revenue']) * 100, 1) : 0); ?>%</h5>
                            <p class="text-muted mb-0">Commission Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Expense Chart
const expenseCtx = document.getElementById('expenseChart').getContext('2d');
const expenseData = <?php echo json_encode($expenseByCategory, 15, 512) ?>;

new Chart(expenseCtx, {
    type: 'doughnut',
    data: {
        labels: expenseData.map(item => item.category),
        datasets: [{
            data: expenseData.map(item => item.total),
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': $' + context.parsed.toLocaleString();
                    }
                }
            }
        }
    }
});

// Financial Chart
const financialCtx = document.getElementById('financialChart').getContext('2d');
let financialChart;

function initFinancialChart() {
    financialChart = new Chart(financialCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Revenue',
                data: [<?php echo e($reports['revenue'] / 4); ?>, <?php echo e($reports['revenue'] / 4); ?>, <?php echo e($reports['revenue'] / 4); ?>, <?php echo e($reports['revenue'] / 4); ?>],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1
            }, {
                label: 'Commissions',
                data: [<?php echo e($reports['commissions'] / 4); ?>, <?php echo e($reports['commissions'] / 4); ?>, <?php echo e($reports['commissions'] / 4); ?>, <?php echo e($reports['commissions'] / 4); ?>],
                borderColor: 'rgb(255, 205, 86)',
                backgroundColor: 'rgba(255, 205, 86, 0.1)',
                tension: 0.1
            }, {
                label: 'Expenses',
                data: [<?php echo e($reports['expenses'] / 4); ?>, <?php echo e($reports['expenses'] / 4); ?>, <?php echo e($reports['expenses'] / 4); ?>, <?php echo e($reports['expenses'] / 4); ?>],
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

function updateReport() {
    const period = document.getElementById('period').value;
    window.location.href = `<?php echo e(route('admin.financial.reports')); ?>?period=${period}`;
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
}

function exportReport() {
    // Here you would typically generate and download a report
    console.log('Exporting report...');
    alert('Report export functionality would be implemented here.');
}

// Initialize chart when page loads
document.addEventListener('DOMContentLoaded', function() {
    initFinancialChart();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/financial/reports.blade.php ENDPATH**/ ?>