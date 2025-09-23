

<?php $__env->startSection('title', 'Transactions'); ?>
<?php $__env->startSection('page-title', 'Transaction History'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('client.financial.transactions')); ?>" class="row g-3">
                    <div class="col-md-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="credit" <?php echo e(request('type') === 'credit' ? 'selected' : ''); ?>>Credit</option>
                            <option value="debit" <?php echo e(request('type') === 'debit' ? 'selected' : ''); ?>>Debit</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Completed</option>
                            <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="failed" <?php echo e(request('status') === 'failed' ? 'selected' : ''); ?>>Failed</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" 
                               value="<?php echo e(request('date_from')); ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" 
                               value="<?php echo e(request('date_to')); ?>">
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>Filter
                        </button>
                        <a href="<?php echo e(route('client.financial.transactions')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Clear
                        </a>
                        <a href="<?php echo e(route('client.financial.statements.download', request()->query())); ?>" class="btn btn-success">
                            <i class="bi bi-download me-2"></i>Export
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Transaction Summary -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-success">
                            <i class="bi bi-arrow-down-circle"></i>
                        </h5>
                        <h4 class="text-success">R$ <?php echo e(number_format($summary['total_credits'] ?? 0, 2, ',', '.')); ?></h4>
                        <small class="text-muted">Total Credits</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-danger">
                            <i class="bi bi-arrow-up-circle"></i>
                        </h5>
                        <h4 class="text-danger">R$ <?php echo e(number_format($summary['total_debits'] ?? 0, 2, ',', '.')); ?></h4>
                        <small class="text-muted">Total Debits</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-info">
                            <i class="bi bi-list-check"></i>
                        </h5>
                        <h4 class="text-info"><?php echo e($summary['total_transactions'] ?? 0); ?></h4>
                        <small class="text-muted">Total Transactions</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary">
                            <i class="bi bi-wallet2"></i>
                        </h5>
                        <h4 class="text-primary">R$ <?php echo e(number_format($summary['net_amount'] ?? 0, 2, ',', '.')); ?></h4>
                        <small class="text-muted">Net Amount</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Transaction History
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown">
                            Sort by
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'date_desc'])); ?>">Date (Newest)</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'date_asc'])); ?>">Date (Oldest)</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'amount_desc'])); ?>">Amount (Highest)</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'amount_asc'])); ?>">Amount (Lowest)</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if($transactions->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Transaction ID</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold"><?php echo e($transaction->created_at->format('d/m/Y')); ?></span>
                                            <small class="text-muted"><?php echo e($transaction->created_at->format('H:i')); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="text-primary"><?php echo e($transaction->transaction_id ?? 'N/A'); ?></code>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold"><?php echo e($transaction->description); ?></span>
                                            <?php if($transaction->notes): ?>
                                                <small class="text-muted"><?php echo e($transaction->notes); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($transaction->type === 'credit' ? 'success' : 'danger'); ?> fs-6">
                                            <i class="bi bi-<?php echo e($transaction->type === 'credit' ? 'arrow-down' : 'arrow-up'); ?>-circle me-1"></i>
                                            <?php echo e(ucfirst($transaction->type)); ?>

                                        </span>
                                    </td>
                                    <td class="text-<?php echo e($transaction->type === 'credit' ? 'success' : 'danger'); ?> fw-bold">
                                        <?php echo e($transaction->type === 'credit' ? '+' : '-'); ?>R$ <?php echo e(number_format($transaction->amount, 2, ',', '.')); ?>

                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger')); ?>">
                                            <?php echo e(ucfirst($transaction->status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($transaction->reference): ?>
                                            <code class="text-muted"><?php echo e($transaction->reference); ?></code>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <p class="text-muted mb-0">
                                Showing <?php echo e($transactions->firstItem()); ?> to <?php echo e($transactions->lastItem()); ?> 
                                of <?php echo e($transactions->total()); ?> results
                            </p>
                        </div>
                        <div>
                            <?php echo e($transactions->links()); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-receipt display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No transactions found</h4>
                        <p class="text-muted">
                            <?php if(request()->hasAny(['type', 'status', 'date_from', 'date_to'])): ?>
                                Try adjusting your filters to see more results.
                            <?php else: ?>
                                Your transaction history will appear here as you make transactions.
                            <?php endif; ?>
                        </p>
                        <?php if(request()->hasAny(['type', 'status', 'date_from', 'date_to'])): ?>
                            <a href="<?php echo e(route('client.financial.transactions')); ?>" class="btn btn-outline-primary">
                                <i class="bi bi-x-circle me-2"></i>Clear Filters
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto-submit form on filter change
    document.getElementById('type').addEventListener('change', function() {
        this.form.submit();
    });
    
    document.getElementById('status').addEventListener('change', function() {
        this.form.submit();
    });
    
    // Date validation
    document.getElementById('date_from').addEventListener('change', function() {
        const dateTo = document.getElementById('date_to');
        if (this.value && dateTo.value && this.value > dateTo.value) {
            dateTo.value = this.value;
        }
    });
    
    document.getElementById('date_to').addEventListener('change', function() {
        const dateFrom = document.getElementById('date_from');
        if (this.value && dateFrom.value && this.value < dateFrom.value) {
            dateFrom.value = this.value;
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/client/financial/transactions.blade.php ENDPATH**/ ?>