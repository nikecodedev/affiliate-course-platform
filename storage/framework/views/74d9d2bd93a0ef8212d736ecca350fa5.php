

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Stats Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Leads</h6>
                        <h3 class="mb-0"><?php echo e(number_format($stats['total_leads'])); ?></h3>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> <?php echo e($stats['active_leads']); ?> active
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people display-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Earnings</h6>
                        <h3 class="mb-0">R$ <?php echo e(number_format($stats['total_earnings'], 2, ',', '.')); ?></h3>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> This month
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-cash-stack display-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-1">Available Balance</h6>
                        <h3 class="mb-0">R$ <?php echo e(number_format($stats['available_balance'], 2, ',', '.')); ?></h3>
                        <small class="text-info">
                            <i class="bi bi-wallet2"></i> For withdrawal
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-wallet2 display-4 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-1">Open Tickets</h6>
                        <h3 class="mb-0"><?php echo e($stats['open_tickets']); ?></h3>
                        <small class="text-warning">
                            <i class="bi bi-headset"></i> Pending
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-headset display-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Active Invoice Progress -->
    <?php if($activeInvoice): ?>
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-receipt me-2"></i>Active Invoice
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Plan</h6>
                        <h5 class="mb-3"><?php echo e($activeInvoice->plan->name ?? 'N/A'); ?></h5>
                        
                        <h6 class="text-muted mb-2">Amount</h6>
                        <h5 class="mb-3 text-success">R$ <?php echo e(number_format($activeInvoice->amount, 2, ',', '.')); ?></h5>
                        
                        <h6 class="text-muted mb-2">Status</h6>
                        <span class="badge bg-success fs-6">Active</span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Time Progress</h6>
                        <div class="progress mb-3" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: <?php echo e($activeInvoice->progress_percentage); ?>%"
                                 aria-valuenow="<?php echo e($activeInvoice->progress_percentage); ?>" 
                                 aria-valuemin="0" aria-valuemax="100">
                                <?php echo e($activeInvoice->progress_percentage); ?>%
                            </div>
                        </div>
                        
                        <h6 class="text-muted mb-2">Days Remaining</h6>
                        <h5 class="mb-3 text-warning"><?php echo e($activeInvoice->days_remaining); ?> days</h5>
                        
                        <h6 class="text-muted mb-2">Expires on</h6>
                        <h6 class="mb-0"><?php echo e($activeInvoice->expires_at->format('d/m/Y')); ?></h6>
                    </div>
                </div>

                <?php if(optional($activeInvoice->plan)->external_product_url || optional($activeInvoice->plan)->courses()->exists()): ?>
                <hr>
                <div class="row">
                    <?php if(optional($activeInvoice->plan)->external_product_url): ?>
                    <div class="col-md-6 mb-3">
                        <a href="<?php echo e($activeInvoice->plan->external_product_url); ?>" target="_blank" class="btn btn-success w-100">
                            <i class="bi bi-download me-2"></i>Download Product
                        </a>
                    </div>
                    <?php endif; ?>
                    <?php if(optional($activeInvoice->plan)->courses()->exists()): ?>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Linked Courses</h6>
                        <ul class="list-unstyled mb-0">
                            <?php $__currentLoopData = $activeInvoice->plan->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-1">
                                    <a href="<?php echo e(route('training.show', $course)); ?>" class="text-decoration-none">
                                        <i class="bi bi-book me-1"></i><?php echo e($course->title); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Course Access -->
    <?php if($client->hasCourseAccess()): ?>
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-book me-2"></i>Course Access
                </h5>
            </div>
            <div class="card-body text-center">
                <i class="bi bi-check-circle display-4 text-success mb-3"></i>
                <h6 class="mb-2">Access Granted!</h6>
                <p class="text-muted mb-3">You have full access to the training area.</p>
                <a href="<?php echo e(route('client.training.index')); ?>" class="btn btn-primary">
                    <i class="bi bi-arrow-right me-2"></i>
                    Access Courses
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-book me-2"></i>Course Access
                </h5>
            </div>
            <div class="card-body text-center">
                <i class="bi bi-lock display-4 text-muted mb-3"></i>
                <h6 class="mb-2">Access Restricted</h6>
                <p class="text-muted mb-3">Purchase a plan to access the courses.</p>
                <a href="<?php echo e(route('client.financial.index')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-right me-2"></i>
                    View Plans
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="row">
    <!-- Recent Transactions -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-credit-card me-2"></i>Recent Transactions
                    </h5>
                    <a href="<?php echo e(route('client.financial.transactions')); ?>" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if($recentTransactions->count() > 0): ?>
                    <?php $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-1"><?php echo e($transaction->description); ?></h6>
                            <small class="text-muted"><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></small>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold <?php echo e($transaction->type === 'credit' ? 'text-success' : 'text-danger'); ?>">
                                <?php echo e($transaction->formatted_amount); ?>

                            </span>
                            <br>
                            <span class="badge bg-<?php echo e($transaction->status_badge_color); ?>">
                                <?php echo e($transaction->status_badge_text); ?>

                            </span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mt-2">Nenhuma transação encontrada</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Recent Leads -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>Recent Leads
                    </h5>
                    <a href="<?php echo e(route('client.leads.index')); ?>" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if($recentLeads->count() > 0): ?>
                    <?php $__currentLoopData = $recentLeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-1"><?php echo e($lead->name); ?></h6>
                            <small class="text-muted"><?php echo e($lead->email); ?></small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-<?php echo e($lead->status_badge_color); ?>">
                                <?php echo e($lead->status_badge_text); ?>

                            </span>
                            <br>
                            <small class="text-muted"><?php echo e($lead->created_at->format('d/m/Y')); ?></small>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-people display-4"></i>
                        <p class="mt-2">Nenhum lead encontrado</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Earnings Chart -->
