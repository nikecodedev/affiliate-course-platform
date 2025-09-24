

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Sales
                        </div>
                        <div class="stat-number"><?php echo e(number_format($stats['total_sales'])); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-cart-check" style="font-size: 2rem;"></i>
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
                            Total Revenue
                        </div>
                        <div class="stat-number">R$ <?php echo e(number_format($stats['total_revenue'], 2, ',', '.')); ?></div>
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
                            Total Commissions
                        </div>
                        <div class="stat-number">R$ <?php echo e(number_format($stats['total_commissions'], 2, ',', '.')); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
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
                            Total Users
                        </div>
                        <div class="stat-number"><?php echo e(number_format($stats['total_users'])); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue</h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Active Plans:</span>
                        <strong><?php echo e($stats['total_plans']); ?></strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Active Courses:</span>
                        <strong><?php echo e($stats['total_courses']); ?></strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Affiliates:</span>
                        <strong><?php echo e($stats['total_affiliates']); ?></strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Pending Withdrawals:</span>
                        <strong class="text-warning"><?php echo e($stats['pending_withdrawals']); ?></strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Pending Commissions:</span>
                        <strong class="text-warning"><?php echo e($stats['pending_commission_payments']); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Sales -->
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Recent Sales</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Plan</th>
                                <th>Customer</th>
                                <th>Affiliate</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recent_sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($sale->sale_date->format('d/m/Y')); ?></td>
                                    <td><?php echo e($sale->plan->title); ?></td>
                                    <td><?php echo e($sale->user->name); ?></td>
                                    <td><?php echo e($sale->affiliate ? $sale->affiliate->name : '-'); ?></td>
                                    <td><?php echo e($sale->formatted_amount); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($sale->status_badge_class); ?>">
                                            <?php echo e(ucfirst($sale->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No sales found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Affiliates -->
    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Top Affiliates</h6>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $top_affiliates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $affiliate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong><?php echo e($affiliate->name); ?></strong><br>
                            <small class="text-muted"><?php echo e($affiliate->sales_count); ?> sales</small>
                        </div>
                        <div class="text-end">
                            <strong>R$ <?php echo e(number_format($affiliate->total_commission, 2, ',', '.')); ?></strong>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted text-center">No affiliates found</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Expenses -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Recent Expenses</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recent_expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($expense->expense_date->format('d/m/Y')); ?></td>
                                    <td><?php echo e($expense->title); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($expense->category_badge_class); ?>">
                                            <?php echo e(ucfirst($expense->category)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($expense->formatted_amount); ?></td>
                                    <td><?php echo e($expense->creator->name); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center">No expenses found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = <?php echo json_encode($monthly_revenue, 15, 512) ?>;
    
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => {
                const [year, month] = item.month.split('-');
                return new Date(year, month - 1).toLocaleDateString('pt-BR', { 
                    year: 'numeric', 
                    month: 'short' 
                });
            }),
            datasets: [{
                label: 'Revenue (R$)',
                data: revenueData.map(item => item.revenue),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>