<?php if(isset($monthlyEarnings)): ?>
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>Ganhos dos Últimos 12 Meses
                </h5>
            </div>
            <div class="card-body">
                <canvas id="earningsChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Capture Sites -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-link-45deg me-2"></i>Sites de Captura
                    </h5>
                    <a href="<?php echo e(route('client.capture-sites')); ?>" class="btn btn-sm btn-outline-primary">
                        Gerenciar Sites
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="border rounded p-3 mb-3">
                            <h6 class="mb-2">
                                <i class="bi bi-globe me-2"></i>Site Principal
                            </h6>
                            <p class="text-muted mb-2">Site de captura com seus tracking tags</p>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" 
                                       value="<?php echo e(route('client.capture.main', ['code' => $client->id])); ?>" 
                                       readonly>
                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                        onclick="copyToClipboard(this)">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 mb-3">
                            <h6 class="mb-2">
                                <i class="bi bi-globe2 me-2"></i>Site Secundário
                            </h6>
                            <p class="text-muted mb-2">Site de captura alternativo</p>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" 
                                       value="<?php echo e(route('client.capture.secondary', ['code' => $client->id])); ?>" 
                                       readonly>
                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                        onclick="copyToClipboard(this)">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Earnings Chart
<?php if(isset($monthlyEarnings)): ?>
const ctx = document.getElementById('earningsChart').getContext('2d');
const earningsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($monthlyEarnings['months'], 15, 512) ?>,
        datasets: [{
            label: 'Ganhos (R$)',
            data: <?php echo json_encode($monthlyEarnings['earnings'], 15, 512) ?>,
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#3498db',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6
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
<?php endif; ?>

// Copy to clipboard function
function copyToClipboard(button) {
    const input = button.parentElement.querySelector('input');
    input.select();
    input.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-check"></i>';
    button.classList.remove('btn-outline-secondary');
    button.classList.add('btn-success');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-secondary');
    }, 2000);
}

// Auto-refresh stats every 30 seconds
setInterval(function() {
    fetch('<?php echo e(route("client.quick-stats")); ?>')
        .then(response => response.json())
        .then(data => {
            // Update stats cards here if needed
            console.log('Stats updated:', data);
        })
        .catch(error => console.error('Error updating stats:', error));
}, 30000);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\affiliate\resources\views/client/dashboard.blade.php ENDPATH**/ ?>